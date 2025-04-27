<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'name' => 'Highrise Apartment Tower',
                'description' => '30-story luxury apartment',
                'owner_id' => 1,
            ],
            [
                'name' => 'Riverside Shopping Mall',
                'description' => '5-floor riverside shopping mall',
                'owner_id' => 1,
            ],
            [
                'name' => 'Downtown Office Complex',
                'description' => 'Modern office buildings',
                'owner_id' => 1,
            ],
            [
                'name' => 'Bridge Construction - River X',
                'description' => 'Suspension bridge over River X',
                'owner_id' => 1,
            ],
            [
                'name' => 'City Hospital Expansion',
                'description' => 'New surgery wing and ICU',
                'owner_id' => 1,
            ],
            [
                'name' => 'Metro Line Phase 2',
                'description' => 'Underground metro construction',
                'owner_id' => 1,
            ],
            [
                'name' => 'Solar Power Plant Installation',
                'description' => 'Renewable energy construction',
                'owner_id' => 1,
            ],
            [
                'name' => 'Airport Terminal Renovation',
                'description' => 'New terminal design and upgrade',
                'owner_id' => 1,
            ],
            [
                'name' => 'Water Treatment Facility',
                'description' => 'Advanced water filtration plant',
                'owner_id' => 1,
            ],
            [
                'name' => 'National Museum Building',
                'description' => 'New national heritage museum',
                'owner_id' => 1,
            ],
        ];

        $this->db->table('projects')->insertBatch($projects);
    }
}
