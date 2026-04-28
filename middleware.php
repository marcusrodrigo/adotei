<?php
class Middleware {
    public static function validarCampos($post) {
        if (empty($post['nome']) || empty($post['idade']) || empty($post['curso'])) {
            die("⚠️ Erro: Todos os campos são obrigatórios!");
        }
        if (!is_numeric($post['idade'])) {
            die("⚠️ Erro: A idade deve ser um número válido!");
        }
        return true;
    }
}