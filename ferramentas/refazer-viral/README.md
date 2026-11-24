# refazer-viral

Transforma a análise de um vídeo viral (de `ferramentas/ver-video`, arquivo `analise.json`) em um molde de estrutura e ritmo: cena a cena com tempo, função provável, palavras por segundo e quadro de referência, mais colunas vazias para a NOSSA cena.

**Por quê:** um viral já provou gancho, ritmo e ordem. Copiamos só isso. Rosto, marca, áudio, falas e trechos de terceiros nunca entram. Quando vale a pena e quando não: `conhecimento/video-ia/arsenal.md`.

## Usar
```
python3 ../ver-video/ver_video.py VIDEO.mp4 --saida PASTA
python3 molde_viral.py PASTA --produto "projetor pet"
```
Saída: `PASTA/molde.md` e `PASTA/molde.json`.
