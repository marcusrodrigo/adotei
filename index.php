<?php
require_once 'PetRepository.php';
require_once 'service.php';
require_once 'controller.php';
require_once 'middleware.php';

// Montagem (Injeção de Dependência)
$repository = new PetRepository();
$service    = new PetService($repository);
$controller = new PetController($service);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Middleware::validarCampos($_POST); // Validação simples
    $resposta = $controller->handlePost($_POST);
}

include 'view.php';