<?php
/**
 * CreatePetsTable.php
 * Migration responsável por criar a tabela `pets` no banco SQLite.
 *
 * Executar via: php app/migration/CreatePetsTable.php
 * Ou é chamado automaticamente pelo index.php na primeira execução.
 */

class CreatePetsTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function up(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS pets (
                id        INTEGER PRIMARY KEY AUTOINCREMENT,
                nome      TEXT    NOT NULL,
                categoria TEXT    NOT NULL,
                descricao TEXT    DEFAULT '',
                foto      TEXT    DEFAULT NULL,
                data      TEXT    NOT NULL
            )
        ");
        echo "✅ Tabela 'pets' criada (ou já existia).\n";
    }

    public function down(): void
    {
        $this->pdo->exec('DROP TABLE IF EXISTS pets');
        echo "🗑️  Tabela 'pets' removida.\n";
    }
}

// Execução direta via CLI: php app/migration/CreatePetsTable.php
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $rootDir = __DIR__ . '/../../';
    require_once $rootDir . 'config.php';
    require_once $rootDir . 'app/model/Database.php';

    $pdo = Database::getInstance();
    $migration = new CreatePetsTable($pdo);
    $migration->up();
}
