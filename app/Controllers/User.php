<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\UserModel;

class User extends BaseController
{

  public function showAll()
  {
    $studentModel = new UserModel();
    $students = $studentModel->findAll();


    return $this->response->setJSON($students);
  }

  public function create()
  {

    $userModel = new UserModel();
    $body = $this->request->getJSON();
    $validator = \Config\Services::validation();

    $is_valid = $this->validate($userModel->validationRules, $userModel->validationMessages);
    if (!$is_valid) {
      return $this->response->setJSON($validator->getErrors())->setStatusCode(400);
    }

    if ($userModel->where('email', $body->email)->first()) {
      return $this->response->setJSON(['message' => 'Email já cadastrado'])->setStatusCode(400);
    }

    $body->password = password_hash($body->password, PASSWORD_DEFAULT);
    $userModel->insert($body);

    $id = $userModel->getInsertID();

    return $this->response->setJSON([
      'message' => 'usuário criado com sucesso',
      'id' => $id,

    ])->setStatusCode(200);

  }

}
