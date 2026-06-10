<?php
/**
 * Pet.php
 * Entidade simples (Model/Entity).
 * Contém APENAS propriedades e métodos mágicos.
 * NENHUM SQL aqui — todo acesso ao banco fica no PetRepository.
 */

class Pet
{
    public ?int   $id        = null;
    public string $nome      = '';
    public string $categoria = '';
    public string $descricao = '';
    public ?string $foto     = null;
    public string $data      = '';

    public function __construct(array $dados = [])
    {
        foreach ($dados as $chave => $valor) {
            if (property_exists($this, $chave)) {
                $this->$chave = $valor;
            }
        }
        if (empty($this->data)) {
            $this->data = date('d/m/Y');
        }
    }

    // Método mágico: permite acessar propriedades dinamicamente
    public function __get(string $nome): mixed
    {
        return $this->$nome ?? null;
    }

    public function __set(string $nome, mixed $valor): void
    {
        $this->$nome = $valor;
    }

    public function __isset(string $nome): bool
    {
        return isset($this->$nome);
    }

    /**
     * Serializa a entidade para array (útil para JSON e views)
     */
    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'nome'      => $this->nome,
            'categoria' => $this->categoria,
            'descricao' => $this->descricao,
            'foto'      => $this->foto,
            'data'      => $this->data,
        ];
    }
}
