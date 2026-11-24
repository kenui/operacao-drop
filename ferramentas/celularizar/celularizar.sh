#!/usr/bin/env bash
# celularizar: deixa um vídeo gerado com IA com cara de gravação de celular postada em rede social.
# Não gasta crédito: roda só com ffmpeg, depois da geração e antes do texto na tela.
# Uso: celularizar.sh ENTRADA.mp4 SAIDA.mp4 [textura|leve|medio]   (padrão: textura)
# Kenui (24/11): realismo é textura e detalhe, não qualidade ruim. "textura" mantém a resolução e a voz limpa;
# só tira o brilho perfeito de IA (granulado fino, nitidez de celular, micro-tremida). "medio" é o antigo e só serve para teste.
set -euo pipefail
IN="$1"; OUT="$2"; NIVEL="${3:-textura}"
case "$NIVEL" in
  textura) RUIDO=4;  RES=1920; CRF=20; TREME=2; SOM=limpo ;;
  leve)    RUIDO=6;  RES=1280; CRF=23; TREME=3; SOM=limpo ;;
  medio)   RUIDO=10; RES=720;  CRF=29; TREME=5; SOM=celular ;;
  *) echo "nível: textura, leve ou medio"; exit 1 ;;
esac
TMP="$(mktemp -d)"
# 1) imagem: perde resolução e volta (sensor pequeno + zoom digital), nitidez exagerada de celular,
#    granulado que muda a cada quadro, leve tremida de mão, exposição que respira, cor um pouco lavada.
VF="scale=-2:${RES}:flags=bilinear,scale=1080:1920:flags=bicubic,\
crop=iw-4*${TREME}:ih-4*${TREME}:2*${TREME}+${TREME}*sin(t*6.1)+${TREME}*0.6*sin(t*13.7):2*${TREME}+${TREME}*cos(t*5.3)+${TREME}*0.5*sin(t*11.1),\
scale=1080:1920,unsharp=5:5:0.6:5:5:0.0,noise=c0s=${RUIDO}:c0f=t+u:c1s=4:c1f=t,\
eq=contrast=0.97:saturation=0.93:brightness='0.012*sin(t*1.7)':eval=frame,fps=30,format=yuv420p"
# 2) som: microfone de celular (corta grave e agudo, comprime), eco curto de cômodo, chiado de fundo baixo.
if [ "$SOM" = celular ]; then
  AF="highpass=f=140,lowpass=f=8500,acompressor=threshold=-20dB:ratio=3:attack=5:release=120,aecho=0.8:0.5:22|37:0.18|0.10,volume=1.1"
  AMP=0.006
else
  AF="highpass=f=60"   # voz limpa: só tira ronco grave; o ambiente vem do próprio vídeo
  AMP=0.0015
fi
DUR="$(ffprobe -v error -show_entries format=duration -of csv=p=0 "$IN")"
if ffprobe -v error -select_streams a -show_entries stream=index -of csv=p=0 "$IN" | grep -q .; then
  ffmpeg -v error -y -i "$IN" -f lavfi -t "$DUR" -i "anoisesrc=color=pink:amplitude=${AMP}:sample_rate=44100" \
    -filter_complex "[0:v]${VF}[v];[0:a]${AF},aresample=44100[a0];[a0][1:a]amix=inputs=2:duration=first:normalize=0[a]" \
    -map "[v]" -map "[a]" -c:v libx264 -preset medium -crf "$CRF" -c:a aac -b:a 160k "$TMP/p1.mp4"
else
  ffmpeg -v error -y -i "$IN" -vf "$VF" -an -c:v libx264 -preset medium -crf "$CRF" "$TMP/p1.mp4"
fi
# 3) segunda compressão (repostado) só no nível medio.
if [ "$NIVEL" = medio ]; then
  ffmpeg -v error -y -i "$TMP/p1.mp4" -c:v libx264 -preset veryfast -b:v 3500k -maxrate 4000k -bufsize 6000k \
    -c:a aac -b:a 96k -movflags +faststart "$OUT"
else
  ffmpeg -v error -y -i "$TMP/p1.mp4" -c copy -movflags +faststart "$OUT"
fi
rm -rf "$TMP"
echo "$OUT"
