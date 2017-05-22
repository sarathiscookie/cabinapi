<?php

namespace App\Mail;

use App\Userlist;
use App\Booking;
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
    protected $bookingDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userDetails                    = Userlist::select('_id', 'usrFirstname', 'usrLastname', 'usrEmail')
            ->find($this->bookingDetails->user);

        return $this->view('emails.FaultyPayment')
            ->to($userDetails->usrEmail)
            ->subject('Fehlerhafte Zahlung für Ihr Hüttenbuchung')
            ->with([
                'firstname' => $userDetails->usrFirstname,
                'lastname' => $userDetails->usrLastname,
                'userID' => $userDetails->_id,
                'subject' => 'Fehlerhafte Zahlung Ihrer Hüttenbuchung'
            ]);
    }
}
