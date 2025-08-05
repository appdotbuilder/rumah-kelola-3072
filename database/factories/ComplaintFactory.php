<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\House;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Complaint>
     */
    protected $model = Complaint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['maintenance', 'security', 'facility', 'neighbor', 'other'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'resolved', 'closed', 'cancelled'];
        
        $titles = [
            'Lampu jalan mati',
            'Keran air bocor',
            'Pagar rusak',
            'Jalan berlubang',
            'Tempat sampah penuh',
            'Keamanan kurang',
            'Tetangga berisik'
        ];
        
        return [
            'house_id' => House::factory(),
            'reported_by' => User::factory(),
            'title' => $this->faker->randomElement($titles),
            'description' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement($categories),
            'priority' => $this->faker->randomElement($priorities),
            'status' => $this->faker->randomElement($statuses),
            'assigned_to' => $this->faker->optional()->randomElement(
                User::whereIn('role', ['administrator', 'housing_manager'])->pluck('id')->toArray()
            ),
            'response' => $this->faker->optional()->paragraph(2),
            'target_resolution_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'resolved_date' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'estimated_cost' => $this->faker->optional()->numberBetween(100000, 2000000),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the complaint is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
            'resolved_date' => null,
        ]);
    }

    /**
     * Indicate that the complaint is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the complaint is high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }
}