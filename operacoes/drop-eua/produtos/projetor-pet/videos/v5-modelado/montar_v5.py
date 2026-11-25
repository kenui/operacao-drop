"""V5 modelado na referência escalada: unboxing com o cachorro, clique, prova aprovada do esquilo, dona descansando.
Roteiro e decisão em 08-videos-que-vendem.md (V5). Duas versões de som."""
import subprocess, os
V="/mnt/project-files/operacoes/drop-eua/produtos/projetor-pet/videos"
ABRE=f"{V}/v5-modelado/clipe-abertura.mp4"; PROVA=f"{V}/prova-esquilo/clipe-prova-esquilo.mp4"; FIM=f"{V}/v5-modelado/clipe-final.mp4"
MUS=f"{V}/v1-chuva/musica-ia-d-v1.mp3"; OUT=f"{V}/v5-modelado"
TMP=os.path.dirname(os.path.abspath(__file__))+"/txt"; os.makedirs(TMP,exist_ok=True)
FONT="/usr/share/fonts/opentype/inter/Inter-ExtraBold.otf"
# (entrada, início, fim): abertura cortada antes de a luz aparecer (5,1 s), prova, final
CORTES=[(0,0.3,5.05),(1,1.5,7.2),(2,0.2,4.2)]
# (início, fim, y, texto) dentro da zona segura (topo 14% livre, base 35% livre)
TEXTOS=[(0,4.75,250,"dog moms, do NOT open this"),(0,4.75,322,"in front of him.."),
        (4.75,10.45,250,"lights low. that's it."),
        (10.45,15,250,'comment "CATCH"'),(10.45,15,322,"if your dog needs this")]
fc=[]; lab=""
for i,(src,a,b) in enumerate(CORTES):
    fc.append(f"[{src}:v]trim={a}:{b},setpts=PTS-STARTPTS,fps=24,scale=720:1280:force_original_aspect_ratio=increase,crop=720:1280,setsar=1[v{i}];"
              f"[{src}:a]atrim={a}:{b},asetpts=PTS-STARTPTS,aresample=48000,aformat=channel_layouts=stereo,afade=t=in:d=0.03,areverse,afade=t=in:d=0.03,areverse[a{i}]")
    lab+=f"[v{i}][a{i}]"
fc.append(f"{lab}concat=n={len(CORTES)}:v=1:a=1[vc][ac]")
dt=[]
for i,(a,b,y,t) in enumerate(TEXTOS):
    p=f"{TMP}/t{i}.txt"; open(p,"w").write(t)
    dt.append(f"drawtext=fontfile={FONT}:textfile={p}:fontcolor=white:fontsize=40:box=1:boxcolor=black@0.55:boxborderw=14:x=(w-text_w)/2:y={y}:enable='between(t\\,{a}\\,{b})'")
fc.append("[vc]"+",".join(dt)+"[v]")
fc.append("[ac]anull[a]")
total=round(sum(b-a for _,a,b in CORTES),2)
run=lambda c: subprocess.run(["ffmpeg","-v","error","-y",*c],check=True)
SEM=f"{OUT}/V5-modelado-sem-musica.mp4"; COM=f"{OUT}/V5-modelado-com-musica.mp4"
run(["-i",ABRE,"-i",PROVA,"-i",FIM,"-filter_complex",";".join(fc),"-map","[v]","-map","[a]","-c:v","libx264","-crf","18","-preset","medium",
     "-pix_fmt","yuv420p","-c:a","aac","-b:a","160k","-movflags","+faststart",f"{TMP}/base.mp4"])
# áudio com cara de celular na sala (piso contínuo, curva de microfone, reflexo curto); ver conhecimento/video-ia/audio-realista.md
FERR=os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)),"../../../../../../ferramentas/audio-celular/audio_celular.py"))
subprocess.run(["python3",FERR,f"{TMP}/base.mp4",SEM],check=True)
# passo 1: cama de música do tamanho do vídeo; passo 2: abaixa sob a voz e mistura
run(["-stream_loop","-1","-i",MUS,"-t",str(total),"-af",f"volume=0.22,afade=t=in:d=0.8,afade=t=out:st={total-1.5}:d=1.5","-ar","48000","-ac","2",f"{TMP}/bed.wav"])
run(["-i",SEM,"-vn","-ar","48000","-ac","2",f"{TMP}/voz.wav"])
run(["-i",SEM,"-i",f"{TMP}/bed.wav","-i",f"{TMP}/voz.wav","-filter_complex",
     "[2:a]asplit[av][sc];[1:a][sc]sidechaincompress=threshold=0.03:ratio=8:attack=20:release=400[md];[av][md]amix=inputs=2:duration=longest:normalize=0[a]",
     "-map","0:v","-map","[a]","-c:v","copy","-c:a","aac","-b:a","160k","-movflags","+faststart",COM])
print("ok",total)
