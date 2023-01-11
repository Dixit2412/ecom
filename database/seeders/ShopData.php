<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShopData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = Shop::factory()->count(100)->create();
        if(!empty($model)){
            foreach ($model as $shop_key => $shop_value) {
                User::create([
                    'name' => $shop_value->name,
                    'email' => $shop_value->email,
                    'email_verified_at' => now(),
                    'password' => Hash::make($shop_value->email),
                    'shop_id' => $shop_value->id,
                    'type' => 'shop',
                ]);
            }
        }
    }
}
