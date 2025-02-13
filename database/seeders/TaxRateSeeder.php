<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=TaxRateSeeder
     */
    public function run(): void
    {
        $taxRates = [
            ['name' => '0% Vat', 'value' => 0],
            ['name' => '13.5% Vat', 'value' => 13.5],
            ['name' => '23% Vat', 'value' => 23],
        ];

        foreach ($taxRates as $taxRate) {
            TaxRate::firstOrCreate(['name' => $taxRate['name']], $taxRate);
        }
    }
}
