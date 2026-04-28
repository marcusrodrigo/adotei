<?php
require_once 'controller.php';
require_once 'middleware.php';

class Router {
    public function tratarRequisicao() {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            include 'view.php';
        } elseif ($metodo === 'POST') {
            Middleware::validarCampos($_POST);
            $controller = new MatriculaController();
            $resposta = $controller->processarMatricula($_POST);
            include 'view.php';
        }
    }
}