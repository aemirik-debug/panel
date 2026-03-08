<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contact $contact,
        public ?string $tenantId = null,
    ) {
    }

    public function build(): self
    {
        $siteLabel = $this->tenantId ? 'Tenant: ' . $this->tenantId : 'Merkezi site';

        return $this
            ->subject('Yeni iletişim formu mesajı')
            ->view('emails.contact-form-submitted')
            ->with([
                'contact' => $this->contact,
                'siteLabel' => $siteLabel,
            ]);
    }
}
