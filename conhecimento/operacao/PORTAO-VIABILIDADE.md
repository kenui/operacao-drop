# Portão de viabilidade (v1.1, 23/11/2026)

Nenhum produto passa para dossiê, página ou vídeo sem passar por este portão. Quem roda: o Garimpeiro antes de apresentar candidatos ao Kenui, e o agente de Dossiê confere de novo antes de começar (Fase 0).
Nasceu da vila de Natal (23/11/2026): o produto foi escolhido com custo estimado (US$14 a 20) e, na checagem, o fornecedor real custava US$24 a 30, saía só da China, a peça tinha 17 cm contra fotos de IA que a faziam parecer grande, e a Amazon já vendia igual a US$33. Ver [a lição](../../operacoes/drop-eua/produtos/vila-teleferico/LICAO.md).

## 1. Os 8 itens (todos com link ou print; "estimado" não conta)
| # | Item | Como checar | Passa se |
|---|---|---|---|
| 1 | **Custo real entregue nos EUA** | carrinho do fornecedor (CJ, AliExpress em dólar, ou cotação por escrito) com frete para um CEP dos EUA | custo ≤ **30%** do preço de venda |
| 2 | **Estoque e prazo** | "ship from" do fornecedor + prazo informado | entrega em ≤ 12 dias, e a janela de venda que sobra (data-limite do evento − prazo − 5 dias de montagem) é ≥ 21 dias, ou o produto não é sazonal |
| 3 | **Preço na Amazon e no Walmart** | busca pelo produto e por foto | o mais barato com entrega antes do evento custa ≥ **85%** do nosso preço, ou o nosso tem algo que lá não tem (kit, versão, tamanho) |
| 4 | **Margem antes do anúncio** | conta da seção 2 | ≥ **US$20** e ≥ 45% do preço |
| 5 | **CPA de empate** cabe no mercado | margem antes do anúncio = CPA máximo | CPA máximo ≥ US$20 (Q4) ou ≥ US$15 (janeiro a setembro) |
| 6 | **Tamanho e aparência reais** | medida do fornecedor + foto ou vídeo real (não render) | o real bate com o que os anúncios mostram, ou dá para vender honestamente com a escala real |
| 7 | **Função real** | vídeo do fornecedor sem edição | faz o que os anúncios prometem |
| 8 | **Verba de teste cabe** | **CPA esperado da época** × 3 compras (Q4: US$25; janeiro a setembro: US$18). Não usar o CPA de empate aqui: margem alta não pode reprovar. | ≤ verba disponível (hoje R$500 ≈ US$90) |

Um item reprovado = produto parado. Dois itens "a confirmar" = não apresentar como recomendado, só como "em checagem".

## 2. A conta
```
preço de venda (P)
− Stripe: 0,029 × P + 0,30
− produto entregue (C)
− reserva de quebra/reembolso: 5% de P (10% se frágil)
= margem antes do anúncio (M) = CPA de empate
Kit de 2 (se fizer sentido): recalcular com 2C e o preço do kit.
```
Exemplo da vila: P 39,98; Stripe 1,46; C 28; reserva 10% 4,00 → **M = 6,52**. Reprovada nos itens 1, 4 e 5.
Exemplo de aprovado: P 39,98; C 11; reserva 5% 2,00 → M = 25,52 (64% do preço). Passa.

## 3. Ordem barata (para não gastar trabalho à toa)
1. Amazon e Walmart (3 minutos): se já tem igual mais barato, para aqui.
2. Fornecedor: custo em dólar, "ship from", prazo, medida.
3. Conta da seção 2.
4. Só então: Biblioteca do Meta a fundo, dossiê, ângulos, roteiros.

## Histórico
- v1 (2026-11-23): criado depois do descarte da vila de Natal.
- v1.1 (2026-11-23): item 8 passa a usar o CPA esperado da época, não o de empate. Na v1, qualquer margem acima de US$30 reprovava (30 × 3 > US$90), barrando os melhores produtos. Achado pela thread do garimpo na rodada 4.
