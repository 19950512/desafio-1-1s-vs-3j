<?php

declare(strict_types=1);

namespace App;

final class Router
{

    public $controller;
    public $action;

    public function __construct(
	    readonly private string $request_uri,
    ){

        $uri = explode('/', $this->request_uri);
        $controllerName = explode('?', ($uri[1] ?? 'Index'))[0];
        $controllerName = ucfirst(empty($controllerName) ? 'Index' : $controllerName);
        
        $controllerName = str_replace(
            ['-', '_'],
            '',
            $controllerName
        );

        $action = ($uri[2] ?? 'Index');
        $this->action = explode('?', (ucfirst(empty($action) ? 'Index' : $action)))[0];

        $controllerNameSpace = "App\\API\Controladores\Errors\Erro404Controlador";

        $pathController = __DIR__."/API/Controladores/$controllerName/{$controllerName}Controlador.php";

		if(is_file($pathController)){

			$nameSpace =  "App\\API\Controladores\\$controllerName\\{$controllerName}Controlador";

			if(class_exists($nameSpace)){

				$this->controller = new $nameSpace();
			}

		}else{

			$this->controller = new $controllerNameSpace();
		}

        if(!method_exists($this->controller, $this->action)){

            $controllerNameSpace = "App\API\Controladores\Errors\Erro404Controlador";
            $this->controller = new $controllerNameSpace();
            $this->action = 'Index';
        }

        $this->controller->{$this->action}();
    }
}