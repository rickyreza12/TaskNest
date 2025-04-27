<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectMemberSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Fetch all users and projects
        $users = $db->table('users')->get()->getResultArray();
        $projects = $db->table('projects')->get()->getResultArray();

        $members = [];

        foreach ($projects as $project) {
            // Assign 3 random users to each project
            $randomUsers = array_rand($users, 3);

            foreach ((array) $randomUsers as $index) {
                $members[] = [
                    'project_id' => $project['id'],
                    'user_id' => $users[$index]['id'],
                    'role' => 'member',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($members)) {
            $db->table('project_members')->insertBatch($members);
        }
    }
}
