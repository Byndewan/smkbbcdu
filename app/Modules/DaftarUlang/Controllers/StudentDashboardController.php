<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Events\PaymentReceived;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\DuTransaction;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;

class StudentDashboardController extends Controller
{
    private function getActiveBill($student)
    {
        return Bill::where('is_active', true)
            ->where('target_school_year_id', $student->school_year_id)
            ->where(function ($query) use ($student) {
                $query->where('target_major_id', $student->major_id)
                    ->orWhereNull('target_major_id');
            })
            ->first();
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $activeBill = $this->getActiveBill($student);

        $trxStatus = null;
        $trxNote = null;

        if ($activeBill) {
            $transaction = DuTransaction::where('student_id', $student->id)
                ->where('du_bill_id', $activeBill->id)
                ->first();

            if ($transaction) {
                $trxStatus = $transaction->status;
                $trxNote = $transaction->admin_note;
            }
        }

        return view('DaftarUlang::students.dashboard', compact('student', 'activeBill', 'trxStatus', 'trxNote'));
    }

    public function show($id)
    {
        $student = Auth::guard('student')->user();
        $bill = Bill::where('id', $id)
            ->where('is_active', true)
            ->where('target_school_year_id', $student->school_year_id)
            ->first();

        if (! $bill) {
            return redirect()->route('student.dashboard')->with('error', 'Tagihan tidak ditemukan / tidak aktif.');
        }

        $transaction = DuTransaction::with('files')
            ->where('student_id', $student->id)
            ->where('du_bill_id', $bill->id)
            ->first();
        if ($transaction) {
            if ($transaction->status == 'payment_rejected') {
                return redirect()->route('student.bills.payment', $id);
            }
            if (in_array($transaction->status, ['paid', 'payment_review'])) {
                return $this->invoice($bill->id);
            }
        }
        $requirements = $bill->requirements->map(function ($req) use ($transaction) {
            $file = $transaction ? $transaction->files->firstWhere('du_bill_requirement_id', $req->id) : null;
            $req->file_path = $file ? $file->file_path : null;
            $req->file_status = $file ? $file->status : null;
            $req->reject_reason = $file ? $file->reject_reason : null;
            $req->req_id = $req->id;

            return $req;
        });

        return view('DaftarUlang::students.document', compact('student', 'bill', 'requirements', 'transaction'));
    }

    public function uploadRequirement(Request $request, FileService $fileService)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'req_id' => 'required|exists:du_bill_requirements,id',
            'bill_id' => 'required|exists:du_bills,id',
        ]);
        $student = Auth::guard('student')->user();
        try {
            DB::beginTransaction();

            $requirement = DB::table('du_bill_requirements')->where('id', $request->req_id)->first();
            abort_if(!$requirement, 404);
            $documentName = Str::slug($requirement->document_name);

            $transaction = DuTransaction::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'du_bill_id' => $request->bill_id,
                ],
                [
                    'trx_code' => 'TRX-' . date('y') . $student->nipd . '-' . strtoupper(Str::random(4)),
                    'status' => 'draft',
                    'total_amount' => 0,
                ]
            );
            $existingFile = $transaction->files()->where('du_bill_requirement_id', $request->req_id)->first();
            $statusToSave = 'pending';
            $reasonToSave = null;
            if ($existingFile) {
                if ($existingFile->file_path) {
                    $fileService->delete($existingFile->file_path);
                }
                if ($existingFile->status == 'invalid') {
                    $statusToSave = 'invalid';
                    $cleanOldReason = str_replace('Untuk Dokumen Ini Sudah Diperbaiki Sesuai: ', '', $existingFile->reject_reason);
                    $reasonToSave = 'Untuk Dokumen Ini Sudah Diperbaiki Sesuai: ' . $cleanOldReason;
                }
            }

            $file = $request->file('file');
            $path = $fileService->uploadStudentFile($file, $student, 'Dokumen', $documentName);
            $transaction->files()->updateOrCreate(
                ['du_bill_requirement_id' => $request->req_id],
                [
                    'file_path' => $path,
                    'file_name' => basename($path),
                    'mime_type' => $file->getClientMimeType(),
                    'status' => $statusToSave,
                    'reject_reason' => $reasonToSave,
                ]
            );
            $publicUrl = Storage::url($path);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload!',
                'file_url' => asset($publicUrl)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function payment($id)
    {
        $student = Auth::guard('student')->user();
        $bill = Bill::findOrFail($id);

        $transaction = DuTransaction::where('student_id', $student->id)->where('du_bill_id', $id)->first();

        if (! $transaction) {
            return redirect()->route('student.bills.show', $id)->with('error', 'Selesaikan upload dokumen dulu!');
        }
        $mandatoryReqIds = $bill->requirements()->where('is_mandatory', true)->pluck('id')->toArray();
        $uploadedReqIds = $transaction->files()->pluck('du_bill_requirement_id')->toArray();

        $missing = array_diff($mandatoryReqIds, $uploadedReqIds);

        if (! empty($missing)) {
            return redirect()->route('student.bills.show', $id)->with('error', 'Mohon lengkapi semua dokumen persyaratan sebelum lanjut bayar.');
        }

        $bankAccount = [
            'bank_name' => 'Bank BNI',
            'account_number' => '1234567890',
            'account_name' => 'SMK Budi Bakti Ciwidey',
        ];

        return view('DaftarUlang::students.payment', compact('student', 'bill', 'transaction', 'bankAccount'));
    }

    public function processPayment(Request $request, $id, FileService $fileService)
    {
        $request->validate([
            'du_transaction_id' => 'required|exists:du_transactions,id',
            'payment_method' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $transaction = DuTransaction::findOrFail($request->du_transaction_id);
            $bill = $transaction->bill;
            $student = $transaction->student;
            $newStatus = ($transaction->status == 'payment_rejected') ? 'payment_review' : 'pending_docs';
            if ($request->payment_method == 'manual') {
                $request->validate([
                    'bank_sender' => 'required',
                    'account_number' => 'required',
                    'account_name' => 'required',
                    'payment_date' => 'required',
                    'proof_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
                ]);

                $updateData = [
                    'status' => $newStatus,
                    'payment_method' => 'manual',
                    'bank_sender' => $request->bank_sender,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'payment_date' => $request->payment_date,
                    'total_amount' => $bill->amount,
                    'admin_note' => null,
                ];

                if ($request->hasFile('proof_file')) {
                    $path = $fileService->uploadStudentFile(
                        $request->file('proof_file'),
                        $student,
                        'Bukti-Pembayaran'
                    );

                    // 2. Masukkan path yang dihasilkan ke array $updateData
                    $updateData['proof_path'] = $path;
                }

                $transaction->update($updateData);

                DB::commit();

                PaymentReceived::dispatch($transaction, "Order Baru: {$student->name} (Manual)", 'info');

                $msg = ($newStatus == 'payment_review') ? 'Perbaikan data terkirim.' : 'Pembayaran dikirim! Menunggu verifikasi.';

                return redirect()->route('student.dashboard')->with('success', $msg);
            } elseif ($request->payment_method == 'bni') {
                if ($transaction->va_number && $transaction->payment_expiry_time > now()) {
                    DB::rollBack();
                    return redirect()->back()->with('success', 'Nomor VA Anda masih aktif.');
                }

                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $customOrderId = $transaction->trx_code . '-' . time();

                $params = [
                    'payment_type' => 'bank_transfer',
                    'transaction_details' => [
                        'order_id' => $customOrderId,
                        'gross_amount' => (int) $bill->amount,
                    ],
                    'bank_transfer' => ['bank' => 'bni'],
                    'customer_details' => [
                        'first_name' => $student->name,
                        'email' => $student->email ?? 'siswa@smkbbc.sch.id',
                        'phone' => $student->phone ?? '08100000000',
                    ],
                    'custom_field1' => $transaction->id,
                ];

                $response = CoreApi::charge($params);
                $vaNumber = $response->va_numbers[0]->va_number ?? null;

                $transaction->update([
                    'status' => $newStatus,
                    'payment_method' => 'bni',
                    'midtrans_transaction_id' => $response->transaction_id,
                    'va_number' => $vaNumber,
                    'payment_expiry_time' => $response->expiry_time,
                    'midtrans_response' => json_encode($response),
                    'total_amount' => $bill->amount,
                ]);

                DB::commit();

                PaymentReceived::dispatch($transaction, "Order Baru: {$student->name} (BNI)", 'info');
                return redirect()->back()->with('success', 'Virtual Account Berhasil Dibuat!');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function resubmit(Request $request, $id, FileService $fileService)
    {
        $transaction = DuTransaction::findOrFail($id);
        $student = Auth::guard('student')->user();

        if ($transaction->status == 'doc_rejected') {
            $transaction->update([
                'status' => 'pending_docs'
            ]);

            PaymentReceived::dispatch($transaction, "{$student->name} Telah Merubah Dokumen nya", 'info');

            return redirect()->route('student.dashboard')->with('success', 'Dokumen perbaikan berhasil dikirim!');
        } elseif ($transaction->status == 'payment_rejected') {
            $request->validate([
                'payment_date' => 'required|date',
                'bank_sender' => 'required|string',
                'account_name' => 'required|string',
                'account_number' => 'required|numeric',
                'proof_file' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            ], [
                'proof_file.required' => 'Anda wajib mengupload bukti transfer baru!',
            ]);

            if ($request->hasFile('proof_file')) {
                $student = $transaction->student;
                if ($transaction->proof_path) {
                    $fileService->delete($transaction->proof_path);
                }
                $path = $fileService->uploadStudentFile(
                    $request->file('proof_file'),
                    $student,
                    'Bukti-Pembayaran'
                );
                $transaction->proof_path = $path;
            }

            $transaction->update([
                'payment_date' => $request->payment_date,
                'bank_sender' => $request->bank_sender,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'status' => 'payment_review'
            ]);

            PaymentReceived::dispatch($transaction, "{$student->name} Telah Merubah Dokumen nya", 'info');

            return redirect()->route('student.dashboard')->with('success', 'Bukti pembayaran dan data berhasil diperbarui!');
        }

        return back();
    }

    public function history()
    {
        $student = Auth::guard('student')->user();
        $transactions = DuTransaction::with('bill')
            ->where('student_id', $student->id)
            ->latest('updated_at')
            ->get();

        return view('DaftarUlang::students.history', compact('transactions'));
    }

    public function invoice($id)
    {
        $student = Auth::guard('student')->user();
        $bill = Bill::findOrFail($id);

        $transaction = DuTransaction::where('student_id', $student->id)
            ->where('du_bill_id', $id)
            ->where('status', 'paid')
            ->first();

        if (! $transaction) {
            return redirect()->route('student.bills.show', $id)->with('error', 'Tagihan belum lunas.');
        }

        return view('DaftarUlang::students.invoice', compact('student', 'bill', 'transaction'));
    }
}
