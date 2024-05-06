<?php

namespace App\Controllers;


use App\Models\UserModel;

class User extends BaseController
{
    private function is_admin()
    {
        $userModel = new UserModel();

        if (!$this->request->hasHeader('Authorization'))
            return false;


        $token = $this->request->getHeader('Authorization')->getValue();
        $user = $userModel->where('remember_me_token', $token)->first();

        return $user && $user['role'] === 'admin';
    }
    private function get_photo_url($user)
    {
        $base_url = $this->request->getServer('HTTP_HOST');
        if ($user['photo']) {
            return [
                'image' => 'http://' . $base_url . '/image/' . $user['id'],
                'name' => $user['photo']
            ];
        }
        return null;
    }

    public function showAll()
    {
        $userModel = new UserModel();
        $users = $userModel->select('id, name, email, photo, phone, address, role')->findAll();

        foreach ($users as $key => $user) {
            $users[$key]['photo'] = $this->get_photo_url($user);
        }

        return $this->response->setJSON($users);
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

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para criar um usuário'])->setStatusCode(403);
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

    public function show($id)
    {
        $userModel = new UserModel();
        $user = $userModel->select('id, name, email, photo, phone, address, role')->find($id);

        if (!$user) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }
        $data = [
            ...$user,
            'photo' => $this->get_photo_url($user)
        ];

        return $this->response->setJSON($data);
    }
    public function edit($id)
    {
        $userModel = new UserModel();
        $body = $this->request->getJSON();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para editar este usuário'])->setStatusCode(403);
        }

        $userModel->update($id, $body);

        if ($userModel->errors()) {
            return $this->response->setJSON($userModel->errors())->setStatusCode(400);
        }

        return $this->response->setJSON(['message' => 'Usuário atualizado com sucesso'])->setStatusCode(200);
    }
    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
        }

        if (!$this->is_admin()) {
            return $this->response->setJSON(['message' => 'Você não tem permissão para deletar este usuário'])->setStatusCode(403);
        }

        $userModel->delete($id);

        return $this->response->setJSON(['message' => 'Usuário deletado com sucesso'])->setStatusCode(200);
    }
}
