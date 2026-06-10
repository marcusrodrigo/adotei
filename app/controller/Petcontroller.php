<?php
/**
 * PetController.php
 * Controlador "enxuto" — sem if/else de regras de negócio.
 *
 * Responsabilidades:
 * - Receber a requisição HTTP
 * - Chamar o Middleware para sanitizar entrada
 * - Chamar o Service para executar a lógica
 * - Capturar BusinessRuleException e renderizar erro
 * - Redirecionar em caso de sucesso
 *
 * Recebe suas dependências via construtor (Injeção de Dependência).
 */

class PetController
{
    private PetService $service;

    public function __construct(PetService $service)
    {
        $this->service = $service;
    }

    /**
     * Exibe a página principal (index) com a listagem de pets.
     */
    public function index(): void
    {
        $filtros = [
            'nome'      => SanitizacaoMiddleware::sanitizarBusca($_GET['nome'] ?? ''),
            'categoria' => SanitizacaoMiddleware::sanitizarBusca($_GET['categoria'] ?? 'Todos'),
        ];

        $pets  = $this->service->listar($filtros);
        $erro  = $_GET['erro'] ?? null;

        require __DIR__ . '/../../view/index.php';
    }

    /**
     * Processa o formulário de cadastro (POST).
     * Contém APENAS um bloco try-catch — sem if/else de validação aqui.
     */
    public function store(): void
    {
        try {
            // Middleware sanitiza e valida entrada
            $dados = SanitizacaoMiddleware::sanitizarCadastroPet($_POST);

            // Service executa a regra de negócio
            $this->service->cadastrar($dados);

            // Sucesso: redireciona
            header('Location: /?sucesso=1');
            exit;

        } catch (BusinessRuleException $e) {
            // Falha de regra de negócio: redireciona com mensagem de erro
            header('Location: /?erro=' . urlencode($e->getMessage()));
            exit;

        } catch (RuntimeException $e) {
            // Erro técnico (banco etc.): não expõe detalhes ao usuário
            error_log('[PetController::store] ' . $e->getMessage());
            header('Location: /?erro=' . urlencode('Erro interno. Tente novamente mais tarde.'));
            exit;
        }
    }

    /**
     * Remove um pet (POST com id).
     */
    public function destroy(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->service->remover($id);
            header('Location: /?sucesso=removido');
            exit;

        } catch (BusinessRuleException $e) {
            header('Location: /?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Retorna detalhes de um pet em JSON (para o modal via fetch).
     */
    public function show(): void
    {
        try {
            $id  = (int) ($_GET['id'] ?? 0);
            $pet = $this->service->buscarPorId($id);

            header('Content-Type: application/json');
            echo json_encode($pet->toArray());

        } catch (BusinessRuleException $e) {
            http_response_code($e->getCode());
            header('Content-Type: application/json');
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }
}
