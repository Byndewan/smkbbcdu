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
            $data = DuTransaction::with(['student.class', 'bill'])
                ->restricted()
                ->where('status', $status)
                ->latest('updated_at');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('trx_code', fn ($row) => '<span class="fw-bold text-primary">#'.$row->trx_code.'</span>')
                ->editColumn('student_name', function ($row) {
                    $className = $row->student && $row->student->class ? $row->student->class->name : '-';

                    return '<strong>'.$row->student->name.'</strong><br><small class="text-muted">'.$row->student->nipd.' - '.$className.'</small>';
                })
                ->addColumn('bill_title', fn ($row) => $row->bill->title ?? '-')
                ->editColumn('total_amount', fn ($row) => '<span class="fw-bold text-success">Rp '.number_format($row->total_amount, 0, ',', '.').'</span>')
                ->editColumn('payment_method', function ($row) {
                    $color = ($row->payment_method == 'manual') ? 'warning' : 'info';
                    $label = ($row->payment_method == 'manual') ? 'Transfer Manual' : 'BNI VA (Midtrans)';

                    return '<span class="badge bg-'.$color.' bg-opacity-10 text-'.$color.'">'.$label.'</span>';
                })
                ->addColumn('action', function ($row) use ($status) {
                    $url = route('admin.finance.verification', $row->id);
                    return $this->getActionButtons($status, $url);
                })
                ->rawColumns(['trx_code', 'student_name', 'total_amount', 'payment_method', 'action'])
                ->make(true);
        }

        $counts = [
            'payment_review' => DuTransaction::restricted()->where('status', 'payment_review')->count(),
            'payment_rejected' => DuTransaction::restricted()->where('status', 'payment_rejected')->count(),
            'paid' => DuTransaction::restricted()->where('status', 'paid')->count(),
        ];

        return view('Keuangan::finance.index', compact('counts'));
    }

    public function verification($id)
    {
        $trx = DuTransaction::with(['student.class', 'bill'])->restricted()->findOrFail($id);

        return view('Keuangan::finance.verification_window', compact('trx'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:valid,invalid',
            'admin_note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $transaction = DuTransaction::findOrFail($id);

            if ($request->payment_status == 'valid') {
                $finalStatus = 'paid';
                $finalNote = 'Pembayaran Diterima.';
            } else {
                $finalStatus = 'payment_rejected';
                $finalNote = $request->admin_note ?? 'Bukti pembayaran tidak valid / dana tidak masuk.';
            }

            $transaction->update([
                'status' => $finalStatus,
                'admin_note' => $finalNote,
            ]);
            DB::commit();
            if ($finalStatus == 'paid' && $transaction->student->email) {
                try {
                    Mail::to($transaction->student->email)->send(new PaymentSuccessMail($transaction));
                } catch (\Exception $e) {
                    Log::error("Gagal kirim email pembayaran ID $id: ".$e->getMessage());
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

    private function getActionButtons($status, $url)
    {
        $js = "const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open('$url', 'FinanceWindow',`width=\${w},height=\${h},left=\${left},top=\${top},resizable=yes,scrollbars=yes`)";

        if ($status === 'paid') {
            return "<button onclick=\"$js\" class=\"btn btn-sm btn-secondary fw-bold text-white shadow-sm\"><i class=\"bi bi-exclamation-circle-fill me-1\"></i> Detail</button>";
        }
        if ($status === 'payment_rejected') {
            return "<button onclick=\"$js\" class=\"btn btn-sm btn-danger fw-bold text-white shadow-sm\"><i class=\"bi bi-exclamation-circle-fill me-1\"></i> Klik Jika Salah Verifikasi!</button>";
        }

        return "<button onclick=\"$js\" class=\"btn btn-sm btn-primary fw-bold text-white shadow-sm\"><i class=\"bi bi-cash-stack me-1\"></i> Verifikasi</button>";
    }
}
