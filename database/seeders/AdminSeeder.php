<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            // Update existing user to admin
            $admin->update([
                'role' => 'admin',
                'password' => Hash::make('admin123')
            ]);
            echo "Admin user updated successfully!\n";
        } else {
            // Create new admin user
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            echo "Admin user created successfully!\n";
        }
        
        echo "Admin login: admin@example.com / admin123\n";
    }
}
