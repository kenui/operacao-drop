#!/usr/bin/env python3
"""Molde de refação de viral: transforma a análise de um vídeo que viralizou num molde de estrutura.

Entrada: a pasta gerada por ferramentas/ver-video (precisa do analise.json).
Saída: molde.md (tabela por cena com tempo, função provável, ritmo de fala e espaço para a nossa
versão) e molde.json (o mesmo em dados, para o prompt de vídeo).

Copiamos ESTRUTURA e RITMO: duração de cada cena, ordem das funções (gancho, prova, efeito,
reação, chamada), densidade de fala, tipo de enquadramento. NÃO copiamos rosto, voz, áudio,
música, texto, marca nem trechos do original. Isso é regra (material de terceiros = estudo).

Uso: python3 molde_viral.py PASTA_DA_ANALISE [--produto "nome"] [--saida PASTA]
"""
import argparse, json, os, re


def funcao(i, n, ini, fim, dur):
    if ini < 0.5:
        return "gancho"
    if i == n - 1 and dur - fim < 0.6:
        return "fecho / chamada"
    meio = (ini + fim) / 2 / dur
    if meio < 0.35:
        return "dor ou contexto"
    if meio < 0.75:
        return "demonstração / efeito"
    return "reação / prova"


def falas_da_cena(falas, ini, fim):
    return [f for f in falas if f["ini"] < fim and f["fim"] > ini]


def tipo_frase(txt):
    t = txt.strip()
    if t.endswith("?"):
        return "pergunta"
    if t.endswith("!"):
        return "exclamação"
    if re.match(r"(?i)^(okay|ok|so|wait|guys|look|watch|this)", t):
        return "abertura falada"
    return "afirmação"


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("analise")
    ap.add_argument("--produto", default="[nosso produto]")
    ap.add_argument("--saida")
    a = ap.parse_args()
    d = json.load(open(os.path.join(a.analise, "analise.json")))
    dur = d["duracao"]
    cenas = d["cenas"]
    falas = d.get("falas") or []
    n = len(cenas)
    linhas, dados = [], []
    for i, c in enumerate(cenas):
        ini, fim = c["ini"], c["fim"]
        fs = falas_da_cena(falas, ini, fim)
        palavras = sum(len(f["texto"].split()) for f in fs)
        ritmo = palavras / max(fim - ini, 0.1)
        tipos = ", ".join(sorted({tipo_frase(f["texto"]) for f in fs})) or "sem fala"
        fn = funcao(i, n, ini, fim, dur)
        dados.append({"cena": i + 1, "ini": ini, "fim": fim, "duracao": round(fim - ini, 2),
                      "pct": round(100 * (fim - ini) / dur), "funcao": fn, "palavras": palavras,
                      "palavras_por_s": round(ritmo, 1), "tipo_fala": tipos, "quadro": c["quadro"]})
        linhas.append(f"| {i+1} | {ini:.1f}-{fim:.1f} s ({fim-ini:.1f} s, {round(100*(fim-ini)/dur)}%) | {fn} | "
                      f"{palavras} palavras, {ritmo:.1f}/s, {tipos} | {c['quadro']} | | |")
    cortes_por_s = n / dur
    md = [f"# Molde de refação: {d['video']} → {a.produto}", "",
          f"Duração {dur:.1f} s, {n} cenas ({cortes_por_s:.2f} cortes/s), "
          f"{sum(x['palavras'] for x in dados)} palavras faladas.",
          "Copiar só estrutura e ritmo. Nunca rosto, voz, áudio, música, texto, marca ou trechos do original.", "",
          "| # | tempo | função provável | fala no original (ritmo, tipo) | quadro de referência | enquadramento (preencher olhando o quadro) | NOSSA cena (mesma duração e função, com o nosso produto) |",
          "|---|---|---|---|---|---|---|"] + linhas + [
          "", "## Por que este viral merece refação (preencher, ver arsenal.md)",
          "- Visualizações e data:", "- Mesmo público e mesma dor que o nosso produto?",
          "- O efeito do nosso produto cabe na mesma função de cena?",
          "- O que faz ele funcionar (gancho, virada, reação):",
          "", "## Diferenças obrigatórias",
          "- Pessoa, voz, casa, falas e texto são nossos.", "- O produto e o efeito são os reais (tabela de verdade).",
          "- Sem música do original. Música só licenciada ou nenhuma."]
    out = a.saida or a.analise
    os.makedirs(out, exist_ok=True)
    open(os.path.join(out, "molde.md"), "w").write("\n".join(md) + "\n")
    json.dump({"origem": d["video"], "duracao": dur, "cenas": dados}, open(os.path.join(out, "molde.json"), "w"),
              ensure_ascii=False, indent=1)
    print(os.path.join(out, "molde.md"))


if __name__ == "__main__":
    main()
