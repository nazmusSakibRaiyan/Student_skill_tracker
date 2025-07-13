<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
        
        // Seed clubs before skill categories
        $this->call(ClubSeeder::class);
        
        // Seed skill categories after roles, permissions, and clubs
        $this->call(SkillCategorySeeder::class);

        // Create admin user with master_admin role (HARDCODED - NEVER CHANGE)
        $masterAdminRole = \App\Models\Role::where('name', 'master_admin')->first();
        
        User::firstOrCreate(
            ['email' => 'nazmus.sakib.raiyan@g.bracu.ac.bd'],
            [
                'name' => 'Nazmus Sakib Raiyan',
                'email' => 'nazmus.sakib.raiyan@g.bracu.ac.bd',
                'password' => Hash::make('admin123'),
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
                'email_verified_at' => null, // Require email verification
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
                'email_verified_at' => null, // Require email verification
            ]
        );

        // Assign club manager to clubs and add students to clubs
        $this->assignClubMemberships();
    }

    /**
     * Assign club managers and students to clubs
     */
    private function assignClubMemberships()
    {
        // Get the first club (BRACU Response Team)
        $club = \App\Models\Club::where('name', 'BRACU Response Team')->first();
        
        // Get club manager user
        $clubManager = User::where('email', 'manager@example.com')->first();
        
        // Get student user  
        $student = User::where('email', 'student@example.com')->first();
        
        if ($club && $clubManager) {
            // Assign club manager to club
            \App\Models\ClubManager::firstOrCreate([
                'user_id' => $clubManager->id,
                'club_id' => $club->id,
            ], [
                'banned' => false,
            ]);
        }
        
        if ($club && $student) {
            // Add student to club
            \DB::table('club_student')->insertOrIgnore([
                'club_id' => $club->id,
                'user_id' => $student->id,
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
