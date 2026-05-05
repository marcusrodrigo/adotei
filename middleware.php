<?php
class Middleware {
    public static function validarCampos($post) {
        if (empty($post['nome']) || empty($post['idade'])) {
            die("Campos obrigatórios faltando.");
        }
    }

    public static function sanitizar($dados) {
        return array_map(fn($v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8'), $dados);
    }
}