#!/usr/bin/env python3
"""Mede a luz da sala quadro a quadro e, se pedido, trava a exposição.

Por quê: modelos de vídeo associam "aparelho de luz ligou" a "sala escura" e
escurecem o cômodo sozinhos (V5b, 25/11). A medição pega isso sem depender do
olho; a correção devolve a sala ao brilho de antes sem regenerar (custo zero).

A luz da sala é medida só nos pixels médios (exclui sombras fundas e os
brilhos do efeito, que não devem entrar na conta).

Uso:
  python3 luz_clipe.py CLIPE.mp4 --saida PASTA                 # só mede
  python3 luz_clipe.py CLIPE.mp4 --saida PASTA --base 0:4.5 --corrigir 4.5:fim
"""
import argparse, json, os, subprocess
import cv2, numpy as np

def luz_quadro(q):
    y = cv2.cvtColor(q, cv2.COLOR_BGR2YCrCb)[:, :, 0].astype(np.float32)
    m = (y > 20) & (y < 220)          # fora sombra funda e brilho do efeito
    return float(np.median(y[m])) if m.any() else float(np.median(y))

def faixa(txt, dur):
    a, b = txt.split(":")
    return float(a), (dur if b in ("fim", "") else float(b))

def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("clipe"); ap.add_argument("--saida", required=True)
    ap.add_argument("--base", help="faixa de referência em s, ex 0:4.5")
    ap.add_argument("--corrigir", help="faixa a corrigir em s, ex 4.5:fim")
    ap.add_argument("--limite", type=float, default=0.12,
                    help="queda relativa que conta como alerta (padrão 12%%)")
    a = ap.parse_args()
    os.makedirs(a.saida, exist_ok=True)
    cap = cv2.VideoCapture(a.clipe)
    fps = cap.get(cv2.CAP_PROP_FPS) or 24
    quadros, luz = [], []
    while True:
        ok, q = cap.read()
        if not ok: break
        quadros.append(q); luz.append(luz_quadro(q))
    dur = len(quadros) / fps
    t = [i / fps for i in range(len(luz))]
    # suaviza para não confundir com movimento da câmera
    k = max(1, int(fps // 2))
    suave = np.convolve(np.pad(luz, (k, k), mode="edge"), np.ones(k) / k, mode="same")[k:-k].tolist()
    b0, b1 = faixa(a.base, dur) if a.base else (0, min(1.5, dur))
    ref = float(np.median([l for tt, l in zip(t, suave) if b0 <= tt <= b1]))
    alertas = [round(tt, 2) for tt, l in zip(t, suave) if l < ref * (1 - a.limite)]
    trechos = []
    for tt in alertas:
        if trechos and tt - trechos[-1][1] <= 2 / fps: trechos[-1][1] = tt
        else: trechos.append([tt, tt])
    rel = {"clipe": a.clipe, "fps": fps, "duracao": round(dur, 2),
           "luz_base": round(ref, 1), "base_s": [b0, b1],
           "queda_max_pct": round(100 * (1 - min(suave) / ref), 1),
           "trechos_escuros_s": trechos,
           "luz_por_segundo": [round(suave[int(i * fps)], 1) for i in range(int(dur))]}
    # gráfico simples
    g = np.full((220, 660, 3), 255, np.uint8)
    mx = max(max(suave), ref) * 1.1
    pts = [(int(10 + 640 * tt / dur), int(210 - 200 * l / mx)) for tt, l in zip(t, suave)]
    cv2.polylines(g, [np.array(pts)], False, (40, 40, 40), 2)
    yref = int(210 - 200 * ref / mx); ylim = int(210 - 200 * ref * (1 - a.limite) / mx)
    cv2.line(g, (10, yref), (650, yref), (0, 150, 0), 1)
    cv2.line(g, (10, ylim), (650, ylim), (0, 0, 220), 1)
    cv2.imwrite(os.path.join(a.saida, "luz.png"), g)
    if a.corrigir:
        c0, c1 = faixa(a.corrigir, dur)
        tmp = os.path.join(a.saida, "_q"); os.makedirs(tmp, exist_ok=True)
        for i, q in enumerate(quadros):
            tt = i / fps
            if c0 <= tt <= c1 and suave[i] < ref:
                # gama que leva a luz medida de volta à base: levanta médios e
                # sombras e quase não mexe nos brilhos (o efeito continua vivo)
                e = np.log(ref / 255) / np.log(suave[i] / 255)
                # rampa de 0,4 s nas pontas para não dar salto
                r = min(1.0, (tt - c0) / 0.4, (c1 - tt) / 0.4 if c1 < dur else 1.0)
                e = 1 + (e - 1) * max(0.0, r)
                lut = np.clip(255 * (np.arange(256) / 255) ** e, 0, 255).astype(np.uint8)
                q = cv2.LUT(q, lut)
            cv2.imwrite(os.path.join(tmp, f"{i:05d}.png"), q)
        saida = os.path.join(a.saida, "clipe_luz_travada.mp4")
        subprocess.run(["ffmpeg", "-loglevel", "error", "-y", "-framerate", str(fps),
                        "-i", os.path.join(tmp, "%05d.png"), "-i", a.clipe,
                        "-map", "0:v", "-map", "1:a?", "-c:v", "libx264", "-crf", "16",
                        "-pix_fmt", "yuv420p", "-c:a", "copy", "-shortest", saida], check=True)
        for f in os.listdir(tmp): os.remove(os.path.join(tmp, f))
        os.rmdir(tmp)
        rel["corrigido"] = saida
    json.dump(rel, open(os.path.join(a.saida, "luz.json"), "w"), indent=1, ensure_ascii=False)
    print(json.dumps({k: v for k, v in rel.items() if k != "luz_por_segundo"}, ensure_ascii=False))
    print("luz por segundo:", rel["luz_por_segundo"])

if __name__ == "__main__":
    main()
