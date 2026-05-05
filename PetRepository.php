<?php
require_once 'Database.php';
require_once 'IPetRepository.php';

class PetRepository implements IPetRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function save(array $dados) {
        $stmt = $this->db->prepare("INSERT INTO alunos (nome, idade, curso) VALUES (:nome, :idade, :curso)");
        return $stmt->execute($dados);
    }

    public function findAll() {
        return $this->db->query("SELECT * FROM alunos")->fetchAll(PDO::FETCH_ASSOC);
    }
}