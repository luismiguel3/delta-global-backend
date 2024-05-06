<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Users extends Seeder
{
    public function run()
    {
        $data = [
            'email' => 'teste@teste.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
        ];
        $this->db->table('users')->insert($data);
    }
}
