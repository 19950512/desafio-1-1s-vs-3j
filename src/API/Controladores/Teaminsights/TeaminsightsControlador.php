<?php

declare(strict_types=1);

namespace App\API\Controladores\Activeusersperday;

use App\API\Controladores\Middlewares\Controlador;

final class ActiveusersperdayControlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index(): void
    {
        if($this->method != 'GET'){
            $this->metodoNaoPermitido();
        }

        $this->response([
            'statusCode' => 200,
            'message' => 'OK, mas ainda não tem nada aqui',
        ]);
    }
}
