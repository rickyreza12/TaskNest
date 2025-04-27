<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tasks');

        $faker = \Faker\Factory::create();

        // Get available project IDs and user IDs
        $projectIds = $db->table('projects')->select('id')->get()->getResultArray();
        $userIds = $db->table('users')->select('id')->get()->getResultArray();

        if (empty($projectIds) || empty($userIds)) {
            echo "Please seed projects and users first!\n";
            return;
        }

        $projectIds = array_column($projectIds, 'id');
        $userIds = array_column($userIds, 'id');

        for ($i = 0; $i < 20; $i++) {
            $builder->insert([
                'project_id'  => $faker->randomElement($projectIds),
                'assigned_to' => $faker->randomElement($userIds),
                'title'       => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'status'      => $faker->randomElement(['todo', 'in_progress', 'done']),
                'focus_mode'  => $faker->numberBetween(0, 1),
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
