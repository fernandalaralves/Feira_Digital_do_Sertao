<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Produto.php';
require_once __DIR__ . '/../src/ProdutoUnitario.php';
require_once __DIR__ . '/../src/ProdutoPorPeso.php';
require_once __DIR__ . '/../src/ProdutoPerecivel.php';
require_once __DIR__ . '/../src/ItemPedido.php';
require_once __DIR__ . '/../src/Pedido.php';

// ---------- Funções de persistência (bônus) ----------
function carregarPedidos(): array
{
    $arquivo = __DIR__ . '/dados.json';
    if (!file_exists($arquivo)) {
        return [];
    }
    $conteudo = file_get_contents($arquivo);
    return json_decode($conteudo, true) ?? [];
}

function salvarPedido(Pedido $pedido): void
{
    $arquivo = __DIR__ . '/dados.json';
    $dados   = carregarPedidos();
    $dados[] = $pedido->paraArray();
    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ---------- Catálogo de produtos de exemplo ----------
function catalogoProdutos(): array
{
    return [
        new ProdutoUnitario('Queijo coalho', 35.00),
        new ProdutoUnitario('Rapadura', 12.50),
        new ProdutoPorPeso('Carne de sol', 42.90),
        new ProdutoPorPeso('Mel de engenho', 58.00),
        new ProdutoPerecivel('Manteiga da terra', 28.00, new DateTime('+2 days')),
        new ProdutoPerecivel('Doce de leite', 22.00, new DateTime('+30 days')),
        new ProdutoPerecivel('Queijo manteiga vencido', 18.00, new DateTime('-1 day')),
    ];
}

// ---------- Rota JSON (W2) ----------
if (isset($_GET['formato']) && $_GET['formato'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'produtos' => array_map(fn($p) => $p->paraArray(), catalogoProdutos()),
        'pedidos'  => carregarPedidos(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ---------- Roteamento (W1) ----------
$pagina = $_GET['pagina'] ?? 'home';
$paginasValidas = ['home', 'produtos', 'pedido', 'contato'];

if (!in_array($pagina, $paginasValidas, true)) {
    http_response_code(404);
    $pagina = '404';
}

// ---------- Formulário POST (W3) ----------
$mensagem = '';
$erro     = '';
$total    = 0.0;
$pedido   = new Pedido();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pagina === 'pedido') {
    try {
        $indice = (int) ($_POST['produto'] ?? -1);
        $qtd    = (float) ($_POST['quantidade'] ?? 0);
        $lista  = catalogoProdutos();

        if (!isset($lista[$indice])) {
            throw new Exception("Produto inválido.");
        }

        $pedido->adicionarItem(new ItemPedido($lista[$indice], $qtd));
        $pedido->finalizar();
        salvarPedido($pedido);
        $mensagem = "Pedido registrado com sucesso! Total: R$ " . number_format($pedido->calcularTotal(), 2, ',', '.');
        $total    = $pedido->calcularTotal();
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

// ---------- Função de escape (W4) ----------
function h(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Feira Digital do Sertão</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header>
    <h1>Feira Digital do Sertão</h1>
    <nav>
        <a href="?pagina=home">Home</a>
        <a href="?pagina=produtos">Produtos</a>
        <a href="?pagina=pedido">Pedido</a>
        <a href="?pagina=contato">Contato</a>
        <a href="?formato=json">JSON</a>
    </nav>
</header>
<main>
<?php if ($pagina === '404'): ?>
    <h2>404 — Página não encontrada</h2>
    <p>A página que você procura não existe.</p>
<?php elseif ($pagina === 'home'): ?>
    <h2>Bem-vindo à Feira Digital do Sertão</h2>
    <p>Compre queijos, rapaduras, carnes de sol, méis e doces direto do produtor.</p>
<?php elseif ($pagina === 'produtos'): ?>
    <h2>Produtos disponíveis</h2>
    <table>
        <thead><tr><th>Nome</th><th>Tipo</th><th>Preço</th></tr></thead>
        <tbody>
        <?php foreach (catalogoProdutos() as $p): ?>
            <tr>
                <td><?= h($p->getNome()) ?></td>
                <td><?= h((new ReflectionClass($p))->getShortName()) ?></td>
                <td>R$ <?= number_format($p->getPreco(), 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($pagina === 'pedido'): ?>
    <h2>Fazer pedido</h2>
    <?php if ($mensagem): ?><p class="ok"><?= h($mensagem) ?></p><?php endif; ?>
    <?php if ($erro): ?><p class="erro">Erro: <?= h($erro) ?></p><?php endif; ?>
    <form method="POST" action="?pagina=pedido">
        <label>Produto:
            <select name="produto">
                <?php foreach (catalogoProdutos() as $i => $p): ?>
                    <option value="<?= (int)$i ?>"><?= h($p->getNome()) ?> — R$ <?= number_format($p->getPreco(), 2, ',', '.') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Quantidade: <input type="number" name="quantidade" step="0.01" min="0.01" value="1"></label>
        <button type="submit">Adicionar ao pedido</button>
    </form>
    
    <h3>Pedidos salvos (persistência JSON)</h3>
    <?php $salvos = carregarPedidos(); ?>
    <?php if (empty($salvos)): ?>
        <p>Nenhum pedido salvo ainda.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($salvos as $i => $p): ?>
                <li>Pedido <?= (int)($i+1) ?> — Total: R$ <?= number_format($p['total'], 2, ',', '.') ?> (<?= count($p['itens']) ?> item(ns))</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php elseif ($pagina === 'contato'): ?>
    <h2>Contato</h2>
    <p>Fale com a feira pelo e-mail: <a href="mailto:contato@feiradigital.com">contato@feiradigital.com</a></p>
<?php endif; ?>
</main>
<footer><p>© 2026 Feira Digital do Sertão — IFCE Boa Viagem</p></footer>
</body>
</html>