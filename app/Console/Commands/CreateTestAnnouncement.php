<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Models\User;

class CreateTestAnnouncement extends Command
{
    protected $signature = 'test:announcement {type=all}';
    protected $description = 'Create test announcements for different user types';

    public function handle()
    {
        $type = $this->argument('type');
        $adminUser = User::where('email', 'like', '%admin%')->first();
        
        if (!$adminUser) {
            $adminUser = User::first(); // Fallback to first user
        }

        switch ($type) {
            case 'emergency':
                $announcement = Announcement::create([
                    'title' => '🚨 EMERGENCY: System Maintenance',
                    'message' => 'Urgent system maintenance will begin in 30 minutes. Please save your work immediately.',
                    'priority' => 'emergency',
                    'target_type' => 'all',
                    'target_filters' => [],
                    'created_by' => $adminUser->id,
                    'is_active' => true,
                ]);
                break;

            case 'club_manager':
                $announcement = Announcement::create([
                    'title' => 'Club Manager Meeting Tomorrow',
                    'message' => 'All club managers are required to attend the monthly meeting tomorrow at 2 PM in the conference room.',
                    'priority' => 'high',
                    'target_type' => 'role',
                    'target_filters' => ['roles' => ['club_manager']],
                    'created_by' => $adminUser->id,
                    'is_active' => true,
                ]);
                break;

            case 'students':
                $announcement = Announcement::create([
                    'title' => 'New Course Enrollments Open',
                    'message' => 'Registration for next semester courses is now open! Visit the student portal to enroll.',
                    'priority' => 'medium',
                    'target_type' => 'role',
                    'target_filters' => ['roles' => ['student']],
                    'created_by' => $adminUser->id,
                    'is_active' => true,
                ]);
                break;

            case 'club':
                // Get the first available club for testing
                $club = \App\Models\Club::first();
                if (!$club) {
                    $this->error('No clubs found in database. Please create a club first.');
                    return 1;
                }
                
                $announcement = Announcement::create([
                    'title' => '🚨 CLUB EMERGENCY: Urgent Meeting',
                    'message' => "Emergency club meeting for {$club->name} members scheduled for tomorrow at 3 PM. All students and managers of this club must attend.",
                    'priority' => 'emergency',
                    'target_type' => 'club',
                    'target_filters' => ['clubs' => [$club->id]],
                    'created_by' => $adminUser->id,
                    'is_active' => true,
                ]);
                
                $this->info("Targeted to club: {$club->name} (ID: {$club->id})");
                break;

            default:
                $announcement = Announcement::create([
                    'title' => 'Welcome to Student Skill Tracker!',
                    'message' => 'This is a test announcement visible to all users. The announcement system is working correctly!',
                    'priority' => 'medium',
                    'target_type' => 'all',
                    'target_filters' => [],
                    'created_by' => $adminUser->id,
                    'is_active' => true,
                ]);
                break;
        }

        $this->info("Test announcement created: {$announcement->title}");
        $this->info("Target: {$announcement->target_type}");
        $this->info("Priority: {$announcement->priority}");
        
        return 0;
    }
}
