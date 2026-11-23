# Plano de medição e testes contínuos (v1, 23/11/2026)

Regra do Kenui (23/11): **tudo é medido e testado o tempo todo, e o que decide é o ROI.** Vale para página, funil, oferta, checkout e anúncio. Este arquivo diz o que medir, como decidir e como registrar o ganho.

---

## 1. A métrica-mestre
**Lucro por visitante (LPV)** = (receita líquida − custo do produto entregue − taxas − reembolsos − contestações) ÷ visitantes únicos da página, na mesma janela.
- Por que não conversão: um bump que sobe o ticket e baixa a conversão pode dar mais lucro; um "de/por" que sobe conversão e sobe reembolso pode dar menos.
- Por que não ROAS: ignora custo do produto e reembolso.
- Somado ao custo do anúncio: **lucro líquido por dia** = visitantes × LPV − gasto em anúncio. É a régua da operação inteira (desenho v1).

Métricas de apoio (explicam o LPV, não decidem sozinhas): CTR do anúncio, custo por visitante, taxa de carregamento (cliques que viram visitas), rolagem por bloco, clique no botão, início de checkout, conversão, ticket médio, aceite de bump, aceite de upsell, reembolso, contestação.

**Guarda-corpos** (um teste "vencedor" que piora estes é descartado): reembolso em 30 dias, contestação (meta < 0,5%; limite da Stripe 0,75%), reclamações por e-mail, velocidade da página.

## 2. Eventos que cada etapa registra
Todo evento leva: `visitante_id` (cookie próprio), `sessao_id`, `produto`, `angulo` (`?a=`), `variante` do teste, `utm_*`, `fbclid`, dispositivo, data e hora.

| Etapa | Evento nosso | Evento no Meta (pixel + API de Conversões, mesmo `event_id`) |
|---|---|---|
| Página carregou | `page_view` (+ tempo até carregar) | PageView |
| Conteúdo visto | `view_content` (3 s na página) | ViewContent |
| Cada bloco visto | `block_view` com ID do bloco (50% visível por 1 s) | — |
| Rolagem | `scroll_25/50/75/100` | — |
| Vídeo | `video_play`, `video_25/50/75/100` | — |
| Kit escolhido | `kit_select` (qual kit) | AddToCart |
| Clique em comprar | `cta_click` (qual botão) | — |
| Checkout aberto | `initiate_checkout` | InitiateCheckout |
| Bump | `bump_toggle` (marcou ou desmarcou) | — |
| Pagamento enviado | `add_payment_info` | AddPaymentInfo |
| Pagamento recusado | `payment_failed` (motivo da Stripe) | — |
| Compra do front | `purchase_front` (pelo webhook da Stripe) | — |
| Upsell / downsell | `upsell_view`, `upsell_accept`, `upsell_decline` (idem downsell) | — |
| Fim do funil | `funnel_complete` com valor total | **Purchase** com valor total, `event_id` = id do pedido |
| Pós-venda | `refund`, `dispute`, `email_suporte` (webhooks e caixa de e-mail) | — |

Decisões técnicas:
- **Purchase vai uma vez só, com o valor total do pedido** (front + bump + upsell), quando o funil termina. Assim o Meta aprende o valor real do cliente e não conta o upsell como segunda compra. Servidor e navegador mandam o mesmo `event_id` para o Meta não duplicar ([Meta](https://developers.facebook.com/docs/marketing-api/conversions-api/deduplicate-pixel-and-server-events)). [HIPÓTESE da casa: testar contra Purchase imediato no front]
- A fonte da verdade da venda é o webhook da Stripe, nunca o navegador.
- Os eventos ficam no nosso banco (MySQL da Hostinger, rota `/api/evento`). Microsoft Clarity (gratuito) para mapa de calor e gravação de sessões.
- Cada linha de pedido guarda o custo do fornecedor, para o LPV sair direto do banco.

## 3. Como o agente decide uma melhoria
### 3.1 O ciclo (toda segunda e quinta, ou a cada 1.000 visitantes, o que vier antes)
1. **Diagnóstico:** monte o funil etapa por etapa com os números da janela. Antes de tudo, confira a entrega do anúncio (skill `conformidade-anuncios`): anúncio limitado parece oferta ruim.
2. **Ache o maior vazamento perto do dinheiro:** compare cada etapa com a referência (seção 5) e calcule quanto LPV a etapa ganharia se chegasse na referência. A etapa com maior ganho possível em dólar vence. Ordem de desempate: checkout > oferta e kits > primeira tela > resto da página > upsell.
3. **Hipótese escrita:** "Se mudarmos X no bloco Y, a métrica Z sobe porque [frase do comprador, mapa de calor ou princípio do manual]". Uma mudança por teste.
4. **Teste A/B** (seção 4) com 50/50 de tráfego, sorteio por visitante (a mesma pessoa vê sempre a mesma versão).
5. **Decisão** pela regra da seção 4.
6. **Registro:** linha em `operacoes/<op>/registro-de-ganhos.csv` e em `aprendizados.md` do tema; se virou padrão, `JEITO-CERTO.md` com histórico e prova.

### 3.2 Fila de testes
Mantida em `operacoes/<op>/produtos/<produto>/testes/fila.md`: hipótese, bloco, métrica, ganho esperado em LPV, custo para fazer. Ordem: ganho esperado ÷ custo. Sempre há um teste rodando quando há tráfego.

## 4. Estatística honesta com pouca verba
Verba inicial: R$500 (cerca de US$90). Isso não dá para provar diferença em compras: para detectar uma subida de 2% para 3% de conversão (80% de poder, 5% de erro) são precisos cerca de **3.800 visitantes por versão**; de 2% para 2,5%, cerca de 13.800. [conta padrão de duas proporções]

Por isso:
- **Fase de pouca verba:** teste só mudanças grandes (outro ângulo, outra oferta, outra primeira tela), e decida por métricas mais perto do topo, que têm muito mais eventos: clique no botão, início de checkout, rolagem até a oferta. Com 10% contra 15% de clique no botão, bastam cerca de **690 visitantes por versão**.
- **Regra de decisão (bayesiana):** probabilidade de a versão nova ser melhor no LPV (ou na métrica de topo, nesta fase) ≥ 90%, com no mínimo 7 dias e 300 visitantes por versão, sem guarda-corpo piorando. Abaixo disso, o teste continua ou é encerrado como empate (fica a mais barata ou a mais simples).
- **Não espiar e parar cedo:** decidir só no fim da janela combinada (Kohavi, *Trustworthy Online Controlled Experiments*). Conferir no início se a divisão está mesmo perto de 50/50; se não, o teste está quebrado.
- **Ganho medido de verdade:** depois de adotar o vencedor, comparar as 2 semanas seguintes com as 2 anteriores no LPV. Se o ganho sumir, registrar como "não se confirmou".
- Quando houver mais de 200 compras por mês, mudar a decisão para compras e LPV direto.

## 5. Referências iniciais (substituir pelos nossos números)
| Etapa | Referência de partida | Origem |
|---|---|---|
| Carregamento (cliques → visitas) | ≥ 85% | [OPINIÃO de mercado] |
| Conversão da landing (e-commerce) | mediana 4,2% | [DADO DE FORNECEDOR] Unbounce |
| Abandono de checkout | média 70% | [FATO] Baymard |
| Bump | 10% | hipótese conservadora da casa |
| Upsell de um clique | 4,7% média | [DADO DE FORNECEDOR] ReConvert |
| Contestação | < 0,5% (Stripe: excessivo acima de 0,75%) | [FATO] Stripe |

## Histórico
- v1 (2026-11-23): criação, a pedido do Kenui ("tudo metrificado e testado constantemente, o que der mais ROI").
