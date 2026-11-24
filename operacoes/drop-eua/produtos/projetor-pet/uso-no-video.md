# Projetor pet: ficha de uso no vídeo (v1, 24/11/2026)

Feita pelo [modelo](../../../../conhecimento/video-ia/modelo-ficha-uso-produto.md). **Provisória:** os campos marcados [CJ] só fecham com o vídeo e a ficha técnica do fornecedor. Nenhum prompt é gerado antes disso.

## 1. Identidade
- Nome interno: projetor-pet (kit "a caça completa").
- O que é: um aparelhinho do tamanho de uma caneca que joga ratinhos de luz andando no chão para o gato caçar.
- O que ele faz de verdade: [CJ] projetar vários ratinhos de luz se movendo no chão, saindo de "buracos" de luz, em penumbra.
- O que ele NÃO faz: o rato não reage à pata (a não ser que o modelo tenha sensor, [CJ]); não aparece com sol na sala; não é para ficar ligado o dia inteiro; o gato não pega a luz (por isso o ratinho de pelúcia).

## 2. Física do produto
| Campo | Valor real | Como a IA vai entender |
|---|---|---|
| Tamanho | ~10 a 12 cm [CJ] | "about the size of a coffee mug, fits in one palm" |
| Peso | ~200 a 250 g sem caixa [CJ] | "light, she sets it down with one hand, no effort" |
| Material | plástico ABS [provável] | "matte white plastic, soft rounded edges, no shine" |
| Cor | branco ou branco com detalhe [CJ] | "warm white, not glossy" |
| Estados | desligado → ligado (luz acesa embaixo, bichos aparecem no chão) | "off: plain white object; on: a soft glow under it and small light shapes appear on the floor" |
| Som | [CJ] em geral silencioso ou zumbido baixo de motor | "near silent, faint motor hum up close" |
| Luz própria | LED que projeta para baixo/para frente | "the light falls on the floor in a wide soft circle, not a beam into the air" |
| Ratinho de pelúcia | ~8 a 10 cm, cinza, rabo de cordão [CJ] | "small gray plush mouse, palm-sized, string tail" |
| Rótulo | sem marca | "blank surface, no readable text" |

## 3. Como se segura
- Uma mão, pegada por baixo, como quem pega uma caneca sem alça.
- A outra mão aperta o botão no topo ou atrás [CJ] ou segura o celular (melhor: celular no tripé, mãos livres, como na referência da capinha).
- Dono sentado no chão ou no sofá; o aparelho fica no chão ou numa mesinha baixa, apontado para o piso livre.
- Erros comuns: deixar na luz do dia (bichos somem); apontar para tapete estampado (some o contraste); deixar o fio onde o gato morde.

## 4. Sequência de uso real
| Passo | Ação física | Estado antes → depois | Tempo real |
|---|---|---|---|
| 1 | Baixa a luz da sala ou fecha a cortina | claro → penumbra | 2 s |
| 2 | Põe o aparelho no chão, de frente para o piso livre | na mão → no chão | 2 s |
| 3 | Aperta o botão | desligado → bichos de luz aparecem no chão | 1 s |
| 4 | O gato percebe, abaixa o corpo, olha fixo | deitado → agachado | 2 a 5 s |
| 5 | O gato dá o bote, olha embaixo da pata, tenta de novo | parado → caçando | 10 s a 10 min |
| 6 | Dono desliga e joga o ratinho de pelúcia na frente do gato | bichos de luz → ratinho real | 2 s |
| 7 | Gato agarra o ratinho com as patas da frente, chuta com as de trás ou leva na boca | caçando → capturou | 3 a 10 s |

## 5. Dor e efeito uau
- Dor visível (ângulo A): o gato procurando o ponto vermelho que sumiu, olhando para a parede vazia. (ângulo B): o dono no sofá, cara de cansado, gato miando e batendo no pé dele.
- **Efeito uau 1 (a caça):** chão vazio → ratinhos de luz correndo → o gato dá o bote, na mesma tomada.
- **Efeito uau 2 (a captura, só nosso):** a luz apaga, o ratinho de pelúcia cai na frente, o gato agarra e sai andando com ele na boca.
- Por que surpreende: a pessoa espera um ponto; vê vários bichos saindo do chão. E nunca viu o "final feliz" num anúncio de luz.
- Reação de quem testa: "sits back on her heels, covers her mouth, laughs once through her nose, glances at the camera".
- Filmagem real de referência: [CJ] pedir 3 a 10 s do aparelho projetando no chão, em ambiente escuro.

## 6. Física que a IA costuma errar aqui
- **Luz:** os bichos ficam no chão, achatados no piso, seguem a perspectiva do chão. Não flutuam, não brilham no ar, não fazem feixe de laser visível. Ficam por cima da pata do gato (a luz projeta sobre o pelo), não por baixo.
- **Escala dos bichos:** do tamanho de um rato pequeno (~5 a 8 cm), não do tamanho do gato. [CJ] confirmar.
- **Gato:** quatro patas, um rabo, anatomia certa no salto; patas da frente batem juntas no chão no bote; nunca atravessa o aparelho.
- **Aparelho:** um só, fica parado no mesmo lugar entre cortes; não cresce.
- **Ratinho de pelúcia:** um só, deforma levemente quando o gato morde, não muda de cor.
- **Movimento dos bichos:** velocidade real [CJ]; sem câmera lenta.

## 7. Referências para o Higgsfield
- Imagens do aparelho por ângulo (frente, lado, cima, na mão) e do ratinho: [CJ].
- Vídeo real do fornecedor (3 a 10 s) do efeito no chão: [CJ], vai como referência no Kling Omni.
- Gato: gerar um gato só (tigrado, comum de abrigo americano, não de raça) e manter o mesmo nos três roteiros.
- Não herdar das referências do fornecedor: marca, texto, tapete ou cenário chinês, música.

## 8. PRODUCT LOCK (rascunho; números fecham com a CJ)
```
PRODUCT LOCK: ONE small matte white pet projector, about the size of a coffee mug, never duplicates, same size and same spot on the floor in every shot.
Weight: light, set down with one hand. Material: matte plastic, no shine. Blank surface, no readable text.
Grip: held from below in one palm; the other hand presses the single button on top.
States only move forward: off -> on (soft glow, small light mice appear on the floor) -> off. Never flickers.
Light physics: the mice are flat light shapes lying on the floor, following the floor's perspective, about 2-3 inches long; they never float, never glow in the air, no visible beam. When the cat's paw covers one, the light falls on top of the paw.
Cat: ONE gray tabby, same markings in every shot, four legs, one tail, real cat anatomy when pouncing.
Plush: ONE small gray plush mouse with a string tail, palm-sized, squashes slightly when bitten.
Sound: near silent device; real sounds only: paws thumping on the floor, a soft chirp from the cat.
WOW BEAT: empty dim floor -> light mice run out -> the cat crouches, butt wiggles, pounces, looks under its paw. One continuous shot, real speed, no slow motion, matching reference video @[n].
CATCH BEAT: the light switches off, the plush mouse lands in front of the cat, the cat grabs it with both front paws and walks off with it in its mouth.
Reaction: she sits back, laughs once through her nose, glances at the lens. She does not claim she bought it or long-term results.
```

## 9. Histórico de vídeos
| id | versão do jeito certo | o que mudou | nota interna | taxa de gancho | custo por compra | resultado |
|---|---|---|---|---|---|---|
