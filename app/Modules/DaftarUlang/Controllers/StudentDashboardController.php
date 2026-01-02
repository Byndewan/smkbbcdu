<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $isPaid = false;
        if ($activeBill) {
            $isPaid = DB::table('du_transactions')
                ->where('student_id', $student->id)
                ->where('du_bill_id', $activeBill->id)
                ->where('status', 'paid')
                ->exists();
        }

        return view('DaftarUlang::students.dashboard', compact('student', 'activeBill', 'isPaid'));
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
            return redirect()->route('student.dashboard')->with('error', 'Tagihan tidak ditemukan atau tidak akses.');
        }
        $transaction = DB::table('du_transactions')
            ->where('student_id', $student->id)
            ->where('du_bill_id', $bill->id)
            ->first();
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

        return view('DaftarUlang::students.show', compact('student', 'bill', 'requirements', 'transaction'));
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
                DB::table('du_transaction_files')
                    ->where('id', $existingFile->id)
                    ->update([
                        'file_path' => $path,
                        'file_name' => $filename,
                        'mime_type' => $file->getClientMimeType(),
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
            'bank_sender' => 'required_if:payment_method,manual',
            'account_number' => 'required_if:payment_method,manual',
            'account_name' => 'required_if:payment_method,manual',
            'payment_date' => 'required_if:payment_method,manual',
            'proof_file' => 'required_if:payment_method,manual|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $transaction = DB::table('du_transactions')->where('id', $request->du_transaction_id)->first();
            $bill = DB::table('du_bills')->where('id', $transaction->du_bill_id)->first();
            $proofPath = null;
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $filename = 'BUKTI_'.time().'_'.$transaction->trx_code.'.'.$file->getClientOriginalExtension();
                $proofPath = $file->storeAs('public/payments/'.$transaction->student_id, $filename);
            }
            DB::table('du_transactions')
                ->where('id', $transaction->id)
                ->update([
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'bank_sender' => $request->bank_sender,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'payment_date' => $request->payment_date,
                    'total_amount' => $bill->amount,
                    'proof_path' => $proofPath,
                    'admin_note' => null,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->route('student.dashboard')->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses: '.$e->getMessage());
        }
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
