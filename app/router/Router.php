<?php
/**
 * Router.php — Roteador simples baseado em URI e método HTTP.
 * Namespace : App\router
 * Localização: app/router/Router.php
 *
 * Rotas registradas:
 *   GET  /              → RelatoController::index()
 *   POST /              → RelatoController::store()
 *   POST /delete        → RelatoController::destroy()
 *   GET  /api/pets      → RelatoController::apiList()
 *
 * Arquivos estáticos da view (css, js, html) são servidos diretamente
 * pelo Apache/Nginx; o Router não os intercepta.
 */

namespace App\router;

use App\controller\RelatoController;

class Router
{
    /** Executa o dispatch baseado na URI atual. */
    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        // Remove query string e normaliza barras
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');

        // Remove o sub-diretório se o projeto não estiver na raiz do servidor
        // Ex: http://localhost/adotapet/  →  /
        $script = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
        if ($script !== '/' && str_starts_with($uri, $script)) {
            $uri = substr($uri, strlen($script));
        }
        $uri = '/' . ltrim($uri, '/');

        $controller = new RelatoController();

        // Suporte a formulários HTML que não podem usar DELETE/PUT:
        // o campo oculto _route=delete redireciona POST / → POST /delete
        if ($method === 'POST' && ($uri === '/' || $uri === '') && isset($_POST['_route'])) {
            $uri = '/' . ltrim($_POST['_route'], '/');
        }

        // Tabela de rotas
        $routes = [
            'GET'  => [
                '/'         => [$controller, 'index'],
                '/api/pets' => [$controller, 'apiList'],
            ],
            'POST' => [
                '/'         => [$controller, 'store'],
                '/delete'   => [$controller, 'destroy'],
            ],
        ];

        $action = $routes[$method][$uri] ?? null;

        if ($action !== null) {
            call_user_func($action);
            return;
        }

        // 404 — rota não encontrada
        http_response_code(404);
        echo '<h1>404 — Página não encontrada</h1>';
        echo '<p>URI: ' . htmlspecialchars($uri) . ' | Método: ' . $method . '</p>';
    }
}
