<?php
declare(strict_types=1);

require_once __DIR__ . '/src/Produto.php';
require_once __DIR__ . '/src/ProdutoUnitario.php';
require_once __DIR__ . '/src/ProdutoPorPeso.php';
require_once __DIR__ . '/src/ProdutoPerecivel.php';
require_once __DIR__ . '/src/ItemPedido.php';
require_once __DIR__ . '/src/Pedido.php';

echo "=== TESTE: Feira Digital do Sertão ===\n\n";

// 1) Polimorfismo
echo "--- 1. Polimorfismo ---\n";
$produtos = [
    new ProdutoUnitario('Queijo coalho', 35.00),
    new ProdutoPorPeso('Carne de sol', 42.90),
    new ProdutoPerecivel('Manteiga da terra', 28.00, new DateTime('+2 days')),
];
foreach ($produtos as $p) {
    printf("%-22s x2 => R$ %8.2f\n", $p->getNome(), $p->calcularPreco(2));
}
echo "\n";

// 2) Regras de negócio
echo "--- 2. Regras de negócio ---\n";

try { new ProdutoUnitario('', 10); echo "ERRO: nome vazio aceito\n"; }
catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

try { new ProdutoUnitario('X', 0); echo "ERRO: preço zero aceito\n"; }
catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

try { new ItemPedido($produtos[0], 0); echo "ERRO: quantidade zero aceita\n"; }
catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

$vencido = new ProdutoPerecivel('Queijo vencido', 18.00, new DateTime('-1 day'));
try {
    $ped = new Pedido();
    $ped->adicionarItem(new ItemPedido($vencido, 1));
    echo "ERRO: perecível vencido aceito\n";
} catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

try {
    $vazio = new Pedido();
    $vazio->finalizar();
    echo "ERRO: pedido vazio finalizado\n";
} catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

try { new ProdutoPorPeso('X', 10, 60); echo "ERRO: peso 60 aceito\n"; }
catch (Exception $e) { echo "OK, recusado: {$e->getMessage()}\n"; }

// 3) Composição + cálculo
echo "\n--- 3. Composição e cálculo ---\n";
$p = new Pedido();
$p->adicionarItem(new ItemPedido($produtos[0], 2));
$p->adicionarItem(new ItemPedido($produtos[1], 1.5));
$p->finalizar();
echo "Itens: {$p->quantidadeItens()}\n";
echo "Total: R$ " . number_format($p->calcularTotal(), 2, ',', '.') . "\n";