# V5 modelado na referência escalada (25/11/2026)

Por quê: o Kenui perguntou por que não havia um criativo com a estrutura do anúncio da Enjoyaitlife, que ele escolheu como referência, e aprovou a geração ("Pode sim") por cerca de 17 créditos. Roteiro e DECISÃO CRIATIVA em [08-videos-que-vendem.md](../08-videos-que-vendem.md), V5.

| Peça | Modelo | Parâmetros | Custo real |
|---|---|---|---|
| Quadro da abertura (`v5-modelado/quadro-abertura-4k.png`): mesma sala, caixa parda, aparelho desligado, piso sem luz, cachorro farejando | Nano Banana Pro | 9:16, 4K, referências img_9 e img_6 | 1,43 crédito (img_10) |
| Quadro do final (`v5-modelado/quadro-final-4k.png`): por trás do ombro dela, encostada no sofá com a caneca | Nano Banana Pro | 9:16, 4K, referências img_9 e img_5 | 1,43 crédito (img_11) |
| Clipe da abertura (`v5-modelado/clipe-abertura.mp4`) | Topview T1 | 8 s, 720p, áudio nativo | 8 créditos (vid_6) |
| Clipe do final (`v5-modelado/clipe-final.mp4`) | Topview T1 | 6 s, 720p, áudio nativo | 6 créditos (vid_7) |
| Miolo | trecho aprovado de `prova-esquilo/clipe-prova-esquilo.mp4` (1,5 a 7,2 s) | | 0 |

Saldo: 103 → 86,14 créditos. Gasto real: 16,86 (cotado: cerca de 17).

## Conferência (quadros + transcrição)
- Abertura: aparelho desligado e piso vazio até 5,1 s; a luz só aparece depois de ela apertar o botão. A montagem corta em 5,05 s, antes da luz, porque o desenho que o T1 projetou depois do clique (um esquilo grande em anéis concêntricos) não bate com o da prova.
- Fala da abertura: "Hey, hey, wait." (1,5 a 2,7 s). Cachorro fareja a caixa e o aparelho, boca vazia.
- Final: ela só assiste, toma um gole; cachorro brinca sozinho, boca vazia, olho no chão; aparelho parado. Sem fala (a transcrição automática inventou palavras em cima dos baques das patas; conferir de ouvido).
- Diferença para o roteiro: a abertura saiu filmada de frente (pelo parceiro), não em ponto de vista das mãos. Mantive porque casa com a câmera da prova e nos afasta ainda mais da cena da referência.

## Montagem
`v5-modelado/montar_v5.py`: abertura 0,3 a 5,05 s, prova 1,5 a 7,2 s, final 0,2 a 4,2 s. Total 14,5 s. Mixagem de celular, -16 LUFS. Arquivos `V5-modelado-{sem,com}-musica.mp4`.

Textos: `dog moms, do NOT open this / in front of him..` (0 a 4,75 s), `lights low. that's it.` (até 10,45 s), `comment "CATCH" / if your dog needs this` (até o fim).

## Pontos fracos
- Na abertura o cabelo dela aparece preso para trás, sem o coque alto da prova, e o aparelho parece um pouco maior.
- O aparelho muda de lugar no corte (meio da parede na abertura, canto esquerdo na prova).
- CTA de comentário pede que alguém responda os comentários com o link.

## Resultado
(preencher com a avaliação do Kenui e o custo por compra)
