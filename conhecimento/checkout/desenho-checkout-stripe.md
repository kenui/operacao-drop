# Desenho do checkout próprio com a Stripe (v1, 23/11/2026)

Fase de desenho: **nenhum código ainda**, nenhuma mudança na conta da Stripe. Tudo que mexe na conta (chaves, domínios, regras do Radar, webhooks) só acontece com o aval do Kenui.

Objetivo: checkout na própria landing page, com bump e upsell de um clique, medido evento por evento, sem aumentar contestação.

---

## 1. Perguntas que mudam o desenho (para o Kenui)
1. **A conta da Stripe é do Brasil ou dos EUA?** Muda a taxa: conta BR cobra 3,99% + R$0,39 **+ 2% por cartão internacional** e repassa só em reais, com câmbio embutido ([Stripe Brasil](https://stripe.com/en-br/pricing)); conta dos EUA cobra 2,9% + US$0,30. O portão de viabilidade usava a taxa americana.
2. **Qual plano da Hostinger?** Todo plano roda PHP. Node.js só no Business, Cloud ou VPS ([Hostinger](https://www.hostinger.com/support/1583661-is-node-js-supported-at-hostinger/)).
3. **Tem empresa nos EUA?** Afeta rodapé legal, imposto sobre vendas e o nome que aparece na fatura do cartão.

Suposição até a resposta: conta BR, plano com PHP, sem empresa nos EUA.

## 2. Peças da Stripe que vamos usar
| Peça | Para quê | Fonte |
|---|---|---|
| **Payment Element** | formulário de pagamento embutido na nossa página: cartão, Apple Pay, Google Pay, Link (cartão salvo da Stripe). Os dados do cartão nunca passam pelo nosso servidor (PCI no nível mais simples, SAQ A). | [docs](https://docs.stripe.com/payments/save-during-payment?payment-ui=elements) |
| **Address Element** | endereço de entrega com autocompletar (menos campos) | docs Stripe |
| **PaymentIntent** (servidor) | cria e ajusta o valor do pedido: kit + bump | docs Stripe |
| **Customer + `setup_future_usage: off_session`** | salvar o cartão **com consentimento** para cobrar o upsell sem digitar de novo | [docs](https://docs.stripe.com/payments/save-during-payment?payment-ui=elements) |
| **PaymentIntent off-session** | o clique no upsell cria uma nova cobrança no cartão salvo | [docs](https://docs.stripe.com/payments/off-session-payments) |
| **Webhooks** | fonte da verdade da venda: `payment_intent.succeeded`, `payment_intent.payment_failed`, `charge.refunded`, `charge.dispute.created`, `radar.early_fraud_warning.created` | docs Stripe |
| **Radar** (incluso) | antifraude; regras para bloquear CVC ou CEP errados | docs Stripe |
| **Domínios de pagamento** | registrar o domínio para Apple Pay aparecer | docs Stripe |
| **Recibo por e-mail da Stripe** | comprovante automático | painel |
| **Descritor da fatura** | nome que o comprador reconhece no extrato (ex.: o nome da página) | painel |

Não usar agora: Stripe Billing (assinatura), Stripe Tax (custo e, por enquanto, sem obrigação; ver seção 6), Connect.

## 3. O fluxo
```
1. Visitante escolhe o kit na página → evento kit_select
2. Clica em comprar → o servidor cria Customer + PaymentIntent (valor do kit, USD,
   setup_future_usage=off_session, metadata: produto, ângulo, utm, event_id)
   → evento initiate_checkout
3. Payment Element + Address Element aparecem na mesma página
4. Marca o bump → o servidor atualiza o valor do PaymentIntent → evento bump_toggle
5. Texto de consentimento acima do botão:
   "By completing your purchase you agree we may charge this card for any
    add-on you accept on the next page. No subscriptions."
6. Confirma → Stripe aprova (ou pede 3D Secure, raro nos EUA)
7. Webhook payment_intent.succeeded → grava o pedido → evento purchase_front
8. Página de upsell (mesmo domínio): "Yes, add it" / "No thanks"
   - Sim → servidor cria PaymentIntent off_session, confirm=true, com o cartão salvo
     - aprovado → upsell_accept
     - banco pede autenticação ou recusa → mostra o Payment Element para confirmar
       na hora, ou segue sem o upsell (nunca insistir mais de uma vez)
   - Não → downsell (uma vez) → mesmo mecanismo
9. Obrigado → resumo do que foi cobrado, prazo, contato, como pedir reembolso
10. Fim do funil (decisão tomada ou 15 min sem resposta) → evento Purchase
    para o Meta com o valor total → pedido ao fornecedor numa caixa só
```

## 4. O que precisa de servidor na Hostinger
Pequeno, em PHP (roda em qualquer plano) com a biblioteca oficial `stripe-php`. Cinco rotas:
| Rota | Faz |
|---|---|
| `POST /api/checkout` | cria Customer e PaymentIntent; limita tentativas por IP |
| `POST /api/bump` | atualiza o valor do PaymentIntent |
| `POST /api/upsell` | cria a cobrança off-session do upsell ou downsell |
| `POST /api/webhook` | recebe eventos da Stripe, confere assinatura, grava pedido, dispara Purchase pela API de Conversões do Meta |
| `POST /api/evento` | recebe os eventos de comportamento da página (ver `conhecimento/metricas/plano-de-medicao.md`) |

Banco MySQL da Hostinger: tabelas `pedidos`, `eventos`, `testes`. Chaves secretas num arquivo de configuração fora da pasta pública e fora do Git. HTTPS obrigatório.

## 5. Riscos de contestação e como reduzir
- **Limites:** a Stripe considera excessiva a taxa acima de **0,75%** de contestações ([Stripe](https://stripe.com/en-dk/docs/disputes/measuring)); cada contestação custa **US$15**, e contestar custa mais US$15, devolvidos só se ganhar ([Chargeback Gurus](https://www.chargebackgurus.com/blog/stripe-dispute-fees)). O programa da Visa (VAMP) multa acima de certos níveis, mas só a partir de 1.500 casos por mês ([Checkout.com](https://www.checkout.com/blog/visa-acquirer-monitoring-program-explained)); para nós, quem fecha a porta antes é a própria Stripe.
- **Causas típicas em drop:** não reconhecer a cobrança, demora na entrega, produto diferente do anúncio, upsell que a pessoa não entendeu.
- **Defesas no desenho:** descritor reconhecível; e-mail de confirmação na hora com o que foi cobrado (front, bump e upsell em linhas separadas); prazo real na página e no e-mail; rastreio enviado assim que existir; reembolso fácil por e-mail (reembolso custa menos que contestação); imagem e tamanho reais (princípio 3); botão "No thanks" claro; caixa nunca pré-marcada.
- **Teste de cartões roubados (card testing):** checkout próprio é alvo. Não criar PaymentIntent ao carregar a página; limitar tentativas por IP e por e-mail; Cloudflare Turnstile (gratuito) se aparecer ataque; valor mínimo; alertas do Radar ([Stripe](https://docs.stripe.com/disputes/prevention/card-testing?locale=en-US)).
- **Revisão da conta:** drop com prazo longo pode levar a Stripe a reter parte do saldo (reserva). Manter prazo honesto e baixa contestação. [a confirmar com a conta do Kenui]

## 6. Conformidade (EUA)
- **Regra da FTC de pedidos pela internet** (16 CFR 435): enviar no prazo anunciado, ou em 30 dias se não anunciar; se atrasar, avisar e oferecer cancelamento com reembolso ([eCFR](https://www.ecfr.gov/current/title-16/chapter-I/subchapter-D/part-435)). É a regra mais importante para drop.
- **Avaliações e depoimentos:** regra da FTC de 2024 proíbe avaliação falsa ou comprada, com multa civil ([FTC](https://www.ftc.gov/news-events/news/press-releases/2024/08/federal-trade-commission-announces-final-rule-banning-fake-reviews-testimonials)).
- **Assinatura:** a regra "click to cancel" da FTC foi anulada pela Justiça em julho de 2025, mas a ROSCA e as leis estaduais de renovação automática continuam valendo ([Latham & Watkins](https://www.lw.com/en/insights/eighth-circuit-vacates-ftc-click-to-cancel-rule)). Decisão da casa: **sem assinatura nesta fase**.
- **Cartão salvo:** a Stripe exige termo claro de como o cartão salvo será usado e consentimento registrado ([Stripe](https://docs.stripe.com/payments/save-during-payment?payment-ui=elements)). Nosso uso: só para o upsell da página seguinte.
- **Imposto sobre vendas:** em geral, a obrigação de um vendedor de fora começa quando passa do limite do estado (muitos usam US$100 mil por ano). Longe de nós agora; revisar com contador quando a receita crescer. [a confirmar]
- **Privacidade:** política com seção da Califórnia (CCPA), banner de cookies com opção de recusar.

## 7. O que fica para a fase de código
Escrever o servidor, testar tudo no modo de teste da Stripe (cartões de teste, inclusive os que pedem autenticação e os que recusam), medir peso e velocidade, e só então pedir ao Kenui para ligar as chaves reais.

## Histórico
- v1 (2026-11-23): desenho inicial, sem código.
