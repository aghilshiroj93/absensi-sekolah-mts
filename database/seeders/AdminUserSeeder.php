<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            [ 'username' => 'admin' ],
            [
                'super_admin' => 1,
                'password' => Hash::make('admin12345'), // Change this password after first login
            ]
        );

        // Regular user 1
        User::updateOrCreate(
            [ 'username' => 'user1' ],
            [
                'super_admin' => 0,
                'password' => Hash::make('user12345'),
            ]
        );

        // Regular user 2
        User::updateOrCreate(
            [ 'username' => 'user2' ],
            [
                'super_admin' => 0,
                'password' => Hash::make('user12345'),
            ]
        );
    }
}
