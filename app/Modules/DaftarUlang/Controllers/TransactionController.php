<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending_docs');
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
                ->editColumn('total_amount', fn ($row) => 'Rp '.number_format($row->total_amount, 0, ',', '.'))
                ->addColumn('student_info', fn ($row) => '<strong>'.$row->student_name.'</strong><br><small class="text-muted">'.$row->nipd.' - '.($row->class_name ?? '-').'</small>')
                ->addColumn('action', function ($row) use ($status) {
                    $url = route('du.transactions.verification', $row->id);

                    if ($status === 'paid') {
                        return '
                            <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'VerifikasiWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                            class="btn btn-sm btn-secondary text-white shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Detail
                            </button>
                        ';
                    }

                    if ($status === 'payment_review') {
                        return '
                            <span class="btn btn-sm btn-secondary text-white shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-1"></i>Menunggu Di Review Keuangan
                            </span>
                        ';
                    }

                    if ($status === 'doc_rejected') {
                        return '
                        <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'VerifikasiWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                        class="btn btn-sm btn-danger text-white shadow-sm">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Klik Jika Salah Verifikasi!
                        </button>
                    ';
                    }

                    return '
                        <button onclick="const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open(\''.$url.'\', \'VerifikasiWindow\',`width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                        class="btn btn-sm btn-primary text-white shadow-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Verifikasi
                        </button>
                    ';

                })
                ->rawColumns(['student_info', 'action'])
                ->make(true);
        }

        $counts = [
            'pending_docs' => DB::table('du_transactions')->where('status', 'pending_docs')->count(),
            'doc_rejected' => DB::table('du_transactions')->where('status', 'doc_rejected')->count(),
            'payment_review' => DB::table('du_transactions')->where('status', 'payment_review')->count(),
            'paid' => DB::table('du_transactions')->where('status', 'paid')->count(),
        ];

        return view('DaftarUlang::transactions.index', compact('counts'));
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

        $files = DB::table('du_transaction_files')
            ->join('du_bill_requirements', 'du_transaction_files.du_bill_requirement_id', '=', 'du_bill_requirements.id')
            ->where('du_transaction_files.du_transaction_id', $id)
            ->select('du_transaction_files.*', 'du_bill_requirements.document_name')
            ->get();

        return view('DaftarUlang::transactions.verification_window', compact('trx', 'files'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'verdict' => 'required|in:valid,invalid',
            'admin_note' => 'nullable|string',
            'files' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();
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
                }
            }

            if ($request->verdict == 'valid') {
                $newStatus = 'payment_review';
                $note = 'Dokumen Valid. Data diteruskan ke Bagian Keuangan.';
            } else {
                $newStatus = 'doc_rejected';
                $note = $request->admin_note ?? 'Dokumen tidak lengkap. Mohon periksa kembali.';
            }

            DB::table('du_transactions')->where('id', $id)->update([
                'status' => $newStatus,
                'admin_note' => $note,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => ($newStatus == 'payment_review') ? 'Verifikasi Berhasil! Lanjut ke Keuangan.' : 'Verifikasi Berhasil! Dikembalikan ke Siswa.',
                'new_status' => $newStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
