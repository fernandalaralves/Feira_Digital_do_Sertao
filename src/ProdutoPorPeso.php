<?php
declare(strict_types=1);
require_once __DIR__ . '/Produto.php';

class ProdutoPorPeso extends Produto
{
    private float $pesoMaximo;

    public function __construct(string $nome, float $preco, float $pesoMaximo = 50.0)
    {
        parent::__construct($nome, $preco);
        if ($pesoMaximo <= 0 || $pesoMaximo > 50) {
            throw new Exception("Peso máximo deve estar entre 0 e 50 kg.");
        }
        $this->pesoMaximo = $pesoMaximo;
    }

    public function getPesoMaximo(): float
    {
        return $this->pesoMaximo;
    }

    public function calcularPreco(float $quantidade): float
    {
        if ($quantidade <= 0 || $quantidade > $this->pesoMaximo) {
            throw new Exception("Quantidade em kg deve estar entre 0 e {$this->pesoMaximo}.");
        }
        return round($this->preco * $quantidade, 2);
    }

    public function paraArray(): array
    {
        return parent::paraArray() + ['pesoMaximo' => $this->pesoMaximo];
    }
}