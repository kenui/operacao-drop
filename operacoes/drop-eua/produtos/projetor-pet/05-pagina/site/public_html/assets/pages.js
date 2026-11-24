/* Preenche campos do config nas páginas do funil e nas políticas. */
(function(){
  var C=window.CONFIG||{};
  function pend(l){return '<span class="pend">['+l+']</span>'}
  var F={brand:C.brand||pend("nome da loja"),
    email:C.supportEmail?('<a href="mailto:'+C.supportEmail+'">'+C.supportEmail+'</a>'):pend("e-mail de suporte"),
    legalName:C.legalName||pend("nome legal"),
    delivery:C.delivery||pend("prazo real da CJ"),shipsFrom:C.shipsFrom||pend("origem do envio, CJ"),
    upsell:"$"+C.upsell.price.toFixed(2),upsellReg:"$"+C.upsell.regular.toFixed(2),downsell:"$"+C.downsell.price.toFixed(2),
    updated:"November 24, 2026"};
  window.FIELDS=F;
  function fill(){
    if(C.preview){var b=document.getElementById("previewBar"); if(b) b.hidden=false;}
    [].forEach.call(document.querySelectorAll("[data-f]"),function(el){if(F[el.dataset.f]!==undefined) el.innerHTML=F[el.dataset.f]});
  }
  if(document.readyState==="loading") document.addEventListener("DOMContentLoaded",fill); else fill();
})();
