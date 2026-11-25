/* Medição: nossa rota /api/evento + pixel do Meta (mesmo event_id que o servidor manda pela API de Conversões) + Clarity.
   v3 (24/11/2026), pedido do Kenui: rastreamento no máximo para a qualidade dos eventos.
   - Sem banner de cookies (EUA não exige consentimento prévio para medir anúncios).
   - Correspondência avançada: e-mail, telefone, nome, cidade, estado, CEP e external_id vão ao pixel e à API de Conversões
     assim que a pessoa digita no checkout, e ficam guardados neste navegador para os eventos seguintes (e visitas futuras).
   - Lei da Califórnia (CCPA/CPRA) e de outros estados: quem manda o sinal GPC ou clica em "Your Privacy Choices" não é
     cortado; os eventos seguem com Limited Data Use (LDU), que é o modo do próprio Meta para esse caso.
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
  // Último clique pago fica 7 dias neste navegador (janela de clique do Meta). Quem volta sem o link continua no anúncio de origem, marcado "retorno".
  var ORIG=["utm_source","utm_medium","utm_campaign","utm_content","utm_term","fbclid","a"];
  if(q.get("utm_source")||q.get("fbclid")){var t={};ORIG.forEach(function(k){if(ctx[k])t[k]=ctx[k]});ls("toque",JSON.stringify({t:Date.now(),c:t}));delete ctx.retorno}
  else if(!ctx.utm_source&&!ctx.fbclid){try{var tq=JSON.parse(ls("toque")||"null");if(tq&&tq.c&&Date.now()-tq.t<7*864e5){ORIG.forEach(function(k){if(tq.c[k]&&!ctx[k])ctx[k]=tq.c[k]});ctx.retorno="1"}}catch(e){}}
  ss("ctx",JSON.stringify(ctx));
  // _fbc: grava/atualiza sempre que chega um fbclid novo (clique novo = fbc novo)
  if(ctx.fbclid){ var old=ck("_fbc"); if(!old||old.split(".").pop()!==ctx.fbclid) setck("_fbc","fb.1."+Date.now()+"."+ctx.fbclid,90); }
  // _fbp próprio (primeira parte) se o pixel ainda não criou: a API de Conversões já sai com fbp desde o 1º evento
  if(!ck("_fbp")) setck("_fbp","fb.1."+Date.now()+"."+Math.floor(Math.random()*1e10),90);

  var ldu=ls("optout")==="1"||navigator.globalPrivacyControl===true;
  var META={page_view:"PageView",view_content:"ViewContent",kit_select:"AddToCart",initiate_checkout:"InitiateCheckout",add_payment_info:"AddPaymentInfo"};

  /* ---------- identidade (correspondência avançada) ---------- */
  var idn={}; try{idn=JSON.parse(ls("idn")||"{}")||{}}catch(e){idn={}}
  function norm(o){
    var r={};
    if(o.email) r.em=String(o.email).trim().toLowerCase();
    if(o.phone){ var d=String(o.phone).replace(/\D/g,""); if(d.length===10) d="1"+d; if(d.length>=11) r.ph=d; }
    if(o.name){ var p=String(o.name).trim().toLowerCase().split(/\s+/); r.fn=p[0]; if(p.length>1) r.ln=p[p.length-1]; }
    if(o.city) r.ct=String(o.city).toLowerCase().replace(/[^a-z]/g,"");
    if(o.state) r.st=String(o.state).toLowerCase().replace(/[^a-z]/g,"").slice(0,2);
    if(o.zip) r.zp=String(o.zip).replace(/\D/g,"").slice(0,5);
    if(o.country) r.country=String(o.country).toLowerCase().slice(0,2);
    return r;
  }
  function pixelUD(){ var u={external_id:vid}; for(var k in idn) if(idn[k]) u[k]=idn[k]; return u; }
  window.identify=function(o){
    var n=norm(o||{}), changed=false;
    for(var k in n) if(n[k]&&idn[k]!==n[k]){ idn[k]=n[k]; changed=true; }
    if(!changed) return;
    ls("idn",JSON.stringify(idn));
    if(window.fbq&&C.metaPixelId) fbq("init",C.metaPixelId,pixelUD());   // re-init atualiza a correspondência avançada
  };
  window.IDENT=function(){return idn};

  function loadPixel(){
    if(!C.metaPixelId||window.fbq)return;
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    if(ldu) fbq("dataProcessingOptions",["LDU"],0,0); // o Meta descobre o estado pela localização
    fbq("init",C.metaPixelId,pixelUD());
  }
  function loadClarity(){
    if(!C.clarityId||ldu)return;
    (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)})(window,document,"clarity","script",C.clarityId);
  }

  function base(){
    var st=window.STATE||{};
    return {visitante_id:vid,sessao_id:sid,produto:"projetor-pet",pet:st.pet||ctx.pet||"",angulo:st.angle||ctx.a||"",
      variante:C.variant||"",utm:ctx,fbc:ck("_fbc"),fbp:ck("_fbp"),pagina:location.pathname,
      disp:(matchMedia("(max-width:760px)").matches?"mobile":"desktop"),optout:ldu?1:0};
  }
  // track(nome, dados, opções) -> event_id
  window.track=function(name,data,opt){
    var ev=base(); ev.evento=name; ev.dados=data||{}; ev.event_id=(opt&&opt.eventId)||(name+"."+rid());
    var m=(opt&&opt.meta)||META[name];
    if(m){ ev.meta=(opt&&opt.metaData)||{}; ev.ud=idn; ev.url=location.href.split("#")[0]; } // identidade só vai junto de evento do Meta
    var body=JSON.stringify(ev);
    try{ if(navigator.sendBeacon) navigator.sendBeacon("api/evento.php",new Blob([body],{type:"application/json"}));
         else fetch("api/evento.php",{method:"POST",body:body,keepalive:true,headers:{"Content-Type":"application/json"}}); }catch(e){}
    if(m&&window.fbq){ fbq("track",m,(opt&&opt.metaData)||{},{eventID:ev.event_id}); }
    if(window.clarity) clarity("event",name);
    return ev.event_id;
  };
  window.TRACK_CTX=function(){return base()};
  // "Your Privacy Choices" (privacy.html#choices)
  window.setPrivacyChoice=function(limit){ ls("optout",limit?"1":"0"); ldu=!!limit; };
  window.privacyChoice=function(){return ldu};

  loadPixel(); loadClarity();
})();
