# Áudio realista em vídeo de IA (v1, 24/11/2026)

Pedido do Kenui (24/11), depois de aprovar o V1: "quero deixar o áudio muito mais realista, faça uma pesquisa sobre isso, parece muito de IA".

Legenda: **[medido]** medimos nos nossos arquivos / **[mercado]** blogs de fornecedores e de áudio, sem teste independente / **[nosso]** leitura da casa, vira teste.
Limite deste guia: quem escreveu não ouve o arquivo, só mede. O juiz do resultado é o ouvido do Kenui.

## 1. O que denuncia o áudio de IA
| Sinal | Como aparece | Fonte |
|---|---|---|
| Silêncio digital entre as falas | nos clipes do Topview T1 o fundo cai a -52 até -65 dBFS entre as frases; celular numa sala nunca fica mudo, sempre há chiado e ar | [medido] nos 3 clipes; [mercado] Respeecher, iZotope |
| O ambiente muda a cada corte | cada clipe gerado tem outro "ar" de sala; na emenda o ouvido percebe | [medido] |
| Voz perto demais e limpa demais | soa locução de estúdio, não alguém a 1 m do celular; quase mono | [medido] lateral 8 a 19 dB abaixo do centro |
| Agudo cortado em 16 kHz e normalização que "bombeia" | nossa montagem antiga usava normalização dinâmica, que sobe o fundo nas pausas | [medido] |
| Prosódia regular | mesma velocidade, sem hesitar, sem respirar, sem rir no meio da palavra, sem falar por cima do outro | [mercado] Respeecher, Resemble, Soundverse |
| Sons sem causa | pata, unha e latido que não batem com o que se vê | [nosso] |
| Música por cima de fala | soa propaganda e destaca a voz sintética | manual de retenção §6 |

## 2. Três camadas de correção, da mais barata à mais cara
### Camada 1: mixagem de celular (zero crédito, já aplicada)
Ferramenta: `ferramentas/audio-celular/audio_celular.py ENTRADA.mp4 SAIDA.mp4`.
| Passo | Valor | Por quê |
|---|---|---|
| Piso de sala contínuo do começo ao fim | ruído rosa filtrado (120 Hz a 5 kHz) a cerca de -50 dBFS | tira o silêncio digital e esconde a troca de ambiente nos cortes; blogs sugerem de -40 a -60 dB, começamos no meio [mercado] |
| Curva de microfone de celular | corta abaixo de 90 Hz e acima de 14 kHz, +2 dB em 3,5 kHz, -1,5 dB em 250 Hz | celular não capta grave e realça a presença [mercado: iZotope, guias de EQ de voz] |
| Reflexo curto de sala igual em tudo | ecos de 17, 29 e 43 ms, 10% do volume, só entre 250 Hz e 6 kHz | cola clipes diferentes no mesmo cômodo; reverb filtrado não embola o grave [mercado] |
| Compressão leve + limitador | 2,5:1, limite em -3 dB | imita o controle automático de ganho do celular |
| Volume final por ganho linear | -16 LUFS | sem normalização dinâmica, o fundo não sobe e desce |
| Emenda com fade de 30 ms em cada trecho | | tira o estalo do corte seco |
| Música mais baixa | 22% (era 30%), só onde não há fala | a voz é a prova |

Não é filtro de "piorar": não tira qualidade da voz, só põe a voz dentro de uma sala. A textura da imagem continua vindo do prompt.

### Camada 2: pedir o som certo no prompt (zero crédito a mais, vale para todo clipe novo)
Bloco VOICE & SOUND para colar no prompt de vídeo, adaptando as falas:
```
VOICE: a real woman in her late 20s, relaxed low-effort American voice, slightly husky, not a presenter. She is about 1 m from the phone, a little off-mic, the room is audible in her voice. Uneven pace: she starts a sentence, half laughs through one word, takes a small audible breath before the last line, trails off at the end. One false start ("it's, it's this little..."). The man behind the camera is louder and closer to the mic, and they overlap for a moment.
SOUND: one continuous room tone for the whole clip (quiet apartment hum, faint rain on the window), never silent. Every sound has a visible cause: nails and paws on the wood floor when he moves, the plastic base knocking on the floor, fabric rustle when she leans, the phone being gripped. One short bark at most. No music, no voice-over, no sound effects, no studio voice.
```
Por quê: guias de prompt de áudio pedem uma fala clara, um som principal e uma cama de ambiente, descritos junto com a cena, com a voz acima do fundo [mercado: guias de Veo 3.1 do Google, Leonardo, Replicate]. Hesitação, respiração e sobreposição são o que os guias de voz apontam como marca de fala humana [mercado]; ninguém mediu isso em anúncio, então é teste [nosso].
Regras: falas curtas (até 3 por clipe), com contrações e uma hesitação escrita; nunca "locutora"; a pessoa que filma fala também, mais perto do microfone.

### Camada 3: trocar a voz (custa crédito ou trabalho; só com aval do Kenui)
| Opção | Como | O que ganha | Custo / risco |
|---|---|---|---|
| A. Voz para voz por cima do áudio gerado | um trocador de voz (ElevenLabs Voice Changer ou equivalente) converte a fala do clipe para uma voz mais natural, mantendo tempo e entonação | mantém a boca em sincronia, porque o tempo não muda [mercado: Wireflow, MindStudio] | orçar na hora; o timbre melhora, a prosódia continua a da IA |
| B. Pessoa real grava as falas no celular | alguém grava as 3 falas num cômodo, e o vídeo é gerado ou sincronizado em cima desse áudio | é a voz mais real possível: respiração e ritmo humanos de verdade [mercado: Respeecher] | precisa de alguém gravar; pede passada de lip sync (crédito) ou modelo que aceite áudio de entrada |
| C. Pessoa real grava + voz para voz | a atuação humana com o timbre da personagem | junta as duas | o mais trabalhoso |
| D. Sons reais gravados | patas no piso, respiração do cachorro, apito da pelúcia gravados de verdade, por cima | som com causa real | precisa de gravação; sem banco de sons pago |

Recomendação [nosso]: camada 1 em tudo, camada 2 em todo clipe novo, e testar a opção A num clipe só antes de decidir. A opção B é a que mais muda o resultado se houver alguém para gravar.

## 3. Conferência antes de mostrar
- [ ] Fundo nunca abaixo de cerca de -50 dBFS em nenhum trecho (medir em janelas de 200 ms).
- [ ] Mesmo ar de sala antes e depois de cada corte.
- [ ] Nenhum som sem causa visível; nenhuma fala que a transcrição não confirme.
- [ ] Pico abaixo de 0 dB; volume perto de -16 LUFS.
- [ ] Ouvir em alto-falante de celular e em fone (fone exagera chiado e "s"; celular mostra voz fina) [mercado].
- [ ] Guardar a versão de antes para comparar.

## 4. Medição dos nossos vídeos (janelas de 200 ms)
| Vídeo | Fundo mais baixo, antes | depois | Pico, antes | depois |
|---|---|---|---|---|
| V1 chuva | -49,1 dBFS | -48,2 | +1,5 dB (estourava) | -0,1 |
| V4 fake | -52,0 dBFS | -47,4 | +1,5 dB (estourava) | 0,0 |
Nos clipes brutos, antes de qualquer montagem, o fundo caía a -52,3 (dor), -59,6 (prova) e -64,5 dBFS (V4).

## Fontes [mercado]
- https://www.respeecher.com/blog/how-to-make-ai-voice-sound-human
- https://www.resemble.ai/resources/make-ai-voice-sound-human-like
- https://www.soundverse.ai/blog/article/what-makes-ai-voice-sound-robotic-vs-authentic
- https://www.izotope.com/en/learn/better-voice-recordings
- https://cleanvoice.ai/blog/how-to-eq-reverb/
- https://www.wireflow.ai/blog/kling-4-voice-fix
- https://www.mindstudio.ai/blog/elevenlabs-voice-cloning-replace-ai-generated-voices
- https://invideo.io/blog/ai-video-models-with-audio/
- https://cloud.google.com/blog/products/ai-machine-learning/ultimate-prompting-guide-for-veo-3-1
- https://www.leonardo.ai/news/how-to-prompt-audio-for-veo-3
- https://replicate.com/blog/using-and-prompting-veo-3
