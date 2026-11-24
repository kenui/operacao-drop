# Vídeo V4: "achei que era fake" (24/11/2026)

Roteiro e DECISÃO CRIATIVA: [08-videos-que-vendem.md](../08-videos-que-vendem.md), seção V4. Objeção nº 1 ("Is this legit?"), para quem já viu o produto.
Canvas do Topview: id `19d5e1826be14a49ade7a6f8a27770b4`.

| Peça | Modelo | Parâmetros | Custo real |
|---|---|---|---|
| Quadro de partida (ela ajoelhada segurando o aparelho desligado para a câmera, caneca no chão para escala, piso vazio, cachorro ao lado) feito com as [fichas](../fichas/README.md) | Nano Banana Pro | 9:16, 4K (3072×5504), referências img_7 + img_5 + img_6 | 1,4 crédito (img_8) |
| Clipe da objeção (`v4-fake/clipe-v4-a.mp4`) | Topview T1, imagem para vídeo | 15 s, 720p, áudio nativo | 0 (teste grátis, vid_4) |
| Prova e recompensa | `v1-chuva/clipe-prova-b.mp4`, 5,8 a 15 s, tomada contínua | | 0 (reaproveitada) |
| Música | faixa por IA do D-v1 | repetida | 0 |

Saldo: 105,8 → 104,4 créditos (total gasto em fichas e quadros: 5,6 do teto de 10). Cota restante: 1 vídeo T1 e 8 imagens.

## Montagem (16,7 s, `v4-fake/montar_v4.py`)
| Tempo | Origem | Fala | Texto |
|---|---|---|---|
| 0 a 2,3 s | clipe novo, 1,2 a 3,5 s: ela mostra o aparelho desligado | "Okay, I honestly thought these were fake." | ok I thought these videos were fake. |
| 2,3 a 7,5 s | clipe novo, 4,7 a 9,9 s: põe no chão ao lado da caneca, cachorro fareja, piso vazio, o interruptor apaga a luz | "It's this little paw thing. See? Nothing on the floor yet." | nothing on the floor yet |
| 7,5 a 13,4 s | prova, 5,8 a 11,7 s: cachorro caçando o coelho de luz | "You do have to turn the lights down." (voz dela por cima) | works best with the lights low |
| 13,4 a 16,7 s | prova, 11,7 a 15 s | "Honestly, if your dog gets bored inside, get this." | 30-day refund if he just stares at it → tap to see the kit |

## Conferência (1 quadro por segundo + transcrição)
- Aparelho desligado os 15 s do clipe novo, lente escura, piso sem luz nenhuma. Forma e cor iguais às da prova.
- Falas exatas. Cachorro de boca vazia, fareja o aparelho.
- A prova é uma tomada só de 9,2 s.

## Mudanças em relação ao roteiro, e por quê
- **Tirei o texto "no edits. one take."** O vídeo é gerado por IA; afirmar que não tem edição seria enganoso. Ficou o limite honesto.
- **A terceira fala entra por cima da prova.** No clipe novo a sala fica quase preta depois do interruptor (o abajur também apaga), e a prova tem o abajur aceso. Só 0,7 s do escuro ficou, como transição.
- **Sem dedo no botão em close:** a virada é o interruptor e o corte.

## Pontos fracos conhecidos
- Continuidade: ela põe o aparelho no meio da sala e, na prova, ele está encostado na parede. Quem procura defeito pode notar; num ângulo de "é fake?" isso pesa. Correção possível: regerar o clipe com o aparelho posto junto à parede e só a luz do teto apagando (1 teste grátis).
- Coelho x esquilo: a CJ cotou a versão esquilo. Nenhum vídeo com coelho vai ao ar antes de fechar isso com o vídeo real da CJ.
- A garantia de 30 dias no texto precisa continuar igual à da página.
- Referência modelada: sem referência; roteiro de persona (objeção nº 1, 03-persona.md §5 e §6).

## Áudio (24/11, depois do pedido do Kenui: "parece muito de IA")
Montagem refeita com a mixagem de celular ([audio-realista.md](../../../../../conhecimento/video-ia/audio-realista.md), camada 1): piso de sala contínuo, curva de microfone, reflexo curto igual em todos os trechos, ganho linear, música a 22%. Custo zero. A versão anterior ficou guardada como `*-audio-antes.mp4` para comparar. A voz em si continua a gerada pelo T1; trocar a voz é a camada 3 e pede aval.

## Esquilo (24/11)
Remontado com a prova nova, que mostra o esquilo da versão da CJ e usa o bloco novo de voz: [prova-esquilo.md](prova-esquilo.md). Duração agora: 15,2 s. A parte anterior à prova não mudou.

## Resultado
Aguardando avaliação do Kenui.
