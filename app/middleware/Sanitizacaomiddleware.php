<?php
/**
 * SanitizacaoMiddleware.php
 * Middleware de sanitização e validação de entrada.
 *
 * Responsabilidades:
 * - Verificar se campos obrigatórios não vieram em branco.
 * - Aplicar filter_input para barrar tentativas de XSS.
 * - Retornar dados limpos ou lançar exceção.
 */

class SanitizacaoMiddleware
{
    /**
     * Sanitiza e valida os dados de um POST de cadastro de pet.
     *
     * @param array $post  Normalmente $_POST
     * @return array       Dados limpos e seguros
     * @throws BusinessRuleException se dados obrigatórios estiverem ausentes.
     */
    public static function sanitizarCadastroPet(array $post): array
    {
        // Verifica presença dos campos obrigatórios
        $camposObrigatorios = ['nome', 'categoria'];
        foreach ($camposObrigatorios as $campo) {
            if (empty(trim($post[$campo] ?? ''))) {
                throw new BusinessRuleException("O campo '{$campo}' é obrigatório.");
            }
        }

        // Sanitização anti-XSS usando filter_var
        $nome      = self::limpar($post['nome'] ?? '');
        $categoria = self::limpar($post['categoria'] ?? '');
        $descricao = self::limpar($post['descricao'] ?? '');
        $foto      = $post['foto'] ?? null; // base64 ou null (tratado separadamente)

        // Verificação extra: não permite tags HTML no nome
        if ($nome !== strip_tags($nome)) {
            throw new BusinessRuleException('O campo nome não pode conter HTML.');
        }

        return [
            'nome'      => $nome,
            'categoria' => $categoria,
            'descricao' => $descricao,
            'foto'      => $foto,
        ];
    }

    /**
     * Remove HTML, scripts e espaços desnecessários de uma string.
     */
    private static function limpar(string $valor): string
    {
        // Remove tags HTML e PHP
        $valor = strip_tags($valor);
        // Converte entidades especiais (evita XSS via &lt;script&gt;)
        $valor = htmlspecialchars($valor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Remove espaços extras
        $valor = trim($valor);
        return $valor;
    }

    /**
     * Sanitiza um parâmetro de busca (GET).
     */
    public static function sanitizarBusca(string $valor): string
    {
        return self::limpar($valor);
    }
}
