<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CeoWeeklyDigest extends Mailable
{
    use SerializesModels;

    /**
     * $digest is prepared by CeoWeeklyDigestService (next step).
     * This class only presents data — it does not query the database.
     */
    /**
     * @param  array<int, array{name: string, bytes: string}>  $pdfs
     */
    public function __construct(public array $digest, public array $pdfs = [])
    {
    }

    public function envelope(): Envelope
    {
        $start = $this->digest['week_start'] ?? '';
        $end = $this->digest['week_end'] ?? '';

        return new Envelope(
            subject: "Weekly briefing — {$start} to {$end}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.ceo-weekly-digest',
            with: [
                'digest' => $this->digest,
            ],
        );
    }

    public function attachments(): array
    {
        return array_map(
            fn (array $pdf) => Attachment::fromData(fn () => $pdf['bytes'], $pdf['name'])
                ->withMime('application/pdf'),
            $this->pdfs,
        );
    }
}
