<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Address;

class GeoFenceAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $ipAddress;
    public string $country;
    public string $uri;
    public string $method;
    public string $userAgent;
    public string $attemptTime;

    public function __construct(array $details)
    {
        $this->ipAddress = $details['ip'] ?? 'Unknown';
        $this->country = $details['country'] ?? 'Unknown';
        $this->uri = $details['uri'] ?? 'Unknown';
        $this->method = $details['method'] ?? 'Unknown';
        $this->userAgent = $details['user_agent'] ?? 'Unknown';
        $this->attemptTime = $details['timestamp'] ?? now()->toDateTimeString();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(Config::get('mail.from.address'), Config::get('mail.from.name')),
            subject: 'Unauthorized Access Attempt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.geo_fence_alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
