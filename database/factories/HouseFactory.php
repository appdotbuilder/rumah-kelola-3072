<?php

namespace Database\Factories;

use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\House>
     */
    protected $model = House::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $houseTypes = ['Type 36', 'Type 45', 'Type 54', 'Type 70', 'Type 90'];
        $statuses = ['available', 'sold', 'reserved', 'maintenance'];
        
        return [
            'block_number' => 'B' . $this->faker->numberBetween(1, 50) . '/' . $this->faker->numberBetween(1, 20),
            'address' => $this->faker->streetAddress(),
            'house_type' => $this->faker->randomElement($houseTypes),
            'land_area' => $this->faker->numberBetween(60, 200),
            'building_area' => $this->faker->numberBetween(36, 150),
            'status' => $this->faker->randomElement($statuses),
            'owner_name' => $this->faker->name(),
            'owner_phone' => $this->faker->phoneNumber(),
            'handover_date' => $this->faker->optional()->date(),
            'selling_price' => $this->faker->numberBetween(200000000, 800000000),
            'bedrooms' => $this->faker->numberBetween(2, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'notes' => $this->faker->optional()->text(200),
        ];
    }

    /**
     * Indicate that the house is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'owner_name' => null,
            'owner_phone' => null,
            'handover_date' => null,
        ]);
    }

    /**
     * Indicate that the house is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'handover_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ]);
    }
}