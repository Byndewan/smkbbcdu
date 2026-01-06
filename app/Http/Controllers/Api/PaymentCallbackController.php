<?php

namespace App\Http\Controllers\Api;

use App\Events\PaymentReceived;
use App\Http\Controllers\Controller;
use App\Models\DuTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Callback received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $type = $payload['payment_type'] ?? null;

        if (! $orderId) {
            return response()->json(['message' => 'Invalid Order ID'], 400);
        }
        $transaction = DuTransaction::where('trx_code', $orderId)->first();

        if (! $transaction) {
            $parts = explode('-', $orderId);
            if (count($parts) >= 3) {
                $trxId = $payload['custom_field1'] ?? null;
                if ($trxId) {
                    $transaction = DuTransaction::find($trxId);
                }
            }
        }

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $newStatus = null;
        $note = "Callback received: $transactionStatus ($type)";
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $newStatus = 'pending_docs';
                $note = "Pembayaran BERHASIL via $type. Menunggu Verifikasi Admin.";
                break;
            case 'pending':
                $newStatus = 'pending_docs';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $newStatus = 'payment_rejected';
                $note = "Pembayaran GAGAL/EXPIRED ($transactionStatus).";
                break;
        }

        if ($newStatus && $transaction->status != $newStatus) {
            $transaction->update([
                'status' => $newStatus,
                'midtrans_response' => json_encode($payload),
                'payment_method' => $type,
                'payment_date' => now(),
                'admin_note' => $note,
            ]);

            if ($newStatus == 'pending_docs' && ($transactionStatus == 'capture' || $transactionStatus == 'settlement')) {
                PaymentReceived::dispatch($transaction, 'Uang Masuk! Rp '.number_format($transaction->total_amount), 'success');
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                PaymentReceived::dispatch($transaction, 'Transaksi Gagal/Expired.', 'error');
            }

            return response()->json(['message' => "Transaction updated to $newStatus"]);
        }

        return response()->json(['message' => 'No status change needed']);
    }
}
