<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Resident>
     */
    protected $model = Resident::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $relationships = ['owner', 'tenant', 'family_member'];
        
        return [
            'house_id' => House::factory(),
            'user_id' => $this->faker->optional()->randomElement(
                User::where('role', 'resident')->pluck('id')->toArray()
            ),
            'name' => $this->faker->name(),
            'email' => $this->faker->optional()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'id_number' => $this->faker->optional()->numerify('################'),
            'relationship' => $this->faker->randomElement($relationships),
            'move_in_date' => $this->faker->optional()->dateTimeBetween('-5 years', 'now'),
            'move_out_date' => null,
            'is_active' => true,
            'notes' => $this->faker->optional()->text(200),
        ];
    }

    /**
     * Indicate that the resident is an owner.
     */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'owner',
        ]);
    }

    /**
     * Indicate that the resident is a tenant.
     */
    public function tenant(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'tenant',
        ]);
    }

    /**
     * Indicate that the resident is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'move_out_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}