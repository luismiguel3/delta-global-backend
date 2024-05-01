<?php

namespace App\Controllers;

use App\Models\UserModel;


class Image extends BaseController
{
  public function create()
  {

    $image = $this->request->getFile('image');

    if ($image->isValid() && !$image->hasMoved()) {
      $imageName = $image->getRandomName();
      $image->move("uploads/" . $imageName);
      return $this->response->setJSON(['message' => 'Imagem enviada com sucesso'])->setStatusCode(201);
    }

  }


}
