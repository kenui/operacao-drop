# cena-3d

Monta uma cena 3D simples no Blender a partir de um JSON (cômodo, produto, efeito, animal, câmera de celular) e renderiza um guia de movimento e dois quadros-chave. Custo zero de crédito.

**Por quê:** o modelo de vídeo erra ordem de estados (efeito antes de ligar) e olhar do animal. Aqui isso é exato por construção: o efeito só aparece depois de `liga_em_s` e a cabeça do animal tem restrição TRACK_TO na presa (GAZE LOCK). O vídeo serve de referência de movimento para Kling 3.0 Omni ou Seedance 2.5, e os quadros servem de composição para o quadro inicial feito em Nano Banana Pro. Ver `conhecimento/video-ia/arsenal.md`.

## Instalar
```
pip install bpy==5.3.0   # Python 3.13
```
Sem GPU, Workbench e EEVEE quebram; o script usa Cycles na CPU com poucas amostras.

## Usar
```
python3 cena_3d.py exemplo_projetor_pet.json --saida PASTA
```
Saída: `guia_movimento.mp4`, `quadro_inicial.png`, `quadro_efeito.png`, `cena.blend`. Cerca de 3 a 4 minutos para 8 s em 540x960.

## Limites
Formas primitivas: é guia de movimento e enquadramento, nunca vai para o anúncio. O realismo vem do modelo de vídeo, com o produto real nas referências.
