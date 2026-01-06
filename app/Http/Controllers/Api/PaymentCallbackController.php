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
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $type = $payload['payment_type'] ?? null;

        if (! $orderId) {
            return response()->json(['message' => 'Invalid Order ID'], 400);
        }

        $transaction = DuTransaction::where('trx_code', $orderId)
            ->orWhere('trx_code', explode('-', $orderId)[0].'-'.explode('-', $orderId)[1].'-'.explode('-', $orderId)[2])
            ->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $newStatus = null;
        $note = "Callback received: $transactionStatus";

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $newStatus = 'pending_docs';
            $note = "Pembayaran BERHASIL via $type.";

        } elseif ($transactionStatus == 'pending') {
            $newStatus = 'pending_docs';

        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $newStatus = 'expired';
            $note = "Pembayaran GAGAL/EXPIRED ($transactionStatus).";
        }

        if ($newStatus && $transaction->status != $newStatus) {
            $transaction->update([
                'status' => $newStatus,
                'midtrans_response' => json_encode($payload),
                'payment_method' => $type,
                'payment_date' => now(),
                'admin_note' => $note,
            ]);

            if ($newStatus == 'pending_doc') {
                PaymentReceived::dispatch($transaction, 'Dana Masuk! Rp '.number_format($transaction->total_amount), 'success');
            } elseif ($newStatus == 'expired') {
                PaymentReceived::dispatch($transaction, 'Transaksi Expired/Gagal.', 'error');
            }

            return response()->json(['message' => "Transaction updated to $newStatus"]);
        }

        return response()->json(['message' => 'No status change needed']);
    }
}
