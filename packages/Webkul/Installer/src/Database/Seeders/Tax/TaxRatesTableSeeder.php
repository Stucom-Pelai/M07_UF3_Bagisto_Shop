<?php

namespace Webkul\Installer\Database\Seeders\Tax;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaxRatesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('tax_rates')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('tax_rates')->insert([
            'id'         => 1,
            'identifier'       => trans('installer::app.seeders.tax.identifiers.super_Reduced_IVA', [], $defaultLocale),
            'is_zip'      => '0',
            'zip_code'   => '',
            'zip_from'  => null,
            'zip_to'     => null,
            'state'    => 'Barcelona',
            'country'     => 'ES',
            'tax_rate'    => 4,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);

        DB::table('tax_rates')->insert([
            'id'         => 2,
            'identifier'       => trans('installer::app.seeders.tax.identifiers.reduced_IVA', [], $defaultLocale),
            'is_zip'      => '0',
            'zip_code'   => '',
            'zip_from'  => null,
            'zip_to'     => null,
            'state'    => 'Barcelona',
            'country'     => 'ES',
            'tax_rate'    => 10,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);        
        
        DB::table('tax_rates')->insert([
            'id'         => 3,
            'identifier'       => trans('installer::app.seeders.tax.identifiers.general_IVA', [], $defaultLocale),
            'is_zip'      => '0',
            'zip_code'   => '',
            'zip_from'  => null,
            'zip_to'     => null,
            'state'    => 'Barcelona',
            'country'     => 'ES',
            'tax_rate'    => 21,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
    }
}
