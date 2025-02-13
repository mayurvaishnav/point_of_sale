<?php

namespace App\Mail;

use App\Models\ScheduledJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutoReOrderProductsMail extends Mailable
{
    use Queueable, SerializesModels;

    public ScheduledJob $scheduledJob;
    public $products;

    /**
     * Create a new message instance.
     */
    public function __construct(ScheduledJob $job, $products)
    {
        $this->scheduledJob = $job;
        $this->products = $products;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->scheduledJob->email_subject,
            cc: [env('MAIL_CC_ADDRESS')]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // dd($this->replacePlaceholders($this->scheduledJob->email_body));
        return new Content(
            htmlString: $this->replacePlaceholders($this->scheduledJob->email_body),
        );
    }

    private function replacePlaceholders($html)
    {
        $supplier = $this->products->first()->supplier;
        $productList = '<ul>';
        foreach ($this->products as $product) {
            $productList .= "<li>{$product->name} (Quantity: {$product->quantity})</li>";
        }
        $productList .= '</ul>';

        return str_replace(
            ['#supplierName#', '#productList#'],
            [$supplier->name, $productList],
            $html
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
