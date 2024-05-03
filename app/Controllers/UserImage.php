<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserImage extends BaseController
{
  public function create($id)
  {
    $userModel = new UserModel();
    $image = $this->request->getFile('image');
    $user = $userModel->find($id);

    if (!$image->isValid() && !$image->hasMoved()) {
      return $this->response->setJSON([
        'message' => 'Erro ao enviar a imagem',
        'errors' => $image->getErrorString()
      ])->setStatusCode(400);
    }

    if ($user['photo']) {
      helper('filesystem');
      delete_files('uploads/' . $user['photo']);
    }

    $imageName = $image->getRandomName();
    $image->move("uploads/" . $imageName);

    $user['photo'] = $imageName;
    $userModel->save($user);

    return $this->response->setJSON(['message' => 'Imagem enviada com sucesso'])->setStatusCode(201);
  }
}
