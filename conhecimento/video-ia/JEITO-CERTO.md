# Jeito certo: vídeo UGC realista com IA

Versão atual: v0 (23/11/2026) = método do Kenui de 16/11/2026 no Higgsfield. Provisória: qualidade visual aprovada pelo Kenui, sem métrica de anúncio ainda.
Guia completo: https://claude.ai/artifact/BLavwj65DLQWSpSJKHZjrV
Montagem do anúncio (retenção, credibilidade, viralidade, música): [retencao-viralidade-credibilidade.md](retencao-viralidade-credibilidade.md)
Regras de consistência (pessoa, animal, produto) e portões antes e depois de gerar: [regras-consistencia.md](regras-consistencia.md). Ler antes de qualquer geração.

## Histórico
| Versão | Data | O que mudou | Prova (ficha) |
| --- | --- | --- | --- |
| v0 | 2026-11-23 | Método do Kenui (16/11): linguagem de celular/documentário, sem linguagem de cinema | Histórico Higgsfield 12/11 vs 16/11 (Kling 3.0, Nano Banana 2) |

## Regra de conteúdo (Kenui, 23/11)
A pessoa gerada aparece testando o produto como alguém real testaria, ao vivo, com reação natural (demonstração estilo UGC). Não se passa por cliente: sem "comprei", "verified buyer", resultado de longo prazo ou número inventado. O efeito mostrado tem que ser o que o produto faz de verdade, copiado de filmagem real do produto; sem prova do efeito, mostra só o uso.

## Regra de promoção
Uma mudança por tentativa. Nota interna >= média da versão atual para subir. Vira jeito certo quando ganha da campeã no mesmo produto e corpo de vídeo em taxa de gancho (3 s) e custo por compra, com 1.000 a 2.000 impressões e 7 dias. Versão antiga nunca é apagada.

## Configuração campeã (v0)

### Imagem: Nano Banana 2, 2k
Estrutura do prompt, nesta ordem:
1. Aparelho: "A real phone photo, auto HDR off." (noite: "night mode off, visible sensor noise"; dashcam: "A real frame from a cheap wide-angle dashcam, slight barrel distortion, deep focus, mild compression artifacts.")
2. Referências numeradas com papel: "References 1 and 2 define the [person] (face, hair, marks, outfit); reference 3 defines the [product/place]; use nothing else from them."
3. Cena com "the exact same [person]" fazendo uma ação no meio do gesto, olhando um pouco ao lado da lente.
4. Marcas da pessoa: idade, cidade, ocupação, cicatriz/pinta/marcas, tom de pele irregular, brilho na testa.
5. Luz: fonte + lado + qual lado do rosto fica na sombra.
6. Câmera: distância em metros, enquadramento (waist-up, chest-up), rosto no terço superior, levemente fora do centro.
7. Fecho: "Natural color, low contrast, no sharpening, no glow, no macro pores at this distance. No logos, blank plate, no readable text."
Celular sempre visto por trás, tela invisível. Ambiente local específico.

### Vídeo: Kling 3.0 pro, 5 s, start image, cfg 0.5, sem som
Kenui (23/11): Kling 3.0 ficou melhor que Seedance 2.5 no mesmo prompt, mas quer Seedance testado também (tem fama de melhor). Testes comparam os dois.
Ilimitado: em 23/11/2026 não estava ativo para Kling 3.0, Seedance 2.5 nem Nano Banana 2 (provável promoção encerrada). Gerar pelo conector com orçamento aprovado.
"[Handheld phone footage | Handheld documentary footage | Locked-off dashcam recording] [hora/lugar]. [Uma cadeia curta de ações com gesto humano no fim]. [Movimento do fundo]. Same face, [2 traços] and [roupa] throughout. [Um movimento: slow push-in | slow sideways drift | slow pull-back] with subtle handheld shake, [focus settles a little late | exposure breathes slightly]. Natural color, no grading, no glow."

### Proibido (aprendido de 12/11 para 16/11)
shallow depth of field, bokeh, sun flare, camera arc, epic, majestic, triumphant/serene mood, glow, pulsos de luz, efeitos de luz.

## v1 candidata (máximo realismo, não provada)
Imagem: 9:16; "A real frame grabbed from an iPhone front-camera video, auto HDR off"; slight motion blur on the moving hand, imperfect framing, window slightly blown out, mixed white balance; unhas imperfeitas, fio de cabelo no rosto, roupa amassada, fundo bagunçado; poros só em close.
Vídeo Kling: "autofocus hunts once and locks on the product", "brief exposure jump as the product passes the window", "the frame tilts slightly as she shifts her grip", "blinks naturally, breathes", peso nas ações; testar som ligado com fala curta.
Montagem: 1080x1920 a 30 fps, sem correção de cor, som ambiente real, 1-2 planos reais do produto.
Custo (23/11, conector): Nano Banana 2 9:16 2k = 1,5 crédito; Kling 3.0 pro 5 s 9:16 = 8,75 sem som, 12,5 com som.

### Reforço da v1 vindo do AI Video Bootcamp (23/11, fonte pública de Daniel Riley/PromptWise; ver guia)
- Pele: trocar nosso bloco pelo dele: "Real, unretouched photographic skin: natural color variation across the face, a little shine on the forehead and nose, softer texture on the outer cheeks, normal creases under the eyes and real texture on the lips. Hair separates into uneven strands with a few flyaways... No skin smoothing, waxy skin, porcelain teeth, glowing irises, uniform pore texture or helmet-like hair."
- Movimento: "Every character keeps moving naturally: breathing, natural blinking, small shifts of weight, small hand gestures and hair moving slightly. No frozen poses and no slow motion. Real unretouched skin and hands with five fingers."
- Câmera de quem segura: "CAMERA OWNERSHIP LOCK: her partner holds the phone across the counter at chest height about 1.2 m. NOT a selfie, she holds no phone." Libera as duas mãos para testar o produto.
- Estilo de câmera: "Real handheld phone, 24-26mm, vertical, micro-jitter, slight horizon drift, a small late reframe, brief autofocus hunt. No gimbal, no steadicam, no smooth pans, no push-ins."
- Travas de produto: "ONE [produto], never duplicates", posição fixa da tampa, estado só avança (ex.: nível de água só sobe).
- Comportamento no lugar de emoção ("raises eyebrows and leans in", não "surprised"); nomear o que cada mão faz; começar no meio da ação; fala de 2 a 3 palavras por segundo; "her mouth moves only when she speaks".
- Portão de aprovação: conferir reflexo nos olhos coerente com a luz declarada (janela de um lado = reflexo do mesmo lado), mãos com 5 dedos, produto sem duplicar.
- Efeito real: gravar 3 a 10 s do produto funcionando no celular e usar como referência de vídeo no Kling 3.0 Omni (ou Motion Control). Sem amostra, usar filmagem própria/licenciada do fornecedor.

## Alvo de qualidade (referência aprovada pelo Kenui)
- 23/11: capinha de glitter líquido com bichinhos, `referencias/2026-11-23_capinha-glitter.md`. É o nível de naturalidade que buscamos. Lições: celular no tripé (mãos livres), produto colado na lente com o efeito acontecendo, segundo ângulo no espelho, física certa do líquido, som do produto na ação, sem música, final em reação silenciosa.

## Cenas da pesquisa como referência (desde 24/11, pedido do Kenui)
Antes da ficha de uso, os vídeos reais achados na pesquisa passam pela ferramenta `ferramentas/ver-video` (cenas, quadros, som, transcrição). Cada comportamento do produto no prompt precisa de uma cena real que prove, com fonte e tempo. O vídeo gerado passa pela mesma ferramenta e é comparado cena a cena antes de aprovar. Método completo: `cenas-de-referencia.md`.

## Realismo UGC (v2, 24/11; a v1 sem voz e com imagem degradada foi reprovada no D-v4)
Realismo é textura e detalhe (pele, pelo, casa vivida, luz de casa), escrito dentro do prompt, não filtro nem qualidade ruim (`celularizar` só para teste). Prompts escritos pelo Fable 5.2 ou modelo mais inteligente. UGC precisa do criador na tela, com voz e conversa natural com o parceiro que filma. O animal sempre olha para a presa (GAZE LOCK). O prompt é longo, em blocos com travas, e descreve o produto exatamente como ele é. Antes do prompt, o bloco DECISÃO CRIATIVA (persona, ângulo, tipo de anúncio, força do CTA, por quê com dados, teste) é obrigatório. Modelo completo: `realismo-de-celular.md`.

## Etapa obrigatória antes do prompt: ficha de uso do produto (desde 23/11, pedido do Kenui)
Nenhum prompt de vídeo é escrito sem a ficha `operacoes/<op>/produtos/<produto>/uso-no-video.md`, feita a partir do modelo `conhecimento/video-ia/modelo-ficha-uso-produto.md`.
Regras de conversão da ficha para o prompt:
1. Número vira comparação visual: tamanho em relação à mão ou a objetos comuns ("about the size of a smartphone"), com o número junto.
2. Peso vira comportamento: o pulso cede, usa as duas mãos, apoia com um baque leve; ou ergue com dois dedos.
3. Material vira luz: fosco sem brilho, metal com reflexo pequeno e suave, translúcido com a luz passando.
4. Estados só avançam (fechado -> aberto, vazio -> cheio). Nunca voltam no mesmo clipe.
5. Uma ação física por cena, com as duas mãos nomeadas, tirada da sequência real de uso.
6. O efeito uau é uma cena própria: uma mudança visível antes -> depois, numa tomada contínua, em velocidade real, igual à filmagem de referência. Sem câmera lenta.
7. Gancho: abrir na dor visível ou no meio do efeito uau, nunca no produto parado.
8. Som real só na ação que o produz (clique, zumbido), descrito no prompt de áudio.
9. Física específica escrita como regra (líquido escorre para baixo, objeto pesado não flutua, cabo não some).
10. Rótulo em branco, sem texto legível. O produto real entra em plano próprio na montagem, se for preciso mostrar a marca.
No portão de aprovação: escala igual entre cortes, peso crível, efeito igual à filmagem real, nada duplicado.

## Próximos testes (uma mudança por vez)
1. 9:16 em vez de 16:9
2. Áudio nativo com fala (Seedance 2.5 ou Kling com som)
3. Clipe multi-corte de 1 a 2 s por corte vs clipes de 5 s
4. Gancho com primeiro quadro no meio da ação/dor
5. Textura de pele só nos closes
6. Produto como referência numerada com rótulo real
7. Créditos por vídeo aprovado
8. Tomada contínua de 15 s com prompt mestre LOCK (Seedance 2.5) vs 8 cortes secos
9. Bloco de pele e movimento do Riley vs nosso bloco v0
10. Referência de vídeo real do produto no Kling 3.0 Omni vs só imagem
11. Prompt com bloco PRODUCT LOCK da ficha vs prompt sem ficha
12. Estrutura da referência da capinha (tripé, produto na lente, espelho, final mudo) vs 8 cortes

## Esqueleto para os próximos testes
- Pessoa: Soul 2 ou Nano Banana 2, sem objeto nas mãos (modelo A do guia)
- Quadro-roteiro: GPT Image 2.6, 21:9, 8 quadros 9:16
- Limpeza: Seedream 5.5 (modelo B do guia)
- Clipe: Seedance 2.5, omni_reference, 9:16, 1080p, áudio nativo, 8 cortes (modelo C do guia)
- Música e legenda: na montagem, música licenciada
