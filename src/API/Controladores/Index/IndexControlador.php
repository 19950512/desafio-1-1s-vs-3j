<?php

declare(strict_types=1);

namespace App\API\Controladores\Index;

use App\API\Controladores\Middlewares\Controlador;

final class IndexControlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index(): void
    {
        $this->response([
            'statusCode' => 422,
            'message' => 'Método não permitido',
        ]);
    }
}
