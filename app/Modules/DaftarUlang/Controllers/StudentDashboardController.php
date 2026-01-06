<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Events\PaymentReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $activeBill = DB::table('du_bills')
            ->where('is_active', true)
            ->where(function ($query) use ($student) {
                $query->where('target_major_id', $student->major_id)
                    ->orWhereNull('target_major_id');
            })
            ->where('target_school_year_id', $student->school_year_id)
            ->first();

        $trxStatus = null;
        $trxNote = null;

        if ($activeBill) {
            $transaction = DB::table('du_transactions')
                ->where('student_id', $student->id)
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
        $bill = DB::table('du_bills')
            ->where('id', $id)
            ->where('is_active', true)
            ->where(function ($query) use ($student) {
                $query->where('target_major_id', $student->major_id)
                    ->orWhereNull('target_major_id');
            })
            ->where('target_school_year_id', $student->school_year_id)
            ->first();

        if (! $bill) {
            return redirect()->route('student.dashboard')->with('error', 'Tagihan tidak ditemukan.');
        }

        $transaction = DB::table('du_transactions')
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

        $transactionId = $transaction ? $transaction->id : null;

        $requirements = DB::table('du_bill_requirements as req')
            ->leftJoin('du_transaction_files as file', function ($join) use ($transactionId) {
                $join->on('req.id', '=', 'file.du_bill_requirement_id')
                    ->where('file.du_transaction_id', '=', $transactionId);
            })
            ->where('req.du_bill_id', $id)
            ->select(
                'req.id as req_id',
                'req.document_name',
                'req.is_mandatory',
                'file.file_path',
                'file.status as file_status',
                'file.reject_reason'
            )
            ->get();

        return view('DaftarUlang::students.document', compact('student', 'bill', 'requirements', 'transaction'));
    }

    public function uploadRequirement(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'req_id' => 'required|exists:du_bill_requirements,id',
            'bill_id' => 'required|exists:du_bills,id',
        ]);

        $student = Auth::guard('student')->user();
        $billId = $request->bill_id;

        try {
            DB::beginTransaction();
            $transaction = DB::table('du_transactions')
                ->where('student_id', $student->id)
                ->where('du_bill_id', $billId)
                ->first();
            if (! $transaction) {
                $trxCode = 'TRX-'.date('y').$student->nipd.'-'.strtoupper(Str::random(4));
                $transactionId = DB::table('du_transactions')->insertGetId([
                    'trx_code' => $trxCode,
                    'student_id' => $student->id,
                    'du_bill_id' => $billId,
                    'status' => 'draft',
                    'total_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $transactionId = $transaction->id;
            }
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('public/transactions/'.$student->id, $filename);
            $existingFile = DB::table('du_transaction_files')
                ->where('du_transaction_id', $transactionId)
                ->where('du_bill_requirement_id', $request->req_id)
                ->first();
            if ($existingFile) {
                $newRejectReason = null;
                if ($existingFile->status == 'invalid' && ! empty($existingFile->reject_reason)) {
                    $newRejectReason = 'Untuk Dokumen Ini Sudah Diperbaiki Sesuai Dengan Alasan Yang Anda Berikan Sebelumnya: '.$existingFile->reject_reason;
                }
                DB::table('du_transaction_files')
                    ->where('id', $existingFile->id)
                    ->update([
                        'file_path' => $path,
                        'file_name' => $filename,
                        'mime_type' => $file->getClientMimeType(),
                        'reject_reason' => $newRejectReason,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('du_transaction_files')->insert([
                    'du_transaction_id' => $transactionId,
                    'du_bill_requirement_id' => $request->req_id,
                    'file_path' => $path,
                    'file_name' => $filename,
                    'mime_type' => $file->getClientMimeType(),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $publicUrl = \Illuminate\Support\Facades\Storage::url($path);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'File berhasil diupload!', 'file_url' => asset($publicUrl)]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function payment($id)
    {
        $student = Auth::guard('student')->user();
        $bill = DB::table('du_bills')->where('id', $id)->first();
        if (! $bill) {
            abort(404);
        }
        $transaction = DB::table('du_transactions')
            ->where('student_id', $student->id)
            ->where('du_bill_id', $id)
            ->first();
        if (! $transaction) {
            return redirect()->route('student.bills.show', $id)->with('error', 'Selesaikan upload dokumen dulu!');
        }
        $mandatoryReqIds = DB::table('du_bill_requirements')
            ->where('du_bill_id', $id)
            ->where('is_mandatory', true)
            ->pluck('id')
            ->toArray();
        $uploadedReqIds = DB::table('du_transaction_files')
            ->where('du_transaction_id', $transaction->id)
            ->pluck('du_bill_requirement_id')
            ->toArray();
        $missing = array_diff($mandatoryReqIds, $uploadedReqIds);
        if (! empty($missing)) {
            return redirect()->route('student.bills.show', $id)
                ->with('error', 'Mohon lengkapi semua dokumen persyaratan sebelum lanjut bayar.');
        }
        $bankAccount = [
            'bank_name' => 'Bank BNI',
            'account_number' => '1234567890',
            'account_name' => 'SMK Budi Bakti Ciwidey',
        ];

        return view('DaftarUlang::students.payment', compact('student', 'bill', 'transaction', 'bankAccount'));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'du_transaction_id' => 'required|exists:du_transactions,id',
            'payment_method' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $transaction = DB::table('du_transactions')->where('id', $request->du_transaction_id)->first();
            $newStatus = ($transaction->status == 'payment_rejected') ? 'payment_review' : 'pending_docs';
            $bill = DB::table('du_bills')->where('id', $transaction->du_bill_id)->first();
            $student = DB::table('core_students')->where('id', $transaction->student_id)->first();

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
                    'updated_at' => now(),
                ];

                if ($request->hasFile('proof_file')) {
                    $file = $request->file('proof_file');
                    $filename = 'BUKTI_'.time().'_'.$transaction->trx_code.'.'.$file->getClientOriginalExtension();
                    $updateData['proof_path'] = $file->storeAs('public/payments/'.$transaction->student_id, $filename);
                }

                DB::table('du_transactions')->where('id', $transaction->id)->update($updateData);

                PaymentReceived::dispatch($transaction, "Order Baru: {$student->name} membuat tagihan.", 'info');
                DB::commit();

                $msg = ($newStatus == 'payment_review')
                    ? 'Perbaikan data berhasil dikirim ke Bagian Keuangan.'
                    : 'Pembayaran berhasil dikirim! Menunggu verifikasi dokumen.';

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

                $customOrderId = $transaction->trx_code.'-'.time();
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

                DB::table('du_transactions')->where('id', $transaction->id)->update([
                    'status' => $newStatus,
                    'payment_method' => 'bni',
                    'midtrans_transaction_id' => $response->transaction_id,
                    'va_number' => $vaNumber,
                    'payment_expiry_time' => $response->expiry_time,
                    'midtrans_response' => json_encode($response),
                    'total_amount' => $bill->amount,
                    'updated_at' => now(),
                ]);

                PaymentReceived::dispatch($transaction, "Order Baru: {$student->name} membuat tagihan.", 'info');

                DB::commit();

                return redirect()->back()->with('success', 'Virtual Account Berhasil Dibuat!');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function resubmit(Request $request, $id)
    {
        $transaction = DB::table('du_transactions')->where('id', $id)->first();
        $student = DB::table('core_students')->where('id', $transaction->student_id)->first();

        if (! $transaction) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($transaction->status == 'doc_rejected') {
            DB::table('du_transactions')->where('id', $id)->update([
                'status' => 'pending_docs',
                'updated_at' => now(),
            ]);

            $url = route('du.transactions.index');
            PaymentReceived::dispatch(
                $transaction,
                "{$student->name} Sudah Memperbaiki Dokumennya. Silahkan di cek kembali",
                'info'
            );

            return redirect()->route('student.dashboard')->with('success', 'Dokumen perbaikan berhasil dikirim! Mohon tunggu verifikasi.');
        } elseif ($transaction->status == 'payment_rejected') {
            DB::table('du_transactions')->where('id', $id)->update([
                'status' => 'payment_review',
                'updated_at' => now(),
            ]);

            return redirect()->route('student.dashboard')->with('success', 'Bukti pembayaran berhasil dikirim ulang!');
        }

        return back();
    }

    public function history()
    {
        $student = Auth::guard('student')->user();

        $transactions = DB::table('du_transactions')
            ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
            ->select(
                'du_transactions.*',
                'du_bills.title as bill_title',
                'du_bills.amount as bill_amount'
            )
            ->where('du_transactions.student_id', $student->id)
            ->orderBy('du_transactions.updated_at', 'desc')
            ->get();

        return view('DaftarUlang::students.history', compact('transactions'));
    }

    public function invoice($id)
    {
        $student = Auth::guard('student')->user();

        $bill = DB::table('du_bills')->where('id', $id)->first();

        $transaction = DB::table('du_transactions')
            ->where('student_id', $student->id)
            ->where('du_bill_id', $id)
            ->where('status', 'paid')
            ->first();
        if (! $transaction) {
            return redirect()->route('student.bills.show', $id)->with('error', 'Tagihan belum lunas.');
        }

        return view('DaftarUlang::students.invoice', compact('student', 'bill', 'transaction'));
    }
}
