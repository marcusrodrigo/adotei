<?php
/**
 * autoload.php — PSR-4 simples para o namespace "App".
 * Mapeia  App\Foo\Bar  →  app/Foo/Bar.php
 * Localização: raiz do projeto.
 */

spl_autoload_register(function (string $class): void {
    // Prefixo e diretório-base
    $prefix   = 'App\\';
    $base_dir = __DIR__ . '/app/';

    // Se a classe não pertence ao namespace "App", ignora
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Remove o prefixo e monta o caminho do arquivo
    $relative = substr($class, strlen($prefix));           // ex: "model\Relato"
    $file     = $base_dir . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
