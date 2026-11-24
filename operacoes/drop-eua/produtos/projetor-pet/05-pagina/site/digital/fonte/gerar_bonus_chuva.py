"""Gera o bônus 2: Rainy Day Play Plan (cachorro) em HTML e PDF (Playwright).
Uso: python3 gerar_bonus_chuva.py  -> ../rainy-day-play-plan-dog.pdf
Por quê (persona C, 03-persona.md): ângulo "dia de chuva sem culpa" e objeção nº 2 ("my dog will ignore it / lose interest").
Texto: inglês americano, voz humana, sem promessa de saúde ou de comportamento garantido. Só brincadeiras com o que a pessoa tem em casa.
Versão de gato: escrever quando a frente de gato tiver fornecedor.
"""
import os, subprocess
AQUI = os.path.dirname(os.path.abspath(__file__))

CSS = """
@page{size:Letter;margin:0.7in 0.75in}
body{font:11.5pt/1.55 Georgia,'Times New Roman',serif;color:#232046}
h1{font:800 30pt/1.1 -apple-system,'Segoe UI',Helvetica,Arial,sans-serif;margin:0 0 6pt}
h2{font:700 15pt/1.2 -apple-system,'Segoe UI',Helvetica,Arial,sans-serif;margin:20pt 0 6pt}
.kicker{font:700 9pt -apple-system,Helvetica,Arial,sans-serif;letter-spacing:.12em;text-transform:uppercase;color:#e8531f}
.lead{font-size:13pt;color:#4b4868}
.cover{background:#232046;color:#fff8ef;border-radius:14pt;padding:28pt 26pt;margin-bottom:16pt}
.cover h1{color:#fff}.cover .lead{color:#d9d6ea}
.g{border:1.5pt solid #efe3d3;border-radius:10pt;padding:10pt 14pt;margin:10pt 0;background:#fff8ef;page-break-inside:avoid}
.g b{font-family:-apple-system,Helvetica,Arial,sans-serif;font-size:12.5pt}
.g .m{font:600 9pt -apple-system,Helvetica,Arial,sans-serif;color:#1f8f6a;letter-spacing:.04em;text-transform:uppercase}
.g p{margin:4pt 0 0}
table{width:100%;border-collapse:collapse}td{padding:5pt 8pt;border-bottom:1pt solid #efe3d3;font-size:11pt;vertical-align:top}
td:first-child{font-weight:700;font-family:Helvetica,Arial,sans-serif;color:#e8531f;white-space:nowrap}
.src{font-size:9pt;color:#7d7a93}
"""

GAMES = [
 ("Find It", "Nose · 5 min", "Show him a treat, let him watch you put it under one of three cups, then say \"find it\". When he gets it every time, hide treats around one room while he waits in another."),
 ("The Towel Burrito", "Nose · 3 min", "Roll a few small treats inside an old towel, loosely. He noses and paws it open. Supervise so the towel doesn't become a chew toy."),
 ("Muffin Tin Puzzle", "Nose · 5 min", "Treats in a few cups of a muffin tin, tennis balls on top of every cup. He has to move the balls to find which ones pay off."),
 ("Box Shred", "Nose · 5 min", "An empty cardboard box with crumpled paper and a few treats inside. Some dogs love tearing it apart. Pick up the pieces after, and skip it if he swallows cardboard."),
 ("Hallway Fetch", "Move · 5 min", "A soft toy, a long hallway, closed doors on the sides. Short throws, slow rolls for older dogs."),
 ("Stair Sprints", "Move · 3 min", "Only for healthy adult dogs who are steady on stairs. Toss a toy up a few steps, call him back down slowly. Skip it for puppies, seniors and long-backed breeds."),
 ("Tug With Rules", "Move · 5 min", "He grabs on \"take it\", lets go on \"drop it\" for a treat, then you start again. The rules are the game."),
 ("Hide and Seek", "Bond · 5 min", "Someone holds him (or ask for a sit-stay), you hide behind a door, then call once. Easy spots first."),
 ("Name That Toy", "Brain · 5 min", "Pick one toy, say its name each time he touches it, reward. After a few days, put it next to another toy and ask for it by name."),
 ("Two-Minute Tricks", "Brain · 2 min", "Spin, paw, touch your hand with his nose. Short and fun, and stop while he still wants more."),
 ("Lights and Catch", "The kit · 10 min", "Dim the lights, run the projector for 5 to 8 minutes, then switch it off and drop the squeaky squirrel. See the First Hunt Guide."),
 ("The Calm-Down Chew", "Rest · 15 min", "End every rainy-day session with something to chew in his bed: a safe chew or a stuffed food toy. It tells him play time is over."),
]

PLAN = [("Monday", "Find It + Two-Minute Tricks"), ("Tuesday", "Lights and Catch + Calm-Down Chew"),
        ("Wednesday", "Muffin Tin Puzzle + Hallway Fetch"), ("Thursday", "Tug With Rules + Name That Toy"),
        ("Friday", "Lights and Catch + Calm-Down Chew"), ("Weekend rain", "Hide and Seek + Box Shred + Calm-Down Chew")]

def html():
    gs = "".join(f'<div class="g"><div class="m">{m}</div><b>{i}. {t}</b><p>{d}</p></div>' for i, (t, m, d) in enumerate(GAMES, 1))
    rows = "".join(f"<tr><td>{a}</td><td>{b}</td></tr>" for a, b in PLAN)
    return f"""<!doctype html><html><head><meta charset="utf-8"><style>{CSS}</style></head><body>
<div class="cover"><div class="kicker" style="color:#ffb547">Bonus · Rainy Day Play Plan · for dogs</div>
<h1>No walk today?<br>Here's the plan.</h1><p class="lead">12 indoor games that use things you already have, and a simple week to rotate them, so the projector and the toys stay new.</p></div>
<h2>How to use this</h2>
<ul><li>Pick two games a day. Ten to twenty minutes total is plenty for most dogs.</li>
<li>Mix one "nose" or "brain" game with one "move" game. Sniffing and thinking tire a lot of dogs out as much as running.</li>
<li>Stop while he's still having fun, and always finish with something calm.</li></ul>
<h2>The 12 games</h2>{gs}
<h2>A rainy week</h2><table>{rows}</table>
<p>Rotating keeps things new. Most dogs lose interest in a toy they see every day, so put the projector and the squirrel away between sessions.</p>
<h2>Safety</h2><ul><li>Clear the floor of anything he could slip on or knock over.</li><li>Use small, soft treats and count them into his daily food.</li><li>Skip running and stair games for puppies, seniors, and dogs with joint or back problems.</li><li>Indoor games add to walks and outdoor play; they don't replace them.</li></ul>
<p class="src">General play ideas for healthy dogs, not veterinary or training advice. If your dog has a health or behavior problem, talk to your vet. Questions or a refund: reply to your order email.</p>
</body></html>"""

p = os.path.join(AQUI, "rainy-day-play-plan-dog.html"); open(p, "w").write(html())
js = """const {chromium}=require('playwright');(async()=>{const b=await chromium.launch();const p=await b.newPage();
await p.goto('file://'+process.argv[2]+'/rainy-day-play-plan-dog.html');
await p.pdf({path:process.argv[2]+'/../rainy-day-play-plan-dog.pdf',format:'Letter',printBackground:true});await b.close();})();"""
jp = os.path.join(AQUI, "_pdf2.js"); open(jp, "w").write(js)
subprocess.run(["node", jp, AQUI], check=True)
print("ok")
