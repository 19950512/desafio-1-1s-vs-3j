<?php

declare(strict_types=1);

namespace App\API\Controladores\Middlewares;

use App\Cache\Redis;

abstract class Controlador
{

    public $method;

    private float $startTime;
    private float $endTime;
    private float $executionTime;

    protected Redis $redis;
    protected int $tempoDeCache = 300;

    public function __construct(){

        $this->method = $_SERVER['REQUEST_METHOD'] ?? '';

        if(is_array($_POST) and count($_POST) === 0){
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $this->startTime = microtime(true);
        $this->redis = new Redis();
    }

    public function response(array $data): void
    {

        $this->endTime = microtime(true);
        $this->executionTime = $this->endTime - $this->startTime;
        $data['execution_time_ms'] = (int)($this->executionTime * 1000);
        $data['timestamp'] = date('Y-m-d H:i:s');

        header('Content-Type: application/json; charset=utf-8');
        header('X-Powered-By: 19950512 - Esse e meu jeito ninja de ser!');

        if(isset($data['statusCode']) and is_numeric($data['statusCode'])){
            header("HTTP/1.0 {$data['statusCode']}");
            unset($data['statusCode']);
        }


        echo json_encode($data);
        exit;
    }

    public function metodoNaoPermitido(): void
    {
        $this->response([
            'statusCode' => 405,
            'message' => 'Método não permitido'
        ]);
    }
}