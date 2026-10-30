# NOME DO SISTEMA

> Trabalho individual de Programação Web I (IFCE Campus Boa Viagem): sistema web orientado a objetos em PHP.

**Autor(a):** Fernanda Lara Alves de Oliveira
**Tema:** 01 - Feira Digital do Sertão
**Vídeo:** _(cole aqui o link do vídeo)_

## O problema

Feirantes do sertão cearense, que vendem produtos como queijos, rapaduras e carnes de sol, muitas vezes não possuem um catálogo digital simples para alcançar clientes. O sistema resolve isso oferecendo uma plataforma onde é possível visualizar produtos unitários, por peso e perecíveis (com desconto automático se estiverem perto do vencimento), montar um pedido e visualizar o valor total calculado de forma inteligente para cada tipo de item.

## Como executar

Requisitos: PHP 8.3 ou superior.

```
git clone https://github.com/fernandalaralves/Feira_Digital_do_Sertao.git
cd Feira_Digital_do_Sertao
php -S localhost:8000 -t public -d display_errors=1 -d output_buffering=0
```

Abra http://localhost:8000

Para rodar o roteiro de testes no terminal:

```
php teste.php
```

## Telas

_(Coloque 2 ou 3 prints na pasta `docs/` e mostre aqui.)_

## Rotas

| URL                    | O que mostra                                | Método | Status |
| `/`                    | Página inicial(home)                        | GET    | 200    |
| `/?pagina=produto`     | Catálogo de produtos da feira               | GET    | 200    |
| `/?pagina=pedido`      | Formulário para criar um pedido             | GET    | 200    |
  `/?pagina=contato`     | Informações de contato da feira             | GET    | 200    |
| `/?pagina=inexistente` | Mensagem de erro de página não encontrada   | GET    | 404    |
| `/?formato=json`       |Retorna os produtos e pedidos salvos em JSON | GET    | 200    |
| `/?pagina==pedido`     | Envia os dados do formulário                | POST   | 200    |

## Regras de negócio

| Nº| Regra                                               | Classe e método              | Mensagem da exceção                               |
| 1 | Preço do produto deve ser maior que zero            | Produto::setPreco()          | "Preço deve ser maior que zero."                  |
| 2 | Quantidade do item deve ser maior que zero          | ItemPedido::__construct()    | "Quantidade deve ser maior que zero."             |
| 3 | Produto perecível vencido não pode entrar no pedido |Pedido::adicionarItem()       | "Produto perecível vencido não pode ser vendido." |
| 4 | Pedido não pode ser finalizado vazio                | Pedido::finalizar()          | "Pedido não pode ser finalizado vazio."           |
| 5 | Peso (kg) deve ser maior que zero e no máximo 50    |ProdutoPorPeso::__construct() | 	"Peso máximo deve estar entre 0 e 50 kg."        |

## Documentação

- [Modelagem (diagrama de classes)](docs/modelagem.md)
- [Testes](docs/testes.md)
- [Diário de uso de LLM](docs/llm.md)
- [Relatório (PDF)](docs/relatorio.pdf)

## Ferramentas utilizadas
| Ferramenta           | Para que usei                                             |
| PHP 8.3              | Execução do servidor embutido e do roteiro de testes.     |
| VS Code              | Edição do código, terminal integrado e controle do Git.   |
| Git e GitHub         | Versionamento, criação de issues, branches, PRs e tags.   |
| curl                 | Teste das rotas HTTP (ex: curl.exe -i na rota JSON e 404).|
| Navegador e DevTools | Visualização das páginas e inspeção das respostas HTTP.   |
| LLM: DeepSeek        | Apoio ao aprendizado e revisão de código.                 |
| Gravador: OBS Studio | Gravação do vídeo explicativo do sistema.                 |
