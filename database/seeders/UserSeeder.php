<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed around 30 demo users using Faker/factory and fixed password.
     */
    public function run(): void
    {
        // Ensure a deterministic set of named demo users exists.
        $namedUsers = [
            ['name' => 'John Doe', 'email' => 'john.doe@rankit.demo'],
            ['name' => 'Alice Tan', 'email' => 'alice.tan@rankit.demo'],
            ['name' => 'Ahmad Hakim', 'email' => 'ahmad.hakim@rankit.demo'],
            ['name' => 'Nur Aisyah', 'email' => 'nur.aisyah@rankit.demo'],
            ['name' => 'David Lee', 'email' => 'david.lee@rankit.demo'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@rankit.demo'],
            ['name' => 'Farid Azman', 'email' => 'farid.azman@rankit.demo'],
            ['name' => 'Sarah Lim', 'email' => 'sarah.lim@rankit.demo'],
            ['name' => 'Daniel Wong', 'email' => 'daniel.wong@rankit.demo'],
            ['name' => 'Aina Sofea', 'email' => 'aina.sofea@rankit.demo'],
        ];

        foreach ($namedUsers as $user) {
            User::query()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
            ]);
        }

        // Complete the pool to 30 users using the factory.
        User::factory()->count(20)->create([
            'password' => Hash::make('password'),
        ]);
    }
}
