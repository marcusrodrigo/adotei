<?php
/**
 * RelatoRepository.php — Implementação SQLite do IRelatoRepository.
 * Namespace : App\repository
 * Localização: app/repository/RelatoRepository.php
 */

namespace App\repository;

use App\config\Database;
use App\model\Relato;

class RelatoRepository implements IRelatoRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** Retorna todos os relatos ordenados do mais recente. */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM relatos ORDER BY id DESC');
        $rows = $stmt->fetchAll();

        return array_map(fn(array $row) => Relato::fromArray($row), $rows);
    }

    /** Persiste um novo relato e devolve o objeto com id. */
    public function save(Relato $relato): Relato
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO relatos (nome, categoria, descricao, foto, data)
            VALUES (:nome, :categoria, :descricao, :foto, :data)
        ');

        $stmt->execute($relato->toArray());
        $relato->id = (int) $this->pdo->lastInsertId();
        return $relato;
    }

    /** Exclui pelo id. */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM relatos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
