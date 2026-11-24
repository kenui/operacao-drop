"""Montagem do V4 ("achei que era fake"): objeção + aparelho desligado (clipe-v4-a) e prova em tomada
contínua de 9,2 s (clipe-prova-b, o mesmo trecho aprovado no V1). A fala "You do have to turn the
lights down" entra por cima do começo da prova, porque no clipe novo a sala escurece demais depois
do interruptor (o abajur também apaga) e não bate com a prova.
Duas versões: sem música e com música por IA baixa, mixada em dois passos."""
import subprocess, os
V="/mnt/project-files/operacoes/drop-eua/produtos/projetor-pet/videos"
A=f"{V}/v4-fake/clipe-v4-a.mp4"; PROVA=f"{V}/v1-chuva/clipe-prova-b.mp4"
MUS=f"{V}/v1-chuva/musica-ia-d-v1.mp3"; OUT=f"{V}/v4-fake"
TMP=os.path.dirname(os.path.abspath(__file__))+"/txt"; os.makedirs(TMP,exist_ok=True)
FONT="/usr/share/fonts/opentype/inter/Inter-ExtraBold.otf"
# (entrada, início, fim): objeção, aparelho no chão + interruptor, prova e recompensa contínuas
CORTES=[(0,1.2,3.5),(0,4.7,9.9),(1,5.8,15.0)]
FALA3=(10.6,12.45)      # trecho do clipe novo com a terceira fala
FALA3_EM=7.7            # onde ela entra na montagem (começo da prova)
TEXTOS=[(0,2.3,250,"ok I thought"),(0,2.3,322,"these videos were fake."),
        (2.3,7.5,250,"nothing on the floor yet"),
        (7.5,13.4,250,"works best with the lights low"),
        (13.4,15.2,250,"30-day refund"),(13.4,15.2,322,"if he just stares at it"),
        (15.2,17,250,"tap to see the kit")]
fc=[]; lab=""
for i,(src,a,b) in enumerate(CORTES):
    fc.append(f"[{src}:v]trim={a}:{b},setpts=PTS-STARTPTS,fps=24,scale=720:1280:force_original_aspect_ratio=increase,crop=720:1280,setsar=1[v{i}];"
              f"[{src}:a]atrim={a}:{b},asetpts=PTS-STARTPTS,aresample=48000,aformat=channel_layouts=stereo,afade=t=in:d=0.03,areverse,afade=t=in:d=0.03,areverse[a{i}]")
    lab+=f"[v{i}][a{i}]"
fc.append(f"{lab}concat=n={len(CORTES)}:v=1:a=1[vc][ac]")
fc.append(f"[0:a]atrim={FALA3[0]}:{FALA3[1]},asetpts=PTS-STARTPTS,aresample=48000,aformat=channel_layouts=stereo,afade=t=in:d=0.05,adelay={int(FALA3_EM*1000)}:all=1[f3]")
fc.append("[ac][f3]amix=inputs=2:duration=first:normalize=0[a]")
dt=[]
for i,(a,b,y,t) in enumerate(TEXTOS):
    p=f"{TMP}/t{i}.txt"; open(p,"w").write(t)
    dt.append(f"drawtext=fontfile={FONT}:textfile={p}:fontcolor=white:fontsize=40:box=1:boxcolor=black@0.55:boxborderw=14:x=(w-text_w)/2:y={y}:enable='between(t\\,{a}\\,{b})'")
fc.append("[vc]"+",".join(dt)+"[v]")
total=round(sum(b-a for _,a,b in CORTES),2)
run=lambda c: subprocess.run(["ffmpeg","-v","error","-y",*c],check=True)
SEM=f"{OUT}/V4-fake-sem-musica.mp4"; COM=f"{OUT}/V4-fake-com-musica.mp4"
run(["-i",A,"-i",PROVA,"-filter_complex",";".join(fc),"-map","[v]","-map","[a]","-c:v","libx264","-crf","18","-preset","medium",
     "-pix_fmt","yuv420p","-c:a","aac","-b:a","160k","-movflags","+faststart",f"{TMP}/base.mp4"])
# áudio com cara de celular na sala (piso contínuo, curva de microfone, reflexo curto); ver conhecimento/video-ia/audio-realista.md
FERR=os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)),"../../../../../../ferramentas/audio-celular/audio_celular.py"))
subprocess.run(["python3",FERR,f"{TMP}/base.mp4",SEM],check=True)
run(["-stream_loop","-1","-i",MUS,"-t",str(total),"-af",f"volume=0.22,afade=t=in:d=0.8,afade=t=out:st={total-1.5}:d=1.5","-ar","48000","-ac","2",f"{TMP}/bed.wav"])
run(["-i",SEM,"-vn","-ar","48000","-ac","2",f"{TMP}/voz.wav"])
run(["-i",SEM,"-i",f"{TMP}/bed.wav","-i",f"{TMP}/voz.wav","-filter_complex",
     "[2:a]asplit[av][sc];[1:a][sc]sidechaincompress=threshold=0.03:ratio=8:attack=20:release=400[md];[av][md]amix=inputs=2:duration=longest:normalize=0[a]",
     "-map","0:v","-map","[a]","-c:v","copy","-c:a","aac","-b:a","160k","-movflags","+faststart",COM])
print("ok",total)
