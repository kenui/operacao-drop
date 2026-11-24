# Modelo: plano de campanha no Meta (copiar para `operacoes/<op>/produtos/<produto>/09-plano-campanha.md`)

Preencher tudo. Campo sem dado vira **[HIPÓTESE]** e entra na fila de testes. Base: [manual](manual-meta-ads.md).

## 0. Portão (nada sobe sem todos ✅)
- [ ] Cobrança real ligada (Stripe em produção) e compra de teste real reembolsada
- [ ] Produto do anúncio = produto do fornecedor (cor, modelo, acessório) e = página
- [ ] Prazo de entrega real escrito na página e no plano
- [ ] Pixel + API: Purchase com event_id único, e-mail/telefone/CEP; eventos de teste conferidos
- [ ] Domínio verificado no Business; Página + Instagram ligados; 2 fatores nos admins
- [ ] Criativos aprovados pelo Kenui e passados na skill de conformidade
- [ ] UTM padrão em todos os anúncios (manual §8.2)
- [ ] Valor total aprovado pelo Kenui por escrito no thread

## 1. Contas do produto
| Item | Valor | Fonte |
|---|---|---|
| Ticket médio por comprador (com bump/upsell/downsell) | | funil |
| Custo entregue por pedido | | fornecedor |
| Taxas de pagamento | | Stripe |
| Reserva de reembolso | | política |
| **Contribuição** | | conta |
| **CPA de empate no Gerenciador** (contribuição ÷ 1,1383) | | |
| **CPA alvo** | | decisão |
| Cenário pessimista (só produto principal, reembolso alto) | | |

## 2. Estrutura (fase atual)
Campanha, conjunto, público, posicionamentos, lance, evento, orçamento diário, spend cap, datas.

## 3. Criativos (3 a 5 conceitos distintos)
| Nome do anúncio | Ângulo | Persona | Formato | Consciência | Hipótese em 1 frase | Arquivo |
|---|---|---|---|---|---|---|

## 4. Régua com os números deste produto
Esperar / corte duro / corte / segurar / escalar / fadiga em US$ (manual §6).

## 5. Verba e marcos
Quanto, por quantos dias, o que esperamos ver em cada marco e qual decisão cada resultado dispara (incluindo "não deu": o que aprendemos e o que muda).

## 6. Calendário
Datas especiais, corte de presente pelo prazo de entrega, quando trocar mensagem.

## 7. O que vira teste depois
Ligações com a fila do manual (§9) e com a fila da página (`testes/fila.md`).

## Histórico
