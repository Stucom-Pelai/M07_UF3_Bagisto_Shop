<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Product\Models\Product;
use Webkul\Admin\Mail\Mailable;
use Webkul\Customer\Contracts\Customer;

class DropPriceNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Product $product,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans("PRICE DROP "),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.price-drop',
            with: [
                'product' => $this->product,
            ]
        );
    }
}
