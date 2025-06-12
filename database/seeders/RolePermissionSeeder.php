<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Create, read, update, delete users'],
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'View user information'],
            
            // Role Management
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'description' => 'Create, read, update, delete roles'],
            ['name' => 'assign_roles', 'display_name' => 'Assign Roles', 'description' => 'Assign roles to users'],
            
            // Club Management
            ['name' => 'manage_clubs', 'display_name' => 'Manage Clubs', 'description' => 'Create, read, update, delete clubs'],
            ['name' => 'view_clubs', 'display_name' => 'View Clubs', 'description' => 'View club information'],
            ['name' => 'manage_own_club', 'display_name' => 'Manage Own Club', 'description' => 'Manage assigned club'],
            
            // Student Management
            ['name' => 'manage_students', 'display_name' => 'Manage Students', 'description' => 'Manage student records'],
            ['name' => 'view_students', 'display_name' => 'View Students', 'description' => 'View student information'],
            ['name' => 'manage_own_profile', 'display_name' => 'Manage Own Profile', 'description' => 'Manage own profile'],
            
            // Skill Management
            ['name' => 'manage_skills', 'display_name' => 'Manage Skills', 'description' => 'Create, read, update, delete skills'],
            ['name' => 'view_skills', 'display_name' => 'View Skills', 'description' => 'View skill information'],
            ['name' => 'track_skills', 'display_name' => 'Track Skills', 'description' => 'Track student skill progress'],
            ['name' => 'view_own_skills', 'display_name' => 'View Own Skills', 'description' => 'View own skill progress'],
            
            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'View system reports'],
            ['name' => 'export_data', 'display_name' => 'Export Data', 'description' => 'Export data in various formats'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create Roles
        $masterAdmin = Role::firstOrCreate(
            ['name' => 'master_admin'],
            [
                'display_name' => 'Master Admin',
                'description' => 'Full system access with all permissions'
            ]
        );

        $clubManager = Role::firstOrCreate(
            ['name' => 'club_manager'],
            [
                'display_name' => 'Club Manager',
                'description' => 'Manages a specific club and its students'
            ]
        );

        $student = Role::firstOrCreate(
            ['name' => 'student'],
            [
                'display_name' => 'Student',
                'description' => 'Can view and manage own profile and skills'
            ]
        );

        // Assign all permissions to Master Admin
        $allPermissions = Permission::all();
        $masterAdmin->permissions()->sync($allPermissions->pluck('id'));

        // Assign specific permissions to Club Manager
        $clubManagerPermissions = Permission::whereIn('name', [
            'view_users',
            'view_clubs',
            'manage_own_club',
            'manage_students',
            'view_students',
            'view_skills',
            'track_skills',
            'view_reports',
            'export_data',
        ])->get();
        $clubManager->permissions()->sync($clubManagerPermissions->pluck('id'));

        // Assign specific permissions to Student
        $studentPermissions = Permission::whereIn('name', [
            'manage_own_profile',
            'view_own_skills',
            'view_clubs',
        ])->get();
        $student->permissions()->sync($studentPermissions->pluck('id'));
    }
}
