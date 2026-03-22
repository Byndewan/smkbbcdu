<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Events\PaymentReceived;
use App\Events\TransactionUpdated;
use App\Http\Controllers\Controller;
use App\Mail\TransactionRejected;
use App\Models\DuTransaction;
use App\Models\DuTransactionFile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending_docs');

        if ($request->ajax()) {
            $data = DuTransaction::with(['student.class', 'bill'])
                ->restricted()
                ->where('status', $status)
                ->latest('updated_at');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('bill_title', fn($row) => $row->bill->title ?? '-')
                ->editColumn('total_amount', fn($row) => 'Rp ' . number_format($row->total_amount, 0, ',', '.'))
                ->addColumn('student_info', function ($row) {
                    $className = $row->student && $row->student->class ? $row->student->class->name : '-';

                    return '<strong>' . $row->student->name . '</strong><br><small class="text-muted">' . $row->student->nipd . ' - ' . $className . '</small>';
                })
                ->addColumn('action', function ($row) use ($status) {
                    $url = route('admin.du.transactions.verification', $row->id);
                    return $this->getActionButtons($status, $url);
                })
                ->rawColumns(['student_info', 'action'])
                ->make(true);
        }

        $counts = [
            'pending_docs' => DuTransaction::restricted()->where('status', 'pending_docs')->count(),
            'doc_rejected' => DuTransaction::restricted()->where('status', 'doc_rejected')->count(),
            'payment_review' => DuTransaction::restricted()->where('status', 'payment_review')->count(),
            'paid' => DuTransaction::restricted()->where('status', 'paid')->count(),
        ];

        return view('DaftarUlang::transactions.index', compact('counts'));
    }

    public function verification($id)
    {
        $trx = DuTransaction::with(['student.class', 'bill', 'files.requirement'])
            ->restricted()
            ->findOrFail($id);

        return view('DaftarUlang::transactions.verification_window', compact('trx'));
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
            $transaction = DuTransaction::with(['student', 'bill'])->findOrFail($id);
            if ($request->has('files')) {
                foreach ($request->input('files') as $fileId => $data) {
                    $status = $data['status'];
                    $reason = $data['reason'] ?? null;
                    $file = $transaction->files()->where('id', $fileId)->first();

                    if ($file) {
                        $file->update([
                            'status' => $status,
                            'reject_reason' => ($status == 'invalid') ? $reason : null,
                        ]);
                    }
                }
            }
            $newStatus = $transaction->status;
            $note = $request->admin_note;

            if ($request->has('verdict')) {
                if ($request->verdict == 'valid') {
                    $newStatus = 'payment_review';
                    $note = 'Dokumen Valid. Data diteruskan ke Bagian Keuangan.';
                } else {
                    $newStatus = 'doc_rejected';
                    $note = $request->admin_note ?? 'Dokumen tidak lengkap. Mohon periksa kembali.';
                }
            }
            if ($request->has('payment_status')) {
                if ($request->payment_status == 'valid') {
                    $newStatus = 'paid';
                    $note = 'Pembayaran Lunas via Admin Verification.';
                } else {
                    $newStatus = 'payment_rejected';
                    $note = $request->admin_note ?? 'Bukti pembayaran tidak valid.';
                }
            }
            $transaction->update([
                'status' => $newStatus,
                'admin_note' => $note,
            ]);
            DB::commit();

            $msg = "Update Transaksi #{$transaction->trx_code}: Status berubah menjadi {$newStatus}";
            PaymentReceived::dispatch($transaction, $msg, 'info');
            TransactionUpdated::dispatch($transaction, "Status transaksi Anda diperbarui.", $newStatus);

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

    private function getActionButtons($status, $url)
    {
        $jsPopup = "const w = 1500; const h = 900;const left = (screen.width - w) / 2;const top = (screen.height - h) / 2;window.open('$url', 'VerifikasiWindow',`width=\${w},height=\${h},left=\${left},top=\${top},resizable=yes,scrollbars=yes`)";

        if ($status === 'paid') {
            return '<button onclick="' . $jsPopup . '" class="btn btn-sm btn-secondary text-white shadow-sm"><i class="bi bi-exclamation-circle-fill me-1"></i> Detail</button>';
        }
        if ($status === 'payment_review') {
            return '<span class="btn btn-sm btn-secondary text-white shadow-sm"><i class="bi bi-exclamation-circle-fill me-1"></i>Menunggu Review Keuangan</span>';
        }
        if ($status === 'doc_rejected') {
            return '<button onclick="' . $jsPopup . '" class="btn btn-sm btn-danger text-white shadow-sm"><i class="bi bi-exclamation-circle-fill me-1"></i> Klik Jika Salah Verifikasi!</button>';
        }

        return '<button onclick="' . $jsPopup . '" class="btn btn-sm btn-primary text-white shadow-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Verifikasi</button>';
    }
}
