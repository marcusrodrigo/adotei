<?php
require_once 'model.php';
require_once 'service.php';

class MatriculaController {
    public function processarMatricula($dados) {
        try {
            $service = new MatriculaService();
            $dadosProcessados = $service->validarMatricula($dados);

            $aluno = new AlunoModel();
            $aluno->setNome($dadosProcessados['nome']);
            $aluno->setIdade($dadosProcessados['idade']);
            $aluno->setCurso($dadosProcessados['curso']);
            
            if ($aluno->save()) {
                return ["sucesso" => true, "msg" => "Sucesso: " . $dadosProcessados['mensagem']];
            }
        } catch (Exception $e) {
            return ["sucesso" => false, "msg" => $e->getMessage()];
        }
    }
}