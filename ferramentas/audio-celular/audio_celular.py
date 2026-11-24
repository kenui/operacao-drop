#!/usr/bin/env python3
"""Deixa o áudio de uma montagem com cara de gravação de celular numa sala real.
Uso: audio_celular.py ENTRADA.mp4 SAIDA.mp4 [--piso -50] [--sala 0.10] [--lufs -16]

O que faz e por quê (conhecimento/video-ia/audio-realista.md):
1. Piso de sala contínuo (ruído rosa filtrado, bem baixo) do começo ao fim: o áudio de IA cai a
   silêncio digital entre as falas e muda de ambiente a cada corte; celular real nunca fica mudo.
2. Curva de microfone de celular: corta grave abaixo de 90 Hz e agudo acima de 14 kHz, leve presença em 3,5 kHz.
3. Reflexos curtos de sala iguais em todos os trechos: cola clipes diferentes no mesmo cômodo.
4. Compressão leve como o controle automático de ganho do celular, e limitador.
5. Ganho linear até o volume alvo (sem normalização dinâmica, que "bombeia" o fundo).
O vídeo é copiado sem recodificar. Não usa crédito."""
import subprocess, sys, re, argparse
p=argparse.ArgumentParser(); p.add_argument("entrada"); p.add_argument("saida")
p.add_argument("--piso",type=float,default=-50.0,help="nível do piso de sala em dBFS RMS")
p.add_argument("--sala",type=float,default=0.10,help="quanto de reflexo de sala (0 a 0.3)")
p.add_argument("--lufs",type=float,default=-16.0)
a=p.parse_args()
dur=float(subprocess.run(["ffprobe","-v","error","-show_entries","format=duration","-of","csv=p=0",a.entrada],capture_output=True,text=True).stdout)
amp=10**(a.piso/20)*4   # anoisesrc rosa filtrado perde cerca de 12 dB
cadeia=(f"[0:a]aresample=48000,aformat=channel_layouts=stereo,highpass=f=90,lowpass=f=14000,"
        f"equalizer=f=3500:t=q:w=1.2:g=2,equalizer=f=250:t=q:w=1:g=-1.5,"
        f"acompressor=threshold=-22dB:ratio=2.5:attack=15:release=250:makeup=1.5,asplit[d][w];"
        f"[w]aecho=1:1:17|29|43:0.5|0.35|0.2,highpass=f=250,lowpass=f=6000,volume={a.sala}[r];"
        f"anoisesrc=color=pink:amplitude={amp:.5f}:duration={dur}:sample_rate=48000:seed=7,"
        f"highpass=f=120,lowpass=f=5000,aformat=channel_layouts=stereo,afade=t=in:d=0.15[n];"
        f"[d][r][n]amix=inputs=3:duration=first:normalize=0[m]")
def medir(extra):
    r=subprocess.run(["ffmpeg","-hide_banner","-i",a.entrada,"-filter_complex",cadeia+";[m]"+extra+"ebur128[o]","-map","[o]","-f","null","-"],capture_output=True,text=True).stderr
    return float(re.findall(r"I:\s+(-?[\d.]+) LUFS",r)[-1])
g=a.lufs-medir("")
subprocess.run(["ffmpeg","-v","error","-y","-i",a.entrada,"-filter_complex",cadeia+f";[m]volume={g:.2f}dB,alimiter=limit=0.7:attack=3:release=80:level=false[o]",
    "-map","0:v","-map","[o]","-c:v","copy","-c:a","aac","-b:a","160k","-movflags","+faststart",a.saida],check=True)
print(f"ok ganho {g:.1f} dB, piso {a.piso} dBFS, sala {a.sala}")
