#!/usr/bin/env python3
"""Cena 3D por código (Blender/bpy) para guiar o modelo de vídeo.

Monta, a partir de um JSON, um cômodo simples, o produto feito de peças, o efeito dele e um
animal em blocos que persegue e OLHA para a presa (trava de olhar garantida por restrição),
mais uma câmera de celular baixa com tremida. Gera:
  guia_movimento.mp4  vídeo de referência de movimento (Kling Omni / Seedance reference video)
  quadro_inicial.png  primeiro quadro com o produto já no estado certo (base para imagem de IA)
  quadro_efeito.png   quadro no meio do efeito
  cena.blend          a cena para ajustar à mão, se quiser

Uso: python3 cena_3d.py exemplo_projetor_pet.json --saida PASTA
Instalação: pip install bpy==5.3.0   (Blender como módulo Python; não precisa de placa de vídeo)
Por que: o modelo de vídeo erra posição, ordem de estados e olhar. Aqui isso é exato
por construção; o modelo só troca a aparência (pele, pelo, textura) por cima.
"""
import argparse, json, math, os, random, subprocess, sys

import bpy
from mathutils import Vector


def limpar():
    bpy.ops.wm.read_factory_settings(use_empty=True)


def material(nome, cor, emissao=0.0):
    m = bpy.data.materials.new(nome)
    b = m.node_tree.nodes["Principled BSDF"]
    b.inputs["Base Color"].default_value = (*cor, 1)
    b.inputs["Roughness"].default_value = 0.7
    if emissao:
        b.inputs["Emission Color"].default_value = (*cor, 1)
        b.inputs["Emission Strength"].default_value = emissao
    m.diffuse_color = (*cor, 1)  # cor no modo Workbench
    return m


def objeto_ativo(mat=None):
    o = bpy.context.active_object
    if mat:
        o.data.materials.append(mat)
    return o


def comodo(c):
    bpy.ops.mesh.primitive_plane_add(size=8, location=(0, 0, 0))
    objeto_ativo(material("piso", c["piso"]))
    bpy.ops.mesh.primitive_plane_add(size=8, location=(0, 2.0, 2), rotation=(math.radians(90), 0, 0))
    objeto_ativo(material("parede", c["parede"]))
    bpy.ops.mesh.primitive_cube_add(size=1, location=(0, 1.97, 0.05), scale=(8, 0.03, 0.1))
    objeto_ativo(material("rodape", (0.95, 0.95, 0.93)))
    if c.get("abajur"):
        bpy.ops.object.light_add(type="POINT", location=(-1.5, 1.2, 1.1))
        l = bpy.context.active_object
        l.data.energy = 60
        l.data.color = (1.0, 0.75, 0.45)
    bpy.ops.object.light_add(type="AREA", location=(1.5, -1.0, 2.5))
    a = bpy.context.active_object
    a.data.energy = 25
    a.data.color = (0.75, 0.82, 1.0)


def produto(p, fps):
    px, py, pz = p["posicao"]
    liga = int(p["liga_em_s"] * fps)
    lente_mat = None
    for i, parte in enumerate(p["partes"]):
        cor = parte["cor"]
        z = pz + parte.get("z", 0)
        y = py + parte.get("y", 0)
        if parte["tipo"] == "cilindro":
            bpy.ops.mesh.primitive_cylinder_add(radius=parte["raio"], depth=parte["altura"], location=(px, y, z))
            objeto_ativo(material(f"prod{i}", cor))
        elif parte["tipo"] == "capsula":
            bpy.ops.mesh.primitive_uv_sphere_add(radius=parte["raio"], location=(px, y, z))
            o = objeto_ativo(material(f"prod{i}", cor))
            o.scale.z = parte["altura"] / (2 * parte["raio"])
        elif parte["tipo"] == "lente":
            bpy.ops.mesh.primitive_uv_sphere_add(radius=parte["raio"], location=(px, y, z))
            lente_mat = material("lente", cor, 0.0)
            objeto_ativo(lente_mat)
    if lente_mat:  # lente apagada até o clique, acesa depois (nunca antes)
        s = lente_mat.node_tree.nodes["Principled BSDF"].inputs["Emission Strength"]
        lente_mat.node_tree.nodes["Principled BSDF"].inputs["Emission Color"].default_value = (0.1, 0.35, 1, 1)
        s.default_value = 0.0
        s.keyframe_insert("default_value", frame=liga - 1)
        s.default_value = 8.0
        s.keyframe_insert("default_value", frame=liga)
    return liga


def aparece(obj, quadro):
    obj.hide_render = obj.hide_viewport = True
    obj.keyframe_insert("hide_render", frame=1)
    obj.keyframe_insert("hide_viewport", frame=1)
    obj.keyframe_insert("hide_render", frame=quadro - 1)
    obj.keyframe_insert("hide_viewport", frame=quadro - 1)
    obj.hide_render = obj.hide_viewport = False
    obj.keyframe_insert("hide_render", frame=quadro)
    obj.keyframe_insert("hide_viewport", frame=quadro)


def efeito(e, liga, fps, total):
    mat = material("luz_efeito", e["cor"], 6.0)
    for (x, y) in e["aneis"]:
        bpy.ops.mesh.primitive_torus_add(major_radius=e["raio_anel"], minor_radius=0.012, location=(x, y, 0.004))
        o = objeto_ativo(mat)
        o.scale.z = 0.05  # achatado no piso: a luz deita no chão, não flutua
        aparece(o, liga)
    bpy.ops.mesh.primitive_uv_sphere_add(radius=0.035, location=(0, 0, 0.01))
    presa = objeto_ativo(mat)
    presa.scale.z = 0.15
    presa.name = "presa"
    aparece(presa, liga)
    caminho = e["presa_caminho"]
    volta = int(e["presa_volta_s"] * fps)
    n = len(caminho) - 1
    f = liga
    while f <= total:
        t = ((f - liga) % volta) / volta * n
        i = int(t)
        a, b = caminho[i], caminho[min(i + 1, n)]
        k = t - i
        presa.location = (a[0] + (b[0] - a[0]) * k, a[1] + (b[1] - a[1]) * k, 0.01)
        presa.keyframe_insert("location", frame=f)
        f += 2
    return presa


def animal(a, presa, liga, fps, total):
    escala = {"pequeno": 0.7, "medio": 1.0, "grande": 1.3}[a.get("tamanho", "medio")]
    mat = material("animal", a["cor"])
    bpy.ops.mesh.primitive_uv_sphere_add(radius=0.18 * escala, location=(0, 0, 0))
    corpo = objeto_ativo(mat)
    corpo.scale = (0.6, 1.4, 0.7)
    corpo.name = "corpo"
    bpy.ops.mesh.primitive_uv_sphere_add(radius=0.09 * escala, location=(0, 0, 0))
    cabeca = objeto_ativo(mat)
    cabeca.name = "cabeca"
    bpy.ops.mesh.primitive_cone_add(radius1=0.03 * escala, depth=0.08 * escala, location=(0, -0.09 * escala, 0))
    focinho = objeto_ativo(mat)
    focinho.rotation_euler = (math.radians(90), 0, 0)
    focinho.parent = cabeca
    focinho.location = (0, -0.09 * escala, 0)
    atraso = int(a.get("segue_presa_com_atraso_s", 0.35) * fps)
    x0, y0 = a["inicio"]
    altura_corpo = 0.2 * escala
    for f in range(1, total + 1, 2):
        bpy.context.scene.frame_set(max(liga, f - atraso))
        alvo = presa.matrix_world.translation.copy() if f >= liga else Vector((x0 - 0.2, y0 + 0.3, 0))
        if f < liga:  # antes de ligar: deitado, entediado, parado
            pos = Vector((x0, y0, altura_corpo * 0.6))
        else:
            atual = corpo.location.copy()
            dirv = Vector((alvo.x - atual.x, alvo.y - atual.y, 0))
            passo = min(dirv.length, 0.06)
            pos = atual + (dirv.normalized() * passo if dirv.length > 0.25 else Vector((0, 0, 0)))
            pos.z = altura_corpo + (0.03 * abs(math.sin(f * 0.6)))
        corpo.location = pos
        dirv = Vector((alvo.x - pos.x, alvo.y - pos.y, 0))
        if dirv.length > 0.01:
            corpo.rotation_euler = (0, 0, math.atan2(dirv.y, dirv.x) - math.radians(90) + math.radians(180))
        corpo.keyframe_insert("location", frame=f)
        corpo.keyframe_insert("rotation_euler", frame=f)
        frente = Vector((math.sin(corpo.rotation_euler.z), -math.cos(corpo.rotation_euler.z), 0))
        cabeca.location = pos + frente * 0.24 * escala + Vector((0, 0, 0.12 * escala))
        cabeca.keyframe_insert("location", frame=f)
    if a.get("olha_para_presa", True):  # GAZE LOCK exato
        c = cabeca.constraints.new("TRACK_TO")
        c.target = presa
        c.track_axis = "TRACK_NEGATIVE_Y"
        c.up_axis = "UP_Z"
    return corpo, cabeca


def camera(c, fps, total):
    x, y = c["posicao"]
    bpy.ops.object.camera_add(location=(x, y, c["altura_m"]))
    cam = bpy.context.active_object
    cam.data.lens = c.get("lente_mm", 24)
    bpy.context.scene.camera = cam
    bpy.ops.object.empty_add(location=tuple(c["mira"]))
    mira = bpy.context.active_object
    t = cam.constraints.new("TRACK_TO")
    t.target = mira
    t.track_axis = "TRACK_NEGATIVE_Z"
    t.up_axis = "UP_Y"
    random.seed(7)
    amp = c.get("tremida", 0.5)
    for f in range(1, total + 1, 3):  # mão de quem segura o celular: micro tremida + deriva lenta
        cam.location = (x + amp * 0.02 * math.sin(f * 0.21) + random.uniform(-1, 1) * amp * 0.006,
                        y + random.uniform(-1, 1) * amp * 0.006,
                        c["altura_m"] + amp * 0.015 * math.sin(f * 0.13) + random.uniform(-1, 1) * amp * 0.005)
        cam.keyframe_insert("location", frame=f)


def render(saida, spec, total):
    sc = bpy.context.scene
    sc.render.resolution_x, sc.render.resolution_y = spec["resolucao"]
    sc.render.fps = spec["fps"]
    sc.frame_start, sc.frame_end = 1, total
    # guia de movimento: Cycles na CPU com poucas amostras (Workbench/EEVEE exigem placa de vídeo)
    sc.render.engine = "CYCLES"
    sc.cycles.device = "CPU"
    sc.cycles.samples = int(spec.get("amostras_guia", 6))
    sc.cycles.use_denoising = False
    sc.render.resolution_percentage = int(spec.get("escala_guia_pct", 67))
    pasta_q = os.path.join(saida, "quadros")
    os.makedirs(pasta_q, exist_ok=True)
    sc.render.image_settings.file_format = "PNG"
    sc.render.filepath = os.path.join(pasta_q, "q_")
    bpy.ops.render.render(animation=True)
    subprocess.run(["ffmpeg", "-v", "error", "-y", "-framerate", str(spec["fps"]), "-i",
                    os.path.join(pasta_q, "q_%04d.png"), "-vf", "scale=trunc(iw/2)*2:trunc(ih/2)*2", "-c:v", "libx264", "-pix_fmt", "yuv420p",
                    os.path.join(saida, "guia_movimento.mp4")], check=True)
    # quadros-chave com luz real (Cycles, CPU, poucas amostras)
    sc.render.resolution_percentage = 100
    sc.cycles.samples = 32
    for nome, f in (("quadro_inicial", 1), ("quadro_efeito", int(total * 0.6))):
        sc.frame_set(f)
        sc.render.filepath = os.path.join(saida, nome + ".png")
        bpy.ops.render.render(write_still=True)
    bpy.ops.wm.save_as_mainfile(filepath=os.path.join(saida, "cena.blend"))


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("spec")
    ap.add_argument("--saida", default="saida_cena_3d")
    a = ap.parse_args(sys.argv[sys.argv.index("--") + 1:] if "--" in sys.argv else None)
    spec = json.load(open(a.spec))
    os.makedirs(a.saida, exist_ok=True)
    total = int(spec["duracao_s"] * spec["fps"])
    limpar()
    bpy.ops.scene.new(type="EMPTY") if not bpy.context.scene else None
    comodo(spec["comodo"])
    liga = produto(spec["produto"], spec["fps"])
    presa = efeito(spec["efeito"], liga, spec["fps"], total)
    animal(spec["animal"], presa, liga, spec["fps"], total)
    camera(spec["camera"], spec["fps"], total)
    render(os.path.abspath(a.saida), spec, total)
    print(os.path.abspath(a.saida))


if __name__ == "__main__":
    main()
