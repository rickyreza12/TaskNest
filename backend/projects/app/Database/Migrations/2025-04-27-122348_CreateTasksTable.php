<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'project_id'   => ['type' => 'INT', 'unsigned' => true],
            'assigned_to'  => ['type' => 'INT', 'unsigned' => true, 'null' => true], // nullable
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'  => ['type' => 'TEXT', 'null' => true],
            'status'       => ['type' => 'ENUM', 'constraint' => ['todo', 'in_progress', 'done'], 'default' => 'todo'],
            'focus_mode'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0: normal, 1: focus
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true], // for soft deletes
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}
