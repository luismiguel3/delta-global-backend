<?php

namespace App\Controllers;

use App\Models\UserModel;


class Auth extends BaseController
{
	public function login()
	{
		$userModel = new UserModel();
		$body = $this->request->getJSON();
		$user = $userModel->where('email', $body->email)->first();

		if (!$user) {
			return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
		}

		if (!password_verify($body->password, $user['password'])) {
			return $this->response->setJSON(['message' => 'Senha inválida'])->setStatusCode(400);
		}

		$token = bin2hex(random_bytes(32));

		$userModel
			->where('id', $user['id'])
			->set(['remember_me_token' => $token])
			->update();


		return $this->response->setJSON([
			'message' => 'Usuário logado com sucesso',
			'token' => $token
		])->setStatusCode(200);

	}

	public function logout()
	{
		$userModel = new UserModel();
		$userModel
			->where('remember_me_token', $this->request->getHeader('Authorization')->getValue())
			->set(['remember_me_token' => null])
			->update();

		if ($userModel->affectedRows() === 0) {
			return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
		}

		return $this->response->setJSON(['message' => 'Usuário deslogado com sucesso'])->setStatusCode(200);
	}

}
