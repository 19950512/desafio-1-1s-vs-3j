<?php

declare(strict_types=1);

namespace App\API\Controladores\Errors;

use App\API\Controladores\Middlewares\Controlador;

final class Erro404Controlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        header("HTTP/1.0 404 Not Found");
    }
}
