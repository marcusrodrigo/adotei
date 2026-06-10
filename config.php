<?php
/**
 * config.php
 * Arquivo de configuração sensível - NÃO versionar (adicionar ao .gitignore)
 * Guarda credenciais e caminhos do banco de dados
 */

define('DB_PATH', __DIR__ . '/database/adotapet.sqlite');
define('DB_DRIVER', 'sqlite');

// Configurações da aplicação
define('APP_NAME', 'AdotaPet');
define('APP_URL', 'http://localhost:8000');
define('APP_ENV', 'development'); // 'production' em deploy

// Controle de erros baseado no ambiente
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
