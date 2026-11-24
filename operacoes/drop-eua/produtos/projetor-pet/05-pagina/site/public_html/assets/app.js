/* Página principal: textos por pet, kits, barra fixa, medição por bloco e checkout Stripe (Payment Element). */
(function(){
  var C=CONFIG,S=STATE,$=function(s,r){return (r||document).querySelector(s)},$$=function(s,r){return [].slice.call((r||document).querySelectorAll(s))};
  var money=function(n){return "$"+Number(n).toFixed(2)};
  if(C.preview) $("#previewBar").hidden=false;

  /* ---------- campos trocáveis (config.js) ---------- */
  function pend(label){return '<span class="pend">['+label+']</span>'}
  function fields(){
    var P=COPY[S.pet], sp=C.specs||{};
    var laser = sp.ledNotLaser===true ? "No. It's an LED that projects moving shapes onto the floor. There's no beam pointed at anyone's eyes."
              : sp.ledNotLaser===false ? "It uses a low-power light source that projects shapes onto the floor. Don't let anyone stare into the lens."
              : pend("CJ: confirmar se é LED e não laser");
    return {
      brand:C.brand||pend("nome da loja"),
      priceOne:money(C.kits.one.price), priceTwo:money(C.kits.two.price),
      eachTwo:money(C.kits.two.price/2)+" each", saveTwo:"Save "+money(C.kits.two.save),
      priceBump:money(C.bump.price),
      delivery:C.delivery||pend("prazo real da CJ"),
      deliveryLong:C.delivery?("Arrives in "+C.delivery):pend("prazo da CJ"),
      deliveryShort:C.delivery?("Arrives in "+C.delivery):pend("prazo"),
      shipsFrom:C.shipsFrom||pend("origem do envio, CJ"),
      xmasCutoff:C.xmasCutoff,
      size:sp.size||pend("tamanho real, CJ"), power:sp.power||pend("USB ou recarregável, CJ"),
      laserAns:laser,
      email:C.supportEmail?('<a href="mailto:'+C.supportEmail+'">'+C.supportEmail+'</a>'):pend("e-mail de suporte"),
      legalName:C.legalName||pend("nome legal"),
      critters:P.critters, plush:P.plush, plushShort:P.plushShort, step3:P.step3
    };
  }
  function paint(){
    var F=fields(),P=COPY[S.pet],A=P.angles[S.angle];
    $$("[data-f]").forEach(function(el){var v=F[el.dataset.f]; if(v!==undefined&&v!==null) el.innerHTML=v;});
    $$("[data-f-show]").forEach(function(el){el.hidden=!C[el.dataset.fShow]});
    $$("[data-f-show-bonus]").forEach(function(el){el.hidden=!(C.bonus&&C.bonus.on)});
    if(C.kits.two.highlight){$('[data-kit="two"]').classList.add("hl");$("#hlTag").hidden=false;}
    $$("[data-pet-only]").forEach(function(el){el.hidden=el.dataset.petOnly!==S.pet});
    $$(".pets button").forEach(function(b){b.setAttribute("aria-pressed",b.dataset.pet===S.pet)});
    $("#h1").textContent=A.h1; $("#sub").textContent=A.sub;
    $("#prob").innerHTML=A.prob.map(function(t){return "<p>"+t+"</p>"}).join("");
    $("#mech").innerHTML=P.mech.map(function(t){return "<p>"+t+"</p>"}).join("");
    $("#bumpT").textContent=P.bumpT.replace("{bump}",money(C.bump.price)); $("#bumpD").textContent=P.bumpD;
    $("#finalH").textContent=S.pet==="cat"?"Give the hunt an ending tonight.":"Give the chase an ending tonight.";
    media(); summary();
  }

  /* ---------- mídia: vídeo real do fornecedor ou ilustração ---------- */
  function media(){
    var m=$("#media");
    if(C.heroVideo){ m.innerHTML='<video src="'+C.heroVideo+'" autoplay muted loop playsinline preload="metadata"></video>'; return; }
    var dog=S.pet==="dog";
    var critter=function(id,path,dur,delay){
      return '<g filter="url(#gl)"><g><ellipse rx="13" ry="7" fill="#ffd27a"/><circle cx="12" cy="0" r="5.5" fill="#ffd27a"/>'+
      (dog?'<path d="M-12 0 q-14 -16 -4 -20" stroke="#ffd27a" stroke-width="6" fill="none" stroke-linecap="round"/>':'<path d="M-12 0 q-12 6 -20 -2" stroke="#ffd27a" stroke-width="2" fill="none"/>')+
      '<animateMotion dur="'+dur+'s" begin="'+delay+'s" repeatCount="indefinite" rotate="auto" path="'+path+'"/></g></g>';
    };
    m.innerHTML='<svg viewBox="0 0 400 300" role="img" aria-label="Drawing: glowing '+(dog?"critters":"mice")+' running across a dark floor around the projector">'+
      '<defs><radialGradient id="fl" cx="50%" cy="70%" r="70%"><stop offset="0" stop-color="#3a2f3f"/><stop offset="1" stop-color="#14121a"/></radialGradient>'+
      '<filter id="gl" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="2.2" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs>'+
      '<rect width="400" height="300" fill="url(#fl)"/>'+
      '<g fill="none" stroke="#ffb547" stroke-width="3" opacity=".75" filter="url(#gl)"><ellipse cx="95" cy="215" rx="38" ry="12"/><ellipse cx="300" cy="235" rx="42" ry="13"/><ellipse cx="250" cy="150" rx="30" ry="9"/></g>'+
      critter(1,"M95 215 C150 260 230 250 300 235",3.4,0)+critter(2,"M300 235 C330 190 300 160 250 150",2.8,-.9)+critter(3,"M250 150 C190 140 120 180 95 215",3.1,-1.7)+
      '<ellipse cx="200" cy="118" rx="60" ry="16" fill="#ffb547" opacity=".12"/>'+
      '<g><path d="M178 112 q22 -40 44 0 z" fill="#efe8de"/><circle cx="183" cy="74" r="7" fill="#efe8de"/><circle cx="199" cy="66" r="7" fill="#efe8de"/><circle cx="215" cy="74" r="7" fill="#efe8de"/><rect x="176" y="108" width="48" height="8" rx="4" fill="#d8cfc2"/></g>'+
      '</svg><div class="cap">Illustration · real product video coming</div>';
  }

  /* ---------- trocar pet ---------- */
  $$(".pets button").forEach(function(b){b.addEventListener("click",function(){
    if(S.pet===b.dataset.pet)return;
    S.pet=b.dataset.pet; S.angle=C.defaultAngle[S.pet]; S.mix=[S.pet,S.pet];
    document.documentElement.dataset.pet=S.pet;
    $$("[data-mix]").forEach(function(s,i){s.value=S.mix[i]});
    var u=new URL(location.href); u.searchParams.set("pet",S.pet); u.searchParams.set("a",S.angle); history.replaceState(null,"",u);
    paint(); track("pet_select",{pet:S.pet}); if(started) update();
  })});

  /* ---------- kits ---------- */
  function total(){return (S.kit==="two"?C.kits.two.price:C.kits.one.price)+(S.bump?C.bump.price:0)}
  function summary(){
    var two=S.kit==="two";
    $("#sumKit").textContent=two?("2 kits ("+S.mix.join(" + ")+")"):("1 kit ("+S.pet+")");
    $("#sumKitP").textContent=money(two?C.kits.two.price:C.kits.one.price);
    $("#sumBumpRow").hidden=!S.bump;
    $("#sumTotal").textContent=money(total()); $("#payAmt").textContent=money(total());
  }
  $$(".kit").forEach(function(k){k.addEventListener("click",function(e){
    if(e.target.tagName==="SELECT"||e.target.tagName==="OPTION")return;
    if(S.kit===k.dataset.kit)return;
    S.kit=k.dataset.kit; $$(".kit").forEach(function(x){x.classList.toggle("on",x===k)}); k.querySelector("input").checked=true;
    summary(); track("kit_select",{kit:S.kit},{metaData:{value:total(),currency:"USD"}}); if(started) update();
  })});
  $$("[data-mix]").forEach(function(s){s.value=S.mix[+s.dataset.mix]; s.addEventListener("change",function(){S.mix[+s.dataset.mix]=s.value; summary(); if(started) update();})});
  $("#bump").addEventListener("change",function(e){S.bump=e.target.checked; summary(); track("bump_toggle",{on:S.bump}); if(started) update();});

  /* ---------- cliques nos botões ---------- */
  $$("[data-cta]").forEach(function(b){b.addEventListener("click",function(){track("cta_click",{botao:b.dataset.cta})})});

  /* ---------- barra fixa depois do hero ---------- */
  var sticky=$("#sticky"), hero=$(".hero"), co=$("#checkout");
  var heroOut=false, coIn=false;
  function st(){sticky.classList.toggle("show",heroOut&&!coIn)}
  new IntersectionObserver(function(e){heroOut=!e[0].isIntersecting;st()}).observe(hero);
  new IntersectionObserver(function(e){coIn=e[0].isIntersecting;st()}).observe($("#kits"));

  /* ---------- medição: view_content, block_view, rolagem ---------- */
  track("page_view",{carregou_ms:Math.round(performance.now())});
  setTimeout(function(){track("view_content",{},{metaData:{content_type:"product",content_ids:["projetor-pet-"+S.pet],value:C.kits.one.price,currency:"USD"}})},3000);
  var seen={}, timers={};
  var bo=new IntersectionObserver(function(es){es.forEach(function(e){var id=e.target.dataset.bloco;
    if(e.isIntersecting&&!seen[id]){timers[id]=setTimeout(function(){seen[id]=1;track("block_view",{bloco:id})},1000)}
    else clearTimeout(timers[id]);})},{threshold:.5});
  $$("[data-bloco]").forEach(function(el){bo.observe(el)});
  var marks={25:0,50:0,75:0,100:0};
  addEventListener("scroll",function(){var h=document.documentElement,p=(h.scrollTop+innerHeight)/h.scrollHeight*100;
    [25,50,75,100].forEach(function(m){if(p>=m-1&&!marks[m]){marks[m]=1;track("scroll_"+m,{})}})},{passive:true});

  /* ---------- checkout ---------- */
  var started=false, order=null, stripe=null, elements=null, email="";
  function err(t){$("#coErr").textContent=t||""}
  function post(url,data){return fetch(url,{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(data)}).then(function(r){return r.json().then(function(j){if(!r.ok)throw new Error(j.error||"Something went wrong. Please try again.");return j})})}
  function payload(){return {kit:S.kit,mix:S.mix,pet:S.pet,bump:S.bump,ctx:TRACK_CTX(),order:order}}
  function loadStripe(cb){if(window.Stripe)return cb();var s=document.createElement("script");s.src="https://js.stripe.com/v3/";s.onload=cb;s.onerror=function(){err("Payment form didn't load. Check your connection and try again.")};document.head.appendChild(s)}
  // pré-carrega o Stripe.js quando os kits aparecem
  if(C.stripePk) new IntersectionObserver(function(e,o){if(e[0].isIntersecting){o.disconnect();loadStripe(function(){})}}).observe($("#kits"));

  function update(){
    if(!order||order==="demo")return Promise.resolve();
    return post("api/atualizar.php",payload()).then(function(j){ if(elements) return elements.fetchUpdates(); }).catch(function(e){err(e.message)});
  }

  $("#startCo").addEventListener("click",function(){
    var btn=this; btn.disabled=true; err("");
    track("initiate_checkout",{kit:S.kit,bump:S.bump},{metaData:{value:total(),currency:"USD",num_items:S.kit==="two"?2:1}});
    if(!C.stripePk){ // demonstração sem chave
      started=true; order="demo"; btn.hidden=true; $("#coArea").hidden=false;
      $("#linkEl").innerHTML='<div class="demo-box"><b>Modo prévia.</b> Aqui entram o e-mail, o endereço (com autocompletar), Apple Pay, Google Pay e cartão da Stripe, quando a chave de teste estiver no config.<div class="f">Email</div><div class="f">Shipping address</div><div class="f">Card · Apple Pay · Google Pay</div></div>';
      return;
    }
    post("api/checkout.php",payload()).then(function(j){
      order=j.order; started=true;
      // o servidor sorteia a versão pelo mesmo visitante; se o total divergir, avisa em vez de cobrar escondido
      if(Math.round(total()*100)!==j.amount){ err("Price updated: your total is $"+(j.amount/100).toFixed(2)+"."); }
      loadStripe(function(){
        stripe=Stripe(C.stripePk);
        elements=stripe.elements({clientSecret:j.clientSecret,appearance:{theme:"stripe",variables:{colorPrimary:"#e8590c",borderRadius:"10px",fontSizeBase:"16px"}}});
        var link=elements.create("linkAuthentication"); link.mount("#linkEl"); link.on("change",function(e){email=e.value.email||""});
        elements.create("address",{mode:"shipping",allowedCountries:["US"]}).mount("#addrEl");
        var pay=elements.create("payment",{layout:"tabs"}); pay.mount("#payEl");
        btn.hidden=true; $("#coArea").hidden=false;
      });
    }).catch(function(e){err(e.message);btn.disabled=false});
  });

  $("#payBtn").addEventListener("click",function(){
    var btn=this; err("");
    track("add_payment_info",{kit:S.kit,bump:S.bump},{metaData:{value:total(),currency:"USD"}});
    if(order==="demo"){
      try{sessionStorage.setItem("demo_order",JSON.stringify({kit:S.kit,mix:S.mix,pet:S.pet,bump:S.bump,total:total(),front:(S.kit==="two"?C.kits.two.price:C.kits.one.price),bumpPrice:C.bump.price}))}catch(e){}
      location.href="upsell.html?o=demo"; return;
    }
    btn.disabled=true;
    var p=payload(); p.email=email;
    post("api/atualizar.php",p).then(function(){return elements.submit()}).then(function(r){
      if(r&&r.error) throw r.error;
      return stripe.confirmPayment({elements:elements,confirmParams:{return_url:location.origin+location.pathname.replace(/[^\/]*$/,"")+"upsell.html?o="+order,receipt_email:email||undefined},redirect:"if_required"});
    }).then(function(r){
      if(r.error){ track("payment_failed",{motivo:r.error.code||r.error.type,decline:r.error.decline_code||""}); throw r.error; }
      location.href="upsell.html?o="+order;
    }).catch(function(e){err(e.message||"Payment didn't go through. Please try again.");btn.disabled=false});
  });

  paint();
})();
