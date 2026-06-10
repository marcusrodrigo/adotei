<?php
/**
 * Router.php
 * Roteador simples baseado em $_GET['action'].
 *
 * Recebe o Controller já montado (com todas as dependências injetadas)
 * e decide qual método chamar baseado na requisição.
 */

class Router
{
    private PetController $controller;

    public function __construct(PetController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Despacha a requisição para o método correto do Controller.
     */
    public function dispatch(): void
    {
        $action = $_GET['action'] ?? 'index';
        $method = $_SERVER['REQUEST_METHOD'];

        // Mapa de rotas: [action] => [método HTTP => método do controller]
        $routes = [
            'index'   => ['GET'  => 'index'],
            'store'   => ['POST' => 'store'],
            'destroy' => ['POST' => 'destroy'],
            'show'    => ['GET'  => 'show'],
        ];

        if (!isset($routes[$action])) {
            $this->notFound();
            return;
        }

        if (!isset($routes[$action][$method])) {
            $this->methodNotAllowed();
            return;
        }

        $controllerMethod = $routes[$action][$method];
        $this->controller->$controllerMethod();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 — Página não encontrada</h1>';
    }

    private function methodNotAllowed(): void
    {
        http_response_code(405);
        echo '<h1>405 — Método não permitido</h1>';
    }
}
