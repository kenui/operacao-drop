# Prova com esquilo e voz nova + vídeo de controle (24/11/2026)

Por quê: a CJ cotou a versão do aparelho que projeta um esquilo, e a campanha no Meta só sobe com vídeos que mostrem o que vamos entregar. Os clipes anteriores mostravam um coelho. O Kenui escolheu usar o último teste grátis nisso ("Usa esse bônus para criar mais um vídeo") e dispensou a troca de voz paga ("não ficou tão mal").

| Peça | Modelo | Parâmetros | Custo real |
|---|---|---|---|
| Quadro de partida (`prova-esquilo/quadro-prova-esquilo-4k.png`): o quadro aprovado da prova com o coelho trocado por um esquilo plano de luz, todo o resto igual | Nano Banana Pro | 9:16, 4K, referência img_7 | 1,4 crédito (img_9) |
| Clipe (`prova-esquilo/clipe-prova-esquilo.mp4`) | Topview T1 | 15 s, 720p, áudio nativo, com o bloco VOICE & SOUND de [audio-realista.md](../../../../../conhecimento/video-ia/audio-realista.md) | 0 (último teste grátis, vid_5) |

Saldo: 104,4 → 103 créditos (7 gastos do teto de 10). Cota: nenhum vídeo grátis, 8 imagens grátis.

## Conferência (1 quadro por segundo + transcrição + medição)
- Esquilo de luz, plano no chão, em todos os quadros; nenhum coelho. Aparelho parado, anéis planos.
- Cachorro brinca os 15 s (bote com as duas patas, corre de anel em anel), boca vazia, olho no chão.
- Falas: "Babe. Babe, look at him. He's actually hunting it." (2,6 a 5,2 s) / "No way. He's obsessed." (5,6 a 7 s) / "Honestly? If your dog gets bored inside... get this." (10,8 a 15 s). A repetição e a pausa pedidas saíram.
- Áudio: o fundo mais baixo do clipe bruto ficou em -45,5 dBFS (nos clipes anteriores caía a -52 até -65). O bloco de som no prompt tirou o silêncio digital antes de qualquer mixagem.

## O que foi montado com ele
| Vídeo | Arquivo | Duração | Gancho na tela |
|---|---|---|---|
| Controle (demonstração pura, uma tomada) | `prova-esquilo/Controle-esquilo-{sem,com}-musica.mp4`, script `montar_controle.py` | 14,6 s | if your dog gets bored inside, watch this. |
| V1 chuva, agora com esquilo | `v1-chuva/V1-chuva-{sem,com}-musica.mp4` | 21,5 s | no walk today. he's been staring at me since 4. |
| V4 "achei que era fake", agora com esquilo | `v4-fake/V4-fake-{sem,com}-musica.mp4` | 15,2 s | ok I thought these videos were fake. |

Todos passam pela mixagem de celular. As versões com coelho do V1 e do V4 ficaram só como `*-audio-antes.mp4`.

## Pendências
- O desenho do esquilo é o nosso melhor palpite a partir dos anúncios reais; o vídeo real da CJ confirma o formato exato.
- V4: continuidade (aparelho no meio da sala e depois na parede) segue como ponto fraco.
- Referência modelada do controle: formato do controle da Enjoyaitlife (bicho perseguindo a luz em tomada contínua, gancho de identidade); só a estrutura.

## Resultado
Aprovado pelo Kenui em 24/11 (versão com esquilo): "Ok, ficou muito bom".
