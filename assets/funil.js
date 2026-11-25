/* Upsell, downsell e extra de um clique. Demonstração quando ?o=demo (sem Stripe; ?ds=kit mostra o braço B do T7). */
(function(){
  var q=new URLSearchParams(location.search), order=q.get("o")||"", demo=order==="demo";
  var etapa=document.body.dataset.etapa, C=window.CONFIG, busy=false;
  var $=function(s){return document.querySelector(s)};
  function go(next){location.href=({downsell:"downsell.html",extra:"extra.html"}[next]||"obrigado.html")+"?o="+encodeURIComponent(order)+(q.get("ds")?"&ds="+q.get("ds"):"")}
  // preço e tipo vêm do servidor (ele é quem cobra e quem sorteia o teste do pós-compra); a página só mostra
  function money(c){return "$"+(c/100).toFixed(2)}
  function show(o,info){ if(typeof window.onOferta==="function") window.onOferta(o,info||{}); [].forEach.call(document.querySelectorAll("[data-o]"),function(el){var v=o[el.dataset.o]; if(typeof v==="number") el.textContent=money(v)}) }
  var dd={}; try{dd=JSON.parse(sessionStorage.getItem("demo_order")||"{}")}catch(e){}
  var ds=q.get("ds")==="kit";
  var demoOferta={upsell:Math.round(C.upsell.price*100),downsell:ds?2999:Math.round(C.downsell.price*100),downsell_kind:ds?"kit":(C.downsell.kind||"plush"),extra:Math.round(((C.extra||{}).price||7.99)*100),extra_kind:(C.extra||{}).kind||"plush",regular:Math.round(C.kits.one.price*100)};
  var petS=""; try{petS=sessionStorage.getItem("pet")||""}catch(e){}
  if(demo||etapa!=="downsell") show(demoOferta,{pet:dd.pet||petS||"dog",bump:dd.bump?1:0}); // downsell espera o servidor (o tipo muda no T7)
  if(!demo&&order) fetch("api/pedido.php?o="+encodeURIComponent(order)).then(function(r){return r.json()}).then(function(j){if(j.oferta) show(j.oferta,j)}).catch(function(){});
  function msg(t){$("#err").textContent=t||""}
  track(etapa+"_view",{pedido:order});
  function decide(acao){
    if(busy)return; busy=true; msg("");
    var pet=$("#gpet")?$("#gpet").value:null;
    track(etapa+(acao==="aceitar"?"_accept":"_decline"),{pedido:order,pet:pet});
    if(demo){
      try{var d=JSON.parse(sessionStorage.getItem("demo_order")||"{}");d[etapa]=acao==="aceitar"?(pet||true):false;sessionStorage.setItem("demo_order",JSON.stringify(d))}catch(e){}
      if(etapa!=="upsell") return go("obrigado");
      if(acao==="aceitar") return go(C.extra&&C.extra.on?"extra":"obrigado");
      return go(C.downsell.on&&(demoOferta.downsell_kind!=="plush"||!d.bump)?"downsell":"obrigado");
    }
    fetch("api/upsell.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({order:order,etapa:etapa,acao:acao,pet:pet})})
    .then(function(r){return r.json()}).then(function(j){
      if(j.auth&&C.stripePk){ // banco pediu confirmação: uma tentativa só
        var s=document.createElement("script");s.src="https://js.stripe.com/v3/";s.onload=function(){
          Stripe(C.stripePk).confirmCardPayment(j.clientSecret,{payment_method:j.pm}).then(function(){
            return fetch("api/upsell.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({order:order,etapa:etapa,acao:"confirmado",pi:j.pi})}).then(function(r){return r.json()});
          }).then(function(k){ if(k.error){msg(k.error);setTimeout(function(){go(k.next)},2500)} else go(k.next) });
        };document.head.appendChild(s);return;
      }
      if(j.error){msg(j.error); busy=false; if(j.next) setTimeout(function(){go(j.next)},2500); return;}
      go(j.next);
    }).catch(function(){msg("Connection problem. Please try again.");busy=false});
  }
  $("#yes").addEventListener("click",function(){decide("aceitar")});
  $("#no").addEventListener("click",function(){decide("recusar")});
})();
