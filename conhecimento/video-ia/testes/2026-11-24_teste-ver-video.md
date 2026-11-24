# Teste da ferramenta ver-video (24/11/2026)

Pedido do Kenui (24/11): um jeito aberto, do GitHub, de a operação entender vídeos. Ferramenta: `ferramentas/ver-video/ver_video.py` (ffmpeg, PySceneDetect, faster-whisper). Ambiente: "operacao-drop (vídeo)". Sem gasto de créditos.

## Rede
| Endereço | Resultado |
|---|---|
| d2ol7oe51mr4n9.cloudfront.net e d8j0ntlcm91z4.cloudfront.net | liberados, D-v1 e D-v2 baixados |
| pypi.org | liberado, dependências instaladas |
| huggingface.co | liberado |
| us.aws.cdn.hf.co, cas-bridge.xethub.hf.co, cdn-lfs.hf.co | **bloqueados**: o modelo de transcrição não baixa |

Conclusão: cenas, folhas, recortes e mapa do som funcionam. A transcrição continua pendente até liberar os três endereços de arquivo do Hugging Face no ambiente.

## Vídeos rodados
| Vídeo | Duração | Cenas detectadas | Transcrição |
|---|---|---|---|
| Capinha de glitter (upload do Kenui, 23/11) | 15,1 s | 6 (0 / 1,5 / 3,2 / 6,8 / 9,5 / 11,9 s) | pendente (rede) |
| D-v1 som natural (84a746b1) | 15,0 s | 1 (tomada única) | pendente (rede) |
| D-v2 som natural (656745b0) | 20,2 s | 4 (cortes de 5 s certinhos) | pendente (rede) |

A detecção de cortes acertou os 4 cortes do D-v2 e não inventou corte no plano único do D-v1.

## A checagem do método (cenas-de-referencia.md, seção 6) teria pegado os erros?
**D-v1, luz no chão antes de ligar: sim, só com a folha comum.** Aos 4,0 s os anéis laranja já aparecem fracos no tapete, com o aparelho ainda na mão e sem clique; o dedo aperta por volta de 7,5 s e só aí eles acendem forte. A regra "o produto muda de estado fora da ordem" reprova o vídeo. Prova: [D-v1_4a12s_luz-antes-do-clique.jpg](2026-11-24_ver-video/D-v1_4a12s_luz-antes-do-clique.jpg).

**D-v2, aparelho girando: não com a folha comum, sim com o recorte.** Na folha de 2 quadros por segundo o aparelho tem uns 40 px e parece parado. No recorte ampliado a 3 quadros por segundo do corte 4 (15 a 20 s), os dedos da pata mudam de direção a cada quadro: ele gira sozinho na base. No corte 3 o recorte mostra só uma oscilação leve. A forma também reprova pela tabela de verdade: pata deitada numa base cinza, quando o real é pata em pé. Prova: [D-v2_corte4_produto-girando.jpg](2026-11-24_ver-video/D-v2_corte4_produto-girando.jpg).

Bônus: a ficha do D-v2 dizia "checagem de estados: passou nos 4 cortes". Uma checagem sem quadro citado deixou passar o giro.

## O que mudou por causa do teste
1. `ver_video.py` ganhou `--recorte INICIO-FIM:X,Y,LARGURA,ALTURA`: faixa do produto ampliado a 3 quadros por segundo, com campos forma / se mexe / luz no relatório.
2. Seção 6 do método: conferir com recorte em cada corte onde o produto aparece; "passou" só vale com tempo e quadro citados; forma diferente do real reprova.
3. A mensagem de erro e o README nomeiam os três endereços do Hugging Face que faltam.
4. Instalação corrigida para `pip install scenedetect opencv-python-headless faster-whisper yt-dlp` (o extra `[opencv-headless]` não existe na versão 0.7).
