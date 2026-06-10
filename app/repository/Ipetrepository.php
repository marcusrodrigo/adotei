<?php
/**
 * IPetRepository.php
 * Interface que define o CONTRATO obrigatório para qualquer
 * implementação de repositório de Pets.
 *
 * Princípio: o Service depende desta interface, não da implementação concreta.
 * Isso permite trocar o banco de dados sem mudar o Service.
 */

interface IPetRepository
{
    /**
     * Salva um pet (novo ou atualização).
     * @param Pet $pet A entidade Pet a ser persistida.
     * @return int ID gerado ou atualizado.
     */
    public function save(Pet $pet): int;

    /**
     * Busca um pet pelo ID.
     * @param int $id
     * @return Pet|null Retorna a entidade ou null se não encontrada.
     */
    public function find(int $id): ?Pet;

    /**
     * Busca todos os pets, com filtros opcionais.
     * @param array $filtros Ex: ['nome' => 'Rex', 'categoria' => 'Cão']
     * @return Pet[] Array de entidades Pet.
     */
    public function findAll(array $filtros = []): array;

    /**
     * Deleta um pet pelo ID.
     * @param int $id
     * @return bool True se deletado, false se não encontrado.
     */
    public function delete(int $id): bool;
}
