/* Medição: nossa rota /api/evento + pixel do Meta (mesmo event_id que o servidor manda pela API de Conversões) + Clarity.
   Plano: conhecimento/metricas/plano-de-medicao.md */
(function(){
  var C=window.CONFIG||{}, q=new URLSearchParams(location.search);
  function ls(k,v){try{if(v===undefined)return localStorage.getItem(k);localStorage.setItem(k,v)}catch(e){return null}}
  function ss(k,v){try{if(v===undefined)return sessionStorage.getItem(k);sessionStorage.setItem(k,v)}catch(e){return null}}
  function rid(){return (Date.now().toString(36)+Math.random().toString(36).slice(2,10))}
  function ck(n){var m=document.cookie.match("(^|; )"+n+"=([^;]*)");return m?decodeURIComponent(m[2]):""}
  function setck(n,v,d){document.cookie=n+"="+encodeURIComponent(v)+"; path=/; max-age="+(d*86400)+"; SameSite=Lax"+(location.protocol==="https:"?"; Secure":"")}

  var vid=C.vid||ck("vid")||ls("vid")||rid(); setck("vid",vid,365); ls("vid",vid);
  var sid=ss("sid")||rid(); ss("sid",sid);
  // UTMs e fbclid ficam guardados para a sessão inteira (upsell e obrigado também)
  var keep=["utm_source","utm_medium","utm_campaign","utm_content","utm_term","fbclid","a","pet"];
  var ctx={}; try{ctx=JSON.parse(ss("ctx")||"{}")}catch(e){}
  keep.forEach(function(k){if(q.get(k))ctx[k]=q.get(k)});
  ss("ctx",JSON.stringify(ctx));
  if(ctx.fbclid && !ck("_fbc")) setck("_fbc","fb.1."+Date.now()+"."+ctx.fbclid,90);

  var optout=ls("optout")==="1"||navigator.globalPrivacyControl===true;
  var META={page_view:"PageView",view_content:"ViewContent",kit_select:"AddToCart",initiate_checkout:"InitiateCheckout",add_payment_info:"AddPaymentInfo"};

  function loadPixel(){
    if(!C.metaPixelId||optout||window.fbq)return;
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init',C.metaPixelId);
  }
  function loadClarity(){
    if(!C.clarityId||optout)return;
    (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)})(window,document,"clarity","script",C.clarityId);
  }

  function base(){
    var st=window.STATE||{};
    return {visitante_id:vid,sessao_id:sid,produto:"projetor-pet",pet:st.pet||ctx.pet||"",angulo:st.angle||ctx.a||"",
      variante:C.variant||"",utm:ctx,fbc:ck("_fbc"),fbp:ck("_fbp"),pagina:location.pathname,
      disp:(matchMedia("(max-width:760px)").matches?"mobile":"desktop"),optout:optout?1:0};
  }
  // track(nome, dados, opções) -> event_id
  window.track=function(name,data,opt){
    var ev=base(); ev.evento=name; ev.dados=data||{}; ev.event_id=(opt&&opt.eventId)||(name+"."+rid());
    var body=JSON.stringify(ev);
    try{ if(navigator.sendBeacon) navigator.sendBeacon("api/evento.php",new Blob([body],{type:"application/json"}));
         else fetch("api/evento.php",{method:"POST",body:body,keepalive:true,headers:{"Content-Type":"application/json"}}); }catch(e){}
    var m=(opt&&opt.meta)||META[name];
    if(m&&window.fbq&&!optout){ fbq("track",m,(opt&&opt.metaData)||{},{eventID:ev.event_id}); }
    if(window.clarity) clarity("event",name);
    return ev.event_id;
  };
  window.TRACK_CTX=function(){return base()};

  // banner de cookies (EUA: opt-out). Recusar desliga pixel e Clarity.
  function banner(){
    if(ls("cookie_ok")||optout)return;
    var d=document.createElement("div"); d.className="cookie";
    d.innerHTML='<span>We use cookies to measure our ads and improve this page. <a href="privacy.html">Privacy</a></span><button type="button">OK</button><button type="button" class="no">Opt out</button>';
    d.querySelector("button").onclick=function(){ls("cookie_ok","1");d.remove()};
    d.querySelector(".no").onclick=function(){ls("optout","1");optout=true;if(window.fbq)fbq("consent","revoke");d.remove()};
    document.body.appendChild(d);
  }
  loadPixel(); loadClarity();
  document.addEventListener("DOMContentLoaded",banner);
})();
