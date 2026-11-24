# Projetor pet: oferta e funil (v1, 24/11/2026)

Feito pelo agente Funil e oferta (prompt v1), a partir do [dossiê](02-dossie.md), dos [ângulos](04-angulos.md) e do [manual de funil](../../../../conhecimento/funil/manual-funil.md).

**Fase 0, conferência honesta:** o produto **ainda não passou na trava** (itens 1, 2 e 7 aguardam a CJ: pedidos CJSPU961146136 e CJSPU961146540). Este funil foi adiantado a pedido do Kenui. Todo número de custo e prazo está marcado `[HIPÓTESE]` e mora num só lugar trocável: `05-pagina/site/public_html/config.js` (página) e `05-pagina/site/config.php` (servidor). Quando a cotação chegar, troca-se ali e refaz-se a conta da seção 3.

Taxa usada: Stripe BR = 0,06 × P + 0,10 por cobrança, mais câmbio a medir (manual, Parte 5) `[HIPÓTESE até a primeira venda]`.

---

## 1. Oferta de frente

| Kit | O que vai na caixa | Preço | Custo entregue | Margem* | Quem compra e por quê |
|---|---|---|---|---|---|
| **1 kit** | projetor + 1 pelúcia do pet (ratinho com catnip ou esquilo que apita) + caixa de presente | **US$34,99** | ≤ US$10,50 `[HIPÓTESE: teto da trava]` | US$20,54 | P1, P2, P4: "I feel bad that she can't catch it" (dossiê #2) |
| **2 kits** | dois kits completos, **pode misturar gato e cachorro** | **US$59,99** (US$30 cada, "save US$10") | ≤ US$21,00 `[HIPÓTESE]` (caixa única deve sair mais barato) | US$32,29 | P3 presente e casas com gato e cachorro. Dezembro. |

*Margem = preço − custo − taxa Stripe − 5% de reserva de reembolso.

- **Por que 2 níveis e não 3:** com custo não confirmado, um kit de 3 a US$79,99 pode não fechar, e a casa com 3 pets é minoria. 3 kits é convenção, não lei (manual, Parte 1, Scheibehenne 2010). Entra na fila de testes se o kit 2 passar de 25% dos pedidos.
- **Misturar gato e cachorro no kit 2** é a vantagem que só nós temos (a Pouncy vende só gato). Responde à casa com os dois bichos sem criar outro produto.
- **Preço:** US$34,99 termina em 9 (Anderson e Simester 2003) e fica acima da mediana dos concorrentes (US$29,99) porque o kit tem a pelúcia e a caixa de presente. Sem preço riscado: não existe preço anterior real.
- **Frete:** grátis, embutido (manual, Parte 4; Baymard: 40% abandonam por custo extra).
- **Prazo:** campo `delivery` no config. **Não publicar sem o prazo da CJ.** Se o prazo passar de 12 dias úteis, a página diz o número real e o FAQ explica por quê (regra da FTC 16 CFR 435).
- **Garantia:** 30 dias, dinheiro de volta, "inclusive se o seu pet só olhar para ele" (limite honesto do dossiê). Devolução do produto: não exigida abaixo de um valor que a operação define (devolver da China custa mais que o kit) `[DECISÃO DO KENUI: recomendo reembolso sem devolução no primeiro pedido de cada cliente]`.
- **Brinde:** a própria pelúcia é o "fim da caça"; não é brinde, é o kit. Nada além disso.

## 2. Ofertas extras

| Oferta | Persona e frase | Etapa | Preço | Custo | Margem | Risco |
|---|---|---|---|---|---|---|
| **Pacote de pelúcias extras** (3 ratinhos com catnip ou 2 esquilos que apitam) | P1/P4: pelúcia some embaixo do sofá e cachorro destrói apito em dias; "needs to 'kill' something when it's over" (#3) | **Bump** | **US$7,99** | ~US$2,00 `[HIPÓTESE, CJ]` | ~US$5,40 | baixo, mesma caixa |
| **Mais um kit para dar de presente, 30% menos** | P3 e dezembro: "Pouncy vende kit de 2 e 3 para dar a um amigo" | **Upsell 1** | **US$24,99** | ≤ US$10,50 | ~US$11,90 | baixo; mesma caixa; cobrança separada no e-mail |
| **Guia "The 10-Minute Hunt"** (PDF, plano de brincadeira dentro de casa para gato e cachorro) | P1: "It's 3 a.m., and something is thundering down the hallway" (#11); P2: "I always end TV time with the wand and a treat" (#8) | **Downsell** | **US$7,00** | 0 | ~US$6,50 | baixo; nada de saúde |
| Embrulho para presente | P3 | descartado agora | — | — | — | o kit já vem em caixa de presente; vender embrulho seria cobrar duas vezes |
| Processamento prioritário | — | proibido | — | — | — | não muda o prazo real do fornecedor |
| Proteção de pacote | todos | depois | US$2,99 | 0 | — | só quando houver processo real de reenvio |

**Escolhas:** bump = pelúcias extras; upsell = segundo kit 30% menos; downsell = guia digital.
- `[JUSTIFICATIVA bump]` manual Parte 2, item 2 (acessório que completa o uso). 23% do preço do front, decisão de 2 segundos, mesmo pet do kit.
- `[JUSTIFICATIVA upsell]` manual Parte 2, item 1 (mais uma unidade com desconto) + Parte 4 (Q4, ângulo gift). Mesma caixa: o pedido à CJ só sai quando o funil termina.
- `[JUSTIFICATIVA downsell]` manual Parte 3. Teste "pagaria sozinho?": sim para P1, que hoje procura "how to tire out an indoor cat" (dúvida real nos fóruns do dossiê). **Precisa ser escrito antes de ligar o downsell.** Sem ele pronto, a página de downsell fica desligada (`DOWNSELL_ON = false` no config).

### Sumário do guia "The 10-Minute Hunt" (a produzir, ~12 páginas)
1. Why your pet goes nuts at 9 pm (o ciclo caçar, pegar, comer, dormir; fonte: guias de comportamento VCA e HumanePro já citados no dossiê).
2. The 10-minute session: luz baixa, 5 a 8 min de luz, captura com a pelúcia, petisco, fim.
3. Cat vs dog: o que muda (play bow, latido, quando parar).
4. When your pet ignores it: 5 coisas para tentar (posição, escuro, chão liso, horário, cheiro de catnip).
5. Rainy-day plan for dogs: 4 brincadeiras sem sair de casa.
6. Safety notes: não deixar ligado sozinho, fio longe da boca, pausas.
Proibido no guia: promessa de saúde, de peso ou de comportamento garantido.

## 3. A conta (manual, Parte 5)

Hipóteses: 80% kit 1, 20% kit 2; bump 10%; upsell 5%; downsell 3% de quem recusou; reembolso 5%; contestação 0,5% (venda + US$15).

| Item | Base | Pessimista (taxas 30% piores) |
|---|---|---|
| Ticket bruto por comprador | US$42,24 | US$41,72 |
| Custo do produto | US$13,33 | US$13,16 |
| Taxas Stripe | US$2,64 | US$2,61 |
| Reserva de reembolso | US$2,11 | US$2,71 |
| Contestação esperada | US$0,29 | US$0,37 |
| **CPA de empate** | **US$23,87** | **US$22,87** |

**Lucro por visitante** = conversão × 23,87 − custo por visitante:
| Conversão da página | CPC US$0,50 | CPC US$0,80 |
|---|---|---|
| 1,5% | −US$0,14 | −US$0,44 |
| 2,5% | +US$0,10 | −US$0,20 |
| 3,5% | +US$0,34 | +US$0,04 |

Leitura: a operação precisa de conversão perto de 2,5% com clique a US$0,50, ou 3,5% com clique a US$0,80. A alavanca que mais mexe no CPA de empate é o **custo do produto** (cada US$1 a menos na CJ = +US$1 no CPA de empate, mais que bump e upsell juntos, que somam ~US$1,10). A segunda é o **kit 2**: cada 10 pontos a mais de participação somam ~US$1,20.

## 4. Textos do funil
Ficam em [05-pagina/copy.md](05-pagina/copy.md) (bump, upsell, downsell, obrigado, consentimento do cartão, e-mails) e já montados nas páginas do site.

## 5. Medição
Cada etapa dispara os eventos do [plano de medição](../../../../conhecimento/metricas/plano-de-medicao.md): `page_view`, `view_content`, `block_view`, `scroll_*`, `pet_select`, `kit_select`, `cta_click`, `initiate_checkout`, `bump_toggle`, `add_payment_info`, `payment_failed`, `purchase_front` (webhook), `upsell_view/accept/decline`, `downsell_*`, `funnel_complete` → **Purchase** no Meta uma vez só, com o valor total e `event_id` = id do pedido, no pixel e na API de Conversões. Todo evento leva `pet` e `angulo`.

Fila dos 5 primeiros testes: [testes/fila.md](testes/fila.md).

## Histórico
- v1 (2026-11-24): primeira versão, antes da cotação da CJ.
