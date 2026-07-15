<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;

class ExpireCoupons extends Command
{
    protected $signature = 'coupons:expire';
    protected $description = 'Automatically deactivate expired coupons';
    public function handle(): int
    {
        $expiredCoupons = Coupon::where('status', 1)
            ->where('end_date', '<', now())
            ->update([
                'status' => 0,
            ]);

        $this->info("{$expiredCoupons} coupon(s) deactivated.");
        return Command::SUCCESS;
    }
}