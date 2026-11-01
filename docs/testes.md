# Testes

## Roteiro de testes no terminal

Comando: `php teste.php`

```text
=== TESTE: Feira Digital do Sertão ===

--- 1. Polimorfismo ---
Queijo coalho          x2 => R$    70.00
Carne de sol           x2 => R$    85.80
Manteiga da terra      x2 => R$    44.80

--- 2. Regras de negócio ---
OK, recusado: Nome do produto não pode ser vazio.
OK, recusado: Preço deve ser maior que zero.
OK, recusado: Quantidade deve ser maior que zero.
OK, recusado: Produto perecível vencido não pode ser vendido.
OK, recusado: Pedido não pode ser finalizado vazio.
OK, recusado: Peso máximo deve estar entre 0 e 50 kg.

--- 3. Composição e cálculo ---
Itens: 2
Total: R$ 134.35
```

**Explicação de cada linha:**
Polimorfismo: As três primeiras linhas chamam o mesmo método calcularPreco(2). O Queijo (unitário) custou R$ 70,00. A Carne(porpeso)custou R$ 85,80. A Manteiga (perecível, perto de vencer) teve desconto e custou R$ 44,80. Isso comprova que a sobrescrita de métodos (OO2 e OO3) está funcionando.
Regras de negócio: Cada linha "OK, recusado" comprova que uma exceção Exception foi lançada pelo sistema e capturada pelo try/catch (OO5). Por exemplo, "Quantidade deve ser maior que zero" comprova a regra do ItemPedido.
Composição: O sistema criou um Pedido com 2 itens e calculou o total de R$ 134,35, comprovando que o objeto Pedido guarda uma lista de ItemPedido e sabe calcular o total (OO6).

## Camada web

**Uma página que existe:** `curl.exe -i "http://localhost:8000/?pagina=..."` (cole só até o início do HTML)

```text
HTTP/1.1 200 OK, Date, Host, Connection, X-Powered-By, Content-type: text/html; charset=UTF-8 e o início do HTML
```

**Uma página que não existe:** `curl.exe -i "http://localhost:8000/?pagina=inexistente"`

```text
HTTP/1.1 404 Not Found, Date, Host, Connection, X-Powered-By, Content-type: text/html; charset=UTF-8 e o início do HTML de erro
```

**A rota JSON:** `curl.exe -i "http://localhost:8000/?formato=json"`

```text
HTTP/1.1 200 OK, Content-Type: application/json; charset=utf-8 e o JSON com os produtos
```

**O formulário com um dado inválido:** print da página com a mensagem de recusa e o log do servidor.

```text
[Sat Oct 31 22:51:56 2026] 127.0.0.1:61702 [200]: GET /?pagina=pedido
[Sat Oct 31 22:51:56 2026] 127.0.0.1:61703 [200]: GET /estilo.css
(print-erro.png)

```
