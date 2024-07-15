<?php

namespace Database\Factories;

use App\Models\Listings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listings>
 */
class ListingsFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listings::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->word(),
            'imageSrc'=>$this->faker->imageUrl(),
            'imageHref'=>$this->faker->sentence(),
            'price' => $this->faker->randomFloat(),
            'shopId'=> $this->faker->randomDigitNotZero()

        ];
    }
}
