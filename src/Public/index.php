<?php

declare(strict_types=1);

namespace App\Public;

use App\Router;
use Dotenv\Dotenv;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__ . '/../autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

date_default_timezone_set('America/Sao_Paulo');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$router = new Router(
    request_uri: $_SERVER['REQUEST_URI'] ?? '',
);