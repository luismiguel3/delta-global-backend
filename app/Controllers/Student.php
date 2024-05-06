<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\UserModel;

class Student extends BaseController
{
    private function is_admin()
    {
        $userModel = new UserModel();

        if (!$this->request->hasHeader('Authorization'))
            return false;


        $token = $this->request->getHeader('Authorization')->getValue();
        $user = $userModel->where('remember_me_token', $token)->first();

        return $user;
    }
    
    private function get_photo_url($student)
    {
        $base_url = $this->request->getServer('HTTP_HOST');
        if ($student['photo']) {
            return [
                'image' => 'http://' . $base_url . '/image/' . $student['id'],
                'name' => $student['photo']
            ];
        }
        return null;
    }

    public function showAll()
    {
        $studentModel = new StudentModel();
        $students = $studentModel->select('id, name, email, photo, phone, address, role')->findAll();

        foreach ($students as $key => $student) {
            $students[$key]['photo'] = $this->get_photo_url($student);
        }

        return $this->response->setJSON($students);
    }

    public function create()
    {

        $studentModel = new StudentModel();
        $body = $this->request->getJSON();
        $validator = \Config\Services::validation();

        $is_valid = $this->validate($studentModel->validationRules, $studentModel->validationMessages);
        if (!$is_valid) {
            return $this->response->setJSON($validator->getErrors())->setStatusCode(400);
        }

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para criar um usuário'])->setStatusCode(403);
        }

        if ($studentModel->where('email', $body->email)->first()) {
            return $this->response->setJSON(['message' => 'Email já cadastrado'])->setStatusCode(400);
        }

        //$body->password = password_hash($body->password, PASSWORD_DEFAULT);
        $studentModel->insert($body);

        $id = $studentModel->getInsertID();

        return $this->response->setJSON([
            'message' => 'usuário criado com sucesso',
            'id' => $id,

        ])->setStatusCode(200);

    }

    public function show($id)
    {
        $studentModel = new StudentModel();
        $student = $studentModel->select('id, name, email, photo, phone, address')->find($id);

        if (!$student) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }
        $data = [
            ...$student,
            'photo' => $this->get_photo_url($student)
        ];

        return $this->response->setJSON($data);
    }
    
    public function edit($id)
    {
        $studentModel = new StudentModel();
        $body = $this->request->getJSON();
        $student = $studentModel->find($id);

        if (!$student) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para editar este usuário'])->setStatusCode(403);
        }

        $studentModel->update($id, $body);

        if ($studentModel->errors()) {
            return $this->response->setJSON($studentModel->errors())->setStatusCode(400);
        }

        return $this->response->setJSON(['message' => 'Usuário atualizado com sucesso'])->setStatusCode(200);
    }

    public function delete($id)
    {
        $studentModel = new StudentModel();
        $student = $studentModel->find($id);

        if (!$student) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para deletar este usuário'])->setStatusCode(403);
        }

        $studentModel->delete($id);

        return $this->response->setJSON(['message' => 'Usuário deletado com sucesso'])->setStatusCode(200);
    }
}
