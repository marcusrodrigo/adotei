<?php
/**
 * config.php — Carrega config.ini e define constantes globais.
 * Localização: raiz do projeto.
 */

$ini = parse_ini_file(__DIR__ . '/config.ini', true);

if ($ini === false) {
    http_response_code(500);
    die('Erro crítico: config.ini não encontrado ou inválido.');
}

// Constantes de banco
define('DB_DRIVER', $ini['database']['driver'] ?? 'sqlite');
define('DB_PATH',   __DIR__ . '/' . ($ini['database']['path'] ?? 'app/adotapet.db'));

// Constantes de app
define('APP_DEBUG',    filter_var($ini['app']['debug']    ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_TIMEZONE', $ini['app']['timezone'] ?? 'America/Sao_Paulo');

// Caminhos de diretório — usados em todo o sistema
define('APP_DIR',  __DIR__ . '/app');
define('VIEW_DIR', APP_DIR . '/view');

date_default_timezone_set(APP_TIMEZONE);

// Handler de erros legível em desenvolvimento
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
