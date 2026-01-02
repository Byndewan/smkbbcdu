<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccessMail;
use App\Models\DuTransaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
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
                ->orderBy('du_transactions.created_at', 'desc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('total_amount', function ($row) {
                    return 'Rp '.number_format($row->total_amount, 0, ',', '.');
                })
                ->addColumn('student_info', function ($row) {
                    return $row->student_name.' <br> <small class="text-muted">'.($row->class_name ?? '-').'</small>';
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'draft' => '<span class="badge bg-secondary">Draft</span>',
                        'pending' => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
                        'paid' => '<span class="badge bg-success">Lunas / Verified</span>',
                        'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                        'awaiting_payment' => '<span class="badge bg-warning text-dark">Verifikasi Keuangan</span>',
                    ];

                    return $badges[$row->status] ?? $row->status;
                })
                ->addColumn('action', function ($row) {
                    return '<button data-url="'.route('du.transactions.show', $row->id).'" class="btn btn-sm btn-info text-white btn-modal"><i class="bi bi-eye"></i> Cek</button>';
                })
                ->rawColumns(['student_info', 'status', 'action'])
                ->make(true);
        }

        return view('DaftarUlang::transactions.index');
    }

    public function show($id)
    {
        $trx = DB::table('du_transactions')
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
            ->select('du_transactions.*', 'core_students.name as student_name', 'du_bills.title as bill_title')
            ->where('du_transactions.id', $id)
            ->first();
        $files = DB::table('du_transaction_files')
            ->join('du_bill_requirements', 'du_transaction_files.du_bill_requirement_id', '=', 'du_bill_requirements.id')
            ->where('du_transaction_files.du_transaction_id', $id)
            ->select('du_transaction_files.*', 'du_bill_requirements.document_name')
            ->get();

        return view('DaftarUlang::transactions.form', compact('trx', 'files'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $isAllFilesValid = true;

            if ($request->has('files')) {
                foreach ($request->input('files') as $fileId => $data) {
                    $status = $data['status'];
                    $reason = $data['reason'] ?? null;

                    DB::table('du_transaction_files')
                        ->where('id', $fileId)
                        ->update([
                            'status' => $status,
                            'reject_reason' => ($status == 'invalid') ? $reason : null,
                            'updated_at' => now(),
                        ]);

                    if ($status == 'invalid') {
                        $isAllFilesValid = false;
                    }
                }
            }

            if ($isAllFilesValid) {
                $finalStatus = 'awaiting_payment';
                $finalNote = 'Dokumen telah diverifikasi. Menunggu verifikasi pembayaran oleh Keuangan.';
            } else {
                $finalStatus = 'rejected';
                $finalNote = 'Terdapat dokumen persyaratan yang tidak valid. Mohon diperbaiki!';
            }

            DB::table('du_transactions')->where('id', $id)->update([
                'status' => $finalStatus,
                'admin_note' => $finalNote,
                'updated_at' => now(),
            ]);

            DB::commit();

            $msg = ($finalStatus == 'awaiting_payment')
                ? 'Dokumen Valid. Data diteruskan ke Bagian Keuangan.'
                : 'Dokumen Ditolak. Siswa diminta revisi.';

            return response()->json([
                'status' => true,
                'message' => $msg,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function countPending()
    {
        $count = DB::table('du_transactions')->where('status', 'pending')->count();

        return response()->json(['count' => $count]);
    }
}
