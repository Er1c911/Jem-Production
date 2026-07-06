<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Alexander Chen',
                'position' => 'Creative Director',
                'initials' => 'AC',
                'description' => 'Visioner kreatif dengan pengalaman 10+ tahun dalam industri produksi digital dan desain.',
            ],
            [
                'name' => 'Sarah Rodriguez',
                'position' => 'Tech Lead',
                'initials' => 'SR',
                'description' => 'Engineer berpengalaman dalam arsitektur sistem dan optimalisasi performa aplikasi produksi.',
            ],
            [
                'name' => 'Marcus Johnson',
                'position' => 'Product Manager',
                'initials' => 'MJ',
                'description' => 'Strategist dalam pengembangan produk dengan fokus pada user experience dan market fit.',
            ],
            [
                'name' => 'Emma Kowalski',
                'position' => 'UX Designer',
                'initials' => 'EK',
                'description' => 'Desainer berbakat dalam menciptakan antarmuka intuitif dengan estetika monokromatis.',
            ],
            [
                'name' => 'David Martinez',
                'position' => 'DevOps Engineer',
                'initials' => 'DM',
                'description' => 'Spesialis infrastruktur cloud dan continuous deployment untuk aplikasi enterprise.',
            ],
            [
                'name' => 'Lisa Taylor',
                'position' => 'QA Lead',
                'initials' => 'LT',
                'description' => 'Quality assurance expert dengan metodologi testing komprehensif dan automation.',
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
