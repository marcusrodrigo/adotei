<?php
/**
 * index.php — Ponto de entrada único da aplicação
 *
 * Responsabilidades (e SOMENTE estas):
 * 1. Incluir config.php
 * 2. Incluir autoload.php
 * 3. Montar o Container de Injeção de Dependência (DI)
 * 4. Chamar o Router
 *
 * Nenhuma lógica de negócio aqui.
 */

// 1. Configurações e constantes
require_once __DIR__ . '/config.php';

// 2. Autoloader de classes
require_once __DIR__ . '/autoload.php';

// 3. Container de Injeção de Dependência (montagem manual)
//    Ordem obrigatória: PDO → Repository → Service → Controller → Router

try {
    // Garante que a tabela existe (migration idempotente)
    $pdo = Database::getInstance();
    require_once __DIR__ . '/app/migration/CreatePetsTable.php';
    (new CreatePetsTable($pdo))->up();

    // Montagem da cadeia de dependências
    $petRepository = new PetRepository($pdo);           // Repository recebe PDO
    $petService    = new PetService($petRepository);    // Service recebe Interface
    $petController = new PetController($petService);    // Controller recebe Service
    $router        = new Router($petController);        // Router recebe Controller

    // 4. Despachar requisição
    $router->dispatch();

} catch (RuntimeException $e) {
    // Erro de infraestrutura (ex: banco não disponível)
    // Nunca expõe stack trace ao usuário em produção
    http_response_code(500);
    if (APP_ENV === 'development') {
        echo '<pre style="font-family:monospace;color:red;">';
        echo '⚠️  Erro de inicialização: ' . htmlspecialchars($e->getMessage());
        echo '</pre>';
    } else {
        echo '<h1>Serviço temporariamente indisponível.</h1>';
    }
}
