<?php

namespace Webkul\Installer\Database\Seeders\Tax;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxCategoriesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('tax_categories')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('tax_categories')->insert([
            'id'              => 1,
            'code'            => trans('installer::app.seeders.tax_categories.codes.code_1', [], $defaultLocale),
            'name'     => trans('installer::app.seeders.tax_categories.names.tech_cat', [], $defaultLocale),
            'description' => trans('installer::app.seeders.tax_categories.descriptions.tech_desc', [], $defaultLocale),
        ]);

        DB::table('tax_categories')->insert([
            'id'              => 2,
            'code'            => trans('installer::app.seeders.tax_categories.codes.code_2', [], $defaultLocale),
            'name'     => trans('installer::app.seeders.tax_categories.names.cloth_cat', [], $defaultLocale),
            'description' => trans('installer::app.seeders.tax_categories.descriptions.cloth_desc', [], $defaultLocale),
        ]);

        DB::table('tax_categories')->insert([
            'id'              => 3,
            'code'            => trans('installer::app.seeders.tax_categories.codes.code_3', [], $defaultLocale),
            'name'     => trans('installer::app.seeders.tax_categories.names.books_cat', [], $defaultLocale),
            'description' => trans('installer::app.seeders.tax_categories.descriptions.books_desc', [], $defaultLocale),
        ]);

        DB::table('tax_categories_tax_rates')->insert([
            'id' => 1,
            'tax_category_id' => 1,
            'tax_rate_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('tax_categories_tax_rates')->insert([
            'id' => 2,
            'tax_category_id' => 2,
            'tax_rate_id' => 2,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('tax_categories_tax_rates')->insert([
            'id' => 3,
            'tax_category_id' => 3,
            'tax_rate_id' => 3,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
