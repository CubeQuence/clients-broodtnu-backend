<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;


class RegisterConfirmation extends Mailable
{
	public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown(
            'mail.RegisterConfirmation',
            [
                'verifyEmailUrl' => env('APP_DOMAIN') . '/auth/verify/' . $this->user->verify_email_token
            ]
        );
    }
}