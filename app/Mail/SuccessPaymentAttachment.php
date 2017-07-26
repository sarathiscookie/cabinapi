<?php

namespace App\Mail;

use App\Booking;
use App\Userlist;
use Storage;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuccessPaymentAttachment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The booking instance.
     *
     * @var $bookingDetails
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
        $userDetails                    = Userlist::find($this->bookingDetails->user);

        /* PDF Generation begin*/
        setlocale(LC_MONETARY, 'de_DE');

        $html_dav = '';
        if (is_array($userDetails->usrDAV) || is_object($userDetails->usrDAV)) {
            foreach ($userDetails->usrDAV as $one) {
                $html_dav.= $one . "<br>";
            }
        }
        else{
            $html_dav = "Nein";
        }

        /* Checking checkin_from, reserve_to and booking date fields are available or not begin */
        if(!$this->bookingDetails->checkin_from){
            $checkin_from = __('admin.noResult');
        }
        else {
            $checkin_from = ($this->bookingDetails->checkin_from)->format('d.m.y');
        }

        if(!$this->bookingDetails->reserve_to){
            $reserve_to = __('admin.noResult');
        }
        else {
            $reserve_to = ($this->bookingDetails->reserve_to)->format('d.m.y');
        }

        if(!$this->bookingDetails->bookingdate){
            $bookingdate = __('admin.noResult');
        }
        else {
            $bookingdate = ($this->bookingDetails->bookingdate)->format('d.m.y');
        }

        if($this->bookingDetails->checkin_from != '' && $this->bookingDetails->reserve_to != '') {
            $daysDifference = round(abs(strtotime(date_format($this->bookingDetails->checkin_from, 'd.m.Y')) - strtotime(date_format($this->bookingDetails->reserve_to, 'd.m.Y'))) / 86400);
        }
        else {
            $daysDifference = __('admin.noResult');
        }
        /* Checking checkin_from, reserve_to and booking date fields are available or not end */

        $html       = '<!DOCTYPE html>
                        <html lang="en">
                         <head>
                         <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                         <meta http-equiv="X-UA-Compatible" content="IE=edge">
                         <meta name="viewport" content="width=device-width, initial-scale=1">
                         <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
                         <title>Payment Successful</title>
                         <style> 
                             @page{ margin:0; }
                             body {
                                   font-family:font-family:arial,sans-serif;
                                   font-size:15px;
                                   }
                         </style>
                        </head>
                       <body>
                       <table style="padding:10px 30px;width:100%;font-family:arial,sans-serif;font-size:13px;">
                       
                            <tr><td colspan="3" style="color:#afca14;font-size:48px;float:right;" ><img  style="margin-top:15px;" width="300px" id="logo" src="'.public_path('img/pdf_title2.png').'" alt="Huetten-Holiday.de"></td><td style="text-align: right;padding-top:10px;" colspan="4"><img style="width: 250px;" id="logo" src="'.public_path('img/logo.png').'" alt="Huetten-Holiday.de"><br>Waltenhofen, den '.$bookingdate.'</td></tr> 
                            
                            <tr><td colspan="7" style="color:#afca14;font-size:95px;text-align:center;padding-top:40px;padding-bottom:0px;font-family:Amienne;" ><img width="300px" id="logo" src="'.public_path('img/pdf_title1.png').'" alt="Huetten-Holiday.de"></td></tr> 
                            <tr><td colspan="7" style="font-size:25px;font-weight:bold;text-align:center;padding-top:0px;padding-bottom:40px;" >'.$userDetails->usrFirstname.' '.$userDetails->usrLastname.'</td></tr>
                            <tr><td colspan="7" style="font-size:23px;padding-top:40px;padding-bottom:5px;color:#afca14;font-weight:bold;" >Ihre Daten</td></tr>
                            <tr style="background-color:#D9D9D9;font-weight:bold;"><td  colspan="2">Wohnort</td><td  colspan="3">Kontaktdaten</td><td  colspan="2">Vereinsmitglied</td></tr>
                            <tr><td colspan="2">'.$userDetails->usrAddress.'<br> '. $userDetails->usrZip.'<br>'.$userDetails->usrCity.' '.$userDetails->usrCountry.'</td><td  colspan="3" >'.$userDetails->usrTelephone.'<br>'.$userDetails->usrEmail.'</td><td  colspan="2">'.$html_dav.'</td></tr>
                            <tr><td colspan="6" style="font-size:23px;padding-top:40px;padding-bottom:5px;color:#afca14;font-weight:bold;" >Ihre Buchungsübersicht</td></tr>        
                            <tr style="background-color:#D9D9D9;font-weight:bold;">
                                <td style="width:16%">Hütte</td>
                                <td style="width:16%">Buchung</td>
                                <td style="width:16%">Anreise</td>
                                <td style="width:16%">Abreise</td>
                                <td style="width:16%">Anzahl</td>
                                <td style="width:16%">Nächte</td>
                                <td style="width:16%">Gutscheinwert</td>
                            </tr> 
                            
                            <tr>
                            <td>'.$this->bookingDetails->cabinname.'</td>
                            <td>'.$this->bookingDetails->invoice_number.'</td>
                            <td>'.$checkin_from.'</td>
                            <td>'.$reserve_to.'</td> 
                            <td>'.$this->bookingDetails->sleeps.'</td>
                            <td>'.$daysDifference.'</td>
                            <td>'.money_format('%=*^-14#8.2i', $this->bookingDetails->prepayment_amount).' &euro;</td>
                            </tr>
                       </table>
                       
                       <table style="padding:10px 30px;width:100%;font-family:font-family:arial,sans-serif;font-size:13px;"><tr><td colspan="6" style="font-size:23px;padding-top:40px;padding-bottom:5px;color:#afca14;font-weight:bold;" >Wichtige Informationen</td></tr>        
                              <tr><td colspan="1"><img style="width: 20px" id="logo" src="'.public_path('img/plus.png').'"> </td><td colspan="6">Legen Sie diesen Gutschein bei Ankunft dem Hüttenwirt vor.</td> </tr>   
                              <tr><td colspan="1"><img style="width: 20px" id="logo" src="'.public_path('img/plus.png').'"> </td><td colspan="6">Falls Sie Mitglied in einem Alpenverein sind, wird dies vom Hüttenwirt vor Ort geprüft</td> </tr>  
                              <tr><td colspan="1"><img style="width: 20px" id="logo" src="'.public_path('img/plus.png').'"> </td><td colspan="6">Ihre Anzahlung wird mit dem Übernachtungspreis direkt auf der Hütte verrechnet</td> </tr>  
                              <tr><td colspan="1"><img style="width: 20px" id="logo" src="'.public_path('img/plus.png').'"> </td><td colspan="6">Ihnen wird auf der Hütte der Gutscheinwert entsprechend der anwesenden Personen verrechnet.<br>Die Online-Gebühr bleibt hiervon unberührt</td> </tr>
                        </table>
                          <div style="position:fixed; padding:10px 20px;height:60px;bottom:0px;font-family:arial,sans-serif;font-size:12px;width: 100%; background-color: rgb(162, 198, 20); color:#fff;">
                               <span style="float:left;width:17%"><img style="width: 100px" id="logo" src="'.public_path('img/pdf_logo.png').'"></span>
                               <span style="text-align:left;float:left;width:20%;padding-top:18px;">Huetten-Holiday.de<br>Huetten-Holiday.de GmbH</span>
                               <span style="text-align:left;float:left;width:18%;padding-top:18px;">Nebelhornstraße 3<br>87448 Waltenhofen</span>
                               <span style="text-align:left;float:left;width:20%;padding-top:18px;">Umsatzsteuer-Id-Nr.:<br>DE 310 927 476</span>
                               <span style="text-align:right;float:left;width:25%;padding-top:18px;"><img  width="15px"  src="'.public_path('img/phone.png').'"> +49 (0) 9001 / 32 99 99<br><img  width="15px"  src="'.public_path('img/email.png').'">service@huetten-holiday.de</span>
                           </div>
                       </body>
                       </html>';

        PDF::loadHTML($html)->setPaper('a4', 'portrait')->setWarnings(false)->save(storage_path("app/public/Gutschein-". $this->bookingDetails->invoice_number . ".pdf"));
        /* PDF Generation end*/

        return $this->view('emails.successPaymentAttachment')
            ->to('iamsarath1986@gmail.com')
            /* ->bcc(env('MAIL_BCC_PAYMENT')) */
            /* ->to($userDetails->usrEmail) */
            ->subject('Ihre Gutschein für Ihre Buchung-'.$this->bookingDetails->cabinname)
            ->attach(public_path('storage/Huetten-Holiday-AGB.pdf'), [
                'mime' => 'application/pdf',
            ])
            ->attach(public_path("storage/Gutschein-". $this->bookingDetails->invoice_number . ".pdf"), [
                'mime' => 'application/pdf',
            ])
            ->with([
                'firstname' => $userDetails->usrFirstname,
                'lastname' => $userDetails->usrLastname,
                'userID' => $userDetails->_id,
                'subject' => 'Ihre Gutschein für Ihre Buchung-'.$this->bookingDetails->cabinname
            ]);
    }
}
