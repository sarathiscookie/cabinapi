<?php

namespace App\Mail;

use App\Userlist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FaultyPayment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var $userDetails
     */
    protected $userDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Userlist $userDetails)
    {
        $this->userDetails = $userDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.FaultyPayment')
            ->to($this->userDetails->usrEmail)
            ->subject('Fehlerhafte Zahlung für Ihr Hüttenbuchung')
            ->with([
                'firstname' => $this->userDetails->usrFirstname,
                'lastname' => $this->userDetails->usrLastname,
                'userID' => $this->userDetails->_id,
                'subject' => 'Fehlerhafte Zahlung Ihrer Hüttenbuchung'
            ]);
    }
}
