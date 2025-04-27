<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDueDateToTasks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tasks', [
            'due_date' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'assigned_to', // adjust if needed based on your fields
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tasks', 'due_date');
    }
}
