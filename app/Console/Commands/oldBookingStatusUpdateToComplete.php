<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Booking;
use DateTime;

class oldBookingStatusUpdateToComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookingStatusUpdate:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Old bookings status change from fix to completed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date             = date('d.m.y');
        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime         = new DateTime($dateFormatChange);
        $timeStamp        = $dateTime->getTimestamp();
        $utcDateTime      = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);


        Booking::where('is_delete', 0)
            ->where('status', '1')
            ->whereIn('payment_status', ['1', '2'])
            ->whereRaw(['reserve_to' => ['$lt' => $utcDateTime]])
            ->update(['status' => '3']);
    }
}
