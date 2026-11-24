"""Gera o First Hunt Guide (gato e cachorro) em HTML e depois PDF (Playwright).
Uso: python3 gerar_guias.py  (gera ../first-hunt-guide-cat.pdf e ../first-hunt-guide-dog.pdf)
Texto: inglês americano, passado pela voz humana; sem promessa de saúde ou comportamento garantido.
"""
import os, subprocess, json

AQUI = os.path.dirname(os.path.abspath(__file__))

CSS = """
@page{size:Letter;margin:0.7in 0.75in}
body{font:11.5pt/1.55 Georgia,'Times New Roman',serif;color:#1d1a16}
h1{font:800 30pt/1.1 -apple-system,'Segoe UI',Helvetica,Arial,sans-serif;margin:0 0 6pt}
h2{font:700 15pt/1.2 -apple-system,'Segoe UI',Helvetica,Arial,sans-serif;margin:22pt 0 6pt;color:#14121a}
.kicker{font:600 9pt -apple-system,Helvetica,Arial,sans-serif;letter-spacing:.12em;text-transform:uppercase;color:#c94a06}
.lead{font-size:13pt;color:#4a443c}
.cover{background:#14121a;color:#f6efe6;border-radius:14pt;padding:28pt 26pt;margin-bottom:18pt}
.cover h1{color:#fff}.cover .lead{color:#d9d0c4}
.card{border:1.5pt solid #ece4d8;border-radius:10pt;padding:12pt 14pt;margin:12pt 0;background:#fbf7f1;page-break-inside:avoid}
ol,ul{padding-left:18pt}li{margin:4pt 0}
.tl td{padding:5pt 8pt;border-bottom:1pt solid #ece4d8;vertical-align:top;font-size:11pt}
.tl td:first-child{white-space:nowrap;font-weight:700;font-family:Helvetica,Arial,sans-serif;color:#c94a06}
.src{font-size:9pt;color:#7a7268}
tr,li{page-break-inside:avoid}h2{page-break-after:avoid}
"""

G = {
 "cat": dict(
  pet="cat", she="she", her="her", Her="Her", plush="catnip mouse",
  sub="How to set up the first night, what to do if your cat ignores it, and how to end every session with a real catch.",
  first=[
   "Switch it on, then sit down and stay quiet. Don't carry your cat to it or push her face toward the light.",
   "Most cats watch before they move: a long stare, a low crouch, the tail tip twitching, the back end wiggling. That's interest. Give her three to five minutes before you decide anything.",
   "If she pounces, let her. If she looks under her paw for the mouse that isn't there, that's normal. It's exactly why the plush is in the box."],
  session=[("0:00","Lights low, projector on, you on the couch or the floor."),
           ("0:00 to 8:00","She chases. You don't need to do anything."),
           ("When she slows down","Sitting, lying down, grooming, or looking at you: that's the signal. Switch it off."),
           ("Right away","Toss the catnip mouse a foot or two in front of her. Let her grab it, kick it, carry it off."),
           ("After","A meal or a treat. Cats tend to follow a hunt, catch, eat, groom, sleep pattern, and a snack closes the loop.")],
  why="Light is great for the chase, but she can never pin it down. Ending with something she can actually catch gives the game a finish. Many vets and feline behavior resources suggest exactly that for any light toy.",
  ignore=[
   "Make it darker. A lamp in the corner is fine; daylight washes the shapes out.",
   "Use bare, smooth floor. Busy rugs hide the mice.",
   "Angle it so the shapes run away from her, not at her. Prey runs away.",
   "Try before dinner, when a lot of cats are more in the mood to hunt.",
   "Sit on the floor with her. Some cats play more when you're at their level.",
   "Try a few different nights. Some cats warm up slowly.",
   "And honestly: some cats never care. That's what the 30-day refund is for. Email us."],
  wound=[
   "End sooner and always finish with the plush mouse, so the game has an ending.",
   "If she starts chasing reflections or shadows afterward, shorten the sessions and keep them to a set time of day.",
   "Don't leave it running while you're out of the room."],
  fresh="Novelty matters. Three or four sessions a week usually keep it interesting longer than every day. Put the catnip mouse away between sessions so it stays special. Rub it between your hands before tossing it to wake up the catnip smell.",
  safety=[
   "Use it with you in the room. Don't leave it on unattended.",
   "Don't let anyone look straight into the lens.",
   "Keep any cable out of reach of a cat who chews.",
   "Check the plush for loose seams or parts now and then, and replace it when it's worn.",
   "Catnip: many adult cats react to it, but some never do, and kittens under about six months usually don't. If she ignores the smell, that's genetics, not the toy."],
  night="If your cat runs the halls at 3 a.m., try a session about an hour before your bedtime, followed by her meal. It may help her settle at night. Every cat is different, so give it a week before you judge.",
 ),
 "dog": dict(
  pet="dog", she="he", her="his", Her="His", plush="squeaky squirrel",
  sub="How to set up the first night, what to do if your dog ignores it or barks at it, and how to end every session with a real toy in his mouth.",
  first=[
   "Switch it on, then sit down. Let him find it on his own.",
   "Dogs often sniff first: the projector, then the circles of light. A play bow (front down, back end up), a bounce to the side, or one sharp bark all mean he's figuring it out.",
   "Some dogs go straight to chasing, others need a couple of evenings. Both are normal."],
  session=[("0:00","Lights low, projector on, you on the couch."),
           ("0:00 to 8:00","He chases from circle to circle."),
           ("When he slows down","Or before he gets too wound up: switch it off."),
           ("Right away","Drop the squeaky squirrel in front of him. Let him grab it, shake it, squeak it."),
           ("After","If you want the toy back, trade it for a treat and say \"drop it\". Then calm time: a chew, his bed, water.")],
  why="Lights alone never give him the part he likes best: something to grab and shake. Ending with the squeaky toy gives the game a clear finish, so the light going off doesn't feel like the fun just vanished.",
  ignore=[
   "Make it darker. A lamp is fine; daylight washes the critters out.",
   "Use bare, smooth floor. Busy rugs hide the shapes.",
   "Point to the shapes and get excited with him for the first minute. Many dogs follow your energy.",
   "Try after a short sniff walk, not when he's exhausted or just ate.",
   "Try a few different evenings.",
   "Some dogs just aren't interested in lights. That's what the 30-day refund is for. Email us."],
  wound=[
   "Some dogs bark at the lights at first. Keep sessions short (five minutes is fine) and end while he's still having fun, not when he's frantic.",
   "Always finish with the squeaky toy so the game has an ending.",
   "If he starts chasing reflections or shadows around the house afterward, cut back and keep sessions to a set time of day.",
   "We can't promise a quiet dog. If barking doesn't settle down after a few sessions, this may not be the toy for him."],
  fresh="Novelty matters. Three or four sessions a week usually keep it exciting longer than every day. Put the squeaky squirrel away between sessions so it stays the special toy.",
  safety=[
   "Use it with you in the room. Don't leave it on unattended.",
   "Big, excited dogs can knock it over or try to chew it. Put it on a low, stable spot out of tail range.",
   "Don't let anyone look straight into the lens.",
   "Keep any cable out of reach of a dog who chews.",
   "Squeaky toys aren't chew toys. Take it away when the squeaker comes out or the seams open, and replace it."],
  night="Rainy day, no walk? A short session in the late afternoon, then a chew in his bed, gives him something to do in the living room. It won't replace a walk, but it beats staring at you for an hour.",
 ),
}

def html(g):
    li = lambda xs: "".join("<li>%s</li>" % x for x in xs)
    rows = "".join("<tr><td>%s</td><td>%s</td></tr>" % r for r in g["session"])
    return f"""<!doctype html><html><head><meta charset="utf-8"><style>{CSS}</style></head><body>
<div class="cover"><div class="kicker" style="color:#ffb547">First Hunt Guide · for {g['pet']}s</div>
<h1>Chase the light.<br>Then catch something real.</h1><p class="lead">{g['sub']}</p></div>
<h2>Before you switch it on (2 minutes)</h2>
<ul><li><b>Dim the room.</b> Evening with a lamp, or curtains closed. In daylight the shapes are faint.</li>
<li><b>Clear some floor.</b> About six feet of bare, smooth floor in front of the projector.</li>
<li><b>Set it low and steady.</b> On the floor or a low table, pointed at the open floor.</li>
<li><b>Put the {g['plush']} in your pocket.</b> You'll need it at the end.</li></ul>
<h2>The first night</h2><ul>{li(g['first'])}</ul>
<h2>The 10-minute hunt</h2><table class="tl" style="width:100%;border-collapse:collapse">{rows}</table>
<div class="card"><b>Why end with the plush?</b><br>{g['why']}</div>
<h2>If {g['she']} ignores it</h2><ul>{li(g['ignore'])}</ul>
<h2>If {g['she']} gets too wound up</h2><ul>{li(g['wound'])}</ul>
<h2>Keep it new</h2><p>{g['fresh']}</p>
<h2>A note for your evenings</h2><p>{g['night']}</p>
<h2>Safety</h2><ul>{li(g['safety'])}</ul>
<div class="card"><b>Quick card (stick it on the fridge)</b><br>
Lights low · bare floor · switch on and sit · 5 to 8 minutes of chase · switch off when {g['she']} slows down · toss the {g['plush']} · treat or meal · put the toy away.</div>
<p class="src">This guide shares general play tips for healthy pets. It isn't veterinary advice. If your pet has a health or behavior problem, talk to your vet. Tips draw on public guidance such as the AAFP/ISFM Feline Environmental Needs Guidelines (2013) and VCA Animal Hospitals' articles on play and nighttime activity.<br>
Questions or a refund: reply to your order email.</p>
</body></html>"""

for k, g in G.items():
    p = os.path.join(AQUI, f"first-hunt-guide-{k}.html")
    open(p, "w").write(html(g))
js = """const {chromium}=require('playwright');(async()=>{const b=await chromium.launch();const p=await b.newPage();
for(const k of ['cat','dog']){await p.goto('file://'+process.argv[2]+'/first-hunt-guide-'+k+'.html');
await p.pdf({path:process.argv[2]+'/../first-hunt-guide-'+k+'.pdf',format:'Letter',printBackground:true});}
await b.close();})();"""
jp = os.path.join(AQUI, "_pdf.js"); open(jp, "w").write(js)
subprocess.run(["node", jp, AQUI], check=True)
print("ok")
