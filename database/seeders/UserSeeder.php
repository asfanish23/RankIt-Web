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
            ['id' => 'demo_user_1', 'name' => 'John Doe', 'email' => 'john.doe@rankit.demo'],
            ['id' => 'demo_user_2', 'name' => 'Alice Tan', 'email' => 'alice.tan@rankit.demo'],
            ['id' => 'demo_user_3', 'name' => 'Ahmad Hakim', 'email' => 'ahmad.hakim@rankit.demo'],
            ['id' => 'demo_user_4', 'name' => 'Nur Aisyah', 'email' => 'nur.aisyah@rankit.demo'],
            ['id' => 'demo_user_5', 'name' => 'David Lee', 'email' => 'david.lee@rankit.demo'],
            ['id' => 'demo_user_6', 'name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@rankit.demo'],
            ['id' => 'demo_user_7', 'name' => 'Farid Azman', 'email' => 'farid.azman@rankit.demo'],
            ['id' => 'demo_user_8', 'name' => 'Sarah Lim', 'email' => 'sarah.lim@rankit.demo'],
            ['id' => 'demo_user_9', 'name' => 'Daniel Wong', 'email' => 'daniel.wong@rankit.demo'],
            ['id' => 'demo_user_10', 'name' => 'Aina Sofea', 'email' => 'aina.sofea@rankit.demo'],
        ];

        foreach ($namedUsers as $user) {
            User::query()->create([
                'id' => $user['id'],
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
