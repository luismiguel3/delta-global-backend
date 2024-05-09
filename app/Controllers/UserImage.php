<?php

namespace App\Controllers;

use App\Models\StudentModel;

class UserImage extends BaseController
{
  public function create($id)
  {
    $userModel = new StudentModel();
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

    $imageName = $image->getName();

    $image->move(WRITEPATH . 'uploads/', $imageName, true);
    
    $user['photo'] = $imageName;
    $userModel->save($user);

    return $this->response->setJSON(['message' => 'Imagem enviada com sucesso'])->setStatusCode(201);
  }

  public function show($id)
  {
    $userModel = new StudentModel();
    $user = $userModel->find($id);

    if (!$user) {
      return $this->response->setJSON(['message' => 'Usuário não encontrado'])->setStatusCode(404);
    }
    $path = WRITEPATH . 'uploads/' . $user['photo'];

    if (file_exists($path)) {
      $mime = mime_content_type($path);
      header('Content-Type: ' . $mime);
      readfile($path);
      exit;
    }
  }
}
