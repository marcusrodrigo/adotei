<?php
class AlunoModel {
    private $nome;
    private $idade;
    private $curso;

    public function setNome($nome) { $this->nome = $nome; }
    public function setIdade($idade) { $this->idade = $idade; }
    public function setCurso($curso) { $this->curso = $curso; }

    public function save() {
        $pdo = new PDO('sqlite:database.sqlite');
        $stmt = $pdo->prepare("INSERT INTO alunos (nome, idade, curso) VALUES (:nome, :idade, :curso)");
        return $stmt->execute([
            ':nome' => $this->nome,
            ':idade' => $this->idade,
            ':curso' => $this->curso
        ]);
    }
}