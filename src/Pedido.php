<?php
declare(strict_types=1);
require_once __DIR__ . '/ItemPedido.php';
require_once __DIR__ . '/ProdutoPerecivel.php';

class Pedido
{
    private array $itens = [];

    public function adicionarItem(ItemPedido $item): void
    {
        $produto = $item->getProduto();
        if ($produto instanceof ProdutoPerecivel && $produto->estaVencido()) {
            throw new Exception("Produto perecível vencido não pode ser vendido.");
        }
        $this->itens[] = $item;
    }

    public function getItens(): array
    {
        return $this->itens;
    }

    public function quantidadeItens(): int
    {
        return count($this->itens);
    }

    public function calcularTotal(): float
    {
        $total = 0.0;
        foreach ($this->itens as $item) {
            $total += $item->getSubtotal();
        }
        return round($total, 2);
    }

    public function finalizar(): void
    {
        if (empty($this->itens)) {
            throw new Exception("Pedido não pode ser finalizado vazio.");
        }
    }

    public function paraArray(): array
    {
        return [
            'itens' => array_map(fn(ItemPedido $i) => $i->paraArray(), $this->itens),
            'total' => $this->calcularTotal(),
        ];
    }
}