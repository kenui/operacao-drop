# Projetor pet: plano de campanha no Meta (v1, 24/11/2026)

Feito a partir do [manual de tráfego](../../../../conhecimento/trafego/manual-meta-ads.md), do [funil](07-funil.md), da [persona](03-persona.md) e dos [vídeos que vendem](08-videos-que-vendem.md). Nada ativado; nenhum gasto. Ativar e o valor dependem do aval do Kenui.

## 0. Portão (24/11)
| Item | Estado |
|---|---|
| Stripe em produção | ✅ live desde 24/11; o Kenui dispensou a compra de teste, então a 1ª venda real prova o checkout |
| Produto do anúncio = fornecedor | ❌ vídeo aprovado mostra coelho; a CJ cotou a versão com esquilo. Os vídeos V1–V4 precisam mostrar a projeção que a CJ entrega |
| Prazo na página | ✅ 7–15 dias (config.js) |
| Pixel + API de Conversões | ✅ ligados (pixel 545543757913721, event_id deduplicado); conferir eventos de teste com a conta ligada |
| Acesso da IA à conta | ✅ conector Higgsfield (24/11), conta ADS act_1528246354605953 (BRL, fuso Los Angeles) |
| Domínio verificado / Página / Instagram | ✅ Página Little Catch Co. (1591074544081441); ⏳ Instagram e verificação de domínio |
| Criativos V1, V2, V4 + controle | ⏳ thread "Criar vídeos que vendem o projetor pet" |
| Conformidade dos textos | ⏳ passar a skill antes de subir |
| Valor aprovado | ✅ **R$850 por 7 dias (US$20/dia, opção B)**, escolhido pelo Kenui em 24/11. Ativa só quando o resto do portão fechar; spend cap da campanha = esse valor |

## 1. Contas (preços e custos do config em 24/11)
Hipóteses do funil: 80% kit 1 (US$49,99), 20% kit 2 (US$89,99); bump US$9,99 em 10%; upsell US$39,99 em 5%; downsell US$7,99 em 3% de quem vê; reembolso 5%. Custo entregue CJ: kit US$18,77, pelúcias extras US$4,50 (`api/_precos.php`). Stripe BR ~6% + US$0,10 por cobrança.

| Item | Valor |
|---|---|
| Receita média por comprador | US$61,19 |
| Contribuição por comprador (antes do anúncio) | US$30,33 |
| **CPA de empate no Gerenciador** (÷ 1,1383, imposto da Meta no Brasil) | **US$26,64** |
| Cenário pessimista (só kit 1, sem extras, reembolso 8%) | US$21,19 |
| **CPA alvo** (deixa ~US$8 de lucro por pedido e cobre o pessimista) | **US$18** |
| ROAS de empate no Gerenciador | ~2,3 |

Porquê do alvo: US$18 fica abaixo do empate pessimista (US$21), então mesmo se ninguém pegar bump/upsell e o reembolso for alto, a escala não dá prejuízo. **[HIPÓTESE]** até termos 30+ compras; aí o alvo é recalculado com o ticket real do painel.

## 2. Estrutura do lançamento
| Nível | Configuração | Porquê (manual) |
|---|---|---|
| Campanha | `projetor_lanc_vendas_AAAAMMDD`, objetivo Vendas, orçamento da campanha diário, **spend cap = valor aprovado** | §3.1 consolidar; spend cap é a trava de gasto |
| Conjunto | `broad_us`: EUA, 18+, Advantage+ audience com sugestão 25–54; posicionamentos Advantage+; evento **Purchase**; lance maior volume; atribuição padrão | §3.2 e §4; persona C tem 25–44 no centro (persona §1) |
| Anúncios | 4 conceitos (§3), cada um com texto e título próprios | §5.2: 3–5 conceitos distintos |
| Destino | `https://littlecatchco.com/?pet=dog&a=<angulo>&utm_source=meta&utm_medium=paid&utm_campaign={{campaign.id}}&utm_term={{adset.id}}&utm_content={{ad.id}}` | §8.2; painel casa por ID |

Sem remarketing separado nesta fase (§7): o e-mail de checkout abandonado faz esse papel até o público "checkout 14 dias" passar de 1.000 pessoas.

**NO AR desde 25/11/2026 01h23 UTC (17h23 em Los Angeles)** como `projetor_lanc_vendas_20261124`. A regra automática de corte duro `3287020294806426` pausa o anúncio que gastar mais de R$216 (≈US$40) sem compra, checada a cada 30 min. Antes de ligar: campanha `120250938066740381` (Vendas, R$106,70/dia, spend cap R$746,70 de mídia = R$850 com imposto) e conjunto `120250938076520381` `broad_us`. Renomear com a data no dia de ligar.

Anúncios criados em 24/11 (pausados, em revisão do Meta), Página Little Catch Co., UTM por ID em `url_tags`, domínio de conversão littlecatchco.com:

| Anúncio | ID do anúncio | Criativo | Vídeo na biblioteca |
|---|---|---|---|
| chuva-donac-ugc-v1 | 120250938575320381 | 932435983167079 | 1144294901425680 |
| chuva-donac-ugc-v1-musica | 120250938576070381 | 1491709979581016 | 1481621200558764 |
| fake-cetica-demo-v1 | 120250938576430381 | 1122867140742983 | 4678334099102253 |
| fake-cetica-demo-v1-musica | 120250938577520381 | 1828117665123830 | 1142239911872619 |
| aprovado-controle-v1 | 120250938578390381 | 1777757563686595 | 1612303223984909 |
| aprovado-controle-v1-musica | 120250938579440381 | 1116543557885881 | 1714682023401935 |

Com e sem música dentro do mesmo conjunto: a régua julga cada versão; se uma das duas perder com folga depois do dia 3, pausar a pior e anotar em `aprendizados.md` (teste de música, H-T música).

## 3. Criativos
Só cachorro (persona C), porque só a versão cachorro tem fornecedor.

| Anúncio | Ângulo | Quem aparece / para quem | Formato | Consciência | Hipótese |
|---|---|---|---|---|---|
| `chuva-donac-ugc-v1` (V1) | dia de chuva, sem passeio | dona em casa, cachorro inquieto | UGC falado | sabe da dor | a dor que o mercado já valida (74 anúncios) traz o CPA mais baixo no início |
| `cansada-donac-ugc-v1` (V2) | ela chega acabada | dona que trabalha fora, culpa | UGC falado | sabe da dor | a dor do esforço, que ninguém usa, rende CTR mais alto que V1 |
| `fake-cetica-demo-v1` (V4) | "achei que era fake" | quem já viu o produto em outro anúncio | demo em sala normal: desligado, clique, luz | já viu o produto | prova crua baixa o custo por início de checkout para quem desconfia |
| `aprovado-controle-v1` | vídeo aprovado (sem dor) | — | demo | — | controle: mede quanto a dor acrescenta |

**Modelagem (regra do Kenui, 24/11; manual §5.0):** sempre olhar o que já escala antes de fechar os conceitos, sem obrigar todos a serem modelados. O projetor já tem ~140 anúncios de 46 lojas e o público americano começa a conhecer o produto, então o peso da modelagem é **alto**. Mesmo assim fica um conceito saído da persona para provar o nosso diferencial. Situação hoje:
- V1 (chuva) modela a dor mais usada no mercado (74 anúncios de "rainy days/bored" na Biblioteca em 24/11). Falta escolher o anúncio específico que está há 14+ dias com várias versões e decupá-lo.
- O vídeo aprovado segue a referência da Enjoyaitlife que o Kenui escolheu ([estudo/referencia-estilo.md](estudo/referencia-estilo.md)).
- V4 ("achei que era fake") modela o formato de demo crua que os anúncios mais antigos usam. Falta a referência específica.
- V2 (ela chega acabada) **vem da persona**: a dor do esforço, que nenhum anúncio usa hoje.
Antes do lançamento: buscar na Biblioteca (EUA, "pet projector", "dog projector toy", "interactive projector") e no TikTok os anúncios com 14+ dias e várias versões e os vídeos orgânicos com mais views. Guardar as fichas em `estudo/referencias-anuncios.md`. Se aparecer uma referência mais forte que V1 ou V4, ela entra no lugar. Nome dos anúncios com `ref<id>`.

V3 (brinquedo de 10 minutos) entra quando tiver foto real da pelúcia. Todo vídeo precisa mostrar a projeção da versão que a CJ entrega (esquilo), com o aparelho sendo ligado antes de qualquer efeito (regra do Kenui).

### 3b. Textos dos anúncios (prontos para subir; 24/11)
Vídeos com esquilo em `videos/` (thread de vídeos, 24/11). Sobem as versões sem e com música como anúncios separados, até o Kenui escolher. Todos levam o rótulo de IA do Meta (pessoa gerada por IA). Título comum: **"The Hunt Ends With a Real Catch"**. Botão: **Shop Now**.

| Anúncio | Vídeo | Texto principal | Destino `?a=` |
|---|---|---|---|
| `chuva-donac-ugc-v1` | v1-chuva/V1-chuva-*.mp4 | No walk today and he's been staring at me since 4. This little projector puts critters on the floor for him to chase, and the kit comes with a squeaky plush so the hunt ends with something he can actually catch. Works best with the lights low. Free shipping, 30-day refund. | `sozinho` |
| `fake-cetica-demo-v1` | v4-fake/V4-fake-*.mp4 | I thought these videos were fake too. So here it is in a normal living room: off, click, on. If your dog just stares at it, email us within 30 days of delivery for a full refund. | `sozinho` |
| `aprovado-controle-v1` | prova-esquilo/Controle-esquilo-*.mp4 | If your dog gets bored inside, watch this. Projector + squeaky plush + gift box. Free shipping, 30-day refund. | `pega` |

Conferido com a página em 24/11 (a página diz que "usually" não precisa devolver, então o anúncio não promete "no return needed"): "30-day money-back", "no reason needed", "free shipping" e "works best with the lights low" estão lá. Não prometemos prazo de Natal no anúncio (a página mostra o prazo real). Se a política de reembolso mudar, os textos mudam junto.

## 4. Régua com os números deste produto (E = US$26,64; A = US$18)
| Situação | Regra | Ação |
|---|---|---|
| Esperar | anúncio com < US$36 gasto ou < 3 dias | não mexer |
| Corte duro | **US$40 gastos sem compra** (1,5 × E) | pausar o anúncio (regra automática na Meta) |
| Corte | CPA > US$26,64 depois de US$36–54 e 3 dias | pausar |
| Segurar | US$18 < CPA ≤ US$26,64 | sem mexer no orçamento; variação de gancho como anúncio novo |
| Escalar | CPA ≤ US$18 com 10+ compras e lucro do painel positivo | +20% de orçamento, esperar 3 dias |
| Fadiga | frequência 7 d ≥ 3,5 e CTR −30% | criativo novo, não escalar |

**Diagnóstico pelo funil (antes de culpar o criativo):** CTR de saída abaixo de ~1% → gancho; CTR bom e visualização da página baixa → velocidade da página; visualização boa e quase nenhum início de checkout → preço/oferta/página; checkout iniciado sem compra → checkout ou confiança. Referências de partida **[HIPÓTESE]** até termos histórico: CPM US$12–25 nos EUA no fim do ano, CTR de saída 1–2%, conversão da página 2–3% (o funil precisa de ~2,5% com clique a US$0,50).

## 5. Verba e marcos (para o Kenui decidir)
Câmbio de referência R$5,40 por dólar **[HIPÓTESE]**, imposto da Meta incluído no valor em reais.

| Opção | Total no cartão | Vira anúncio | Ritmo | O que compra |
|---|---|---|---|---|
| A. Sinal | R$500 | ~US$81 | US$15/dia por ~5 dias | 2 a 4 compras se o CPA ficar entre US$20 e 30; pode ser zero. Responde "o anúncio gera clique e checkout?" e julga 1 a 2 conceitos, não 4 |
| **B. Teste de verdade (recomendado)** | **R$850** | **~US$138** | **US$20/dia por 7 dias** | julga 3 conceitos a 2,5 × A cada (manual §5.2) e cobre a semana inteira de aprendizado; 5 a 7 compras esperadas no cenário base |

**Por que B:** com R$500, cada conceito recebe menos que o mínimo para ser julgado (US$45 por conceito), então o dinheiro compra mais ruído do que resposta. 7 dias também evita julgar só dias de semana. Os dois cabem no limite diário de conta nova (US$25–50).

**Marcos e o que cada resultado faz:**
- **Dia 1:** conferir entrega, aprovação dos anúncios e Purchase chegando no Gerenciador e no painel (compra real do Kenui reembolsada antes de ligar, se possível).
- **Dia 3:** ler hook rate, CTR de saída, custo por início de checkout por anúncio; aplicar corte duro; checar Audience Network (H-T4).
- **Dia 7:** veredito por conceito pela régua. Três saídas:
  1. CPA ≤ US$26,64 com 3+ compras → pedir ao Kenui verba da fase 2 (lucro reinvestido + extra), subir +20% a cada 3 dias, pedir mais criativos do conceito vencedor.
  2. Cliques e checkouts bons, poucas compras → problema depois do clique: testes da página (T2, T7 da [fila](testes/fila.md)), não mais anúncio.
  3. CTR fraco em tudo → criativo: novos ganchos/formatos pela thread de vídeos antes de gastar mais.

## 6. Calendário
- Black Friday 27/11, Cyber Monday 30/11: o portão ainda não fecha até lá; não forçar lançamento na semana mais disputada com conta nova.
- **Corte de presente:** prazo de até 15 dias → mensagem "chega antes do Natal" só para pedidos até **8/12** **[HIPÓTESE, confirmar com a CJ]**; depois trocar para "for your dog" e "New Year". Preencher `xmasCutoff` no config.js junto.
- 26/12 a 31/12 ("Q5"): CPM no menor nível com intenção alta; bom momento para escalar o que estiver no alvo.

## 7. Depois
- 30+ compras: recalcular CPA alvo com o ticket real; testar custo-alvo (H-T5).
- Conceito vencedor estável por 7 dias: abrir campanha de teste separada (fase de tração) e passar o vencedor para Vendas Advantage+ com o mesmo ID de publicação.
- Gato volta quando houver fornecedor: conjunto próprio só se a verba alimentar dois (manual §1.6); senão, anúncios de gato no mesmo conjunto.

## Histórico
- v1.1 (24/11/2026): Kenui aprovou a opção B (R$850, 7 dias).
- v1 (24/11/2026): primeiro plano, thread "Gerenciar e escalar campanhas no Meta".
