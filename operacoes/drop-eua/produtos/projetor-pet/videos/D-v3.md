# Vídeo D v3: cachorro, chuva, sem passeio (24/11/2026)

Refação do D-v2 depois do retorno do Kenui: pessoa não ultra realista, aparelho girando, luz que não parecia sair dele. Seguiu [regras-consistencia.md](../../../../../conhecimento/video-ia/regras-consistencia.md) do início ao fim.

## O que mudou em relação à v2
- **Aparelho com a forma real**: pata creme em pé numa base preta com lente na frente (visto nos anúncios da Biblioteca do Meta), encostado no rodapé, projetando para a frente e para baixo. PRODUCT LOCK v3 em [uso-no-video.md](../uso-no-video.md).
- **Pessoa ultra real**: imagem-base no Soul 2 com o bloco de pele do Riley (poros, brilho na testa, mechas soltas, moletom usado), usada como referência.
- **Seedance 2.5** (omni_reference), com rascunho 480p conferido antes de finalizar.
- **Um estado por clipe**: clipe A desligado até o clique; clipe B já ligado do início ao fim.

## Peças
| Peça | Modelo | Job |
|---|---|---|
| Pessoa base (p2) | Soul 2 | 30aad9b4-8f2e-4a99-964e-78ccc77b2a1b |
| Produto base (d2) | Nano Banana 2.1 | 978411ea-aa4d-4011-8163-849e847b4e6d |
| Cachorro base | (do v2) | 32db3d8f-e79f-4c19-9484-dea552895633 |
| Quadro inicial A (escolhido kA1) | Nano Banana 2.1 | 80f9dbb3-dd65-424c-bd1e-eb0509a03d26 |
| Quadro inicial B (escolhido kB1) | Nano Banana 2.1 | cd0b6627-0b23-492c-87f6-8309fd5d9a1d |
| Clipe A rascunho 480p | Seedance 2.5 | 8bbc09bb-7291-4aea-8286-12c17e5d63c1 |
| Clipe B rascunho 480p | Seedance 2.5 | 7603fbfb-d480-4def-85c9-2c3b0d329b83 |
| Clipe A final | Seedance 2.5 (saiu 1080×1920) | 8d916901-b51e-4911-b6a1-1fac7f0e1596 |
| Clipe B final | Seedance 2.5 (saiu 1080×1920) | b2fe3da0-1851-413d-a409-0d7267db7883 |

Prompts completos: modelo C do guia + AVATAR, ANIMAL e PRODUCT LOCK (guardados no sandbox Higgsfield `pet3/clips.json`, iguais aos parâmetros dos jobs acima).

## Montagem (19,3 s, 9:16)
- 0 a 9,29 s: clipe A, cortado no exato quadro do clique (o modelo acendeu a pata inteira aos 9,33 s; o corte tira isso, e a troca de estado acontece no corte).
- 9,29 a 19,3 s: clipe B inteiro, tomada contínua no efeito, depois a mão joga a pelúcia.
- Texto na zona segura (y=285 em 1080×1920): "if your dog gets bored on rainy days..." / "he's been staring at me for an hour" / "so I tried this paw projector" / "he's never chased the floor this hard" / "(works best with the lights a bit low)" / "then the real squirrel" / "comment SQUEAK if your dog needs one".
- Duas versões: som natural, e música por IA (a mesma da v2, ElevenLabs) entrando baixa no corte para o ligado.
- Links: com música https://d2ol7oe51mr4n9.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/70381f22-d32c-4473-918c-65716890ad16.mp4 · natural https://d2ol7oe51mr4n9.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/78b7bb57-00b4-49fb-a613-5bd7d5476111.mp4

## Conferência quadro a quadro (2 por segundo + transcrição)
- Pessoa: mesmo rosto, coque e roupa nos 9 s; pele com poros e brilho; boca mexe só na fala. Fala transcrita igual ao roteiro. OK.
- Cachorro: um só, cinza, mancha branca, coleira vermelha; 4 patas; sem esticar. Entre 4,5 e 6 s do clipe B ele passa perto da câmera com algum borrão, mas o corpo continua legível. Rabo um pouco mais comprido que o de um buldogue francês real (ponto fraco pequeno).
- Produto: no mesmo lugar e orientação em todos os quadros, não gira, não duplica. Desligado no clipe A (chão liso). Ligado no B com lente azul, mancha azul no chão na frente da base e anéis só na frente dele; a luz laranja cai sobre as patas. OK.
- Ponto fraco: a pelúcia fica alaranjada sob a luz dos anéis (coerente com a física, mas parece outra cor que na mão).

## Custo
Imagens ~10 créditos (base e quadros), 2 rascunhos 60, 2 finais 140. Total ~210.

## Resultado
Aguardando avaliação do Kenui.
