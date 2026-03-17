<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    public function build(): self
    {
        return $this
            ->subject('Queue Test Email')
            ->text('emails.test');
    }
}
