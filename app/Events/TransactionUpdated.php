<?php

namespace App\Events;

use App\Models\DuTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;
    public $message;
    public $status;

    public function __construct(DuTransaction $transaction, $message = 'Status Transaksi Diperbarui', $status = 'info')
    {
        $this->transaction = $transaction;
        $this->message = $message;
        $this->status = $status;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('student.' . $this->transaction->student_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'transaction.updated';
    }
}
