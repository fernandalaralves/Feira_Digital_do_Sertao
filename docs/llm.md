# Diário de uso de LLM

Registre pelo menos 5 interações relevantes. Em pelo menos uma, mostre um erro ou uma sugestão ruim da LLM que você percebeu e corrigiu.

## Interação 1

- **Ferramenta e data:** ChatGPT, 26/10/2026 
- **Prompt:**

```text
Estou modelando um sistema de feira. Tenho a classe Produto (nome, preco) e quero criar uma hierarquia onde alguns produtos são vendidos por peso e outros são perecíveis (têm validade). Qual a melhor forma de estruturar essa herança em PHP para que o cálculo do preço final seja polimórfico?
```

- **Resumo da resposta:**
- **O que aceitei, modifiquei ou rejeitei, e por quê:**
 A IA sugeriu criar uma classe base Produto com o método calcularPreco(). Para as filhas, recomendou que ProdutoPorPeso sobrescrevesse o método para arredondar as casas decimais, e ProdutoPerecivel sobrescrevesse para aplicar um desconto caso a validade estivesse próxima.
O que aceitei, modifiquei ou rejeitei, e por quê: Aceitei a estrutura. Modifiquei a regra de desconto: em vez de ser um valor fixo, implementei para que usasse DateTime::diff() e aplicasse 20% de desconto se faltassem 3 dias ou menos para vencer.

## Interação 2

- **Ferramenta e data:** 28/10/2026
- **Prompt:**

```text
No meu método adicionarItem(ItemPedido $item), eu preciso validar se o produto é perecível e se já venceu. Se estiver vencido, preciso lançar uma exceção. Como fazer essa verificação de forma segura respeitando o encapsulamento?
```

- **Resumo da resposta:**
 A IA sugeriu usar o operador instanceof para verificar se o objeto é da classe ProdutoPerecivel. Em seguida, chamar um método estaVencido() dentro da própria classe do produto (para não acessar a data diretamente de fora).
- **O que aceitei, modifiquei ou rejeitei, e por quê:**
 Aceitei a abordagem. Criei o método estaVencido(): bool dentro de ProdutoPerecivel e usei o instanceof no Pedido. Isso manteve os atributos privados e a regra de negócio (OO5) funcionando.

## Interação 3

- **Ferramenta e data:** ChatGPT, 30/10/2026
- **Prompt:**

```text
Preciso implementar o bônus de persistência em JSON. Quando o formulário for enviado via POST, o objeto Pedido deve ser salvo em um arquivo dados.json e recuperado na próxima requisição. Como lidar com a leitura do arquivo caso ele ainda não exista?
```

- **Resumo da resposta:**
A IA indicou o uso de file_put_contents e json_encode para salvar. Para ler, sugeriu file_exists() antes de tentar abrir o arquivo para evitar erros fatais. Também alertou sobre o json_decode retornar null se o arquivo estiver corrompido.
- **O que aceitei, modifiquei ou rejeitei, e por quê:**
Aceitei a lógica e criei a função carregarPedidos(). Modifiquei para adicionar o operador ?? [] ao final do json_decode, garantindo que o sistema retorne um array vazio (e não null) se o arquivo estiver vazio ou não existir.

## Interação 4

- **Ferramenta e data:** Claude, 30/10/2026
- **Prompt:**

```text
Ativei o declare(strict_types=1) e o PHP está dando erro de 'TypeError: Argument must be of type float, int given' quando envio o formulário. Como resolvo esse erro no recebimento dos dados do POST?
```

- **Resumo da resposta:**
A IA explicou que o strict_types desativa a coerção automática de tipos do PHP. Como o $_POST sempre traz strings, e eu estava passando para um parâmetro float, o PHP recusou. A solução era fazer o cast explícito: (float).
- **O que aceitei, modifiquei ou rejeitei, e por quê:**
 Aceitei. No index.php, alterei a captura do formulário para $qtd = (float) ($_POST['quantidade'] ?? 0);. O erro foi resolvido e o sistema passou a validar a quantidade corretamente.

## Interação 5

- **Ferramenta e data:** ChatGPT, 30/10/2026
- **Prompt:**

```text
Como calcular o valor total do pedido percorrendo a lista de itens? Me mostre um exemplo de código.
```

- **Resumo da resposta:**
 A IA gerou um código onde a classe Pedido tinha o atributo public array $itens = []; e um método que fazia a soma diretamente em um foreach. Isso facilitaria o acesso ao array.
- **O que aceitei, modifiquei ou rejeitei, e por quê:**
Rejeitei a sugestão. O enunciado do trabalho exige o uso de encapsulamento (OO4), proibindo atributos públicos. Corrigi o código mudando para private array $itens = []; e criei o método adicionarItem(). O cálculo do total foi feito usando um getter para acessar a lista de forma segura.

## O erro que a LLM cometeu

A Interação 5 mostra o erro principal: a IA gerou um código que feria os requisitos do trabalho. Ao sugerir um atributo public, ela desrespeitou o encapsulamento. Percebi o erro porque estava com o PDF do trabalho aberto revisando os requisitos (OO1 a OO8). Corrigi imediatamente alterando a visibilidade do atributo e encapsulando os métodos.
