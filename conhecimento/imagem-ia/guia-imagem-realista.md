# Guia de imagem realista de alta qualidade (v1, 24/11/2026)

Pedido do Kenui (24/11): a imagem também precisa de muito prompt e da maior qualidade possível. A mesma pesquisa feita para vídeo, aplicada à imagem, com o mesmo canal como fonte (Daniel Riley / PromptWise, material público e grátis).

Por que importa: no nosso fluxo, a imagem-base, o quadro inicial e as fichas de personagem alimentam o vídeo. Defeito na imagem vira defeito no vídeo. O próprio Riley diz que um rosto de cera na imagem continua de cera no vídeo.

Legenda: [direto] = da fonte; [nosso] = decisão ou dedução nossa.

## 1. Regras do canal [direto]
1. **Descrever evidência física, não pedir "hiper-realismo".** "Hyper-realistic, 8K, ultra detailed, flawless skin, masterpiece" empurra para pele retocada de comercial. Descreva o que uma foto real tem: sardas, brilho na testa, linhas finas, fios soltos.
2. **Uma luz com motivo para existir:** fonte, posição, cor e queda. "No other light sources and no flat fill light." Conferência: uma luz = um reflexo pequeno em cada olho.
3. **Escrever uma pessoa, não uma modelo:** idade, ocupação, corpo, pele, cabelo, olhos, um sinal de desgaste na roupa, uma ação simples.
4. **Objetos com material, estado e escala.** Em vez de "uma garrafa": "matte black stainless steel bottle with fine condensation droplets, a small scuff near the base".
5. **Câmera concreta:** tamanho do plano, altura, distância em metros, lente em mm.
6. **Sem texto legível** (letreiro embaralhado entrega a IA).
7. **Ordem em camadas:** sujeito → meio (foto) → estilo → luz → composição → paleta → clima → detalhe e acabamento.
8. **Mesma frase de luz em todas as imagens da mesma "sessão"**, para parecerem fotografadas no mesmo dia.
9. **Produto real como referência** é melhor que descrever do zero. Diga o que fica igual ("keep the product exactly as it is, including...") e o que muda.
10. **Personagem recorrente:** um bloco de identidade de 4 a 6 traços, colado palavra por palavra em todo prompt, mais a ficha aprovada como "image 1".
11. **Conferir de perto** antes de animar: pele, olhos, mãos (5 dedos), bordas, sombra coerente com a luz, escala.

## 2. Bloco de realismo de pele [direto]
```
Real, unretouched photographic skin: natural color variation across the face, a little shine on the forehead and nose, softer texture on the outer cheeks, normal creases under the eyes and real texture on the lips. Hair separates into uneven strands with a few flyaways, and makeup and clothing look lived in. A natural, in-the-moment expression rather than a posed model look. It reads as a frame from a film, not a stock photo or a beauty ad. No skin smoothing, waxy skin, porcelain teeth, glowing irises, uniform pore texture or helmet-like hair.
```
Em plano médio ou aberto, não peça poros (eles só aparecem de perto).

## 3. Modelo de prompt de imagem em blocos [nosso, montado sobre o do Riley]
```
SHOT: Photorealistic [plano] [vertical 9:16], a real frame from an iPhone video [ou: a real phone photo, auto HDR off].
SUBJECT (identity block, word for word every time): [4-6 traços fixos da pessoa/animal].
WARDROBE & WEAR: [roupa + um sinal de desgaste].
ACTION & EXPRESSION: [uma ação simples + comportamento, não rótulo de emoção].
PRODUCT (exactly as the reference): [forma, material, cor, tamanho, estado; "keep it exactly as image N"].
EFFECT STATE: [ligado/desligado; onde o efeito aparece e como a luz cai].
ANIMAL (identity + gaze): [raça, cor, marcas, coleira; olhando para X].
SETTING & TEXTURE: [cômodo vivido: marcas no piso, fio no chão, almofada amassada, poeira na luz].
LIGHT: One [fonte] [posição] lights [quem] in [cor] from [lado]; [resto] falls into [sombra]. No other light sources, no flat fill.
CAMERA: [altura], about [x] m away, [lente] mm, framing [enquadramento]; slight imperfect framing.
REFERENCES: Image 1 = [ficha da pessoa], image 2 = [ficha do animal], image 3 = [foto/ficha do produto]. Use only identity from each; nothing else from them.
REALISM: [bloco de pele] + natural color, no beauty retouching, no readable text.
BANS: no 8K/hyper-realistic look, no plastic skin, no glowing eyes, no extra fingers, no duplicated product, no logos.
```

## 4. Qual modelo, em que resolução, quanto custa (Higgsfield, 24/11/2026)
| Uso | Modelo | Config | Créditos |
|---|---|---|---|
| Rosto/pessoa base fotorrealista | Soul 2 (Realism Pro) | 2k, 3:4 ou 9:16 | ~0,12 |
| Ficha de personagem (3 painéis: frente, costas, retrato) | GPT Image 2.6 | 2K, 16:9 | ~1,25 |
| Quadro inicial final, cena com várias referências (até 14) | Nano Banana Pro | 4k, 9:16 | 4 (2k = 2) |
| Rascunhos e variações | Nano Banana 2.1 | 1k a 2k | ~1,5 |
| Teste de textura e detalhe (até 10 referências) | FLUX 3 Image | 4k | 19 (caro: só se os outros falharem) |
| Ficha de criatura/animal ou estilo não realista | Seedream 5.5 | 2K, 16:9 | ~3,5 |
Regra: rascunho barato → escolhe → versão final no melhor modelo em 4k (Nano Banana Pro). Imagem é barata perto do vídeo; vale gastar aqui para economizar retrabalho no vídeo.

## 5. Fichas de personagem (pessoa, animal e produto) [nosso, sobre o fluxo character-sheet do Higgsfield]
- **Quando:** sempre que o mesmo personagem aparece em mais de um clipe ou de um vídeo. É o caso de todo criativo com criador e pet.
- **Pessoa:** rosto no Soul 2, depois ficha de 3 painéis no GPT Image 2.6 (2K, 16:9), com um rosto só, no painel da direita.
- **Animal:** ficha de criatura realista no Seedream 5.5 (2K, 16:9) ou no Nano Banana Pro: lado, frente, três quartos e cabeça de perto, com marcas e coleira iguais.
- **Produto:** de preferência foto real (do fornecedor ou nossa) de frente, de lado e a 45°. Sem foto, gerar uma ficha de produto em fundo cinza claro com as medidas da ficha.
- **Guardar** em `operacoes/<op>/produtos/<produto>/fichas/` (imagem + bloco de identidade em texto + job id), com a mídia no Drive.
- **Passar ao vídeo:**
  - Seedance 2.5 (omni_reference): fichas como referências de imagem, com o papel de cada uma escrito no prompt ("@image1 is the woman, @image2 is the dog, @image3 is the product").
  - Kling 3.0 (Omni/elements): ficha como elemento do personagem, mais o quadro inicial.
  - Topview: avatar e produto como entradas próprias da ferramenta (ver a conversa "Testar TopviewAI para vídeos").
  - Em todos: o bloco de identidade palavra por palavra no prompt.

## Fontes [direto]
- https://promptwise.com/prompts/realistic-ai-people-prompts/
- https://promptwise.com/blog/how-to-write-ai-image-prompts-that-actually-work/
- https://promptwise.com/blog/ai-product-photography-replace-photoshoots/
- https://promptwise.com/blog/5-ai-prompt-mistakes-killing-your-results/
- Fluxo character-sheet do Higgsfield (instruções do conector, 24/11)
