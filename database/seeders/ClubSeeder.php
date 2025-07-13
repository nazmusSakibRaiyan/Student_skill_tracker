<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubs = [
            [
                'name' => 'BRACU Response Team',
                'description' => 'Emergency response and rescue operations team focusing on disaster management and community service.',
                'logo' => null,
            ],
            [
                'name' => 'BRACU Computer Club',
                'description' => 'Technology enthusiasts working on programming, web development, and innovation projects.',
                'logo' => null,
            ],
            [
                'name' => 'BRACU Debate Society',
                'description' => 'Developing communication skills, critical thinking, and public speaking through debates and discussions.',
                'logo' => null,
            ],
            [
                'name' => 'BRACU Cultural Club',
                'description' => 'Promoting arts, culture, and creative expression through various cultural activities and events.',
                'logo' => null,
            ],
            [
                'name' => 'BRACU Business Club',
                'description' => 'Fostering entrepreneurship, business skills, and professional development among students.',
                'logo' => null,
            ],
        ];

        foreach ($clubs as $clubData) {
            Club::firstOrCreate(
                ['name' => $clubData['name']],
                $clubData
            );
        }
    }
}
