<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerAccountStatementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerAccount;

    /**
     * Create a new message instance.
     */
    public function __construct($customerAccount)
    {
        $this->customerAccount = $customerAccount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Statement From Bowes Tyres',
            cc: [env('MAIL_CC_ADDRESS')]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-account-statement',
            with: [
                'customerAccount' => $this->customerAccount
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('customer_accounts.account-statement', [
            'customerAccount' => $this->customerAccount
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'customer_account.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
