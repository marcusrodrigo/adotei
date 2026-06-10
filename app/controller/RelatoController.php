<?php
/**
 * RelatoController.php — Orquestra requisição → service → view.
 * Namespace : App\controller
 * Localização: app/controller/RelatoController.php
 */

namespace App\controller;

use App\exceptions\BusinessRuleException;
use App\middleware\SanitizeMiddleware;
use App\repository\RelatoRepository;
use App\service\RelatoService;

class RelatoController
{
    private RelatoService     $service;
    private SanitizeMiddleware $sanitize;

    public function __construct()
    {
        $this->service  = new RelatoService(new RelatoRepository());
        $this->sanitize = new SanitizeMiddleware();
    }

    // ------------------------------------------------------------------
    // Rota principal: GET /  →  exibe galeria + formulário (view.php)
    // ------------------------------------------------------------------
    public function index(): void
    {
        $relatos  = $this->service->listar();
        $resposta = null;

        $this->renderView($relatos, $resposta);
    }

    // ------------------------------------------------------------------
    // Rota: POST /  →  cadastra pet e redireciona
    // ------------------------------------------------------------------
    public function store(): void
    {
        $raw = [
            'nome'      => $_POST['nome']        ?? '',
            'categoria' => $_POST['categoria']    ?? '',
            'descricao' => $_POST['custom-msg']   ?? '',
            // foto em base64 vinda do JS (ou null se não enviada)
            'foto'      => $_POST['foto']         ?? null,
        ];

        // Passa pelo middleware de sanitização
        $dados = $this->sanitize->handle($raw);

        $resposta = null;

        try {
            $this->service->cadastrar($dados);
            $resposta = ['sucesso' => true, 'msg' => 'Pet cadastrado com sucesso! 🐾'];
        } catch (BusinessRuleException $e) {
            $resposta = ['sucesso' => false, 'msg' => $e->getMessage()];
        } catch (\Throwable $e) {
            $resposta = ['sucesso' => false, 'msg' => 'Erro interno ao cadastrar o pet.'];
        }

        $relatos = $this->service->listar();
        $this->renderView($relatos, $resposta);
    }

    // ------------------------------------------------------------------
    // Rota: POST /delete  →  exclui pet e redireciona
    // ------------------------------------------------------------------
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $resposta = null;

        try {
            $this->service->excluir($id);
            $resposta = ['sucesso' => true, 'msg' => 'Pet removido com sucesso.'];
        } catch (BusinessRuleException $e) {
            $resposta = ['sucesso' => false, 'msg' => $e->getMessage()];
        }

        $relatos = $this->service->listar();
        $this->renderView($relatos, $resposta);
    }

    // ------------------------------------------------------------------
    // Rota: GET /api/pets  →  JSON (usado pelo controller.js via fetch)
    // ------------------------------------------------------------------
    public function apiList(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $relatos = $this->service->listar();

        $payload = array_map(fn($r) => [
            'id'        => $r->id,
            'nome'      => $r->nome,
            'categoria' => $r->categoria,
            'descricao' => $r->descricao,
            'foto'      => $r->foto,
            'data'      => $r->data,
        ], $relatos);

        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // ------------------------------------------------------------------
    // Helper: inclui a view passando variáveis
    // ------------------------------------------------------------------
    private function renderView(array $relatos, ?array $resposta): void
    {
        $viewFile = VIEW_DIR . '/view.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die('View não encontrada: ' . $viewFile);
        }

        // Disponibiliza para a view
        include $viewFile;
    }
}
