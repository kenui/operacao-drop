# Vídeo V1: dia de chuva, sem passeio (24/11/2026)

Roteiro e DECISÃO CRIATIVA: [08-videos-que-vendem.md](../08-videos-que-vendem.md), seção V1. Primeiro vídeo com dor na abertura.
Canvas do Topview: https://www.topview.ai/canvas/19d5e1826be14a49ade7a6f8a27770b4

| Peça | Modelo | Parâmetros | Custo |
|---|---|---|---|
| Quadro de partida (edição do quadro aprovado: aparelho desligado, piso vazio, cachorro encarando a câmera, chuva na janela) | GPT Image 2.5 Flare | 9:16, 1K, medium | 0 (imagem grátis) |
| Clipe da dor e da virada | Topview T1, imagem para vídeo | 15 s, 720p, áudio nativo | 0 (teste grátis) |
| Prova e recompensa | reaproveitadas de [D-v6-topview.md](D-v6-topview.md) | | 0 |
| Música | a mesma faixa por IA do D-v1 (ElevenLabs, já paga) | 16 s, repetida | 0 |

Saldo antes e depois: 110 créditos. Cota restante: 3 vídeos T1 e 8 imagens.

## Montagem (20,9 s, `v1-chuva/montar_v1.py`, ffmpeg)
| Tempo | Origem | Fala | Texto |
|---|---|---|---|
| 0 a 6,8 s | clipe da dor, 0,9 a 7,7 s | "I know buddy, it's pouring." / "Third rainy day in a row and he is so over it." | no walk today. he's been staring at me since 4. → rainy days = bored dog |
| 6,8 a 9,1 s | clipe da dor, 11,9 a 14,2 s (ela estica a mão para o aparelho desligado) | "Okay, let's try this thing." | so I tried this |
| 9,1 a 15,7 s | vídeo aprovado, 0 a 6,6 s | "Babe, look. He's actually hunting it." / "No way." | works best with the lights low |
| 15,7 a 20,9 s | vídeo aprovado, 9,8 a 15 s | "Honestly, if your dog gets bored, get this." | rainy day, no walk, zero guilt → tap to see the kit |

Duas versões: sem música e com música a 30%, que abaixa sozinha quando há fala.

## Conferência do clipe novo (1 quadro por segundo + transcrição)
- Falas: as 3 exatas ("I know buddy, it's pouring." 1,5 a 3,1 s; "Third rainy day in a row and he is so over it." 4,5 a 7,4 s; "Okay, let's try this thing." 12,2 a 14 s).
- Produto: desligado os 15 s, sem luz nem forma no chão, parado. Ela estica a mão por cima dele no fim, sem ligar.
- Cachorro: senta encarando a câmera, deita com o queixo no chão aos 7 s, olha para cima. Boca vazia.
- A conferir assistindo: sincronia da boca, pele, e a emenda entre o clipe novo e a prova (o cachorro passa de deitado na frente para de pé nos anéis; a sala e a luz batem).

## Pontos fracos conhecidos
- Não há o clique filmado de perto: a virada é a mão indo ao aparelho e o corte.
- O bicho projetado é um coelho; a CJ cotou a versão com esquilo.
- Quadro de partida em 1K (o da cota grátis). O vídeo sai em 720p, então o ganho de um quadro em 4K seria pequeno; vale testar com créditos se a pele incomodar.

## Resultado
Aguardando avaliação do Kenui.
