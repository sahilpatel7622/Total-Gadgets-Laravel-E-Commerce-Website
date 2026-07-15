<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouponCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Coupon $coupon,
        public User $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Coupon - Total Gadgets',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.coupons.created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}