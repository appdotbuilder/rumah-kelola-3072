<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HousingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sirumah.com',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'phone' => '08123456789',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create housing manager
        $manager = User::create([
            'name' => 'Manajer Perumahan',
            'email' => 'manager@sirumah.com',
            'password' => Hash::make('password'),
            'role' => 'housing_manager',
            'phone' => '08234567890',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create sales staff
        $sales = User::create([
            'name' => 'Staf Penjualan',
            'email' => 'sales@sirumah.com',
            'password' => Hash::make('password'),
            'role' => 'sales_staff',
            'phone' => '08345678901',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create resident users
        $residents = User::factory(10)->create([
            'role' => 'resident',
            'email_verified_at' => now(),
        ]);

        // Create houses
        $houses = House::factory(25)->create();

        // Create residents for houses
        foreach ($houses->take(15) as $house) {
            $resident = Resident::factory()->create([
                'house_id' => $house->id,
                'user_id' => $residents->random()->id,
            ]);

            // Update house with owner info
            $house->update([
                'owner_name' => $resident->name,
                'owner_phone' => $resident->phone,
                'status' => 'sold',
                'handover_date' => fake()->dateTimeBetween('-2 years', 'now'),
            ]);
        }

        // Create some payments
        foreach ($houses->where('status', 'sold')->take(10) as $house) {
            // Create monthly maintenance payments
            for ($i = 0; $i < 6; $i++) {
                Payment::create([
                    'house_id' => $house->id,
                    'payment_type' => 'Iuran Bulanan',
                    'amount' => random_int(100000, 300000),
                    'due_date' => now()->subMonths($i)->endOfMonth(),
                    'paid_date' => fake()->boolean(70) ? now()->subMonths($i)->addDays(random_int(1, 5)) : null,
                    'status' => fake()->boolean(70) ? 'paid' : 'pending',
                    'description' => 'Iuran pemeliharaan bulan ' . now()->subMonths($i)->format('F Y'),
                    'created_by' => $manager->id,
                    'paid_by' => fake()->boolean(70) ? $manager->id : null,
                ]);
            }
        }

        // Create some complaints
        foreach ($houses->where('status', 'sold')->take(8) as $house) {
            $resident = $house->residents()->where('is_active', true)->first();
            if ($resident && isset($resident->user_id)) {
                Complaint::create([
                    'house_id' => $house->id,
                    'reported_by' => $resident->user_id,
                    'title' => fake()->randomElement([
                        'Lampu jalan mati',
                        'Keran air bocor',
                        'Pagar rusak',
                        'Jalan berlubang',
                        'Tempat sampah penuh',
                        'Keamanan kurang',
                        'Tetangga berisik'
                    ]),
                    'description' => fake()->paragraph(3),
                    'category' => fake()->randomElement(['maintenance', 'security', 'facility', 'neighbor']),
                    'priority' => fake()->randomElement(['low', 'medium', 'high']),
                    'status' => fake()->randomElement(['open', 'in_progress', 'resolved']),
                    'assigned_to' => fake()->boolean(60) ? $manager->id : null,
                    'response' => fake()->boolean(40) ? fake()->paragraph(2) : null,
                    'target_resolution_date' => fake()->boolean(50) ? fake()->dateTimeBetween('now', '+1 month') : null,
                    'resolved_date' => fake()->boolean(30) ? fake()->dateTimeBetween('-1 month', 'now') : null,
                    'estimated_cost' => fake()->boolean(40) ? random_int(100000, 2000000) : null,
                ]);
            }
        }
    }
}