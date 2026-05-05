<?php
class PetController {
    private $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function handlePost($dados) {
        try {
            $this->service->registrar($dados);
            return ["sucesso" => true, "msg" => "Cadastro realizado com sucesso!"];
        } catch (BusinessRuleException $e) {
            return ["sucesso" => false, "msg" => $e->getMessage()];
        }
    }
}