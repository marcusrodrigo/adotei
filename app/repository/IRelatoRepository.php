<?php
/**
 * IRelatoRepository.php — Contrato (interface) do repositório de Relatos.
 * Namespace : App\repository
 * Localização: app/repository/IRelatoRepository.php
 */

namespace App\repository;

use App\model\Relato;

interface IRelatoRepository
{
    /** Retorna todos os relatos. */
    public function findAll(): array;

    /** Salva um novo relato e retorna com o id preenchido. */
    public function save(Relato $relato): Relato;

    /** Remove um relato pelo id. Retorna true se encontrou e deletou. */
    public function delete(int $id): bool;
}
