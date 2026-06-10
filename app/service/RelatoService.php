<?php
/**
 * RelatoService.php — Regras de negócio para Relatos (pets).
 * Namespace : App\service
 * Localização: app/service/RelatoService.php
 */

namespace App\service;

use App\exceptions\BusinessRuleException;
use App\model\Relato;
use App\repository\IRelatoRepository;

class RelatoService
{
    public function __construct(private IRelatoRepository $repository) {}

    /** Lista todos os pets cadastrados no banco. */
    public function listar(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Cria e persiste um novo pet após validação.
     *
     * @throws BusinessRuleException em caso de dados inválidos.
     */
    public function cadastrar(array $dados): Relato
    {
        $nome      = trim($dados['nome']      ?? '');
        $categoria = trim($dados['categoria'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');
        $foto      = $dados['foto'] ?? null;

        if ($nome === '') {
            throw new BusinessRuleException('O nome do pet é obrigatório.');
        }

        if (!in_array($categoria, ['Cão', 'Gato'], true)) {
            throw new BusinessRuleException('Espécie inválida. Escolha Cão ou Gato.');
        }

        if (strlen($nome) > 100) {
            throw new BusinessRuleException('O nome do pet não pode ter mais de 100 caracteres.');
        }

        $relato = new Relato(
            nome:      $nome,
            categoria: $categoria,
            descricao: $descricao !== '' ? $descricao : null,
            foto:      $foto,
            data:      date('d/m/Y')
        );

        return $this->repository->save($relato);
    }

    /**
     * Remove um pet pelo id.
     *
     * @throws BusinessRuleException se o pet não for encontrado.
     */
    public function excluir(int $id): void
    {
        if ($id <= 0) {
            throw new BusinessRuleException('ID inválido.');
        }

        $removido = $this->repository->delete($id);

        if (!$removido) {
            throw new BusinessRuleException('Pet não encontrado para exclusão.', 404);
        }
    }
}
