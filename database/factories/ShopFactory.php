<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=> $this->faker->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            "address"=> $this->faker->address(),
            "image"=> fake()->imageUrl($width = 300, $height = 200)
        ];
    }
}
