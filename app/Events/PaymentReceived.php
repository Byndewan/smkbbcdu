<?php

namespace App\Events;

use App\Models\DuTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public $message;

    public $type;

    /**
     * Create a new event instance.
     */
    public function __construct($transaction, $message, $type = 'success')
    {
        $this->transaction = DuTransaction::with('student', 'bill')->find($transaction->id);
        Log::info('EVENT CONSTRUCTOR JALAN', [
            'trx' => $transaction?->id
        ]);
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-channel'),
        ];
    }

    public function broadcastAs()
    {
        return 'payment.received';
    }

    public function broadcastWith(): array
    {
        Log::info('BROADCAST JALAN');
        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'status' => $this->transaction->status,
                'total_amount' => $this->transaction->total_amount,
            ],
            'student' => $this->transaction->student->name,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}
