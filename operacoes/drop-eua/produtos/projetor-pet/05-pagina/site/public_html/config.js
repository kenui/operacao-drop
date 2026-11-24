/* ============================================================
   CONFIG DA PÁGINA. Tudo que depende da CJ ou do Kenui mora aqui.
   Campo com valor "" ou null = pendente: a página mostra uma
   marca amarela no modo prévia e NUNCA inventa o valor.
   Os PREÇOS precisam ser iguais aos de ../config.php (o servidor
   é quem cobra; este arquivo só mostra).
   ============================================================ */
window.CONFIG = {
  preview: true,                 // true = faixa amarela e campos pendentes destacados. false só para publicar.
  brand: "Catchlight",           // [NOME A DEFINIR com o Kenui] não lembrar Pouncy/PounceBeam
  supportEmail: "",              // ex.: help@dominio.com  [PENDENTE]
  legalName: "",                 // nome legal de quem vende  [PENDENTE: CPF/CNPJ do Kenui ou LLC]
  address: "",                   // endereço de contato  [PENDENTE]

  currency: "USD",
  kits: {
    one: { price: 34.99, label: "1 kit" },
    two: { price: 59.99, label: "2 kits", save: 10.00 }
  },
  bump:     { price: 7.99 },
  upsell:   { price: 24.99, regular: 34.99 },
  downsell: { price: 7.00, on: false },   // liga quando o guia PDF existir

  delivery: "",        // ex.: "7–12 business days"  [PENDENTE: prazo real da CJ]
  shipsFrom: "",       // ex.: "our US warehouse" ou "our partner warehouse overseas"  [PENDENTE: CJ]
  xmasCutoff: "",      // ex.: "Dec 10" só se o prazo garantir  [PENDENTE]
  specs: {
    size: "",          // ex.: "about 4.5 in tall, roughly a coffee mug"  [PENDENTE: CJ]
    power: "",         // ex.: "USB cable (included)" ou "rechargeable, USB-C"  [PENDENTE: CJ]
    ledNotLaser: null  // true só se a ficha da CJ confirmar projeção LED (não laser)
  },

  heroVideo: "",       // caminho do vídeo real do fornecedor (mp4/webm). Vazio = ilustração.
  stripePk: "",        // pk_test_... (modo teste). Vazio = checkout de demonstração.
  metaPixelId: "",     // vazio = pixel desligado
  clarityId: "",       // vazio = Clarity desligado
  defaultPet: "cat",
  defaultAngle: { cat: "pega", dog: "sozinho" }
};
