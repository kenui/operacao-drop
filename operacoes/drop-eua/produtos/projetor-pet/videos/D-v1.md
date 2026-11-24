# Vídeo D v1 (cachorro, controle modelado), 24/11/2026

Aval do Kenui (24/11 04:50): gerar só o primeiro vídeo e mostrar antes dos outros três.
Produto ainda sem vídeo do fornecedor: o efeito (anéis laranja no chão com bichinhos saindo) foi descrito a partir dos anúncios reais da Biblioteca do Meta, sem usar imagem deles.

## Imagem inicial
- Modelo: nano_banana_2_1, 2k, 9:16, 2 variantes (4 créditos). Escolhida: job ae4030f4-0fa0-4830-b93a-e5427e651498.
- Prompt: POV do sofá, caixa kraft sem texto no colo, projetor branco em forma de pata na base cinza com botão azul, buldogue francês cinza de coleira vermelha cheirando; árvore de Natal, chuva na janela, luz mista. Bloco de realismo do JEITO-CERTO v1 (frame de iPhone, HDR off, blur na mão, horizonte torto, sem texto).

## Vídeo
- Modelo: kling3_0 pro, 15 s, som ligado, 9:16, start_image acima. Custo estimado 37,5 créditos. Job 85030d91-152d-4582-a9d0-1b762f9b4aa9.
- Prompt com linha do tempo: 0-3 tira da caixa com o cachorro cheirando; 3-5 põe no chão e aperta; 5-10 anéis e bichinhos, play bow, latido, pata no anel; 10-13 pernas no sofá com caneca; 13-15 joga a pelúcia de esquilo e o cachorro sacode. Som: chuva, patas, latido, apito; sem música, sem fala.
- Texto na tela e música entram na montagem (ffmpeg no sandbox do Higgsfield).

## Resultado (24/11)
- Vídeo bruto (Kling, 15 s, som nativo): https://d8j0ntlcm91z4.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/hf_20261124_045349_85030d91-152d-4582-a9d0-1b762f9b4aa9.mp4
- Música por IA (ElevenLabs Music v2.5, 16 s): https://d8j0ntlcm91z4.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/hf_20261124_045549_742c4dc2-5cfa-439a-bee1-7f5fdeff82a4.mp3
- **Montado com música:** https://d2ol7oe51mr4n9.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/a5c00329-d8ea-4f38-b236-bf5e94a8b83a.mp4
- **Montado só com som natural:** https://d2ol7oe51mr4n9.cloudfront.net/user_3LK6s47BLuXCkFvw3IIFOVnZEvT/84a746b1-4e12-4680-ab77-82c1564b6691.mp4
- Montagem (ffmpeg no sandbox do Higgsfield): textos em Montserrat ExtraBold 60 px, caixa preta 55%, y = 330 (zona segura): 0-3,3 s "if your dog gets zoomies / on rainy days, watch this"; 3,3-5 s "one tap"; 5-8,5 s "he found the critters"; 8,5-11,8 s "him: zoomies / me: couch"; 11,8-15 s "Comment "SQUEAK" / if your dog needs one". Música a 50% sob o som nativo a 160%, fade no fim.
- Custo: ~37,5 (vídeo) + ~4,7 (música) + 2 (imagem) créditos.

### Revisão honesta
- Bom: uma tomada de ponto de vista do sofá, caixa sem texto, aparelho único e no mesmo lugar, anéis laranja achatados no chão, cachorro com a mesma coleira vermelha, caneca e árvore de Natal, chuva na janela.
- Fraco: (1) os anéis aparecem vazios, sem bichinhos saindo; (2) o cachorro passa rápido e pequeno no fundo, a brincadeira com os anéis fica pouco clara; (3) o final com a pelúcia é rápido, ele não agarra e sacode com clareza; (4) tapete estampado tira contraste; (5) o aparelho parece um pouco maior que o real (base larga); (6) a sala está clara, contradiz "works best with the lights low".
- Ajustes para v2, se o Kenui pedir: piso de madeira sem tapete, abajur só, cachorro mais perto da câmera, bichinhos saindo dos anéis, final com 3 s de cachorro sacudindo a pelúcia.
