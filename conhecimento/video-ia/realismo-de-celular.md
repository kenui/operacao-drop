# Realismo UGC: textura, voz com conversa, criador na tela e produto como ele é (v2, 24/11/2026)

Histórico:
- **v1** (24/11, depois do D-v3): tirou a narração e degradou a imagem. **Reprovada pelo Kenui no D-v4.**
- **v2** (esta versão) corrige isso com as palavras dele: "Não é qualidade o problema, é textura"; "precisa ter voz, precisa ter conversa, precisa aparecer o GC [o criador UGC]"; "o cachorro não tá olhando pro peixinho"; "emerge como o produto tem que ser"; "usando muito prompt".

## Os quatro erros que não voltam
1. **Degradar a imagem não é realismo, e textura não é filtro.** Baixa resolução e compressão pesada deixam o vídeo pior, não mais real. A textura vem **de dentro do prompt** do Seedance ou do Kling (seção 1 e blocos SETTING & TEXTURE, CHARACTER e ANIMAL do modelo). Regra do Kenui (24/11): a ferramenta `celularizar` não é mais etapa padrão. Ela fica só como recurso de teste, nunca no lugar de um prompt bem escrito.
2. **Vídeo UGC sem voz não é UGC.** Precisa de uma pessoa real na tela (o criador), falando e conversando. O problema a resolver é a voz soar natural, não eliminar a voz.
3. **O animal precisa olhar para a presa.** A cabeça, os olhos e as orelhas seguem a luz o tempo todo. Bicho que olha para o nada denuncia a IA na hora.
4. **Prompt curto deixa o modelo inventar.** O prompt é longo, em blocos com travas (modelo do Riley), e descreve o produto exatamente como ele é.

## 0. Quem escreve o prompt
Regra do Kenui (24/11): os prompts de vídeo e de quadro inicial são escritos pelo modelo mais inteligente disponível, hoje o Fable 5.2 (`claude-fable-5-2`). A conversa que gera o vídeo troca de modelo para essa etapa, ou entrega a escrita do prompt a um subagente com esse modelo. Ele recebe a ficha do produto, a tabela de verdade, a DECISÃO CRIATIVA e este guia. Depois, a mesma conversa confere o prompt com a lista da seção 6 antes de gastar crédito.

## 1. Textura (o que faz parecer real), sempre dentro do prompt
Escreva no prompt e confira no resultado. Não corrija depois com filtro:
- **Pele:** bloco do Riley inteiro (variação de cor, brilho na testa e no nariz, linhas sob os olhos, textura nos lábios, fios soltos). Maquiagem e roupa com cara de usadas: bolinha no moletom, gola larga.
- **Pelo do animal:** fios separados, tufos desalinhados, um pouco de baba ou focinho úmido, unhas que aparecem quando ele pisa.
- **Casa vivida:** marcas e riscos no piso, poeira visível na luz, um fio de carregador no chão, almofada amassada, brinquedo velho num canto, rodapé lascado. Nada de cenário de catálogo.
- **Luz de casa:** abajur amarelo + resto da luz azulada da janela; cantos escuros; a luz do produto bate de verdade no chão e no animal e muda a cor deles.
- **Câmera de celular, sem sujeira artificial:** "real iPhone video, natural phone sharpening, fine sensor grain in the shadows, slight autofocus hunt, exposure adjusts a moment late". Proibido: cinematic, 4K, crisp, smooth gimbal, bokeh.
- **Física:** peso, sombra embaixo de cada objeto, reflexo coerente com a luz, o animal afunda um pouco no tapete.

## 2. Voz e conversa (o que faz soar real)
- **Duas pessoas:** o criador na tela e um parceiro fora de quadro segurando o celular (trava de câmera do Riley). Eles conversam: pergunta, resposta, risada, um falando por cima do outro.
- **Fala de verdade:** frases curtas, começa no meio ("okay wait, look at him"), uma hesitação ("um", "like"), reação ao que está acontecendo, fala com o animal ("get it, buddy!"). Ritmo de 2 a 3 palavras por segundo, com pausas.
- **Som do lugar:** o áudio nativo do Seedance com a voz no espaço (cômodo pequeno, eco leve), mais os sons do animal e do produto (clique, unhas no piso, latido curto).
- **A boca só mexe quando a pessoa fala**, e a voz do parceiro vem de trás da câmera, um pouco mais alta e mais perto do microfone.
- Se a voz nativa ainda soar falsa: gravar as falas no celular (o Kenui ou alguém autorizado) e trocar o timbre com voice change. Locução limpa de estúdio, nunca.

## 3. O criador aparece (estrutura de UGC)
Estrutura base, a mesma da referência da capinha e dos anúncios que estão rodando:
| Tempo | O que acontece | Ângulo |
|---|---|---|
| 0 a 3 s | Gancho com o criador falando para a câmera, o produto na mão ou ao lado, e a dor visível (o animal entediado atrás) | frontal, celular na mão do parceiro, altura do peito |
| 3 a 6 s | Ele mostra o produto de perto e liga (clique audível) | close nas mãos e no produto |
| 6 a 12 s | Efeito ligado + animal caçando a presa, olhando para ela. O criador reage e conversa com o parceiro | câmera baixa, na altura do animal; um corte de volta para o rosto do criador reagindo |
| 12 a 15 s | Reação final do criador com o efeito ainda rodando no fundo; o brinde (pelúcia) aparece rápido, se for usar | frontal, um pouco mais aberto |
Cortes secos entre ângulos (3 a 5 cortes em 15 s). Texto na tela só para reforçar o gancho e o limite honesto.

## 4. O produto como ele é (projetor pet como exemplo)
Antes de escrever, abra a ficha `uso-no-video.md` e a tabela de verdade (`cenas-de-referencia.md`). Copie no prompt a forma, o tamanho, de onde sai a luz, o que se mexe e o que fica parado. Nada que não esteja provado em vídeo real.

## 4b. Bloco de decisão criativa (vem antes do prompt; pedido do Kenui, 24/11)
O formato muda conforme a persona, o ângulo e o tipo de anúncio. Cada escolha tem um porquê baseado em dados, fica registrada e vira teste, porque o dado pode estar errado.

Preencha antes de escrever o prompt e guarde na ficha do vídeo (`videos/<id>.md`):
```
DECISÃO CRIATIVA
- Persona e nível de consciência: [de 03-persona.md, ex.: dona de cachorro, sabe da dor, não conhece o produto]
- Ângulo: [de 04-angulos.md, ex.: B dono cansado]
- Tipo de anúncio: [demonstração | criador recomendando | problema-solução | reação | comparação | presente]
- Quem fala e como: [criador demonstrando ao vivo | casal conversando | criador recomendando para quem tem o mesmo problema]
- Força do CTA: [suave "comment X" | médio "link in bio, it's on sale" | forte "get yours before it sells out" + motivo real]
- Por quê (dados): [ex.: 46 anunciantes usam "comment DOG" e o anúncio mais antigo tem 8 dias; persona fria pede prova antes de pedido; buraco do mercado = captura]
- Fonte de cada dado: [link ou arquivo]
- Hipótese: [ex.: CTA forte com motivo real aumenta CTR sem derrubar a taxa de gancho]
- Teste: [variável única, ex.: mesmo vídeo com CTA suave x forte; métrica: CTR e custo por compra; mínimo 1.000-2.000 impressões, 7 dias]
- Resultado: [preencher depois; vencedor vira padrão no JEITO-CERTO e no painel.csv]
```

Guia rápido para escolher (ponto de partida, a confirmar com teste):
| Situação do público | Formato que costuma servir | CTA |
|---|---|---|
| Não sabe que o problema tem solução (frio) | demonstração com o efeito uau nos 3 primeiros segundos, criador reagindo | suave ou médio |
| Sabe da dor, não conhece o produto | problema-solução: dor visível, criador mostra e conversa | médio |
| Já viu o produto (remarketing, quente) | criador recomendando com objeção respondida (luz baixa, captura) e oferta | forte, com motivo verdadeiro (kit, frete, estoque real) |
| Época de presente | criador dando de presente ou mostrando a caixa | médio ou forte com prazo real de entrega |

Limites que não mudam com o formato:
- "Alguém recomendando" é o criador recomendando a partir do que ele está vendo ao vivo, por exemplo: "honestly, if your dog gets bored, get this".
- Ele não diz que comprou, que usa há semanas, nem que é cliente verificado.
- Não há resultado inventado.
- Urgência só se for verdadeira.
- O rótulo de IA do Meta vai em todo anúncio.

## 4c. Imagem de partida e fichas
O quadro inicial e as fichas de pessoa, animal e produto seguem o [guia de imagem realista](../imagem-ia/guia-imagem-realista.md) (prompt em blocos, bloco de identidade colado palavra por palavra, ficha aprovada como imagem 1). Para escolher a técnica de cada problema, ver [arsenal.md](arsenal.md).

## 5. Modelo de prompt longo (Seedance 2.5 ou Kling 3.0, um por clipe)
```
(primeiro: DECISÃO CRIATIVA preenchida, ver 4b)
CORE CONCEPT: Real UGC video filmed on an iPhone in a lived-in American living room at dusk. [Creator] shows her partner how [product] works with their [animal]. It must look and sound like a real couple filming a real moment, not an ad.

CHARACTER LOCK: [descrição física fixa do criador, roupa, cabelo, marcas]. Same face, hair and clothes in every shot. [bloco de pele do Riley]

CAMERA OWNERSHIP LOCK: Her partner holds the phone at chest height about 1.2 m away. NOT a selfie; she holds no phone. His voice comes from behind the camera, closer to the mic.

CAMERA STYLE: Real handheld iPhone, 24-26mm, vertical 9:16, micro-jitter, slight horizon drift, one late reframe, brief autofocus hunt, exposure adjusts a moment late, natural phone sharpening, fine grain in the shadows. No gimbal, no smooth pans, no cinematic look.

SETTING & TEXTURE: [piso com marcas, poeira na luz, fio no chão, almofada amassada, brinquedo velho, abajur amarelo + resto de luz azul da janela, cantos escuros].

PRODUCT LOCK: [copiar da ficha: forma, tamanho, cor, de onde sai o efeito, estados, o que NÃO se mexe]. ONE product, never duplicates, never rotates, stays in the same spot.

EFFECT LOCK: [efeito real, ex.: orange rings flat on the floor in front of the lens with small light creatures moving between them]. The light lies flat on the floor, follows floor perspective, falls on top of the dog's paws and fur and tints them orange. Never floats in the air.

ANIMAL LOCK: [raça, cor, marcas, coleira]. ONE dog, four legs, normal body length. GAZE LOCK: his head, eyes and ears follow the moving light creature at all times; head turns as it moves; ears forward; he never looks at the camera or into empty space. Behaviour: sniffs a ring, freezes, pounces with both front paws, play bow, short bark, hops to the next ring. Fur separates into strands, nails visible on the floor.

DIALOGUE (natural, overlapping, 2-3 words per second):
SHE (to camera, mid-thought): "Okay so, he's been bored all day, right?"
HE (off camera): "Yeah, he's been staring at us."
SHE (clicks it on): "Watch this."
HE (laughs): "Oh my god, look at him!"
SHE (to the dog): "Get it, buddy! Get it!"
Her mouth moves only when she speaks.

AUDIO: Native phone-mic audio in a small room with light echo: both voices, the button click, dog nails on the wood floor, one short bark, soft laughter. No music.

BEAT SHEET: [o que acontece em cada segundo, do roteiro].

END FRAME LOCK: [como termina: efeito ainda ligado, cachorro nos anéis, ela sorrindo].

BANS: no text, no subtitles, no music, no slow motion, no morphing, no extra animals, no product rotating, no light before the click, no plastic skin, no smooth gimbal moves.
```
Quadro inicial de cada clipe (imagem barata) com tudo já no lugar: criador, produto, efeito no estado certo e o animal olhando para a presa. O vídeo só anima a partir dele.

## 6. Conferência antes de mostrar ao Kenui
- [ ] A DECISÃO CRIATIVA está preenchida, com dados, fonte e teste?
- [ ] O criador aparece falando, e tem conversa com o parceiro?
- [ ] As vozes soam como pessoas num cômodo (eco leve, ritmo irregular, risada)?
- [ ] O animal olha para a presa em todos os quadros do efeito?
- [ ] O efeito aparece no chão, com luz caindo sobre o animal, por 4 s ou mais?
- [ ] O produto tem a forma real e não gira nem duplica?
- [ ] Tem textura (pele, pelo, casa vivida) sem degradar a imagem?
- [ ] O produto termina como protagonista e a pelúcia, se aparece, é rápida?
- [ ] A textura veio do prompt, sem filtro depois? Rodou `ver-video` para conferir?
- [ ] O prompt foi escrito pelo Fable 5.2 (ou modelo mais inteligente disponível)?

## Economia de crédito
Rascunho em 480p conferido antes do final. Quadro inicial testado antes do vídeo. Reaproveitar o que já foi aprovado. Orçar e pedir o aval do Kenui.
