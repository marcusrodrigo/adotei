<?php
/**
 * Relato.php — Entidade de domínio que representa um pet cadastrado.
 * Namespace : App\model
 * Localização: app/model/Relato.php
 */

namespace App\model;

class Relato
{
    public ?int    $id        = null;
    public string  $nome      = '';
    public string  $categoria = '';
    public ?string $descricao = null;
    public ?string $foto      = null;
    public string  $data      = '';

    public function __construct(
        string  $nome,
        string  $categoria,
        ?string $descricao = null,
        ?string $foto      = null,
        ?string $data      = null,
        ?int    $id        = null
    ) {
        $this->nome      = $nome;
        $this->categoria = $categoria;
        $this->descricao = $descricao;
        $this->foto      = $foto;
        $this->data      = $data ?? date('d/m/Y');
        $this->id        = $id;
    }

    /** Cria instância a partir de array (linha do banco). */
    public static function fromArray(array $row): self
    {
        return new self(
            nome:      $row['nome'],
            categoria: $row['categoria'],
            descricao: $row['descricao'] ?? null,
            foto:      $row['foto']      ?? null,
            data:      $row['data']      ?? date('d/m/Y'),
            id:        isset($row['id']) ? (int) $row['id'] : null
        );
    }

    /** Serializa para array (para persistência). */
    public function toArray(): array
    {
        return [
            'nome'      => $this->nome,
            'categoria' => $this->categoria,
            'descricao' => $this->descricao,
            'foto'      => $this->foto,
            'data'      => $this->data,
        ];
    }
}
