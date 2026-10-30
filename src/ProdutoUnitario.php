<?php
declare(strict_types=1);
require_once __DIR__ . '/Produto.php';

class ProdutoUnitario extends Produto
{
    public function __construct(string $nome, float $preco)
    {
        parent::__construct($nome, $preco);
    }
}