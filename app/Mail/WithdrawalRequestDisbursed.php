<?php

namespace App\Mail;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestDisbursed extends Mailable
{
    use Queueable, SerializesModels;

    public WithdrawalRequest $withdrawalRequest;

    public function __construct(WithdrawalRequest $withdrawalRequest)
    {
        $this->withdrawalRequest = $withdrawalRequest;
    }

    public function build()
    {
        $adminEmail = env('ADMIN_EMAIL');
        return $this->markdown('emails.withdrawal.customer_disbursed')
            ->subject('🚀Funds Disbursed #' . $this->withdrawalRequest->id)
            ->cc($adminEmail)
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
            subject: '🚀Funds Disbursed #' . $this->withdrawalRequest->id,
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
