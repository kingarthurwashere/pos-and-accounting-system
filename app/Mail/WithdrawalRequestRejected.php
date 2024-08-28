<?php

namespace App\Mail;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WithdrawalRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public WithdrawalRequest $withdrawalRequest;

    public function __construct(WithdrawalRequest $withdrawalRequest)
    {
        Log::info('Building Email...');
        Log::info($withdrawalRequest->toArray());
        $this->withdrawalRequest = $withdrawalRequest;
    }

    public function build()
    {
        return $this->markdown('emails.withdrawal.rejected')
            ->subject('Request Rejected #' . $this->withdrawalRequest->id)
            ->with([
                'withdrawalRequest' => $this->withdrawalRequest,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Rejected #' . $this->withdrawalRequest->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
