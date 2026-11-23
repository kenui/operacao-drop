# Manual de landing page de produto único (v1, 23/11/2026)

Base de conhecimento do agente **Landing page** (`agentes/landing-page/`). Complementa a skill `skill-8-pagina-dropshipping` (estrutura e QA) e o `conhecimento/copy/manual-copy.md` (texto). Onde este manual e a skill discordarem, vale este manual, porque foi feito para a nossa stack: **HTML puro na Hostinger, checkout próprio com a Stripe, sem Shopify.**

Marcas: **[FATO]** (estudo ou documento com fonte), **[DADO DE FORNECEDOR]**, **[OPINIÃO]** (prática de especialista, hipótese para testar).

---

## Parte 1. Landing page ou loja?
**Hipótese do Kenui:** uma landing page de produto único converte mais do que uma loja.

O que se sabe:
- A landing page tem um objetivo só e tira as saídas (menu, outros produtos, busca). Tim Ash, *Landing Page Optimization*: "se você destaca muitos itens, todos perdem importância". [OPINIÃO de especialista, base clássica de CRO] [CXL](https://cxl.com/blog/ecommerce-landing-pages/)
- Ezra Firestone (Smart Marketer, Zipify) defende páginas de oferta e pré-venda separadas da página de produto da loja, com testes próprios. [OPINIÃO, vende a ferramenta]
- Unbounce (benchmark de landing pages): mediana de **4,2%** de conversão em e-commerce, e o celular converte cerca de 18% menos que o computador. [DADO DE FORNECEDOR] [Unbounce](https://unbounce.com/conversion-benchmark-report/ecommerce-conversion-rate/)
- Não há estudo público independente que compare "landing page vs. loja" para o mesmo produto. **Só nosso teste responde.**

**Decisão da casa:** começar com landing page de produto único, porque (1) o tráfego é frio e vem de um anúncio com um ângulo, (2) o checkout fica na própria página, (3) é mais rápida e mais simples de medir. A loja com várias páginas fica para quando houver mais de um produto vencedor. Se um dia quisermos provar, o teste é: mesma oferta, mesmo anúncio, landing vs. página de produto com menu, métrica = lucro por visitante.

## Parte 2. Velocidade é conversão [FATO]
- Estudo Deloitte e Google (*Milliseconds Make Millions*, 2020; 37 marcas, 30 milhões de sessões): melhorar **0,1 s** em quatro métricas de velocidade aumentou em **9,1%** a passagem da página de produto para o carrinho e em **9,2%** o gasto no varejo. [web.dev](https://web.dev/case-studies/milliseconds-make-millions)
- Meta da casa: LCP abaixo de 2,0 s no 4G simulado, página inicial abaixo de 500 KB antes do vídeo, nenhuma imagem acima de 150 KB, vídeo carregado só quando aparecer na tela.
- HTML puro já nos dá vantagem: sem tema, sem apps, sem scripts de terceiros além do pixel, da Stripe e do Clarity.

## Parte 3. Celular primeiro [FATO]
- Baymard 2026: celular gera cerca de três quartos das visitas no varejo, e 75% dos sites são só "medíocres" em UX no celular. [Baymard](https://baymard.com/research-articles/mobile-ux-ecommerce)
- Abandono no checkout (Baymard 2026): 40% por custo extra, 20% entrega lenta, 19% desconfiança com o cartão, 18% exigência de conta, 17% checkout longo, 12% não ver o total antes. [Baymard](https://baymard.com/lists/cart-abandonment-rate)
- Consequências obrigatórias: preço final e frete visíveis na primeira tela; nada de criar conta; Apple Pay e Google Pay em cima; campos mínimos; prazo real perto do botão.

## Parte 4. Estrutura padrão (390 px de largura)
Ordem de partida. Cada bloco tem um **ID fixo** para medir e trocar sozinho (os mesmos IDs do agente de copy):

| # | ID | O que mostra | Pergunta que responde |
|---|---|---|---|
| 1 | `hero` | vídeo curto do produto em uso (sem som, com legenda), título do ângulo, preço, kits, botão | o que é, quanto custa |
| 2 | `problema` | 2 ou 3 frases nas palavras do comprador (troca por `?a=`) | isso é pra mim? |
| 3 | `demonstracao` | GIF ou vídeo de 5 a 15 s com escala real (mão, caneca) | funciona mesmo? |
| 4 | `mecanismo` | 3 passos visuais, o detalhe verificável | por que funciona? |
| 5 | `prova` | só prova real: demonstração, especificação, avaliação verdadeira; se não houver, o bloco some | outras pessoas confiam? |
| 6 | `oferta-kits` | kits 1/2/3 com preço unitário caindo e lógica de uso | qual levo? |
| 7 | `checkout` | formulário da Stripe na própria página (ver desenho do checkout), com bump | como pago? |
| 8 | `garantia` | o que cobre, como pedir, em quanto tempo o dinheiro volta | e se não gostar? |
| 9 | `limite` | 2 limites honestos ("not for…", tamanho real) | não vou me arrepender? |
| 10 | `objecoes` | FAQ, uma pergunta por objeção do mapa | e as dúvidas? |
| 11 | `envio` | prazo real, origem, rastreio | chega quando? |
| 12 | `cta-final` | botão + resumo da oferta | ok, vou comprar |
| — | `barra-fixa` | preço e botão fixos no rodapé depois que o `hero` sai da tela | — |
| — | `rodape` | nome legal, endereço, contato, políticas (privacy com seção da Califórnia, returns, shipping, terms) | é loja de verdade? |

**Checkout na página, não em outra:** o botão rola até o bloco `checkout` (ou abre uma folha deslizante na mesma página). Menos um carregamento = menos abandono. [OPINIÃO, testar contra página de checkout separada]

## Parte 5. Uma página, vários ângulos
Princípio 1 de `conhecimento/copy/PRINCIPIOS.md`: o parâmetro `?a=` troca `hero` e `problema` para o ângulo do anúncio. Um só arquivo HTML, textos em um objeto JSON no próprio arquivo, troca feita antes de pintar a tela (sem piscar). O ângulo vai em todo evento medido.

## Parte 6. Referências para estudar (estrutura, nunca copiar texto ou imagem)
Procurar na Biblioteca de Anúncios do Meta páginas de produto único que estão no ar há mais de 30 dias com várias variações de anúncio (sinal de que dão lucro). Registrar em `conhecimento/landing-page/referencias/AAAA-MM-DD_<nome>.md`: link, print da primeira tela no celular, ordem dos blocos, oferta e kits, onde fica o botão, velocidade (PageSpeed). Clássicos para estudar a estrutura de demonstração: Purple (teste do ovo, Harmon Brothers), Squatty Potty, BluBlocker (manual de copy, Parte 2).

## Parte 7. O que a página nunca faz
Avaliação, número de vendas, "de/por", contador ou estoque que não sejam reais; imagem de IA que muda tamanho ou função do produto; selo de pagamento que não existe; pop-up que cobre o botão no celular; som automático.

## Histórico
- v1 (2026-11-23): criação.
