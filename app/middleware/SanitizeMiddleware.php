<?php
/**
 * SanitizeMiddleware.php — Sanitiza strings de entrada (XSS, espaços).
 * Namespace : App\middleware
 * Localização: app/middleware/SanitizeMiddleware.php
 */

namespace App\middleware;

class SanitizeMiddleware implements Middleware
{
    /**
     * Percorre todos os campos do array e:
     * - Remove espaços extras (trim)
     * - Escapa caracteres HTML especiais (htmlspecialchars)
     * - Mantém null sem alteração
     */
    public function handle(array $dados): array
    {
        $sanitized = [];

        foreach ($dados as $chave => $valor) {
            if ($valor === null) {
                $sanitized[$chave] = null;
            } elseif (is_string($valor)) {
                $sanitized[$chave] = htmlspecialchars(trim($valor), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            } else {
                // números, booleanos etc. passam sem alteração
                $sanitized[$chave] = $valor;
            }
        }

        return $sanitized;
    }
}
