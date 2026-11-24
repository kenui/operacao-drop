# Realismo UGC: textura, voz com conversa, criador na tela e produto como ele é (v2, 24/11/2026)

Histórico:
- **v1** (24/11, depois do D-v3): tirou a narração e degradou a imagem. **Reprovada pelo Kenui no D-v4.**
- **v2** (esta versão) corrige isso com as palavras dele: "Não é qualidade o problema, é textura"; "precisa ter voz, precisa ter conversa, precisa aparecer o GC [o criador UGC]"; "o cachorro não tá olhando pro peixinho"; "emerge como o produto tem que ser"; "usando muito prompt".

## Os quatro erros que não voltam
1. **Degradar a imagem não é realismo.** Baixa resolução e compressão pesada deixam o vídeo pior, não mais real. O realismo vem da textura e dos detalhes que o modelo gera. A ferramenta `celularizar` agora usa por padrão o nível `textura` (mantém a resolução e a voz limpa, só tira o brilho perfeito de IA). Os níveis `leve` e `medio` ficam só para teste.
2. **Vídeo UGC sem voz não é UGC.** Precisa de uma pessoa real na tela (o criador), falando e conversando. O problema a resolver é a voz soar natural, não eliminar a voz.
3. **O animal precisa olhar para a presa.** A cabeça, os olhos e as orelhas seguem a luz o tempo todo. Bicho que olha para o nada denuncia a IA na hora.
4. **Prompt curto deixa o modelo inventar.** O prompt é longo, em blocos com travas (modelo do Riley), e descreve o produto exatamente como ele é.

## 1. Textura (o que faz parecer real)
Escreva no prompt e confira no resultado:
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

## 5. Modelo de prompt longo (Seedance 2.5 ou Kling 3.0, um por clipe)
```
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
- [ ] O criador aparece falando, e tem conversa com o parceiro?
- [ ] As vozes soam como pessoas num cômodo (eco leve, ritmo irregular, risada)?
- [ ] O animal olha para a presa em todos os quadros do efeito?
- [ ] O efeito aparece no chão, com luz caindo sobre o animal, por 4 s ou mais?
- [ ] O produto tem a forma real e não gira nem duplica?
- [ ] Tem textura (pele, pelo, casa vivida) sem degradar a imagem?
- [ ] O produto termina como protagonista e a pelúcia, se aparece, é rápida?
- [ ] Rodou `ver-video` e `celularizar` no nível textura?

## Economia de crédito
Rascunho em 480p conferido antes do final. Quadro inicial testado antes do vídeo. Reaproveitar o que já foi aprovado. Orçar e pedir o aval do Kenui.
