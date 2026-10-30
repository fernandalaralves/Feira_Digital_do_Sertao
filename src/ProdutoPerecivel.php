<?php
declare(strict_types=1);
require_once __DIR__ . '/Produto.php';

class ProdutoPerecivel extends Produto
{
    private DateTime $validade;

    public function __construct(string $nome, float $preco, DateTime $validade)
    {
        parent::__construct($nome, $preco);
        $this->validade = $validade;
    }

    public function getValidade(): DateTime
    {
        return $this->validade;
    }

    public function estaVencido(): bool
    {
        return $this->validade < new DateTime('today');
    }

    public function diasParaVencer(): int
    {
        $hoje = new DateTime('today');
        return (int) $hoje->diff($this->validade)->format('%r%a');
    }

    public function calcularPreco(float $quantidade): float
    {
        $base = parent::calcularPreco($quantidade);
        if ($this->diasParaVencer() <= 3 && $this->diasParaVencer() >= 0) {
            return round($base * 0.8, 2); // 20% de desconto
        }
        return $base;
    }

    public function paraArray(): array
    {
        return parent::paraArray() + [
            'validade' => $this->validade->format('Y-m-d'),
        ];
    }
}