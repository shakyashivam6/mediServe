<?php

namespace Database\Seeders;

use App\Models\SetConfig;
use Illuminate\Database\Seeder;

class SetConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds the basic site settings used across the app. Only SITELOGO
     * has a real value for now; the rest are placeholders to fill in later.
     */
    public function run(): void
    {
        $settings = [
            'SITELOGO' => 'assets/images/logo.png',
            'SITENAME' => 'MediServe',
            'SITEEMAIL' => '',
            'SITEPHONE' => '',
            'SITEADDRESS' => '',
        ];

        foreach ($settings as $key => $value) {
            SetConfig::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
