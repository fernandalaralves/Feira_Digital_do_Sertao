<?php
declare(strict_types=1);

class Produto
{
    protected string $nome;
    protected float  $preco;

    public function __construct(string $nome, float $preco)
    {
        $this->setNome($nome);
        $this->setPreco($preco);
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function setNome(string $nome): void
    {
        if (trim($nome) === '') {
            throw new Exception("Nome do produto não pode ser vazio.");
        }
        $this->nome = $nome;
    }

    public function setPreco(float $preco): void
    {
        if ($preco <= 0) {
            throw new Exception("Preço deve ser maior que zero.");
        }
        $this->preco = $preco;
    }

    public function calcularPreco(float $quantidade): float
    {
        return round($this->preco * $quantidade, 2);
    }

    public function paraArray(): array
    {
        return [
            'tipo'  => static::class,
            'nome'  => $this->nome,
            'preco' => $this->preco,
        ];
    }
}