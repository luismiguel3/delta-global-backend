<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["id", "name", "email", "password", "phone", "address", "photo", "role", "remember_me_token"];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required',
        'email' => 'required|valid_email',
        'password'=> 'required',
        'role' => 'required|in_list[admin,user]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'Nome é obrigatório'
        ],
        'email' => [
            'required' => 'Email é obrigatório',
            'valid_email' => 'Email não é válido'
        ],
        'password' => [
            'required' => 'Senha é obrigatória'
        ],
        'role' => [
            'required' => 'Role é obrigatório',
            'in_list' => 'Role não é válido'
        ]
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
