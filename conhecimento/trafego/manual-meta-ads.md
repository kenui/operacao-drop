# Manual de tráfego no Meta Ads para drop de resposta direta (v1, 24/11/2026)

Feito para a operação drop EUA, mas vale para qualquer produto. Regra da casa: **"a gente não faz nada porque acha"**. Cada decisão abaixo tem o porquê, a fonte e o teste que pode derrubá-la. Número de praticante (agência, blog) é **[PRÁTICA]**; número publicado pela Meta é **[META]**; conta nossa é **[CONTA]**; o que ainda não tem prova é **[HIPÓTESE]** e entra na fila de testes.

Fontes no fim (§12). Plano por produto: copiar o [modelo](modelo-plano-campanha.md). Como a IA mexe na conta: [integracao-meta.md](integracao-meta.md). O que aprendemos rodando: [aprendizados.md](aprendizados.md).

---

## 1. Como a máquina da Meta funciona hoje (e por que isso define tudo)

1. **Andromeda escolhe primeiro, o leilão vem depois.** Quando alguém abre o Instagram, a Andromeda varre dezenas de milhões de anúncios e separa alguns milhares de candidatos; depois modelos como o GEM ranqueiam. **[META, dez/2025]**
2. **Anúncios parecidos viram um só.** A Meta agrupa criativos com visual e tema parecidos e trata como variações do mesmo criativo; o aprendizado e a entrega são divididos entre eles. 12 legendas do mesmo vídeo = 1 criativo. **[META]**
3. **Diversificar ≠ iterar.** Diversificar é fazer peças distintas para personas e usos diferentes; iterar é mexer em detalhe (texto, cor, CTA). A Meta pede diversificação como base. **[META]**
4. **O público vem do criativo.** Com público aberto (Advantage+ audience), quem decide quem vê o anúncio é o próprio anúncio. A pessoa que se vê no vídeo (cena, dor, quem fala) é quem recebe. **[PRÁTICA: Sam Tomlinson, Pigeon Digital, Jon Loomer]**
5. **Aprendizado precisa de volume.** Um conjunto sai da fase de aprendizado com cerca de **50 resultados na semana** depois da última edição significativa. Edição significativa: qualquer mudança de público, criativo, evento de otimização, estratégia de lance, **anúncio novo no conjunto**, pausa de 7+ dias. Orçamento: depende do tamanho (US$100 → 101 não reinicia; 100 → 1.000 pode). **[META]**
6. **Quantos conjuntos o orçamento aguenta** = orçamento semanal ÷ (custo por resultado × 50). **[META, post de pico out/2026]** Conjunto abaixo disso fica "aprendizado limitado"; não é punição, só entrega menos eficiente. **[META]**

**Consequência para nós:** poucos conjuntos, público aberto, e o trabalho de verdade é **fazer criativos realmente diferentes entre si**, saídos da matriz consciência × persona que já usamos na página ([princípio de ângulos](../copy/PRINCIPIOS.md)). Isso casa com o que o Kenui já pediu: formato do vídeo muda conforme persona, ângulo e tipo de anúncio.

## 2. As três contas que vêm antes de qualquer anúncio

Nenhuma campanha sobe sem estes números escritos no plano do produto. Fórmulas **[PRÁTICA: GaaS, AdAdvisor]**:

| Número | Fórmula | Para quê |
|---|---|---|
| **Contribuição por comprador** | ticket médio (front + bump + upsell + downsell) − custo entregue CJ − taxa Stripe (~6% + US$0,10 por cobrança, conta BR) − reserva de reembolso | o que sobra antes do anúncio |
| **CPA de empate (no Gerenciador)** | contribuição ÷ **1,1383** | o máximo que o Gerenciador pode mostrar por compra sem perder dinheiro |
| **CPA alvo** | CPA de empate − lucro que queremos por pedido | acima disso não escala |

**Por que dividir por 1,1383:** desde 01/01/2026 a Meta repassa PIS/Cofins (9,25%) e ISS (2,9%) a quem é faturado pela Facebook Serviços Online do Brasil. No cartão, a fatura é o gasto do Gerenciador **+13,83%**; no Pix pré-pago, de cada R$100 só R$87,85 viram anúncio. O Gerenciador **não mostra** esse imposto. **[META, comunicado LATAM Finance; Folha 12/09/2025]** Também vale para o painel: o gasto real é o do Gerenciador × 1,1383.

**ROAS de empate** = 1 ÷ margem de contribuição. Usamos CPA como régua principal porque o ticket muda com bump/upsell e o CPA conversa direto com o lucro por visitante do [plano de medição](../metricas/plano-de-medicao.md).

## 3. Estrutura de conta

### 3.1 Decisão: consolidar
| Fase | Estrutura | Por quê |
|---|---|---|
| **Lançamento** (menos de ~US$100/dia, pixel sem compras) | **1 campanha de Vendas, 1 conjunto, público aberto, 3 a 5 anúncios diversos** | O orçamento não alimenta 2 conjuntos (§1.6). Loja abaixo de ~€10 mil/mês normalmente põe o teste dentro da campanha que escala, porque o sinal não sustenta duas. **[PRÁTICA: EcomLabs360]** "Ao juntar conjuntos, você junta aprendizados." **[META]** |
| **Tração** (CPA no alvo por 7 dias, 10+ compras/semana) | **Escala:** Vendas Advantage+ (orçamento, público e posicionamentos Advantage+), 8 a 20 anúncios provados, mexer o mínimo. **Teste:** campanha manual com orçamento por conjunto (ABO), 1 conjunto por conceito | Escala estável sem ser perturbada pelos testes; teste com leitura limpa. Ponto de partida 65–75% escala, 15–25% teste. **[PRÁTICA: First Byte, nov/2026]** |
| **Volume** (vários produtos, catálogo) | + catálogo Advantage+ 5–15%; remarketing só se passar no teste de incrementalidade (§7) | idem |

Ad set de Vendas Advantage+ aceita até 50 anúncios; o antigo "ASC" acabou na API v25 (1º tri 2026) e o teto de gasto com clientes existentes foi descontinuado. **[META via First Byte/Jon Loomer]**

### 3.2 Configuração padrão de uma campanha de Vendas nossa
- **Objetivo:** Vendas. **Evento:** Purchase (§4). **Atribuição:** padrão (7 dias clique, 1 dia visualização, 1 dia engajamento); comparar sempre com só-clique e com o painel (§8).
- **Orçamento:** da campanha (Advantage+ campaign budget), diário.
- **Público:** Advantage+ audience. Controle rígido só: **EUA**, 18+. Sugestão de idade da persona (ex.: 25–54) como *sugestão*, nunca trava. Sem interesses empilhados.
- **Posicionamentos:** Advantage+ (todos). Ver regra de Audience Network em §9 (teste H-T4).
- **Lance:** Maior volume (sem teto) no início. Custo-alvo (cost cap) só depois de 30+ compras, quando sabemos o CPA real (H-T5).
- **Limite de gasto da campanha (spend cap)** = valor aprovado pelo Kenui. É a trava que impede gastar além do aprovado, mesmo com erro.
- **Exclusão:** compradores (Purchase 180 dias) fora da prospecção quando houver; ou a opção "só clientes novos" se a conta tiver.
- **Destino:** página do produto com `?pet=&a=` (ângulo) + UTM padrão (§8.2).

## 4. Evento de otimização: Purchase desde o dia 1

**Decisão:** otimizar para **Purchase** mesmo com pixel novo.
**Por quê:** com ticket de ~US$60, otimizar para adicionar ao carrinho faz a Meta caçar quem adiciona ao carrinho, não quem compra. Agências que trabalham com conta nova só descem para AddToCart com ticket acima de ~US$500–800; abaixo disso empurram em Purchase ou arrumam a oferta. **[PRÁTICA: Odylic Media, Scale-ova]** O "aprendizado limitado" que vem junto é esperado e não é punição. **[META]**
**Contra-argumento registrado:** parte dos praticantes manda começar em AddToCart/InitiateCheckout com pixel zerado (Nexus, AdsX). Por isso vira teste:
**Teste H-T1:** se depois de 2× o CPA de empate gasto a campanha de Purchase tiver CTR bom (acima da mediana da conta) e 0 InitiateCheckout, o problema é página/oferta, não o evento. Só testar InitiateCheckout numa campanha separada se o funil estiver saudável e a Purchase ficar abaixo de 3 compras/semana por 2 semanas.

## 5. Criativo: o que testar e como

### 5.1 O que é um "criativo diferente"
Diferente em pelo menos **dois** destes: ângulo (motivo de compra), persona/avatar (quem aparece e para quem fala), formato (UGC falado, demo sem fala, estático, carrossel, depoimento, "trend"), nível de consciência. Agrupar por **persona × conceito**, não só conceito. **[META; PRÁTICA: Pigeon Digital]**
Mesmo vídeo com outro gancho = **iteração**: só depois que o conceito provar valor.

### 5.2 Quantos
- Lançamento: **3 a 5 conceitos** distintos no único conjunto. Menos de 3 não dá escolha para a Andromeda; mais de 5 com verba pequena dilui o gasto e ninguém chega à régua.
- Regra de verba por conceito: cada conceito precisa de **2 a 3× o CPA alvo** de gasto para ser julgado. **[PRÁTICA: First Byte, Scale-ova]** Então: nº de conceitos que a verba julga = verba de teste da semana ÷ (2,5 × CPA alvo).
- Tração: 2 a 5 conceitos novos por semana alimentando a escala. Subir orçamento sem criativo novo é como a frequência sobe e o CPA vai junto. **[PRÁTICA]**

### 5.3 Como testar dentro de um conceito
Ferramenta de **Teste de criativo** da Meta (2 a 5 anúncios, até 20% do orçamento, 7 dias, cada pessoa vê só um) para gancho A × gancho B. Conceito novo vai para a campanha de teste (fase de tração). **[PRÁTICA: Jon Loomer]**
Cada teste nasce com **uma frase de hipótese** ("O gancho do cachorro olhando a presa bate o da dona cansada em custo por compra"). Sem frase, não é teste.

### 5.4 Leitura do criativo (em ordem, conserte só a primeira etapa ruim)
| Métrica | Fórmula | Se estiver ruim |
|---|---|---|
| Hook rate | plays de 3 s ÷ impressões | refazer os 3 primeiros segundos |
| Hold rate | ThruPlay (15 s) ÷ plays de 3 s | o meio enrola; cortar |
| CTR de saída | cliques de saída ÷ impressões | oferta/CTA fraco no vídeo |
| Taxa de conversão | compras ÷ visualizações da página | página, preço, frete, prazo — não o anúncio |
| CPA | gasto ÷ compras | régua final |
Sem "número bom universal": comparar com o quartil de cima da própria conta nos últimos 90 dias. **[PRÁTICA: First Byte, definições Motion/Runneth]** Enquanto não temos histórico, referências de partida no plano do produto, marcadas como [HIPÓTESE].

### 5.5 Prova e conformidade (não negociável)
Nada que o produto não faz (regra do Kenui e da FTC). Sem depoimento inventado, sem antes/depois enganoso, sem "imagem ilustrativa" que vira outra coisa. Passar a skill `conformidade-anuncios` em todo texto antes de subir. Anúncio rejeitado atrasa o limite de gasto da conta nova. **[PRÁTICA: AdsX]**

## 6. Regras de corte, espera e escala (a régua)

Valores em múltiplos do **CPA de empate (E)** e do **CPA alvo (A)** do plano do produto, para servir a qualquer preço. **[PRÁTICA: GaaS, AdAdvisor, Scalewin, First Byte]**; nossos ajustes marcados.

| Situação | Regra | Ação |
|---|---|---|
| Esperar dado | anúncio com < 2× A gasto **ou** < 3 dias no ar | **não mexer** |
| Corte duro | gastou **1,5× E sem nenhuma compra** | pausar o anúncio (nosso ajuste: 1,5× em vez de 3× porque a verba inicial é pequena; checar antes se Purchase está chegando no Gerenciador e no painel) |
| Corte | CPA > E depois de 2–3× A gasto e 3+ dias | pausar |
| Segurar | E ≥ CPA > A | não mexer no orçamento; fazer variação de gancho/meio como anúncio novo; olhar a página |
| Promissor | CPA ≤ A com < 10 compras | segurar e deixar rodar (pode ser sorte) |
| Escalar | CPA ≤ A com **10+ compras** e lucro do painel positivo | +20% de orçamento, esperar 3 dias, repetir |
| Recuar | depois de subir, CPA de 3 dias > E ou lucro do painel cai >10% por uma semana | voltar ao último orçamento que deu lucro |
| Fadiga | frequência 7 dias ≥ 3,5 **e** CTR caiu 30%+ desde o lançamento | não escalar; criativo novo |

**Escala:** +15 a 20% a cada 48–72 h, uma mudança por vez, de preferência de manhã no fuso da conta. A Meta não publica percentual seguro; 20% é convenção de praticante. **[META + PRÁTICA]** Não duplicar conjunto vencedor para "escalar"; levar o anúncio vencedor para a campanha de escala com o **mesmo ID de publicação** (mantém curtidas e comentários). Não desligar o vencedor antigo no mesmo dia. **[PRÁTICA]**
**Com orçamento de campanha:** conjunto com pouco gasto não é perdedor, é "esperar dado"; não pausar/despausar conjunto na mão. **[META]**
**Mudança de orçamento no meio do dia:** o teto do dia vira 75% acima do maior orçamento do dia. **[META]** Por isso mudanças de orçamento são feitas no começo do dia.

## 7. Remarketing

**Decisão:** **não** abrir campanha de remarketing separada no lançamento.
**Por quê:**
1. Advantage+ e público aberto já entregam para quem visitou o site; uma campanha de remarketing separada paga duas vezes pelas mesmas pessoas. A mediana de marcas DTC pôs **1,8%** do gasto em remarketing nomeado (out/2026, 67 marcas). **[PRÁTICA: MHI benchmarks, Shero]**
2. Público abaixo de ~1.000 pessoas entrega mal e caro; com menos de 500 visitas/dia os públicos ficam pequenos demais. **[PRÁTICA: MHI]**
3. Remarketing reportado com ROAS 6 pode dar prejuízo se menos de ~40% das vendas forem incrementais. **[PRÁTICA: e-commPartners]**
4. A Meta não recomenda Advantage+ audience para remarketing (inclusão vira só sugestão). **[META via AdAdvisor]**

**O remarketing que roda desde o dia 1 é o nosso:** e-mail de checkout abandonado (o checkout já pede e-mail e telefone antes do pagamento) e o e-mail de pedido. Custo zero por contato.

**Quando abrir (gatilho):** público "InitiateCheckout 14 dias sem compra" ≥ 1.000 pessoas no Gerenciador **e** campanha de prospecção com CPA ≤ A.
**Como:** campanha separada, orçamento por conjunto (ABO), evento Purchase, sem Advantage+ audience:
1. Checkout abandonado 7–14 dias (maior fatia).
2. Carrinho/página do produto 14–30 dias, excluindo o nível 1.
Excluir compradores 30–60 dias em todos. Orçamento pelo tamanho do público, não por percentual: semanal = (CPM ÷ 1.000) × (público × 0,8) × 2–3 impressões por pessoa. **[PRÁTICA: Shero/Shopify]**
Criativo de remarketing: responde à objeção que travou (golpe? prazo? "meu pet vai ignorar?"), mostra garantia de 30 dias e prova real. Não repetir o vídeo de topo.
**Teste H-T6 (incrementalidade):** depois de 4 semanas, desligar o remarketing por 2 semanas (ou excluir ~20% das regiões) e comparar vendas totais do painel. Se a venda incremental não pagar o gasto, desliga.

## 8. Medição: quem é a verdade

### 8.1 Hierarquia
1. **Painel (servidor + Stripe)** = verdade do dinheiro: receita, custo, taxas, reembolsos e, com o token de leitura, gasto do Meta × 1,1383 → **lucro depois do anúncio e lucro por visitante**.
2. **MER** = receita total ÷ gasto total com anúncio (com imposto). Se o ROAS do Gerenciador sobe e o MER não, o Gerenciador está creditando venda que viria de qualquer jeito. **[PRÁTICA: First Byte]**
3. **Gerenciador** = sinal para a máquina e diagnóstico por anúncio. Pixel + API de Conversões com o mesmo event_id (já ligados no site). Meta pede API cobrindo 75%+ dos eventos do pixel. **[META via Odylic]**

### 8.2 Padrão de URL (todo anúncio)
```
?pet=<dog|cat>&a=<angulo>&utm_source=meta&utm_medium=paid&utm_campaign={{campaign.id}}&utm_term={{adset.id}}&utm_content={{ad.id}}
```
O painel casa gasto e venda **pelo ID** (nome muda, ID não). Combinado com a thread do painel em 24/11.

### 8.3 Nomes
`<produto>_<fase>_<objetivo>_<AAAAMMDD>` na campanha (ex.: `projetor_lanc_vendas_20261201`); conjunto `<publico>_<pais>`; anúncio `<angulo>-<persona>-<formato>-v<n>` (ex.: `sozinho-donacansada-ugc-v1`). O nome do anúncio é o que liga ao arquivo do vídeo no repo.

## 9. Fila de testes de tráfego (a ordem muda com dado)

| id | Hipótese | Métrica | Quando |
|---|---|---|---|
| H-T1 | Purchase desde o dia 1 bate começar em InitiateCheckout (§4) | CPA de compra em 2 semanas | só se a Purchase travar com funil saudável |
| H-T2 | Conceitos diversos (persona × ângulo × formato) batem variações de um conceito | CPA e alcance de pessoas novas | já no lançamento (estrutura) |
| H-T3 | Vídeo UGC falado bate demo sem fala para este público | CPA; hook e hold | no lançamento, se houver os dois formatos |
| H-T4 | Audience Network traz clique barato sem compra | compras e IC por posicionamento | ao dia 3: se AN levar >15% do gasto com 0 IC, excluir AN e registrar |
| H-T5 | Custo-alvo no CPA alvo estabiliza a escala melhor que maior volume | lucro do painel por semana | depois de 30+ compras |
| H-T6 | Remarketing separado é incremental | venda total com e sem (§7) | 4 semanas após abrir |
| H-T7 | Abrir a mesma oferta para o Canadá/Reino Unido barateia o CPA | CPA por país | só depois de lucro estável nos EUA e prazo de entrega confirmado |

Cada teste encerrado vira linha em `operacoes/drop-eua/registro-de-ganhos.csv` e parágrafo em [aprendizados.md](aprendizados.md).

## 10. Calendário e sazonalidade
- CPM sobe do fim de outubro até a Black Friday (27/11/2026) e Cyber Monday (30/11), fica alto em dezembro e cai depois do Natal. **[PRÁTICA: GeistM, Adsverse, Triple Whale]** Mas a Meta mostrou que na Black Friday 2025 a conversão subiu 74% e o CPA caiu 14% contra o início de outubro: CPM alto não é CPA alto. **[META, Cyber 5 2025]**
- **"Q5"** (Cyber Monday ao Ano-Novo): CPM no ponto mais baixo com intenção ainda alta. **[META]**
- **Corte de presente:** com prazo de 7–15 dias da China, a mensagem "chega antes do Natal" só pode rodar enquanto a data garantir; depois troca para "para você/seu pet" ou "presente de Ano-Novo". Data por produto no plano (regra da FTC sobre prazo de envio).
- Comparar sempre com as mesmas semanas do ano anterior, nunca com outubro. **[PRÁTICA]**

## 11. Conta nova: cuidados
- Limite de gasto diário baixo nas primeiras semanas (algo como US$25–50/dia em contas novas); sobe com pagamento em dia, zero anúncio rejeitado e gasto sem buracos de 3+ dias. **[PRÁTICA: AdsX]**
- Antes do 1º real: domínio verificado no Gerenciador de Negócios, 2 fatores em todos os admins, Página e Instagram ligados, forma de pagamento, eventos de teste conferidos (Purchase com event_id único, e-mail/telefone/CEP).
- Não fazer mudança grande de orçamento na primeira semana (aciona revisão automática que pode pausar 24–72 h). **[PRÁTICA: AdsX]**

## 12. Fontes (lidas em 24/11/2026)
- Meta: "Demystifying Creative Diversification" (16/12/2025) https://business.meta.com/news/demystifying-creative-diversification/
- Meta: "Unlocking the Power of Diversification with Meta Andromeda" https://business.meta.com/news/the-creative-advantage-unlocking-the-power-of-diversification-with-meta-andromeda/
- Meta: "Cyber 5 2025: What worked" https://business.meta.com/news/cyber-5-2025-what-worked/
- Meta, comunicado sobre impostos a partir de 01/01/2026: https://www.reformatributaria.com/wp-content/uploads/2025/09/Comunicado-oficial-Meta.pdf ; Folha 12/09/2025 https://www1.folha.uol.com.br/mercado/2025/09/meta-aumenta-preco-dos-anuncios-a-partir-de-2026-com-repasse-de-tributos.shtml
- First Byte, "Meta Ads for eCommerce: A Creative Testing System" (12/11/2026) https://firstbyte.agency/blog/meta-ads-ecommerce-creative-testing-scaling/
- GaaS, "Kill / Hold / Scale rulebook" (15/11/2026) https://trygaas.com/blog/kill-hold-scale-rulebook
- Theory Road, "Meta Ads Learning Phase: The 50-Event Rule" (17/11/2026) https://www.theoryroad.com/insights/meta-ads-learning-phase
- AdAdvisor, "Facebook Ads for Dropshipping: The 2026 Guide" https://adadvisor.ai/blog/facebook-ads-for-dropshipping
- EcomLabs360, "Meta Ads Account Structure 2026" https://ecomlabs360.com/blog/meta-ads-account-structure-dtc-2026
- Jon Loomer, "How I Create Ads Now" (Andromeda) https://www.jonloomer.com/andromeda-7/
- Pigeon Digital, "Reasonable creative diversity after Andromeda" https://www.pigeondigital.com/insight/creative-volume-facebook-ads-andromeda-reasonable-diversity
- Sam Tomlinson, "Creative in the Age of Andromeda" https://digitaldownload.samtomlinson.me/posts/issue-137-creative-in-the-age-of-andromeda-1
- Odylic Media, "New Meta Ad Accounts Need Less Setup and More Patience" https://www.odylicmedia.com/research/meta-new-ad-account-setup
- Scale-ova, "Your First Campaign (2026)" https://scale-ova.ai/blog/meta-ads-first-campaign-tutorial
- AdsX, "Warm Up a New Ad Account" https://www.adsx.com/blog/ad-account-warm-up-new-pixel-spend-ramp
- MHI Growth Engine, retargeting https://mhigrowthengine.com/blog/how-to-set-up-retargeting-meta-ads/
- Shero, "Retargeting for eCommerce" https://sherocommerce.com/blogs/insights/retargeting-ecommerce
- e-commPartners, "Retargeting budget" https://www.e-commpartners.com/retargeting-ads-budget-when-to-stop/
- Scalewin, "Meta Ads Optimization Checklist" https://scalewin.ai/blog/meta-ads-optimization-checklist
- GeistM, Q4 2025 rates https://geistm.com/blog/meta-rate-trends-q4-2025 ; Adsverse, Q4 CPM https://adsverse.io/learn/meta-ads-q4-cpm

**Como manter este manual vivo:** a cada mês (ou quando a Meta anunciar mudança de produto), o agente de tráfego refaz a busca nas mesmas fontes, troca o que mudou e registra no histórico. Número nosso substitui número de praticante assim que tivermos amostra (§5.4).

## Histórico
- v1 (24/11/2026): primeira versão, pedido do Kenui na thread "Gerenciar e escalar campanhas no Meta".
