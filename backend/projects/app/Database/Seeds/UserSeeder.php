<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $users = [
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com'],
            ['name' => 'Bob Smith', 'email' => 'bob.smith@example.com'],
            ['name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com'],
            ['name' => 'Daisy Williams', 'email' => 'daisy.williams@example.com'],
            ['name' => 'Ethan Davis', 'email' => 'ethan.davis@example.com'],
            ['name' => 'Fiona Miller', 'email' => 'fiona.miller@example.com'],
            ['name' => 'George Wilson', 'email' => 'george.wilson@example.com'],
            ['name' => 'Hannah Moore', 'email' => 'hannah.moore@example.com'],
            ['name' => 'Ian Taylor', 'email' => 'ian.taylor@example.com'],
            ['name' => 'Julia Anderson', 'email' => 'julia.anderson@example.com'],
            ['name' => 'Kevin Thomas', 'email' => 'kevin.thomas@example.com'],
            ['name' => 'Laura Jackson', 'email' => 'laura.jackson@example.com'],
            ['name' => 'Michael White', 'email' => 'michael.white@example.com'],
            ['name' => 'Nina Harris', 'email' => 'nina.harris@example.com'],
            ['name' => 'Oscar Martin', 'email' => 'oscar.martin@example.com'],
            ['name' => 'Paula Thompson', 'email' => 'paula.thompson@example.com'],
            ['name' => 'Quincy Garcia', 'email' => 'quincy.garcia@example.com'],
            ['name' => 'Rachel Martinez', 'email' => 'rachel.martinez@example.com'],
            ['name' => 'Steven Robinson', 'email' => 'steven.robinson@example.com'],
            ['name' => 'Tina Clark', 'email' => 'tina.clark@example.com'],
            ['name' => 'Ulysses Rodriguez', 'email' => 'ulysses.rodriguez@example.com'],
            ['name' => 'Victoria Lewis', 'email' => 'victoria.lewis@example.com'],
            ['name' => 'William Lee', 'email' => 'william.lee@example.com'],
            ['name' => 'Xander Walker', 'email' => 'xander.walker@example.com'],
            ['name' => 'Yasmine Hall', 'email' => 'yasmine.hall@example.com'],
            ['name' => 'Zachary Allen', 'email' => 'zachary.allen@example.com'],
            ['name' => 'Amelia Young', 'email' => 'amelia.young@example.com'],
            ['name' => 'Benjamin King', 'email' => 'benjamin.king@example.com'],
            ['name' => 'Clara Wright', 'email' => 'clara.wright@example.com'],
            ['name' => 'Daniel Scott', 'email' => 'daniel.scott@example.com'],
        ];

        foreach ($users as &$user) {
            $user['password'] = password_hash('password', PASSWORD_DEFAULT);
            $user['created_at'] = date('Y-m-d H:i:s');
            $user['updated_at'] = date('Y-m-d H:i:s');
        }

        $db->table('users')->insertBatch($users);
    }
}
