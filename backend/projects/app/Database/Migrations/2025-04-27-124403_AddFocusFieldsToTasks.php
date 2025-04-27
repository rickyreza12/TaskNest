<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFocusFieldsToTasks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tasks', [
            'is_focusing' => [
                'type'       => 'BOOLEAN',
                'default'    => 0,
                'after'      => 'due_date',
            ],
            'focus_start_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'is_focusing',
            ],
            'focus_end_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'focus_start_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tasks', ['is_focusing', 'focus_start_at', 'focus_end_at']);
    }
}
