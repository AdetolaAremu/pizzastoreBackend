<?php

namespace Database\Seeders;

use App\Models\Pizza;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pizza::insert([
            ['name' => 'Lagos Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '1500', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Ikorodu Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '2500', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Epe Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '500', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Ijebu Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '1000', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Oyo Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '1200', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Ogun Pizza', 'description' => 'This is the pizza variant you will love to have and we say and we try thats the shit we post here', 'price' => '1500', 'variant_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
