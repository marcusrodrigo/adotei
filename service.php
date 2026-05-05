<?php
require_once 'BusinessRuleException.php';

class PetService {
    private $repository;

    public function __construct(IPetRepository $repository) {
        $this->repository = $repository;
    }

    public function registrar($dados) {
        if ($dados['idade'] < 16) {
            throw new BusinessRuleException("A idade mínima é 16 anos para cursos de veterinária.");
        }
        return $this->repository->save($dados);
    }
}