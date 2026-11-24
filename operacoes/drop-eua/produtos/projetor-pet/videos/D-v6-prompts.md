# Vídeo D v6: prompts (24/11/2026)

Escritos pelo Fable 5.2 (subagente), como manda a seção 0 do [guia](../../../../../conhecimento/video-ia/realismo-de-celular.md). Nada foi gerado ainda. A DECISÃO CRIATIVA é a mesma do [D-v5-plano.md](D-v5-plano.md) (casal conversando, CTA médio, sem urgência).

## O que a v6 corrige da v5b
| Retorno do Kenui | Onde está a correção |
|---|---|
| O cachorro mordeu e carregou um objeto que não existe | Quadro inicial com a boca visivelmente vazia e o chão sem nenhum objeto; MOUTH LOCK no Kling; itens de brinquedo e petisco nos BANS |
| A boca dela não acompanha a fala | Rosto em três quartos para a câmera e iluminado pelo abajur no quadro inicial; bloco LIP SYNC no Kling; depois, passada de Sync Lipsync 3 com o áudio do próprio vídeo (etapa 3, sem prompt) |
| A pele não parece real | Bloco de pele completo no quadro inicial (é ele que decide a pele do clipe) e repetido no Kling |
| O cachorro olhou para a câmera nos primeiros 5 s | Quadro inicial com a cabeça virada e os olhos no coelho; GAZE LOCK no Kling |
| O projetor quase saiu do quadro no fim | Trava de enquadramento: projetor inteiro à esquerda o vídeo todo, sem zoom, sem pan, sem reenquadrar |
| Textura só pelo prompt | Sem filtro em nenhuma etapa |

## (a) Quadro inicial: Nano Banana 2.1, edição de imagem, 9:16
Referências: `@image1` = quadro inicial anterior (mesma sala, mulher, cachorro e projetor); `@image2` = foto de referência do produto; `@image3` = referência do cachorro.

```
Edit @image1. Keep the same room, the same woman, the same dog, the same projector and the same camera position. Output a vertical 9:16 real phone photo, a still from a video her partner is filming on an iPhone at chest height from about 4 feet away. Auto HDR off. Not a selfie: she holds no phone and both her hands are empty.

REFERENCE ROLES: @image1 defines the room, the layout, her face, hair and outfit. @image2 defines only the shape and colors of the projector. @image3 defines only the dog's breed, coat and markings. Use nothing else from them.

ROOM: lived-in American living room on a rainy evening. One yellow table lamp is on, weak blue-gray light comes from the rain-streaked window, a small Christmas tree with warm lights sits in the background, the corners stay dark. Worn wood floor with fine scratches and a little dust catching the lamp light, a creased cushion against the sofa. The floor around the dog and the rings is completely clear: no toys, no treats, no chews, no cables, no objects of any kind.

WOMAN: late 20s, messy bun with loose flyaway strands, cream sweatshirt with light pilling and a stretched collar, black leggings, barefoot, sitting on the floor at the right. Her body faces the dog but her face is turned three-quarter toward the camera, fully visible, lit by the yellow lamp from the front side, no deep shadow over her mouth or eyes. Lips closed in a small amused smile, eyes on the dog. Real unretouched skin: visible pores on the nose and cheeks, uneven tone, slight redness around the nostrils, a little shine on the forehead and nose, fine lines and faint darkness under the eyes, dry texture on the lips, a few baby hairs at the hairline. No makeup look, no beauty filter, no skin smoothing, natural slightly uneven teeth if they show. Five fingers on each hand.

PROJECTOR: ONE upright paw-shaped pet projector exactly as in @image2, about 6-7 inches tall: a cream paw standing vertically with four black toe pads and one black main pad on a low round black base, one round lens glowing soft blue at the front. It stands on the floor at the left edge of the frame, fully visible, facing the middle of the room. It is ON.

PROJECTION: a faint blue glow on the floor right in front of the base, then 6 glowing orange rings with tiny grass tufts lying flat on the wood floor in a fan in front of the projector, following the floor's perspective. Inside ONE ring, about 2 feet in front of the dog, a small flat orange cartoon bunny made of light, about 3 inches tall. The light is flat on the floor like a projection, never floating, never 3D, no beams in the air, no light on the walls. Where a ring touches the dog's front paw, the orange light falls on top of the paw and fur.

DOG: ONE small gray French bulldog exactly as in @image3, white patch on the chest, red collar, bat ears, four legs, one short tail. He is standing in the middle of the frame, weight forward on his front legs, one front paw on the edge of a ring, ears pricked forward, head turned down and to the side toward the bunny, both eyes locked on it. He does not look at the camera. His mouth is closed or very slightly open in a light pant with only the tip of his tongue showing; his mouth is clearly empty, nothing between his teeth, nothing hanging from his jaw. Separate fur strands, a damp nose, nails visible on the wood.

LOOK: real iPhone photo indoors at night, 24mm, natural phone sharpening, fine sensor grain in the shadows, mixed warm and cool white balance, slightly soft corners, everything in focus from the projector to her face. Natural color, low contrast, no glow, no bokeh, not cinematic, not a studio shot. No text, no logos, no watermark.
```

Conferir no quadro antes de gastar no vídeo: boca do cachorro vazia e chão sem objetos; olhos dele no coelho; rosto dela visível e iluminado, boca sem sombra; projetor inteiro à esquerda; pele com poro e brilho; ela sem celular e com 5 dedos em cada mão.

## (b) Vídeo: Kling 3.0 pro, 15 s, 9:16, som ligado, start_image = quadro aprovado
**2.192 caracteres** (contados com `len()` do Python, sem a quebra de linha final; limite combinado: cerca de 2.200).

```
Real iPhone video of a couple at home on a rainy evening, not an ad. ONE continuous shot, no cuts. Her partner films from chest height, 4 feet away; she holds no phone.

CAMERA: handheld iPhone, 24mm, micro-jitter, one brief autofocus hunt, exposure adjusts a moment late, natural phone sharpening, fine grain in the shadows. Framing stays wide: the paw projector stays fully in frame at the left the whole video. No zoom, no pan, no reframe.

HER: same woman as the start image, same face, messy bun, pilled cream sweatshirt, sitting on the wood floor. Real unretouched skin: visible pores, uneven tone, redness around the nose, shine on forehead and nose, fine under-eye lines, dry lip texture, flyaway hairs.

PRODUCT: ONE upright cream paw-shaped projector, black toe pads, round black base, blue lens glowing in front. It never moves or duplicates. Glowing orange rings lie flat on the floor in front of it; a flat orange cartoon bunny made of light pops up in one ring, then the next. The light tints the dog's paws and fur orange. Never floats, never 3D.

DOG: ONE gray French bulldog, white chest, red collar, at real speed, separate fur strands, wet nose. GAZE LOCK: his head, eyes and ears track the bunny from ring to ring the whole time; he never looks at the camera. He freezes, pounces with both front paws on the bunny, sniffs the ring, hops to the next. MOUTH LOCK: his mouth is empty the entire video, closed or lightly panting, only his tongue visible. He never bites, picks up or carries anything. The floor holds only light, no objects.

DIALOGUE, exactly three lines:
[0-4s] SHE, quietly excited, eyes on the dog: "Babe, look... he's actually hunting it."
[5-7s] HE, off camera, laughing, close to the mic: "No way."
[10-14s] SHE, turning her face to the camera with a smile: "Honestly? If your dog gets bored, get this."
LIP SYNC: her lips clearly shape every word in time with her voice, face lit by the lamp. Her mouth stays closed whenever she is not speaking.

AUDIO: phone mic, small room, light echo, rain on the window, nails on wood, soft snorts.

BANS: no text, subtitles, music, slow motion, morphing, extra animals, plastic skin.
```

## (c) Conferência com a seção 6 do guia
| Item | Situação |
|---|---|
| DECISÃO CRIATIVA preenchida, com dados, fonte e teste | Sim, reaproveitada do D-v5-plano.md sem mudança. O campo "Resultado" continua vazio. |
| O criador aparece falando e tem conversa com o parceiro | Sim: 2 falas dela na tela e 1 dele fora do quadro, com tempo, quem fala e tom. |
| Vozes soam como pessoas num cômodo | Pedido no prompt (microfone do celular, cômodo pequeno, eco leve, voz dele mais perto do microfone, chuva). Só dá para confirmar ouvindo o resultado. |
| O animal olha para a presa em todos os quadros | Pedido em dois lugares: pose do quadro inicial e GAZE LOCK. A confirmar quadro a quadro; foi o ponto fraco da v5b nos primeiros 5 s. |
| Efeito no chão, com luz sobre o animal, por 4 s ou mais | Sim: ligado desde o quadro inicial, anéis planos, luz tingindo patas e pelo. Na v5 o coelho sumiu depois de 9 s; conferir. |
| Produto com a forma real, sem girar nem duplicar | Sim, PRODUCT LOCK v3 resumido. A ficha do produto ainda é provisória (campos [CJ]). |
| Textura (pele, pelo, casa vivida) sem degradar a imagem | Pele e pelo nos dois prompts. A casa vivida está só no quadro inicial (riscos no piso, poeira, almofada amassada). |
| Produto termina como protagonista; pelúcia rápida | Parcial: o projetor fica em quadro até o fim, mas o último momento é o rosto dela dizendo a fala de recomendação. Sem pelúcia. |
| Textura veio do prompt, sem filtro; rodou `ver-video` | Sem filtro. O `ver-video` só pode rodar depois de gerar. |
| Prompt escrito pelo Fable 5.2 | Sim. |

### O que não deu para cumprir, ou foi mudado de propósito
1. **Sem brinquedo velho no canto e sem fio de carregador no chão.** O guia sugere os dois como textura de casa vivida. Tirei porque o defeito da v5b foi justamente um objeto inventado na boca do cachorro; qualquer objeto no chão vira convite.
2. **Sem os 3 a 5 cortes da estrutura da seção 3.** Fica uma tomada contínua, pela lição do Kling na v5 (roteiro de várias tomadas é ignorado).
3. **Sem "one late reframe" do modelo de prompt.** Trocado por enquadramento fixo, porque o reenquadramento da v5b quase tirou o projetor do quadro.
4. **Sem bloco END FRAME.** Cortado para caber em cerca de 2.200 caracteres. O fim é sustentado pela trava de enquadramento e pela terceira fala.
5. **Falas sem hesitação ("um", "like").** As três falas ficaram exatamente como no plano, porque saíram certas na v5b.
6. **Tamanho do coelho (cerca de 3 polegadas) e quantidade de anéis (6)** vêm da ficha, que ainda depende do vídeo real da CJ.

### Riscos para olhar no resultado
- **Lista de proibidos pode sugerir o objeto.** Citar "toys, treats, chews, plush" nos BANS pode, em alguns modelos, puxar justamente isso. Se o objeto voltar, a próxima tentativa tira essas quatro palavras e mantém só o MOUTH LOCK positivo ("his mouth is empty... the floor holds only light").
- **Sincronia labial.** O Kling pode continuar errando a boca; por isso a etapa 3 (Sync Lipsync 3) está no plano. O prompt ajuda deixando o rosto virado para a câmera e iluminado na última fala, que é a mais longa.
- **Latido curto x boca vazia.** O latido abre a boca por um instante; conferir se o modelo não aproveita para pôr algo nela.

### Conformidade
Ela demonstra e recomenda a partir do que está vendo. Não diz que comprou, não cita uso prolongado nem resultado, e não há urgência. O rótulo de IA do Meta vai no anúncio.

## Revisão (Claude, antes de gastar)
- Vírgula corrigida em "red collar, at real speed".
- Tirei "one short bark" (abre a boca do cachorro, risco de objeto) e troquei por "soft snorts".
- Tirei "toys, treats, chews, plush" dos BANS: o Kling não tem prompt negativo e citar o objeto pode trazê-lo. Fica só a trava positiva (MOUTH LOCK e chão só com luz).
