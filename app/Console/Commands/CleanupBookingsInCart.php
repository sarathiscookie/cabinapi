<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Booking;
use Carbon\Carbon;

class CleanupBookingsInCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bookings:cartCleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes bookings that are added to cart for more than 3 days';

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
        $bookings = Booking::where('status', "8")->get();

        foreach ($bookings as $booking) {
            $hoursDiff = $booking->bookingdate->diffInHours(Carbon::now());

            if ($hoursDiff > 1) {
                $booking->is_delete = 1;
                $booking->save();
            }
        }
    }
}
