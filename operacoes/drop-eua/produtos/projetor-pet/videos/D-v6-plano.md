# Vídeo D v6: plano para aprovação (24/11/2026)

Prompts escritos pelo Fable 5.2 e revisados: [D-v6-prompts.md](D-v6-prompts.md). Roteiro, falas e DECISÃO CRIATIVA iguais aos da v5 ([D-v5-plano.md](D-v5-plano.md)), porque as falas saíram certas na v5b.

## Os três problemas da v5b e o que muda, pela causa
| Problema (Kenui) | Causa | O que a v6 faz |
|---|---|---|
| O cachorro morde algo que não existe | O quadro inicial da v5b (tirado do vídeo anterior) já parece ter algo claro junto à boca dele. O Kling continua o que vê no primeiro quadro. Na v5, o prompt também citava "brinquedo de corda" e "cabo" no chão. | Quadro inicial novo, com a boca vazia e visível e o chão sem nenhum objeto. Trava positiva no prompt ("a boca fica vazia o vídeo todo; ele nunca morde, pega ou carrega nada; no chão só há luz"). Tirei o latido (que abre a boca) e não cito brinquedo nem petisco (citar pode trazer). |
| A boca dela não sincroniza com a fala | O Kling gera voz e imagem juntos, mas a boca não acompanha bem, e o rosto dela estava de perfil e na sombra. | (1) Quadro inicial com o rosto três-quartos para a câmera e iluminado pelo abajur; (2) trava de LIP SYNC no prompt; (3) **etapa própria de lip sync** (Sync Lipsync 3) sobre o vídeo do Kling, usando o próprio áudio, para acertar a boca palavra por palavra. |
| A pele não parece real | O quadro inicial da v5b era um quadro do próprio vídeo, já suavizado pela compressão, e o vídeo herda a pele do quadro inicial. | Quadro inicial novo em 2k no Nano Banana, com o bloco de pele (poros, tom desigual, vermelhidão no nariz, brilho na testa, linhas sob os olhos, lábio seco, fios soltos, sem filtro de beleza) e o visual de foto de celular. A pele também está escrita no prompt do vídeo. Sem filtro na montagem. |

## Etapas e custo exato (cotado no Higgsfield, 24/11)
| Etapa | Modelo | Créditos |
|---|---|---|
| Quadro inicial, 2 opções em 2k | Nano Banana 2.1 | 4 (2 cada) |
| Vídeo 15 s, pro, com som, 9:16 | Kling 3.0 | 37,5 |
| Lip sync no vídeo inteiro | Sync Lipsync 3 | cerca de 54 (cotado 50,55 para 14 s; cobra o vídeo inteiro mesmo marcando só os trechos de fala) |
| **Total** | | **cerca de 96** |
| Com uma refação do vídeo, se precisar | | cerca de 134 (+37,5) |

Saldo atual: 0,76. Ordem: gero o quadro inicial e confiro antes do vídeo; confiro o vídeo (boca vazia, olhar no coelho, falas) antes do lip sync. Se o vídeo sair com objeto na boca, não gasto no lip sync.

## Riscos
- O lip sync pode deixar a boca com aparência artificial em close; confiro quadro a quadro antes de entregar.
- Se o objeto voltar mesmo com o quadro limpo, o próximo passo é usar quadro final (end_image) também limpo.
