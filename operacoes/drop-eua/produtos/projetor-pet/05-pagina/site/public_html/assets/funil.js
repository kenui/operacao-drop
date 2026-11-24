/* Upsell e downsell de um clique. Demonstração quando ?o=demo (sem Stripe). */
(function(){
  var q=new URLSearchParams(location.search), order=q.get("o")||"", demo=order==="demo";
  var etapa=document.body.dataset.etapa, C=window.CONFIG, busy=false;
  var $=function(s){return document.querySelector(s)};
  function go(next){location.href=(next==="downsell"?"downsell.html":"obrigado.html")+"?o="+encodeURIComponent(order)}
  function msg(t){$("#err").textContent=t||""}
  track(etapa+"_view",{pedido:order});
  function decide(acao){
    if(busy)return; busy=true; msg("");
    var pet=$("#gpet")?$("#gpet").value:null;
    track(etapa+(acao==="aceitar"?"_accept":"_decline"),{pedido:order,pet:pet});
    if(demo){
      try{var d=JSON.parse(sessionStorage.getItem("demo_order")||"{}");d[etapa]=acao==="aceitar"?(pet||true):false;sessionStorage.setItem("demo_order",JSON.stringify(d))}catch(e){}
      return go(etapa==="upsell"&&acao!=="aceitar"&&C.downsell.on?"downsell":"obrigado");
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
