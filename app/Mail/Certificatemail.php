<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $pdfContentBase64;

    public function __construct(
        string $pdfContent,
        public string $recipientName,
        public string $trainingTitle,
        public string $signatoryName,
        public string $signatoryTitle,
        public string $dateIssued,
    ) {
        $this->pdfContentBase64 = base64_encode($pdfContent);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('tagumcityrsp@gmail.com', 'Learning and Development'),
            subject: 'Your Certificate of Training Completion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'certificates.certificate',
            with: [
                'recipientName'  => $this->recipientName,
                'trainingTitle'  => $this->trainingTitle,
                'signatoryName'  => $this->signatoryName,
                'signatoryTitle' => $this->signatoryTitle,
                'dateIssued'     => $this->dateIssued,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => base64_decode($this->pdfContentBase64),
                'certificate.pdf'
            )->withMime('application/pdf'),
        ];
    }
}