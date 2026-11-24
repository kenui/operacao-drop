#!/usr/bin/env python3
"""Ver e ouvir um vídeo: cenas, quadros, mapa do som e transcrição.

Uso: python3 ver_video.py VIDEO_OU_URL [--saida PASTA] [--modelo small] [--idioma en]
           [--recorte INICIO-FIM:X,Y,L,A ...]

Gera na pasta de saída:
  relatorio.md          resumo, tabela de cenas, mapa do som, transcrição com tempo
  cenas/cena_NN.jpg     um quadro do meio de cada cena
  folha_N.jpg           folhas de contato com 2 quadros por segundo
  espectro.png          espectrograma do áudio (fala, música, batidas, silêncio)
  transcricao.json      falas com tempo (quando o modelo está disponível)
  recorte_N.jpg         faixa do produto ampliado, 3 quadros por segundo (com --recorte)

Ferramentas abertas usadas: ffmpeg, PySceneDetect (github.com/Breakthrough/PySceneDetect),
faster-whisper (github.com/SYSTRAN/faster-whisper) e, para links, yt-dlp (github.com/yt-dlp/yt-dlp).
Instalação: pip install scenedetect opencv-python-headless faster-whisper yt-dlp
"""
import argparse, json, math, os, re, subprocess, sys, wave, struct


def sh(cmd, **kw):
    return subprocess.run(cmd, capture_output=True, text=True, **kw)


def baixar(url, pasta):
    alvo = os.path.join(pasta, "video.%(ext)s")
    r = sh(["yt-dlp", "-f", "mp4/best", "-o", alvo, url])
    for f in os.listdir(pasta):
        if f.startswith("video."):
            return os.path.join(pasta, f)
    sys.exit("Não consegui baixar o link (rede bloqueada ou site não suportado):\n" + r.stderr[-800:])


def metadados(v):
    r = sh(["ffprobe", "-v", "error", "-show_entries",
            "format=duration:stream=codec_type,width,height,r_frame_rate", "-of", "json", v])
    d = json.loads(r.stdout)
    dur = float(d["format"]["duration"])
    vid = next((s for s in d["streams"] if s["codec_type"] == "video"), {})
    tem_audio = any(s["codec_type"] == "audio" for s in d["streams"])
    n, _, m = vid.get("r_frame_rate", "0/1").partition("/")
    fps = round(float(n) / float(m or 1), 2)
    return dur, vid.get("width"), vid.get("height"), fps, tem_audio


def cenas(v, pasta, dur):
    from scenedetect import detect, AdaptiveDetector, ContentDetector
    lista = detect(v, AdaptiveDetector(adaptive_threshold=2.0, min_scene_len=6))
    if len(lista) <= 1:
        lista = detect(v, ContentDetector(threshold=18, min_scene_len=6))
    pares = [(a.seconds, b.seconds) for a, b in lista] or [(0.0, dur)]
    os.makedirs(os.path.join(pasta, "cenas"), exist_ok=True)
    for i, (a, b) in enumerate(pares, 1):
        t = a + (b - a) / 2
        sh(["ffmpeg", "-v", "error", "-y", "-ss", f"{t:.2f}", "-i", v, "-frames:v", "1",
            "-vf", "scale=540:-1", os.path.join(pasta, "cenas", f"cena_{i:02d}.jpg")])
    return pares


def folhas(v, pasta, dur):
    por_folha = 18  # 6x3, 2 quadros por segundo = 9 s por folha
    n = max(1, math.ceil(dur * 2 / por_folha))
    sh(["ffmpeg", "-v", "error", "-y", "-i", v, "-vf", "fps=2,scale=240:-1,tile=6x3",
        "-frames:v", str(n), os.path.join(pasta, "folha_%d.jpg")])
    return n


def som(v, pasta):
    wav = os.path.join(pasta, "audio.wav")
    sh(["ffmpeg", "-v", "error", "-y", "-i", v, "-ac", "1", "-ar", "16000", wav])
    sh(["ffmpeg", "-v", "error", "-y", "-i", wav, "-lavfi",
        "showspectrumpic=s=1200x300:legend=1", os.path.join(pasta, "espectro.png")])
    w = wave.open(wav)
    n = w.getnframes()
    dados = struct.unpack("<%dh" % n, w.readframes(n))
    passo = 8000
    mapa = []
    for i in range(0, n, passo):
        c = dados[i:i + passo]
        if not c:
            break
        rms = math.sqrt(sum(x * x for x in c) / len(c))
        mapa.append((i / 16000, 20 * math.log10(rms / 32768 + 1e-9)))
    return wav, mapa


def transcrever(wav, modelo, idioma):
    try:
        from faster_whisper import WhisperModel
        m = WhisperModel(modelo, compute_type="int8")
        # Passa o áudio já decodificado (16 kHz mono): o PyAV 19 removeu um argumento que o
        # faster-whisper 1.2 usa ao abrir arquivos, e assim o PyAV nem entra.
        import numpy as np
        w = wave.open(wav)
        audio = np.frombuffer(w.readframes(w.getnframes()), dtype=np.int16).astype(np.float32) / 32768.0
        segs, info = m.transcribe(audio, language=idioma, vad_filter=True, word_timestamps=True)
        falas = []
        for sg in segs:  # quebra em frases, cada uma com o seu tempo
            atual = []
            for p in (sg.words or []):
                atual.append(p)
                if p.word.strip()[-1:] in ".?!":
                    falas.append(atual); atual = []
            if atual:
                falas.append(atual)
        return info.language, [{"ini": round(f[0].start, 1), "fim": round(f[-1].end, 1),
                                "texto": "".join(p.word for p in f).strip()} for f in falas], None
    except Exception as e:
        return None, [], f"{type(e).__name__}: {str(e)[:200]}"


def recortes(v, pasta, specs):
    """Faixa do produto ampliado, 3 quadros por segundo.
    Na folha comum o produto ocupa poucos pixels e um giro ou uma troca de forma passa batido
    (o giro do D-v2 só apareceu assim)."""
    feitos = []
    for i, spec in enumerate(specs, 1):
        m = re.match(r"^([\d.]+)-([\d.]+):(\d+),(\d+),(\d+),(\d+)$", spec)
        if not m:
            sys.exit(f"--recorte inválido: {spec} (formato INICIO-FIM:X,Y,LARGURA,ALTURA em segundos e pixels)")
        a, b = float(m[1]), float(m[2])
        x, y, w, h = (int(m[k]) for k in range(3, 7))
        n = max(1, math.ceil((b - a) * 3))
        cols = min(n, 5)
        linhas = math.ceil(n / cols)
        saida = os.path.join(pasta, f"recorte_{i}.jpg")
        sh(["ffmpeg", "-v", "error", "-y", "-ss", f"{a}", "-t", f"{b - a}", "-i", v, "-vf",
            f"fps=3,crop={w}:{h}:{x}:{y},scale=250:-1,tile={cols}x{linhas}", "-frames:v", "1", saida])
        feitos.append((a, b, f"recorte_{i}.jpg"))
    return feitos


def rotulo_som(db):
    if db < -45:
        return "silêncio"
    if db < -35:
        return "baixo"
    return "som"


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("video")
    ap.add_argument("--saida")
    ap.add_argument("--modelo", default=os.environ.get("VER_VIDEO_MODELO", "small"))
    ap.add_argument("--idioma", default=None)
    ap.add_argument("--recorte", action="append", default=[],
                    help="INICIO-FIM:X,Y,LARGURA,ALTURA, ex. 15-20:660,1300,360,300 (pode repetir)")
    a = ap.parse_args()

    base = re.sub(r"[^\w.-]", "_", os.path.basename(a.video))[:60]
    pasta = a.saida or os.path.join(os.getcwd(), "analise_" + os.path.splitext(base)[0])
    os.makedirs(pasta, exist_ok=True)
    v = baixar(a.video, pasta) if re.match(r"https?://", a.video) else a.video

    dur, lw, lh, fps, tem_audio = metadados(v)
    pares = cenas(v, pasta, dur)
    nf = folhas(v, pasta, dur)
    rec = recortes(v, pasta, a.recorte)
    mapa, lang, falas, erro = [], None, [], None
    if tem_audio:
        wav, mapa = som(v, pasta)
        lang, falas, erro = transcrever(wav, a.modelo, a.idioma)
        json.dump(falas, open(os.path.join(pasta, "transcricao.json"), "w"), ensure_ascii=False, indent=1)

    L = [f"# Análise de vídeo: {os.path.basename(v)}", "",
         f"Duração {dur:.1f} s, {lw}x{lh}, {fps} fps, áudio: {'sim' if tem_audio else 'não'}.",
         f"Cenas detectadas: {len(pares)}. Folhas de contato: {nf} (2 quadros por segundo).", "",
         "## Cenas", "", "| # | início | fim | duração | quadro | o que acontece (preencher olhando o quadro) | estado do produto | física |",
         "|---|---|---|---|---|---|---|---|"]
    for i, (x, y) in enumerate(pares, 1):
        L.append(f"| {i} | {x:.1f} s | {y:.1f} s | {y - x:.1f} s | cenas/cena_{i:02d}.jpg | | | |")
    if rec:
        L += ["", "## Produto ampliado (3 quadros por segundo; conferir forma, giro, posição e luz)", ""]
        L += [f"- {x:.1f} a {y:.1f} s: {f} | forma: | se mexe?: | luz:" for x, y, f in rec]
    if mapa:
        L += ["", "## Mapa do som (a cada 0,5 s; ver espectro.png para separar fala, música e batidas)", ""]
        trechos, atual = [], None
        for t, db in mapa:
            r = rotulo_som(db)
            if atual and atual[2] == r:
                atual[1] = t + 0.5
            else:
                atual = [t, t + 0.5, r]
                trechos.append(atual)
        L += [f"- {x:.1f} a {y:.1f} s: {r}" for x, y, r in trechos]
        L += ["", "## Transcrição", ""]
        if erro:
            L.append(f"Pendente: o modelo de transcrição não carregou ({erro}). O modelo vem de huggingface.co e dos servidores de arquivo dele (us.aws.cdn.hf.co, cas-bridge.xethub.hf.co, cdn-lfs.hf.co); a rede precisa liberar todos.")
        elif not falas:
            L.append("Nenhuma fala detectada.")
        else:
            L.append(f"Idioma detectado: {lang}.")
            L += [f"- {f['ini']:.1f} a {f['fim']:.1f} s: {f['texto']}" for f in falas]
    L += ["", "## Leitura (preencher)", "",
          "- Gancho (0 a 3 s):", "- Momento do efeito do produto (tempo e o que muda na tela):",
          "- Como o produto se comporta de verdade (liga, acende, gira ou não, som):",
          "- Ângulos usados:", "- Texto na tela e chamada final:", "- O que copiar como estrutura / o que NÃO copiar:"]
    open(os.path.join(pasta, "relatorio.md"), "w").write("\n".join(L) + "\n")
    json.dump({"video": os.path.basename(v), "duracao": dur, "largura": lw, "altura": lh, "fps": fps,
               "cenas": [{"ini": round(x, 2), "fim": round(y, 2), "quadro": f"cenas/cena_{i:02d}.jpg"}
                         for i, (x, y) in enumerate(pares, 1)],
               "som": [{"t": t, "db": round(db, 1)} for t, db in mapa],
               "falas": falas, "idioma": lang, "erro_transcricao": erro},
              open(os.path.join(pasta, "analise.json"), "w"), ensure_ascii=False, indent=1)
    print(os.path.join(pasta, "relatorio.md"))


if __name__ == "__main__":
    main()
