# Manual de funil e oferta (v1, 23/11/2026)

Base de conhecimento do agente **Funil e oferta** (`agentes/funil-e-oferta/`). Lido inteiro antes de desenhar qualquer funil.

Régua única: **lucro por visitante** (e, somado, lucro líquido por dia). Bump, upsell e pacote só existem se aumentarem esse número sem subir reembolso e contestação.

Cada afirmação deste manual vem marcada:
- **[FATO]**: estudo, experimento ou documento oficial, com fonte.
- **[DADO DE FORNECEDOR]**: número publicado por quem vende a ferramenta. Útil como ordem de grandeza, não como verdade.
- **[OPINIÃO]**: prática de especialista ou guru, sem dado público. Vale como hipótese para testar.

---

## Parte 1. O que é fato e o que é opinião

| Afirmação comum | O que se sabe de verdade | Marca |
|---|---|---|
| "35% das vendas da Amazon vêm de recomendações" | Número de um relatório da McKinsey de 2013, sem método público. Experimento controlado com quase 1 milhão de sessões (Kumar e Hosanagar, 2018) achou **+11% nas vendas totais**, com queda de 1,9% no produto principal. Oferta complementar ajuda, mas muito menos do que dizem. | [FATO] [UF News](https://news.ufl.edu/articles/2018/09/how-helpful-are-product-recommendations-really.php) |
| "Order bump converte 30% a 40%" | Não há estudo público sério. Relatórios que circulam (ex.: Focus Digital, 2026, "37,8%") não mostram dados brutos. | [DADO DE FORNECEDOR, baixa confiança] [link](https://focus-digital.co/average-upsell-conversion-rate-2026-report/) |
| "Upsell de um clique pós-compra converte muito" | ReConvert, com dados de 40 mil lojas Shopify em 2023: média de **4,7%** de aceite no upsell de um clique, os melhores em 28,3%; ganho médio de **5,6% no ticket**. | [DADO DE FORNECEDOR] [ReConvert](https://reconvert.io/blog/the-impact-of-post-purchase-upselling-2023) |
| "Upsells aumentam as vendas em 30%" | Frase de venda do Zipify (Ezra Firestone). Os testes dele são reais, mas seletivos e vendem o app. | [OPINIÃO] [Zipify](https://landing.zipify.com/pages/zipify-24-split-tests-from-a-9-figure-shopify-store-funnel-typ) |
| "Preço terminado em 9 vende mais" | Experimento de campo em catálogo de roupa: a versão a US$39 vendeu mais que a US$34 e que a US$44. O efeito cai quando a loja já usa muitos sinais de promoção. | [FATO] Anderson e Simester, 2003, [Quantitative Marketing and Economics](https://ideas.repec.org/a/kap/qmktec/v1y2003i1p93-110.html); [Chicago Booth Review](https://www.chicagobooth.edu/review/yours-only-1999) |
| "Opção isca (decoy) faz escolher o pacote do meio" | Demonstrado por Ariely (assinatura da Economist, em sala de aula). Réplicas posteriores acharam efeito menor e instável. | [FATO com ressalva] testar, não supor |
| "Opções demais travam a compra" | O estudo das geleias (Iyengar e Lepper, 2000) é real, mas a meta-análise de Scheibehenne et al. (2010) achou efeito médio perto de zero. Para nós: 3 kits é convenção, não lei. | [FATO com ressalva] |
| "Custos extras matam o checkout" | Baymard 2026: dos que abandonam (fora os que só olhavam), **40%** saem por custo extra (frete, taxa), 20% por entrega lenta, 19% por não confiar o cartão, 18% por exigir conta, 17% por checkout longo, 12% por não ver o total antes. | [FATO] [Baymard](https://baymard.com/lists/cart-abandonment-rate) |
| "Checkout ideal tem poucos campos" | Baymard: o fluxo ideal tem 12 a 14 elementos (7 a 8 campos); a média nos EUA mostra 23,5. | [FATO] mesma fonte |
| "Escada de valor: front, bump, upsell, backend" | Russell Brunson (ClickFunnels). Estrutura útil e muito copiada, sem dado público de ganho. | [OPINIÃO] *DotCom Secrets* |
| "Quem pode gastar mais para adquirir um cliente vence" | Dan Kennedy. Lógica correta: ticket maior = CPA de empate maior = mais espaço no leilão do Meta. | [OPINIÃO, lógica sólida] |
| "Oferta irresistível: empilhar bônus que resolvem cada obstáculo" | Alex Hormozi, *$100M Offers*. Bom método de desenho; números do livro são dele. | [OPINIÃO] |
| "Tripwire: produto barato que transforma visitante em comprador" | Termo de Perry Belcher e Ryan Deiss. Funciona em infoproduto; em drop, o front já é o tripwire. | [OPINIÃO] |
| "Medir margem de contribuição, não ROAS" | Taylor Holiday (Common Thread Collective): ROAS esconde custo de produto, frete, reembolso. Opinião de operador com dados próprios. | [OPINIÃO, alinhada com nossa régua] |
| "Teste A/B confiável exige amostra, uma métrica-mestre e não espiar" | Kohavi, Tang e Xu, *Trustworthy Online Controlled Experiments* (2020), baseado em Microsoft, Amazon e LinkedIn. | [FATO] |

**Conclusão honesta:** quase tudo sobre bump e upsell vem de quem vende ferramenta ou curso. O que sabemos com certeza: oferta complementar aumenta o ticket um pouco (ordem de 5% a 15%), checkout curto e sem surpresa de custo converte mais, e só o nosso teste diz quanto vale para o nosso produto. Por isso este manual define **hipóteses conservadoras** e o agente as substitui por dados reais assim que existirem.

---

## Parte 2. O funil padrão da casa (produto físico, EUA, checkout próprio)

```
Anúncio (ângulo A) → Landing page (?a=A) → Kits 1/2/3 → Checkout na mesma página (+ bump)
  → Upsell 1 de um clique → (Downsell se recusou) → Obrigado → E-mails pós-compra → Backend
```

| Etapa | Objetivo | Regra |
|---|---|---|
| Kits por quantidade | aumentar unidades por pedido | 2 ou 3 níveis com lógica de uso ("one for you, one to gift"). Preço unitário cai a cada nível. O kit do meio é o "Most popular" só se for verdade (depois de dados). |
| Order bump (caixinha no checkout) | ticket sem atrito | uma caixinha só, **desmarcada**, 15% a 35% do preço do front, decisão de 2 segundos, ligada ao produto. |
| Upsell 1 (página depois do pagamento) | próximo passo natural | um clique, sem digitar cartão de novo. Botão "No thanks" claro. Mesmo fornecedor e mesmo envio, ou digital. |
| Downsell | não perder quem disse não | versão menor ou mais barata do upsell, ou outra objeção. Uma só. |
| Obrigado | confiança e menos contestação | o que acontece agora, prazo real, como falar com a gente, como pedir reembolso. |
| E-mails pós-compra | menos "cadê meu pedido" e menos chargeback | confirmação na hora, envio com rastreio, dica de uso, pedido de avaliação real depois da entrega. |

**Operação:** o pedido ao fornecedor só é feito quando o funil termina (upsell aceito ou recusado, ou 15 minutos depois do pagamento), para que tudo vá numa caixa só.

### Catálogo de ofertas extras para produto físico (escolher pela persona)
Ordem de preferência: o que resolve uma objeção ou dor real que o produto principal deixa aberta.
1. **Mais uma unidade com desconto** ("Add a second one for 30% off"): o upsell mais simples; serve para presente. [OPINIÃO de mercado, muito usado em drop]
2. **Acessório que completa o uso** (pilha, capa, refil, suporte), do mesmo fornecedor.
3. **Embrulho e cartão de presente** na temporada de presentes (Q4, Mother's Day, Father's Day, Valentine's Day), só se o fornecedor faz de verdade.
4. **Processamento prioritário** só se existir de fato um ganho de prazo; nunca vender "prioridade" que não muda nada.
5. **Garantia estendida ou troca sem pergunta** se a margem cobre o custo das trocas.
6. **Produto digital útil** (Parte 3).
7. **Proteção de pacote** (comum nos EUA): só desmarcada, preço claro, e só se a gente realmente reenviar em caso de perda.

**Proibido:** caixinha pré-marcada, assinatura escondida, upsell que repete o que já foi comprado, "só agora" quando a oferta volta amanhã.

---

## Parte 3. Produto digital junto do físico
Regra do Kenui: precisa ser real e útil, não enchimento.

**Teste antes de criar:** a persona pagaria US$7 a 17 por isso sozinho? Ele responde a uma dúvida ou frustração que aparece nas avaliações de 1 a 3 estrelas ou nos fóruns? Se não, não entra.

Formatos que costumam ser úteis:
- **Guia de uso e solução de problemas** que o manual do fornecedor não tem (em inglês nativo, com fotos ou vídeos da ficha de uso).
- **Plano prático** ligado ao resultado: 30 dias de rotina, receitas, cronograma, lista de montagem.
- **Material imprimível** (atividades para crianças, etiquetas, calendário) quando o público é família.
- **Mini-curso em vídeo curto** quando o produto tem curva de aprendizado.

Como fazer: o agente escreve a partir do dossiê (dúvidas reais), passa pela `voz-humana` e pela Conformidade, gera PDF leve. Entrega por link no e-mail de confirmação e na página de obrigado (sem área de membros nesta fase).
Cuidado: nada de saúde, renda ou promessa de resultado. Venda única, **nunca assinatura** nesta fase (assinatura cai na ROSCA e nas leis estaduais de renovação automática; ver `conhecimento/checkout/desenho-checkout-stripe.md`).

---

## Parte 4. Persona e cultura americana (o que muda a oferta)
Pontos de partida, todos [OPINIÃO de mercado] até nosso teste confirmar, exceto onde marcado:
- **Custo total sem surpresa** [FATO, Baymard]: frete grátis embutido no preço costuma ganhar de frete cobrado no fim. O imposto sobre vendas aparece no checkout, não na página.
- **Prazo** [FATO, Baymard: 20% abandona por entrega lenta]: estoque nos EUA converte mais; prazo longo vai escrito na página.
- **Devolução**: o comprador americano espera 30 dias. Política escrita e cumprida.
- **Presentes e datas**: Thanksgiving, Black Friday, Cyber Monday, Christmas (Q4), Valentine's Day, Mother's Day, Father's Day, volta às aulas, Halloween. O ângulo "gift" muda o kit (2 unidades) e o upsell (embrulho).
- **Pagamento**: Apple Pay e Google Pay no celular reduzem campos; Link da Stripe preenche cartão salvo. Parcelamento (Klarna, Afterpay) só se a conta da Stripe oferecer.
- **Linguagem**: "Buy 2, get 1 free" e "Save 20%" são lidos na hora; "% off" funciona melhor que preço riscado quando não há preço anterior real (e "de/por" falso é proibido).
- **Confiança**: endereço, contato e política visíveis; e-mail rápido. O americano abre disputa no cartão antes de mandar um segundo e-mail.
- **Persona manda mais que cultura**: a mesma oferta muda para mãe de criança pequena, avô que compra presente, homem que compra ferramenta. Cada persona da matriz (`conhecimento/copy/PRINCIPIOS.md`) recebe a pergunta: "o que essa pessoa ainda precisa depois de comprar?". A resposta é o upsell.

---

## Parte 5. A conta do funil
Igual ao portão de viabilidade, estendida:
```
Ticket bruto por comprador = preço do kit escolhido (média)
  + taxa de bump × preço do bump
  + taxa de upsell × preço do upsell
  + taxa de downsell × preço do downsell × (1 − taxa de upsell)
Custo variável por comprador = produto(s) entregue(s) + taxas da Stripe + reserva de reembolso + custo de contestação esperado
Lucro antes do anúncio por comprador = ticket bruto − custo variável  (= CPA de empate)
Lucro por visitante = conversão × lucro antes do anúncio − custo do anúncio por visitante
```
**Taxas da Stripe: confirmar o país da conta.** Conta dos EUA: 2,9% + US$0,30 (+1,5% em cartão de fora). **Conta do Brasil: 3,99% + R$0,39 + 2% por cartão internacional** (todo cartão americano é internacional para ela), mais o câmbio para reais no repasse ([Stripe Brasil](https://stripe.com/en-br/pricing)). Ou seja, perto de **6% + câmbio**, não 3%.

Hipóteses conservadoras de partida (trocar por dado real assim que houver 50 compradores):
| Item | Hipótese inicial |
|---|---|
| Bump | 10% de aceite |
| Upsell 1 | 5% de aceite (ReConvert: 4,7% médio) |
| Downsell | 3% de quem recusou |
| Reembolso | 5% (10% se frágil) |
| Contestação | 0,5% dos pedidos, custo = venda + US$15 de taxa da Stripe |

Sempre mostrar também o **cenário pessimista** (taxas 30% piores).

---

## Parte 6. Isolamento e honestidade (herdado da skill-6)
- Um domínio, um pixel, uma lista por produto quando os nichos forem diferentes.
- Upsell recusável com um clique; nada pré-marcado; nada de assinatura sem consentimento expresso.
- Escassez e urgência só verdadeiras. Avaliação só real (regra da FTC de 2024, multa civil por avaliação falsa: [FTC](https://www.ftc.gov/news-events/news/press-releases/2024/08/federal-trade-commission-announces-final-rule-banning-fake-reviews-testimonials)).

---

## Parte 7. Produto digital: só com valor percebido comprovado (regra do Kenui, 24/11/2026)
Palavras dele: "só vamos usar produto digital se vale a pena, se ele vai ter um valor percebido maior para aquele público… a gente não faz nada porque acha."

**Os três papéis possíveis, do mais barato de provar ao mais caro:**
| Papel | Quando entra | Prova exigida |
|---|---|---|
| **Bônus grátis** (vai junto da compra) | quando responde a uma objeção da persona que vira reembolso ou contestação ("vai ignorar", "não sei usar", "vai frustrar") | a objeção existe no dossiê/persona com fonte. Custo zero. Mede-se pelo reembolso e pelo download. |
| **Downsell/upsell digital pago** | quando a persona **paga** por informação parecida e não acha grátis com facilidade | evidência de compra (cursos, e-books vendidos para o mesmo público, reclamação de "não achei em lugar nenhum"). Sem isso: entra só como **braço B de um teste**, contra uma oferta física. |
| **Bump digital** | raramente em drop físico: o bump precisa de decisão de 2 segundos e valor óbvio | idem acima, e ganhar do bump físico em teste |

**Teste do valor percebido (responder por escrito no `07-funil.md`):**
1. Qual frase da persona pede isso? (literal, com fonte)
2. A informação é fácil de achar grátis? (se a persona já busca e acha páginas de veterinário/blog, o valor percebido de pagar é baixo)
3. A persona já compra coisa parecida? (sinal de valor)
4. Entregar custa quanto (escrever, revisar, manter)? Só escrever o PDF pago quando o teste dele chegar na fila.
5. Risco: nada de saúde, renda ou resultado garantido; nunca assinatura.

**Entrega:** link assinado no e-mail de confirmação e na página de obrigado, arquivo fora da pasta pública (ver `site/api/download.php` do projetor pet). Sem área de membros.

**Primeiro caso (projetor pet, 24/11):** bônus grátis aprovado (objeções nº 2 e 4); PDF pago reprovado como padrão (conteúdo grátis abundante em AAHA, PetMD, AARP) e colocado como braço B do teste T6.

## Parte 8. Protocolo de teste contínuo (teste A/B de tudo, um por vez, inclusive preço)
1. **Sempre há um teste rodando** quando há tráfego. Um por vez, para saber o que causou o ganho.
2. **Motor:** sorteio fixo por visitante (hash do id do visitante + id do teste), a mesma pessoa vê sempre a mesma versão; a versão vai em todo evento e em cada pedido; preço de teste mora no servidor, que é quem cobra (nunca confiar no preço que vem do navegador).
3. **Fila ordenada** por ganho esperado em lucro por visitante ÷ custo de fazer, em `produtos/<produto>/testes/fila.md`. Cada linha: id, o que muda, hipótese com a fonte, métrica de decisão, amostra, quando roda.
4. **Régua:** 7 dias e 300 visitantes por versão no mínimo; bayesiana ≥ 90%; guarda-corpos (reembolso, contestação, reclamação, velocidade) anulam vitória; confirmação 2 semanas depois.
5. **Teste de preço:**
   - Decide por **lucro por visitante**, nunca por conversão.
   - Antes de ligar, calcular quanto a conversão pode cair e ainda empatar (ex.: projetor pet, US$34,99 → 39,99 empata com queda de até 18%).
   - Só roda com volume (regra de bolso: ~150 compras por versão para ver diferença de ~20%). Antes disso, testar página e oferta com métrica de topo.
   - Sem "de/por" falso: o preço B é o preço daquela versão, não um desconto inventado.
   - Sorteio fixo por visitante para a mesma pessoa não ver dois preços.
6. **Registro:** `registro-de-ganhos.csv`, `aprendizados.md`; vencedor confirmado vira padrão no `JEITO-CERTO.md` com a prova.

## Histórico
- v1 (2026-11-23): criação, com pesquisa e marcação fato/opinião. Próxima versão: números reais do primeiro produto.
- v2 (2026-11-24): Partes 7 (produto digital só com valor percebido comprovado) e 8 (protocolo de teste contínuo, inclusive preço), a pedido do Kenui.
