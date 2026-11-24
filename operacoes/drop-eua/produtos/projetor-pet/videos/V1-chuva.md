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

## Versão 2 (24/11, depois da avaliação do Kenui)
Avaliação dele da versão 1: "O com música ficou sem som no final, o vídeo ficou bom, só o cachorro sem brincar e o avatar ainda não tá muito real". E perguntou se havia ficha do avatar de perto, em vários ângulos e em alta qualidade. Não havia.

O que mudou e por quê:
| Mudança | Por quê | Custo real |
|---|---|---|
| Fichas 4K da mulher e do cachorro ([fichas/README.md](../fichas/README.md)) | identidade fixa e pele real vêm da imagem, não do vídeo | 2,8 créditos |
| Quadro de partida da prova em 4K (3072×5504) com as fichas: cachorro já em posição de brincar, mulher rindo, rosto maior | o modelo anima o que já está no quadro; partir do cachorro brincando puxa a brincadeira | 1,4 crédito |
| Clipe novo da prova (`clipe-prova-b.mp4`, T1, 15 s, 720p, áudio nativo) com o cachorro descrito segundo a segundo | na v1 a prova vinha do D-v6, onde o cachorro ficava quase parado | 0 (teste grátis) |
| Música mixada em dois passos | o mix num passo só cortava o som aos 18 s | 0 |

Saldo: 110 → 105,8 créditos (4,2 gastos, dentro do teto de 10 aprovado por ele). Cota restante: 2 vídeos T1 e 8 imagens.

Montagem v2 (20,4 s): dor 0 a 6,8 s e virada 6,8 a 9,1 s iguais à v1; prova 9,1 a 17,1 s = clipe novo de 1,5 a 9,5 s, tomada contínua ("Babe, look at him, he's actually hunting it." / "No way, he's obsessed."); recompensa 17,1 a 20,4 s = clipe novo de 11,7 a 15 s ("Honestly, if your dog gets bored inside, get this.").

Conferência do clipe novo (1 quadro por segundo + transcrição): as 3 falas exatas; o cachorro brinca os 15 s (posição de brincar, bote com as duas patas, corre de anel em anel, olho no chão); boca vazia; aparelho parado e ligado; anéis e coelho planos no chão; a luz cai nas costas do cachorro quando ele passa na frente do feixe, como num projetor real. Som da versão com música conferido até o fim (20,4 s).

Referência modelada: sem referência nova nesta versão; a estrutura continua a do molde de 20 s e a prova segue o formato do controle da Enjoyaitlife (bicho perseguindo a luz em tomada contínua).

Pontos fracos que ficam: a parte da dor ainda usa o quadro de 1K sem as fichas, então o rosto dela nos primeiros 9 s é o da v1; se ainda parecer pouco real, refazer o quadro da dor em 4K (1,4 crédito) e o clipe (1 teste grátis).

## Áudio (24/11, depois do pedido do Kenui: "parece muito de IA")
Montagem refeita com a mixagem de celular ([audio-realista.md](../../../../../conhecimento/video-ia/audio-realista.md), camada 1): piso de sala contínuo, curva de microfone, reflexo curto igual em todos os trechos, ganho linear, música a 22%. Custo zero. A versão anterior ficou guardada como `*-audio-antes.mp4` para comparar. A voz em si continua a gerada pelo T1; trocar a voz é a camada 3 e pede aval.

## Resultado
Versão 2 aprovada pelo Kenui em 24/11: "O vídeo ficou muito bom, parabéns".
