<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'project_id'  => ['type' => 'INT', 'unsigned' => true],
            'user_id'     => ['type' => 'INT', 'unsigned' => true],
            'role'        => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'member'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('project_members');
    }

    public function down()
    {
        $this->forge->dropTable('project_members');
    }
}
