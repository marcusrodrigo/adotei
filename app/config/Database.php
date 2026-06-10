<?php
/**
 * Database.php — Wrapper PDO (SQLite).
 * Namespace : App\config
 * Localização: app/config/Database.php
 */

namespace App\config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /** Retorna a conexão singleton com o banco. */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }

    private static function connect(): PDO
    {
        if (DB_DRIVER !== 'sqlite') {
            throw new \RuntimeException('Apenas SQLite é suportado nesta versão.');
        }

        // Garante que o diretório do banco existe
        $dir = dirname(DB_PATH);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA journal_mode=WAL;');
            $pdo->exec('PRAGMA foreign_keys=ON;');

            self::migrate($pdo);
            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            die('Erro de conexão com o banco: ' . ($e->getMessage()));
        }
    }

    /**
     * Cria a tabela "relatos" caso ainda não exista.
     * Mantém o histórico de pets cadastrados via formulário.
     */
    private static function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS relatos (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                nome        TEXT    NOT NULL,
                categoria   TEXT    NOT NULL,
                descricao   TEXT,
                foto        TEXT,
                data        TEXT    NOT NULL,
                criado_em   DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}
