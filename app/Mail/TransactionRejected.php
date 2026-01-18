<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\DuTransaction;

class TransactionRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $type; // 'document' / 'payment'
    public $note; // Alasan penolakan

    /**
     * Create a new message instance.
     */
    public function __construct($transaction, $type, $note)
    {
        $this->transaction = $transaction;
        $this->type = $type;
        $this->note = $note;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $prefix = $this->type == 'document'
            ? '⚠️ Revisi Dokumen'
            : '❌ Pembayaran Ditolak';

        return new Envelope(
            subject: $prefix . ' - ' . $this->transaction->trx_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction_rejected',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
