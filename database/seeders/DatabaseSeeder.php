<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Create admin user with master_admin role
        $masterAdminRole = \App\Models\Role::where('name', 'master_admin')->first();
        
        User::updateOrCreate(
            ['email' => 'nazmus.sakib.raiyan@g.bracu.ac.bd'],
            [
                'name' => 'Nazmus Sakib Raiyan',
                'email' => 'nazmus.sakib.raiyan@g.bracu.ac.bd',
                'password' => Hash::make('Admin123'),
                'role_id' => $masterAdminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create club manager user
        $clubManagerRole = \App\Models\Role::where('name', 'club_manager')->first();
        
        User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Club Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => $clubManagerRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create student user
        $studentRole = \App\Models\Role::where('name', 'student')->first();
        
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
