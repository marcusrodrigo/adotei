<?php
/**
 * Middleware.php — Contrato para middlewares da aplicação.
 * Namespace : App\middleware
 * Localização: app/middleware/Middleware.php
 */

namespace App\middleware;

interface Middleware
{
    /**
     * Processa a requisição.
     * Retorna o array de dados (possivelmente transformado)
     * ou lança exceção para interromper o fluxo.
     */
    public function handle(array $dados): array;
}
