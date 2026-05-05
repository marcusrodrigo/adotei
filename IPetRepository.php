<?php
interface IPetRepository {
    public function save(array $dados);
    public function findAll();
}