<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Payment>
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentTypes = ['Iuran Bulanan', 'Iuran Keamanan', 'Listrik', 'Air', 'Sampah'];
        $statuses = ['pending', 'paid', 'overdue', 'cancelled'];
        
        return [
            'house_id' => House::factory(),
            'payment_type' => $this->faker->randomElement($paymentTypes),
            'amount' => $this->faker->numberBetween(50000, 500000),
            'due_date' => $this->faker->dateTimeBetween('-3 months', '+1 month'),
            'paid_date' => $this->faker->optional()->dateTimeBetween('-2 months', 'now'),
            'status' => $this->faker->randomElement($statuses),
            'receipt_number' => $this->faker->optional()->numerify('RCP-####-###'),
            'description' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
            'created_by' => User::factory(),
            'paid_by' => $this->faker->optional()->randomElement(
                User::whereIn('role', ['administrator', 'housing_manager'])->pluck('id')->toArray()
            ),
        ];
    }

    /**
     * Indicate that the payment is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'receipt_number' => $this->faker->numerify('RCP-####-###'),
        ]);
    }

    /**
     * Indicate that the payment is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
            'paid_date' => null,
        ]);
    }
}