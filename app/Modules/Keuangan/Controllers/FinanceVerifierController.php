<?php

namespace App\Modules\Keuangan\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccessMail;
use App\Models\DuTransaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FinanceVerifierController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $status = $request->get('status', 'payment_review');

        if ($request->ajax()) {
            $data = DB::table('du_transactions')
                ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
                ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
                ->leftJoin('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
                ->select(
                    'du_transactions.*',
                    'core_students.name as student_name',
                    'core_students.nipd',
                    'core_classes.name as class_name',
                    'du_bills.title as bill_title'
                )
                ->restrictMajor('core_students.major_id')
                ->where('du_transactions.status', $status)
                ->orderBy('du_transactions.updated_at', 'desc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('trx_code', fn ($row) => '<span class="fw-bold text-primary">#'.$row->trx_code.'</span>')
                ->editColumn('student_name', fn ($row) => '<strong>'.$row->student_name.'</strong><br><small class="text-muted">'.$row->nipd.' - '.($row->class_name ?? '-').'</small>')
                ->editColumn('total_amount', fn ($row) => '<span class="fw-bold text-success">Rp '.number_format($row->total_amount, 0, ',', '.').'</span>')
                ->editColumn('payment_method', function ($row) {
                    $color = ($row->payment_method == 'manual') ? 'warning' : 'info';
                    $label = ($row->payment_method == 'manual') ? 'Transfer Manual' : 'BNI VA (Midtrans)';

                    return '<span class="badge bg-'.$color.' bg-opacity-10 text-'.$color.'">'.$label.'</span>';
                })
                ->addColumn('action', function ($row) use ($status) {
                    $url = route('finance.transaction.verification', $row->id);

                    if ($status === 'paid') {
                        return '
                            <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'FinanceWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                            class="btn btn-sm btn-secondary fw-bold text-white shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Detail
                            </button>
                        ';
                    }

                    if ($status === 'payment_rejected') {
                        return '
                            <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'FinanceWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                            class="btn btn-sm btn-danger fw-bold text-white shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Klik Jika Salah Verifikasi!
                            </button>
                        ';
                    }

                    return '
                        <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'FinanceWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                        class="btn btn-sm btn-primary fw-bold text-white shadow-sm">
                            <i class="bi bi-cash-stack me-1"></i> Verifikasi
                        </button>
                    ';
                })
                ->rawColumns(['trx_code', 'student_name', 'total_amount', 'payment_method', 'action'])
                ->make(true);
        }

        $counts = [
            'payment_review' => DB::table('du_transactions')->where('status', 'payment_review')->count(),
            'payment_rejected' => DB::table('du_transactions')->where('status', 'payment_rejected')->count(),
            'paid' => DB::table('du_transactions')->where('status', 'paid')->count(),
        ];

        return view('Keuangan::finance.index', compact('counts'));
    }

    public function verification($id)
    {
        $trx = DB::table('du_transactions')
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
            ->leftJoin('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
            ->select(
                'du_transactions.*',
                'core_students.name as student_name',
                'core_students.nipd',
                'core_classes.name as class_name',
                'du_bills.title as bill_title'
            )
            ->where('du_transactions.id', $id)
            ->first();

        if (! $trx) {
            return 'Data Transaksi tidak ditemukan.';
        }

        return view('Keuangan::finance.verification_window', compact('trx'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:valid,invalid',
            'admin_note' => 'nullable|string',
        ]);

        if ($request->payment_status == 'valid') {
            $finalStatus = 'paid';
            $finalNote = 'Pembayaran Diterima.';
        } else {
            $finalStatus = 'payment_rejected';
            $finalNote = $request->admin_note ?? 'Bukti pembayaran tidak valid / dana tidak masuk.';
        }

        DB::beginTransaction();

        try {
            DB::table('du_transactions')->where('id', $id)->update([
                'status' => $finalStatus,
                'admin_note' => $finalNote,
                'updated_at' => now(),
            ]);

            DB::commit();
            if ($finalStatus == 'paid') {
                $trxEloquent = DuTransaction::with('student')->find($id);

                if ($trxEloquent && $trxEloquent->student && $trxEloquent->student->email) {
                    try {
                        Mail::to($trxEloquent->student->email)->send(new PaymentSuccessMail($trxEloquent));
                    } catch (\Exception $mailException) {
                        Log::error("Gagal kirim email pembayaran ID $id: ".$mailException->getMessage());
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => ($finalStatus == 'paid') ? 'Verifikasi Berhasil! Email Terkirim.' : 'Verifikasi Berhasil! Dikembalikan ke siswa.',
                'close_window' => true,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
