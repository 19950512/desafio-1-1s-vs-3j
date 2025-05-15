<?php

declare(strict_types=1);

namespace App\API\Controladores\Topcountries;

use App\API\Controladores\Middlewares\Controlador;

final class TopcountriesControlador extends Controlador
{

    public function __construct(){
        parent::__construct();
    }

    public function index(): void
    {
        if($this->method != 'GET'){
            $this->metodoNaoPermitido();
        }

        $this->redis->delete('topcountries');
        
        if($this->redis->exist('topcountries')){
            $this->response([
                'statusCode' => 200,
                'data' => json_decode($this->redis->get('topcountries'), true)
            ]);
        }

        $superusers = $this->redis->get('superusers');

        if($superusers == null){
            $this->response([
                'statusCode' => 404,
                'message' => 'Nenhum Superusuario encontrado',
            ]);
        }

        $superusers = json_decode($superusers, true);

        $countries = [];
        foreach($superusers as $usuario){
            $country = $usuario['country'];
            if(!isset($countries[$country])){
                $countries[$country] = [
                    'country' => $country,
                    'count' => 0,
                ];
            }else{
                $countries[$country]['count']++;
            }
        }

        usort($countries, function($a, $b){
            return $b['count'] <=> $a['count'];
        });
        
        $topCountries = array_slice($countries, 0, 5, true);

        $this->redis->set('topcountries', json_encode($topCountries), $this->tempoDeCache);

        $this->response([
            'statusCode' => 200,
            'data' => $topCountries,
        ]);
    }
}
