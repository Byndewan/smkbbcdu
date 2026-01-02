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

    public function index()
    {
        if (request()->ajax()) {
            $data = DB::table('du_transactions')
                ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
                ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
                ->select(
                    'du_transactions.*',
                    'core_students.name as student_name',
                    'du_bills.title as bill_title'
                )
                ->where('du_transactions.status', 'awaiting_payment')
                ->orderBy('du_transactions.updated_at', 'asc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('trx_code', function ($row) {
                    return '<span class="fw-bold text-primary">#'.$row->trx_code.'</span>';
                })
                ->editColumn('student_name', function ($row) {
                    return '<div class="fw-bold text-dark">'.$row->student_name.'</div>';
                })
                ->editColumn('total_amount', function ($row) {
                    return 'Rp '.number_format($row->total_amount, 0, ',', '.');
                })
                ->editColumn('payment_method', function ($row) {
                    return '<span class="badge bg-primary bg-opacity-10 text-primary">'.strtoupper($row->payment_method).'</span>';
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d M Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-sm btn-primary fw-bold px-3 btn-modal"
                                data-url="'.route('finance.show', $row->id).'">
                                <i class="bi bi-cash-stack me-1"></i> Cek Dana
                            </button>';
                })
                ->rawColumns(['trx_code', 'student_name', 'payment_method', 'action'])
                ->make(true);
        }

        return view('Keuangan::finance.index');
    }

    public function show($id)
    {
        $trx = DB::table('du_transactions')
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
            ->select('du_transactions.*', 'core_students.name as student_name', 'du_bills.title as bill_title')
            ->where('du_transactions.id', $id)
            ->first();

        return view('Keuangan::finance.show', compact('trx'));
    }

    public function update(Request $request, $id)
    {
        $paymentStatus = $request->payment_status;
        $adminNote = $request->admin_note;

        if ($paymentStatus == 'valid') {
            $finalStatus = 'paid';
            $finalNote = null;
        } else {
            $finalStatus = 'rejected';
            $finalNote = $adminNote;
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

            return redirect()->route('finance.index')
                ->with('success', 'Verifikasi Pembayaran Berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }
    }
}
