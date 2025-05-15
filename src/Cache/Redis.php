<?php

declare(strict_types=1);

namespace App\Cache;

use Exception;
use Predis\Client;
use Predis\Autoloader;

final class Redis
{

    readonly private Client $redis;

    public function __construct(){

        Autoloader::register();

        $configuracao = [
            "scheme" => $_ENV['REDIS_SCHEME'],
            "host" => $_ENV['REDIS_HOST'],
            "port" => $_ENV['REDIS_PORT'],
            "password" => $_ENV['REDIS_PASSWORD'],
        ];

        $this->redis = new Client($configuracao);
    }

    public function exist(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }

    public function get(string $key): string
    {
        return $this->redis->get($key) ?? '';
    }

    public function ttl(string $key): int
    {
        return $this->redis->ttl($key);
    }

    public function set(string $key, string $value, int $expireInSeconds = -1): void
    {
        $this->redis->set(
            key: $key, 
            value: $value
        );

        $this->redis->expire($key, $expireInSeconds);
    }

    public function delete(string $key = '', string $pattern = ''): void
    {

        if($pattern !== ''){

            if($pattern === "*"){
                throw new Exception("O padrão não pode ser *.");
            }

            $separators = ['/', ':', '_', '.', '-'];

            if(str_starts_with($pattern, '*')){
                throw new Exception("O padrão não pode começar com *.");
            }

            foreach ($separators as $separator) {
                if ($pattern === "*$separator*") {
                    throw new Exception("O padrão não pode ser *$separator*.");
                }
                if ($pattern === "*$separator*$separator*") {
                    throw new Exception("O padrão não pode ser *$separator*$separator*.");
                }
            }

            $keys = $this->keys($pattern);

            foreach ($keys as $key) {
                $this->delete($key);
            }
        }

        if($key !== ''){
            $this->redis->del($key);
        }
    }

    function keys(string $pattern): array
    {
        $iterator = 0;
        $keys = [];
        do {
            $result = $this->redis->scan($iterator, ['match' => $pattern]);
            $iterator = $result[0];
            $keys = array_merge($keys, $result[1]);
        } while ($iterator > 0);
        return $keys;
    }

    public function expire(string $key, int $seconds): void
    {
        $this->redis->expire($key, $seconds);
    }
}