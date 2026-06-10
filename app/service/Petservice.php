<?php
/**
 * PetService.php
 * Camada de regras de negócio.
 *
 * REGRA DE OURO: O Service NÃO instancia o Repository.
 * O Repository é INJETADO pelo construtor (Injeção de Dependência).
 * O Service lança BusinessRuleException em vez de retornar false/arrays.
 */

class PetService
{
    private IPetRepository $repository;

    /**
     * Recebe a Interface (contrato), não a implementação concreta.
     * Isso permite trocar o Repository em testes sem alterar o Service.
     */
    public function __construct(IPetRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Cadastra um novo pet após validar regras de negócio.
     *
     * @throws BusinessRuleException se alguma regra falhar.
     */
    public function cadastrar(array $dados): Pet
    {
        // Regra 1: Nome obrigatório e mínimo de 2 caracteres
        $nome = trim($dados['nome'] ?? '');
        if (strlen($nome) < 2) {
            throw new BusinessRuleException('O nome do pet deve ter pelo menos 2 caracteres.');
        }

        // Regra 2: Categoria deve ser válida
        $categoriasValidas = ['Cão', 'Gato'];
        $categoria = trim($dados['categoria'] ?? '');
        if (!in_array($categoria, $categoriasValidas, true)) {
            throw new BusinessRuleException('Espécie inválida. Selecione Cão ou Gato.');
        }

        // Regra 3: Nome não pode ser só números
        if (is_numeric($nome)) {
            throw new BusinessRuleException('O nome do pet não pode ser apenas números.');
        }

        // Montar entidade
        $pet = new Pet([
            'nome'      => $nome,
            'categoria' => $categoria,
            'descricao' => trim($dados['descricao'] ?? ''),
            'foto'      => $dados['foto'] ?? null,
            'data'      => date('d/m/Y'),
        ]);

        // Persistir via Repository
        $id = $this->repository->save($pet);
        $pet->id = $id;

        return $pet;
    }

    /**
     * Lista todos os pets com filtros opcionais.
     */
    public function listar(array $filtros = []): array
    {
        return $this->repository->findAll($filtros);
    }

    /**
     * Busca um pet pelo ID.
     *
     * @throws BusinessRuleException se não encontrado.
     */
    public function buscarPorId(int $id): Pet
    {
        $pet = $this->repository->find($id);
        if ($pet === null) {
            throw new BusinessRuleException("Pet com ID {$id} não encontrado.", 404);
        }
        return $pet;
    }

    /**
     * Remove um pet.
     *
     * @throws BusinessRuleException se não encontrado.
     */
    public function remover(int $id): void
    {
        $removido = $this->repository->delete($id);
        if (!$removido) {
            throw new BusinessRuleException("Pet com ID {$id} não encontrado para exclusão.", 404);
        }
    }
}
