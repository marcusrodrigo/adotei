<?php
class MatriculaService {
    public function validarMatricula($dados) {
        // Regra de Negócio: Idade mínima de 16 anos
        if ($dados['idade'] < 16) {
            throw new Exception("Desculpe, a idade mínima para o curso de {$dados['curso']} é 16 anos.");
        }

        // Simulação de lógica de bolsa
        if ($dados['idade'] > 50) {
            $dados['mensagem'] = "Parabéns! Você ganhou 50% de bolsa (Incentivo Sênior).";
        } else {
            $dados['mensagem'] = "Matrícula pré-aprovada!";
        }

        return $dados;
    }
}