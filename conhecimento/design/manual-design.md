# Manual de design de página e funil (v1, 24/11/2026)

Pedido do Kenui (24/11): "tem muitos textos em caixa baixa, formatação feia, dá para colocar imagens bem legais de order bump [...] fazer uma página para um order bump, criar uma estrutura bem avançada [...] banco de dados de modelos de design [...] analisar a concorrência [...] pelo que a persona gosta. O comportamento da persona, para cada produto."

Regra: **o visual de cada produto sai de duas provas, nunca do gosto da IA:**
1. o que os concorrentes que já escalam fazem (prints guardados no banco de modelos);
2. o que a persona do produto valoriza e o que a faz sair (dossiê de persona, seção de comportamento e filtros).
O que não tiver prova vira teste A/B (fila de testes do produto).

## 1. Passo a passo por produto (antes de escrever HTML)
1. **Coletar 3 a 5 modelos**: páginas de produto e checkout dos concorrentes ativos na Biblioteca de Anúncios do Meta (os mesmos do dossiê). Print do celular (390 px, página inteira) com a ferramenta de print por URL (HCTI) e guardar em `conhecimento/design/modelos/AAAA-MM-DD_<marca>-<tipo>.jpg`, com uma linha no índice abaixo.
2. **Desmontar cada modelo** em uma tabela: ordem dos blocos, tipo de imagem de cada bloco, seletor de kits (selos, economia, preço por unidade), bump (imagem? posição? texto?), prova (tabela comparativa, passos, linha do tempo de envio), tipografia, paleta, botões, barra fixa.
3. **Cruzar com a persona**: o que ela quer ver (ex.: o pet perto, casa real à noite, dona relaxando), o que a faz sair (ex.: voz de comercial, CGI, pet estressado), objeções em ordem. Cada escolha visual cita a linha da persona ou do modelo.
4. **Escrever o brief visual** do produto em `05-pagina/design.md`: paleta, fontes, lista de imagens com a cena de cada uma, componentes, e o que é teste.
5. **Imagens**: primeiro material real (fornecedor, CJ, vídeo bruto). Gerada por IA só com o produto travado (PRODUCT LOCK das regras de vídeo), sem inventar função, tamanho ou acessório, e sem parecer depoimento de cliente. Gasto de créditos com o aval do Kenui.
6. **Montar, printar e conferir** no celular (390 px) antes de publicar; prints em `05-pagina/prints/`.

## 2. Padrões que os modelos que escalam repetem (base: Pouncy, 24/11)
- Galeria de fotos no topo (10 fotos, miniaturas), foto de ambiente quente à noite, pet chegando perto do produto.
- Título do produto em fonte display arredondada e grossa; subtítulo de 1 linha; preço grande; prazo logo abaixo.
- Seletor de pacotes com rádio, foto, selo "MOST POPULAR"/"BEST VALUE", economia em verde e preço por unidade. **Atenção FTC:** preço riscado só se houve preço anterior real; usar "you save" calculado contra o preço de 1 unidade.
- Linha de selos (frete, checkout seguro, 30 dias) e bandeiras de pagamento perto do botão.
- Blocos alternados: foto grande + título com marca-texto em 1 palavra + parágrafo curto + botão.
- "Ready in three steps" com números em círculo.
- Tabela "nós vs. laser pointer" com check e X.
- Linha do tempo do envio (pedido, empacotado, rastreio, entregue).
- FAQ em acordeão com as objeções da persona.
- Barra fixa com miniatura, nome, preço e botão.

## 3. Regras de acabamento (o que o Kenui reprovou na v2)
- **Title Case** em títulos, botões, nomes de kit e de bump ("Add 3 Spare Catnip Mice"), como no e-commerce americano. Frases de corpo em sentence case.
- Nada de bloco só de texto: cada seção grande tem imagem ou ícone.
- Order bump com **foto do item**, selo ("Most Added" só com dado real; senão "One-Time Add-On"), preço e economia reais, caixa de seleção grande.
- Upsell e downsell como páginas de verdade: foto, título forte, lista curta, botão grande, "No thanks" discreto.
- Ícones em SVG inline (sem banco de imagens pago), fonte do Google Fonts.

## 4. Índice do banco de modelos
| Data | Arquivo | Marca / tipo | O que copiar | O que evitar |
|---|---|---|---|---|
| 24/11/2026 | `modelos/2026-11-24_pouncy-produto-mobile.jpg` | Pouncy, página de produto do projetor (EUR) | galeria, seletor de pacotes com selos e preço por unidade, passos, tabela vs. laser, linha do tempo de envio, fonte arredondada, paleta azul-marinho + coral + menta | preço riscado sem preço anterior provado; "Loved by dogs & cats" sem número |

## Histórico
- v1 (24/11/2026): criado a partir do pedido do Kenui e do desmonte da Pouncy.
- 24/11/2026: primeiro brief aplicado em `operacoes/drop-eua/produtos/projetor-pet/05-pagina/design.md` (página v3).
