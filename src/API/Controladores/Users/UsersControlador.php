<?php

declare(strict_types=1);

namespace App\API\Controladores\Users;

use App\Cache\Redis;
use App\API\Controladores\Middlewares\Controlador;

final class UsersControlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index(): void
    {
        if($this->method != 'POST' && $this->method != 'GET'){
            $this->metodoNaoPermitido();
        }

        if($this->method == 'GET'){

            $users = json_decode($this->redis->get('users'), true);

            if($users){
                $this->response([
                    'statusCode' => 200,
                    'message' => 'OK',
                    'data' => $users,
                ]);
            }else{
                $this->response([
                    'statusCode' => 404,
                    'message' => 'Nenhum usuário encontrado',
                ]);
            }
        }

        $this->redis->set('users', json_encode($_POST), $this->tempoDeCache);

        $this->response([
            'statusCode' => 200,
            'message' => 'Arquivo recebido com sucesso',
            'user_count' => count($_POST),
        ]);
    }
}
