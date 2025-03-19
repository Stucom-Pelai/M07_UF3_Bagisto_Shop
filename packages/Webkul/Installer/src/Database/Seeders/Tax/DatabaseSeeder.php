<?php

namespace Webkul\Installer\Database\Seeders\Tax;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(TaxRatesTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(TaxCategoriesTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
