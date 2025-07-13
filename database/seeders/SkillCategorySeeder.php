<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\SkillCategory;

class SkillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubs = Club::all();
        
        $defaultCategories = [
            [
                'name' => 'Leadership',
                'description' => 'Ability to guide and motivate team members',
                'color' => '#EF4444', // Red
                'icon' => 'star',
                'max_points' => 1000,
            ],
            [
                'name' => 'Technical',
                'description' => 'Technical skills and expertise',
                'color' => '#3B82F6', // Blue
                'icon' => 'cog',
                'max_points' => 1000,
            ],
            [
                'name' => 'Communication',
                'description' => 'Effective communication and presentation skills',
                'color' => '#10B981', // Green
                'icon' => 'chat',
                'max_points' => 1000,
            ],
            [
                'name' => 'Teamwork',
                'description' => 'Collaboration and team participation',
                'color' => '#F59E0B', // Yellow
                'icon' => 'users',
                'max_points' => 1000,
            ],
            [
                'name' => 'Creativity',
                'description' => 'Innovation and creative problem solving',
                'color' => '#8B5CF6', // Purple
                'icon' => 'lightbulb',
                'max_points' => 1000,
            ],
            [
                'name' => 'Problem Solving',
                'description' => 'Analytical thinking and solution development',
                'color' => '#F97316', // Orange
                'icon' => 'puzzle',
                'max_points' => 1000,
            ]
        ];

        foreach ($clubs as $club) {
            foreach ($defaultCategories as $category) {
                SkillCategory::firstOrCreate(
                    [
                        'club_id' => $club->id,
                        'name' => $category['name']
                    ],
                    array_merge($category, [
                        'club_id' => $club->id,
                        'active' => true,
                    ])
                );
            }
        }
    }
}
