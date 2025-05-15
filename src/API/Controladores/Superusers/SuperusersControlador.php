<?php

declare(strict_types=1);

namespace App\API\Controladores\Superusers;

use App\API\Controladores\Middlewares\Controlador;

final class SuperusersControlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index(): void
    {
        if($this->method != 'GET'){
            $this->metodoNaoPermitido();
        }

        $usuarios = $this->redis->get('users');

        if($usuarios == null){
            $this->response([
                'statusCode' => 404,
                'message' => 'Nenhum superuser encontrado',
            ]);
        }

        $usuarios = json_decode($usuarios, true);

        $superusers = array_filter($usuarios, function($usuario){
            return $usuario['score'] >= 900 and $usuario['active'];
        });

        sort($superusers);

        $this->redis->set('superusers', json_encode($superusers), $this->tempoDeCache);

        $this->response([
            'statusCode' => 200,
            'data' => $superusers,
        ]);
    }
}
