<?php
/**
 * BusinessRuleException.php — Exceção para violações de regra de negócio.
 * Namespace : App\exceptions
 * Localização: app/exceptions/BusinessRuleException.php
 */

namespace App\exceptions;

use RuntimeException;

class BusinessRuleException extends RuntimeException
{
    /** Cria exceção com mensagem amigável para o usuário. */
    public function __construct(string $mensagem, int $code = 422, ?\Throwable $previous = null)
    {
        parent::__construct($mensagem, $code, $previous);
    }
}
