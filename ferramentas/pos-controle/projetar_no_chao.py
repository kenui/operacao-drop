#!/usr/bin/env python3
"""Aplica na pós a projeção exata do produto sobre o chão de um clipe gerado.

Por quê: o padrão de luz do produto (quantos anéis, onde, qual bicho) é o detalhe
que o comprador confere, e o modelo de vídeo não segura contagem nem forma
(V5b: 3 anéis viraram 2 e o esquilo sumiu). Aqui o padrão é desenhado por
código, fixo no piso por rastreamento plano (homografia), e somado como luz
(modo aditivo), que é o que uma projeção real faz: clareia o que estiver em
cima, inclusive o cachorro. Custo zero de crédito.

Fluxo: gere o clipe SEM o efeito (ou só com um ponto de luz para o animal
seguir), marque 4 pontos do piso no quadro de referência e rode:

  python3 projetar_no_chao.py CLIPE.mp4 padrao.json --saida PASTA

padrao.json (exemplo em exemplo_padrao.json):
  quadro_ref: índice do quadro onde os 4 pontos foram marcados
  piso_px: 4 pontos [x,y] no quadro_ref, em ordem: fundo-esq, fundo-dir,
           frente-dir, frente-esq (um retângulo real no chão)
  piso_cm: [largura, profundidade] desse retângulo no mundo, em cm
  liga_em_s: antes disso não há luz nenhuma (ordem de estados garantida)
  aneis: [{x_cm, y_cm, r_cm}] dentro do retângulo, ou aneis_px: [{x, y, rx}]
         marcados no quadro_ref; cor [B,G,R]; intensidade
  presa: {"icone": "arquivo.png" opcional, "sequencia": [[t_s, indice_anel],...]}
"""
import argparse, json, os, subprocess
import cv2, numpy as np

PX_CM = 4  # resolução do desenho do padrão

def desenha_padrao(p, t, icone):
    W, D = p["piso_cm"]
    img = np.zeros((int(D * PX_CM), int(W * PX_CM), 3), np.float32)
    cor = np.array(p.get("cor", [40, 140, 255]), np.float32) / 255
    esp = max(2, int(p.get("espessura_cm", 1.6) * PX_CM))
    for a in p["aneis"]:
        c = (int(a["x_cm"] * PX_CM), int(a["y_cm"] * PX_CM))
        cv2.circle(img, c, int(a["r_cm"] * PX_CM), cor.tolist(), esp, cv2.LINE_AA)
    seq = p.get("presa", {}).get("sequencia", [])
    atual = None
    for ts, idx in seq:
        if t >= ts: atual = idx
    if atual is not None:
        a = p["aneis"][atual]
        cx, cy, r = a["x_cm"] * PX_CM, a["y_cm"] * PX_CM, a["r_cm"] * PX_CM
        if icone is not None:
            s = int(r * 1.3)
            ic = cv2.resize(icone, (s, s), interpolation=cv2.INTER_AREA)
            x0, y0 = int(cx - s / 2), int(cy - s / 2)
            reg = img[y0:y0 + s, x0:x0 + s]
            reg[:] = np.maximum(reg, ic[:reg.shape[0], :reg.shape[1], None] * cor)
        else:
            cv2.circle(img, (int(cx), int(cy)), int(r * 0.45), cor.tolist(), -1, cv2.LINE_AA)
    # brilho de projetor: borda macia + halo leve
    halo = cv2.GaussianBlur(img, (0, 0), PX_CM * 2.5)
    return np.clip(cv2.GaussianBlur(img, (0, 0), PX_CM * 0.35) + 0.6 * halo, 0, 1)

def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("clipe"); ap.add_argument("padrao"); ap.add_argument("--saida", required=True)
    a = ap.parse_args()
    p = json.load(open(a.padrao)); os.makedirs(a.saida, exist_ok=True)
    icone = None
    if p.get("presa", {}).get("icone"):
        caminho = os.path.join(os.path.dirname(os.path.abspath(a.padrao)), p["presa"]["icone"])
        icone = cv2.imread(caminho, cv2.IMREAD_GRAYSCALE).astype(np.float32) / 255
    cap = cv2.VideoCapture(a.clipe); fps = cap.get(cv2.CAP_PROP_FPS) or 24
    quadros = []
    while True:
        ok, q = cap.read()
        if not ok: break
        quadros.append(q)
    ref_i = p["quadro_ref"]; ref = cv2.cvtColor(quadros[ref_i], cv2.COLOR_BGR2GRAY)
    W, D = p["piso_cm"]
    src = np.float32([[0, 0], [W * PX_CM, 0], [W * PX_CM, D * PX_CM], [0, D * PX_CM]])
    H_piso = cv2.getPerspectiveTransform(src, np.float32(p["piso_px"]))
    if "aneis_px" in p:  # anéis marcados direto no quadro de referência
        Hi = np.linalg.inv(H_piso); p["aneis"] = []
        for an in p["aneis_px"]:
            c, b = cv2.perspectiveTransform(np.float32([[[an["x"], an["y"]], [an["x"] + an["rx"], an["y"]]]]), Hi)[0]
            p["aneis"].append({"x_cm": c[0] / PX_CM, "y_cm": c[1] / PX_CM,
                               "r_cm": float(np.linalg.norm(b - c)) / PX_CM})
    orb = cv2.ORB_create(4000); bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
    # pontos do cenário só na metade de baixo + entorno do piso (onde está o chão)
    kr, dr = orb.detectAndCompute(ref, None)
    H_prev, erros, tmp = np.eye(3), [], os.path.join(a.saida, "_q")
    os.makedirs(tmp, exist_ok=True)
    liga = p.get("liga_em_s", 0); inten = p.get("intensidade", 0.85)
    for i, q in enumerate(quadros):
        t = i / fps
        g = cv2.cvtColor(q, cv2.COLOR_BGR2GRAY)
        if i == ref_i:
            H = np.eye(3)
        else:
            k, d = orb.detectAndCompute(g, None)
            H = None
            if d is not None and len(k) > 30:
                m = sorted(bf.match(dr, d), key=lambda x: x.distance)[:800]
                if len(m) > 30:
                    A = np.float32([kr[x.queryIdx].pt for x in m]); B = np.float32([k[x.trainIdx].pt for x in m])
                    H, msk = cv2.findHomography(A, B, cv2.RANSAC, 3.0)
                    if H is not None:
                        erros.append(float(msk.mean()))
            if H is None: H = H_prev
        H_prev = H
        out = q.astype(np.float32) / 255
        if t >= liga:
            pad = desenha_padrao(p, t, icone)
            luz = cv2.warpPerspective(pad, H @ H_piso, (q.shape[1], q.shape[0]))
            out = 1 - (1 - out) * (1 - inten * luz)   # tela: soma de luz sem estourar
        cv2.imwrite(os.path.join(tmp, f"{i:05d}.png"), (out * 255).astype(np.uint8))
    saida = os.path.join(a.saida, "clipe_projecao.mp4")
    subprocess.run(["ffmpeg", "-loglevel", "error", "-y", "-framerate", str(fps), "-i",
                    os.path.join(tmp, "%05d.png"), "-i", a.clipe, "-map", "0:v", "-map", "1:a?",
                    "-c:v", "libx264", "-crf", "16", "-pix_fmt", "yuv420p", "-c:a", "copy",
                    "-shortest", saida], check=True)
    for f in os.listdir(tmp): os.remove(os.path.join(tmp, f))
    os.rmdir(tmp)
    print(json.dumps({"saida": saida, "quadros": len(quadros),
                      "inliers_medio": round(float(np.mean(erros)), 2) if erros else None}))

if __name__ == "__main__":
    main()
