<?php

namespace Webkul\CartRule\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\CartRule\Database\Seeders\WelcomeCouponSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WelcomeCouponSeeder::class);
    }
}
