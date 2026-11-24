/* ============================================================
   CONFIG DA PÁGINA. Tudo que depende da CJ ou do Kenui mora aqui.
   Campo com valor "" ou null = pendente: a página mostra uma
   marca amarela no modo prévia e NUNCA inventa o valor.
   Os PREÇOS precisam ser iguais aos de ../config.php (o servidor
   é quem cobra; este arquivo só mostra).
   ============================================================ */
window.CONFIG = {
  preview: true,                 // true = faixa amarela e campos pendentes destacados. false só para publicar.
  brand: "Little Catch Co.",     // decidido 24/11 (a IA decide; Kenui): domínio littlecatchco.com livre na checagem, a comprar com o aval dele
  supportEmail: "help@littlecatchco.com", // funciona depois de comprar o domínio e criar a caixa na Hostinger
  legalName: "",                 // razão social exata do CNPJ (Kenui vai mandar). Não inventar.
  legalId: "52.594.756/0001-00", // CNPJ do Kenui (24/11), aparece no rodapé como "Brazil, CNPJ ..."

  currency: "USD",
  kits: {
    one: { price: 34.99, label: "1 kit" },
    two: { price: 59.99, label: "2 kits", save: 10.00 }
  },
  bump:     { price: 7.99 },
  upsell:   { price: 24.99, regular: 34.99 },
  downsell: { price: 7.99, on: true, kind: "plush" },  // control: pelúcias extras só para quem não pegou o bump
  bonus: { on: true, name: "First Hunt Guide" },        // PDF grátis por e-mail (objeções 2 e 4 da persona)

  delivery: "",        // ex.: "7–12 business days"  [PENDENTE: prazo real da CJ]
  shipsFrom: "",       // ex.: "our US warehouse" ou "our partner warehouse overseas"  [PENDENTE: CJ]
  xmasCutoff: "",      // ex.: "Dec 10" só se o prazo garantir  [PENDENTE]
  specs: {
    size: "",          // ex.: "about 4.5 in tall, roughly a coffee mug"  [PENDENTE: CJ]
    power: "",         // ex.: "USB cable (included)" ou "rechargeable, USB-C"  [PENDENTE: CJ]
    ledNotLaser: null  // true só se a ficha da CJ confirmar projeção LED (não laser)
  },

  heroVideo: "",       // caminho do vídeo real do fornecedor (mp4/webm). Vazio = ilustração.
  stripePk: "pk_test_51UefZS9fGfTx7kShypVC0uVAM4MfKRZo7eVHwDuMLJssfF90tIY5OdUJnFZdz3PYSQUH2zFhYMmE15bMZhpK55zI00bIwNKQXd", // TESTE conta Brasil (Kenui, 24/11). Chave pública: pode ficar no Git.
  metaPixelId: "",     // vazio = pixel desligado
  clarityId: "",       // vazio = Clarity desligado
  defaultPet: "cat",
  defaultAngle: { cat: "pega", dog: "sozinho" },

  /* ---------- TESTE A/B (um por vez). Plano e ordem: ../../testes/fila.md ----------
     active = id do teste rodando ("" = nenhum). Sorteio fixo por visitante (a mesma
     pessoa sempre vê a mesma versão). Preços de teste precisam existir também em
     ../config.php ('testes'), porque é o servidor quem cobra. */
  tests: {
    active: "",
    list: {
      "T1-angulo":     { split: 50, note: "ângulo vem do anúncio (?a=); este teste só registra", variants: { A: {}, B: {} } },
      "T2-kit2-mix":   { split: 50, variants: { A: {}, B: { "kits.two.highlight": true } } },
      "T3-preco-front":{ split: 50, variants: { A: {}, B: { "kits.one.price": 39.99, "kits.two.price": 69.99, "kits.two.save": 9.99 } } },
      "T4-bump-preco": { split: 50, variants: { A: {}, B: { "bump.price": 5.99 } } },
      "T5-upsell-preco":{ split: 50, variants: { A: {}, B: { "upsell.price": 19.99 } } },
      "T6-downsell-digital":{ split: 50, variants: { A: {}, B: { "downsell.kind": "guide", "downsell.price": 9.00 } } }
    }
  }
};

/* ---------- visitante e sorteio do teste (roda antes de pintar) ---------- */
(function(C){
  function ck(n){var m=document.cookie.match("(^|; )"+n+"=([^;]*)");return m?decodeURIComponent(m[2]):""}
  var vid=ck("vid"); try{vid=vid||localStorage.getItem("vid")}catch(e){}
  if(!vid){vid=Date.now().toString(36)+Math.random().toString(36).slice(2,10);}
  document.cookie="vid="+vid+"; path=/; max-age=31536000; SameSite=Lax"+(location.protocol==="https:"?"; Secure":"");
  try{localStorage.setItem("vid",vid)}catch(e){}
  C.vid=vid;
  // FNV-1a 32 bits: igual ao do servidor (api/_lib.php -> variante())
  function h(s){var x=0x811c9dc5;for(var i=0;i<s.length;i++){x^=s.charCodeAt(i);x=Math.imul(x,16777619)>>>0}return x}
  var id=C.tests&&C.tests.active, t=id&&C.tests.list[id];
  C.variant="";
  if(t){
    var forced=new URLSearchParams(location.search).get("v");   // ?v=B só para conferir a versão (não use em anúncio)
    var v=(forced==="A"||forced==="B")?forced:((h(id+":"+vid)%100)<(t.split||50)?"B":"A");
    C.variant=id+":"+v;
    var o=t.variants[v]||{};
    Object.keys(o).forEach(function(path){var k=path.split("."),obj=C;for(var i=0;i<k.length-1;i++){obj[k[i]]=obj[k[i]]||{};obj=obj[k[i]]}obj[k[k.length-1]]=o[path]});
  }
})(window.CONFIG);
