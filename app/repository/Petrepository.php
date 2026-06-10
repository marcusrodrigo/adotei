<?php
/**
 * PetRepository.php
 * Implementa IPetRepository.
 * TODO o SQL (PDO) fica ESTRITAMENTE aqui.
 * Recebe o PDO via construtor (Injeção de Dependência).
 */

class PetRepository implements IPetRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Salva pet: INSERT se id = null, UPDATE se id preenchido
     */
    public function save(Pet $pet): int
    {
        if ($pet->id === null) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO pets (nome, categoria, descricao, foto, data)
                 VALUES (:nome, :categoria, :descricao, :foto, :data)'
            );
            $stmt->execute([
                ':nome'      => $pet->nome,
                ':categoria' => $pet->categoria,
                ':descricao' => $pet->descricao,
                ':foto'      => $pet->foto,
                ':data'      => $pet->data,
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        $stmt = $this->pdo->prepare(
            'UPDATE pets
             SET nome = :nome, categoria = :categoria,
                 descricao = :descricao, foto = :foto, data = :data
             WHERE id = :id'
        );
        $stmt->execute([
            ':nome'      => $pet->nome,
            ':categoria' => $pet->categoria,
            ':descricao' => $pet->descricao,
            ':foto'      => $pet->foto,
            ':data'      => $pet->data,
            ':id'        => $pet->id,
        ]);
        return $pet->id;
    }

    /**
     * Busca pet por ID
     */
    public function find(int $id): ?Pet
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pets WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }
        return new Pet($row);
    }

    /**
     * Busca todos os pets com filtros opcionais
     */
    public function findAll(array $filtros = []): array
    {
        $sql    = 'SELECT * FROM pets WHERE 1=1';
        $params = [];

        if (!empty($filtros['nome'])) {
            $sql .= ' AND nome LIKE :nome';
            $params[':nome'] = '%' . $filtros['nome'] . '%';
        }
        if (!empty($filtros['categoria']) && $filtros['categoria'] !== 'Todos') {
            $sql .= ' AND categoria = :categoria';
            $params[':categoria'] = $filtros['categoria'];
        }

        $sql .= ' ORDER BY id DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $pets = [];
        while ($row = $stmt->fetch()) {
            $pets[] = new Pet($row);
        }
        return $pets;
    }

    /**
     * Deleta pet por ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM pets WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
