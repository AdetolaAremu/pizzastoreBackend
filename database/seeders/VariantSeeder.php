<?php

namespace Database\Seeders;

use App\Models\Variant;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variant::insert([
            ['name' => 'pepperoni'],
            ['name' => 'veggie'],
            ['name' => 'cheese'],
            ['name' => 'margherita'],
            ['name' => 'hawai']
        ]);
    }
}
