<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FocusModeSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // get all tasks
        $tasks = $db->table('tasks')->get()->getResult();

        foreach ($tasks as $task) {
            // 50% chance to make it focusing
            if (rand(0, 1)) {
                $focusStart = date('Y-m-d H:i:s', strtotime('-'.rand(1, 5).' hours'));
                $focusEnd = rand(0, 1) ? date('Y-m-d H:i:s', strtotime($focusStart.' +'.rand(1, 3).' hours')) : null;

                $db->table('tasks')
                    ->where('id', $task->id)
                    ->update([
                        'is_focusing'   => 1,
                        'focus_start_at'=> $focusStart,
                        'focus_end_at'  => $focusEnd,
                    ]);
            }
        }
    }
}
