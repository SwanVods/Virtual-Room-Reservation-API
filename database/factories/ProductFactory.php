<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();
        return [
            'name' => $name,
            'slug' => str_replace(' ', '-', $name),
            'description' => $this->faker->text(100),
            'price' => $this->faker->randomDigit(),
            'capacity' => $this->faker->randomDigit(),
            'size' => $this->faker->word(),
            'access_route' => $this->faker->realText(),
        ];
    }
}
