<?php
/**
 * BusinessRuleException.php
 * Exceção personalizada para falhas de regras de negócio.
 *
 * Lançada pelo Service quando uma validação de domínio falha.
 * O Controller captura esta exceção e renderiza a mensagem ao usuário.
 *
 * Separada de PDOException/RuntimeException para que o Controller
 * saiba exatamente o que tratar sem expor erros técnicos.
 */

class BusinessRuleException extends RuntimeException
{
    /**
     * @param string $message  Mensagem amigável para exibir ao usuário.
     * @param int    $code     Código opcional (ex: 422 Unprocessable Entity).
     */
    public function __construct(string $message, int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
