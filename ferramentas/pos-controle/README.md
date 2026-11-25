# pos-controle

Ferramentas de pós que tiram do modelo de vídeo o que precisa ser exato. Custo zero de crédito. Método completo em `conhecimento/video-ia/controle-de-cena.md`.

## luz_clipe.py
Mede a luz da sala quadro a quadro (sem sombras fundas nem o brilho do efeito), aponta quedas acima de 12% e, se pedido, trava a exposição de volta à base.
```
python3 luz_clipe.py CLIPE.mp4 --saida PASTA --base 0:4.5               # mede
python3 luz_clipe.py CLIPE.mp4 --saida PASTA --base 0:4.5 --corrigir 4.6:fim
```
Saída: `luz.json`, `luz.png` (linha verde = base, vermelha = limite) e `clipe_luz_travada.mp4`.

## projetar_no_chao.py
Desenha a projeção do produto (anéis e presa, ligando só em `liga_em_s`), prende ao piso por homografia (ORB + RANSAC contra o quadro de referência) e soma como luz.
```
python3 projetar_no_chao.py CLIPE.mp4 padrao.json --saida PASTA
```
Veja `exemplo_padrao.json`. Gere o clipe SEM o efeito (ou com um ponto de luz para o animal seguir), senão aparecem dois padrões. Precisa de piso com textura e câmera sem giro brusco.

Dependências: `opencv-python`, `numpy`, `ffmpeg`.
