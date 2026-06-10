<?php
/**
 * index.php — Front controller. Ponto de entrada único da aplicação.
 * Localização: raiz do projeto.
 *
 * Fluxo:
 *   index.php → autoload + config → Router → Middleware → Controller → Service → Repository → View
 */

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';

use App\router\Router;

// Instancia e despacha o roteador
$router = new Router();
$router->dispatch();
