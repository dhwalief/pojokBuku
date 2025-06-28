<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // !! Create an admin and regular user, run only once for testing purposes !!
        //  This is a simple way to create an admin user for testing purposes.
        // In a real application, you might want to use a more secure method for creating admin users,
        // such as a registration form with proper validation and security measures
        User::create([
            'name' => 'Admin',
            'email' => 'admin@localhost',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // Create a regular user
        User::create([
            'name' => 'User',
            'email' => 'user@localhost',
            'password' => bcrypt('user'),
            'role' => 'user',
        ]);
    }
}
