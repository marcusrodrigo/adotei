<?php
/**
 * autoload.php
 * Registra o autoloader PSR-4 simplificado.
 * Mapeia namespaces/classes para seus arquivos automaticamente.
 *
 * Convenção de mapeamento:
 *   PetController      → app/controller/PetController.php
 *   PetRepository      → app/model/PetRepository.php
 *   PetService         → app/services/PetService.php
 *   AuthMiddleware     → app/middleware/AuthMiddleware.php
 *   BusinessRuleException → app/services/BusinessRuleException.php
 */

spl_autoload_register(function (string $className): void {

    // Mapa de prefixos de classe para diretórios
    $classMap = [
        'Controller'  => __DIR__ . '/app/controller/',
        'Repository'  => __DIR__ . '/app/model/',
        'Service'     => __DIR__ . '/app/services/',
        'Exception'   => __DIR__ . '/app/services/',
        'Middleware'  => __DIR__ . '/app/middleware/',
        'Migration'   => __DIR__ . '/app/migration/',
    ];

    // Verificar cada sufixo mapeado
    foreach ($classMap as $suffix => $directory) {
        if (str_ends_with($className, $suffix)) {
            $filePath = $directory . $className . '.php';
            if (file_exists($filePath)) {
                require_once $filePath;
                return;
            }
        }
    }

    // Fallback: tentar carregar de qualquer subpasta do app/
    $appDirs = [
        __DIR__ . '/app/controller/',
        __DIR__ . '/app/model/',
        __DIR__ . '/app/services/',
        __DIR__ . '/app/middleware/',
        __DIR__ . '/app/migration/',
        __DIR__ . '/app/router/',
    ];

    foreach ($appDirs as $dir) {
        $filePath = $dir . $className . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
});
