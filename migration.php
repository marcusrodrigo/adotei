<?php
class Migration {
    public static function rodar() {
        try {
            $pdo = new PDO('sqlite:database.sqlite');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "CREATE TABLE IF NOT EXISTS alunos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                idade INTEGER NOT NULL,
                curso TEXT NOT NULL
            )";
            
            $pdo->exec($sql);
            echo "✅ Banco de dados e tabela 'alunos' prontos!";
        } catch (PDOException $e) {
            die("Erro na migração: " . $e->getMessage());
        }
    }
}

Migration::rodar();