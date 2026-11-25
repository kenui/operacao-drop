/* ============================================================
   CONFIG DA PÁGINA. Tudo que depende da CJ ou do Kenui mora aqui.
   Campo com valor "" ou null = pendente: a página mostra uma
   marca amarela no modo prévia e NUNCA inventa o valor.
   Os PREÇOS precisam ser iguais aos de ../config.php (o servidor
   é quem cobra; este arquivo só mostra).
   ============================================================ */
window.CONFIG = {
  preview: false,                // 24/11: no ar com cobrança real ("pode ligar" do Kenui). Campos pendentes somem da página (não aparecem em amarelo).
  brand: "Little Catch Co.",     // decidido 24/11 (a IA decide; Kenui): domínio littlecatchco.com livre na checagem, a comprar com o aval dele
  supportEmail: "help@littlecatchco.com", // funciona depois de comprar o domínio e criar a caixa na Hostinger
  legalName: "52.594.756 KENUI SILVEIRA ALVES DE OLIVEIRA CABRAL", // nome empresarial do MEI (CCMEI, 24/11)
  legalId: "52.594.756/0001-00", // CNPJ do Kenui (24/11), aparece no rodapé como "Brazil, CNPJ ..."

  currency: "USD",
  kits: {
    one: { price: 49.99, label: "1 kit" },   // decisão do Kenui 24/11 (kit projetor + pelúcia a US$49,99)
    two: { price: 89.99, label: "2 kits", save: 9.99 }   // save = 2 × 49,99 − 89,99
  },
  bump:     { price: 9.99 },
  upsell:   { price: 39.99, regular: 49.99 },  // regular = preço real de 1 kit
  downsell: { price: 29.99, on: true, kind: "kit" },  // 25/11 (D16): o 2º kit com desconto maior para quem recusou o upsell. Preço real vem do servidor (T13).
  extra:    { price: 29.99, on: true, kind: "kit" },  // 25/11 (D16): 3º kit para quem aceitou o upsell.  // 24/11: quem ACEITA o upsell vê pelúcias para o 2º kit. Preço real vem do servidor (api/_precos.php, teste T8).
  /* Testes do pós-compra (T7, T8) rodam no servidor, sorteados pelo pedido: ver api/_precos.php -> testes_pos. */
  blocos: { porque: true },  // false esconde o bloco. "porque" = "Everything a Bare Projector Leaves Out" (24/11, objeções; teste T11)
  bonus: { on: true, name: "First Hunt Guide" },        // PDF grátis por e-mail (objeções 2 e 4 da persona)

  delivery: "7–15 days",  // CJ 24/11: CJPacket Sensitive, ~90% entregue em 4–12 dias + 1–3 de separação
  shipsFrom: "our partner warehouse in China",  // CJ 24/11: fornecedor sai da China
  xmasCutoff: "",      // ex.: "Dec 10" só se o prazo garantir  [PENDENTE]
  specs: {
    size: "",          // ex.: "about 4.5 in tall, roughly a coffee mug"  [PENDENTE: CJ]
    power: "",         // ex.: "USB cable (included)" ou "rechargeable, USB-C"  [PENDENTE: CJ]
    ledNotLaser: null  // true só se a ficha da CJ confirmar projeção LED (não laser)
  },

  heroVideo: "",       // caminho do vídeo real do fornecedor (mp4/webm). Vazio = ilustração.
  stripePk: "pk_live_51UfIRn5LJTgOWV2KA1Dj82PnzgRO5GUxeQ3lvq9msmKzqUteOROoBjGXtuk2iVY8k2OJU8Hf0zgRWVV07kcDbUST00cXPXrp0J", // REAL conta Brasil (Kenui ligou em 24/11 pela página de uso único). Chave pública: pode ficar no Git.
  metaPixelId: "545543757913721", // pixel do Meta (Kenui, 24/11). ID público.
  clarityId: "",       // vazio = Clarity desligado
  pets: ["dog"],       // só a versão cachorro (esquilo) tem fornecedor (CJ 24/11). Gato volta quando houver.
  defaultPet: "dog",
  defaultAngle: { cat: "pega", dog: "sozinho" },

  /* ---------- TESTE A/B (um por vez). Plano e ordem: ../../testes/fila.md ----------
     active = id do teste rodando ("" = nenhum). Sorteio fixo por visitante (a mesma
     pessoa sempre vê a mesma versão). Preços de teste precisam existir também em
     ../config.php ('testes'), porque é o servidor quem cobra. */
  tests: {
    active: "T11-porque-aqui",   // 24/11: pedido do Kenui (objeções na página). Só visual: o servidor não precisa saber.
    list: {
      "T1-angulo":     { split: 50, note: "ângulo vem do anúncio (?a=); este teste só registra", variants: { A: {}, B: {} } },
      "T2-kit2-mix":   { split: 50, variants: { A: {}, B: { "kits.two.highlight": true } } },
      "T3-preco-front":{ split: 50, variants: { A: {}, B: { "kits.one.price": 59.99, "kits.two.price": 109.99, "kits.two.save": 9.99, "upsell.regular": 59.99 } } },
      "T4-bump-preco": { split: 50, variants: { A: {}, B: { "bump.price": 7.99 } } },
      "T5-upsell-preco":{ split: 50, variants: { A: {}, B: { "upsell.price": 34.99 } } },
      "T6-downsell-digital":{ split: 50, variants: { A: {}, B: { "downsell.kind": "guide", "downsell.price": 9.00 } } },
      "T11-porque-aqui":{ split: 50, note: "A = com o bloco por que comprar aqui (padrão); B = sem. Decide por lucro por visitante", variants: { A: {}, B: { "blocos.porque": false } } }
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
