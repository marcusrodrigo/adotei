<?php
/**
 * Database.php
 * Classe Singleton responsável EXCLUSIVAMENTE por:
 * - Ler o config.php
 * - Retornar a instância PDO (uma única conexão reutilizada)
 *
 * NENHUM outro arquivo deve saber como conectar ao banco.
 */

class Database
{
    private static ?PDO $instance = null;

    // Construtor e clone privados: ninguém pode instanciar externamente
    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna a instância única do PDO.
     * Se ainda não existir, cria a conexão.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }
        return self::$instance;
    }

    /**
     * Cria e configura a conexão PDO com base no config.php
     */
    private static function createConnection(): PDO
    {
        if (!defined('DB_PATH') || !defined('DB_DRIVER')) {
            throw new RuntimeException(
                'Configuração do banco não encontrada. Verifique o config.php.'
            );
        }

        $dsn = DB_DRIVER . ':' . DB_PATH;

        try {
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Habilita suporte a chaves estrangeiras no SQLite
            $pdo->exec('PRAGMA foreign_keys = ON;');
            return $pdo;

        } catch (PDOException $e) {
            // Nunca expor detalhes técnicos ao usuário final
            error_log('[Database] Falha na conexão: ' . $e->getMessage());
            throw new RuntimeException('Falha ao conectar com o banco de dados.');
        }
    }
}
