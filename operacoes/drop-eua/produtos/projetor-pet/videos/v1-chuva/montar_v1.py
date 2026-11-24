"""Montagem do V1 (chuva): dor + virada do clipe novo, prova + recompensa do vídeo aprovado.
Gera duas versões: sem música e com música por IA baixa (abaixa sozinha quando há fala)."""
import subprocess, os, sys
V="/mnt/project-files/operacoes/drop-eua/produtos/projetor-pet/videos"
DOR=f"{V}/v1-chuva/clipe-dor-a.mp4"; PROVA=f"{V}/topview-v6/D-v6-topview-t1.mp4"
MUS=f"{V}/v1-chuva/musica-ia-d-v1.mp3"; OUT=f"{V}/v1-chuva"
TMP=os.path.dirname(os.path.abspath(__file__))+"/txt"; os.makedirs(TMP,exist_ok=True)
FONT="/usr/share/fonts/opentype/inter/Inter-ExtraBold.otf"
# (início, fim, y, texto) dentro da zona segura (topo 14% livre, base 35% livre)
TEXTOS=[(0,3.2,250,"no walk today."),(0,3.2,322,"he's been staring at me since 4."),
        (3.2,6.8,250,"rainy days = bored dog"),(6.8,9.1,250,"so I tried this"),
        (9.1,15.7,250,"works best with the lights low"),
        (15.7,19.0,250,"rainy day, no walk, zero guilt"),(19.0,21,250,"tap to see the kit")]
# (entrada, início, fim): dor, virada, prova, recompensa
CORTES=[(0,0.9,7.7),(0,11.9,14.2),(1,0,6.6),(1,9.8,15.0)]
fc=[]; lab=""
for i,(src,a,b) in enumerate(CORTES):
    fc.append(f"[{src}:v]trim={a}:{b},setpts=PTS-STARTPTS,fps=24[v{i}];[{src}:a]atrim={a}:{b},asetpts=PTS-STARTPTS[a{i}]")
    lab+=f"[v{i}][a{i}]"
fc.append(f"{lab}concat=n={len(CORTES)}:v=1:a=1[vc][ac]")
dt=[]
for i,(a,b,y,t) in enumerate(TEXTOS):
    p=f"{TMP}/t{i}.txt"; open(p,"w").write(t)
    dt.append(f"drawtext=fontfile={FONT}:textfile={p}:fontcolor=white:fontsize=40:box=1:boxcolor=black@0.55:boxborderw=14:x=(w-text_w)/2:y={y}:enable='between(t\\,{a}\\,{b})'")
fc.append("[vc]"+",".join(dt)+"[v]")
total=sum(b-a for _,a,b in CORTES)
enc=["-map","[v]","-map","[a]","-c:v","libx264","-crf","18","-preset","medium","-pix_fmt","yuv420p","-c:a","aac","-b:a","160k","-movflags","+faststart"]
subprocess.run(["ffmpeg","-v","error","-y","-i",DOR,"-i",PROVA,"-filter_complex",";".join(fc+["[ac]loudnorm=I=-16:TP=-1.5[a]"]),*enc,f"{OUT}/V1-chuva-sem-musica.mp4"],check=True)
mus=[f"[ac]loudnorm=I=-16:TP=-1.5,asplit[av][sc]",
     f"[2:a]atrim=0:{total},volume=0.30,afade=t=in:d=0.8,afade=t=out:st={total-1.5}:d=1.5[m]",
     "[m][sc]sidechaincompress=threshold=0.03:ratio=8:attack=20:release=400[md]",
     "[av][md]amix=inputs=2:duration=first:normalize=0[a]"]
subprocess.run(["ffmpeg","-v","error","-y","-i",DOR,"-i",PROVA,"-stream_loop","1","-i",MUS,"-filter_complex",";".join(fc+mus),*enc,f"{OUT}/V1-chuva-com-musica.mp4"],check=True)
print("ok",total)
