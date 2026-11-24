<?php
// PREÇOS, CUSTOS E PRAZO versionados no Git (não são segredo). Sobrescrevem os de ../config.php do servidor.
// Precisam bater com public_html/config.js. Fonte: operacoes/drop-eua/garimpo/2026-11-24/trava-projetor-pet.md
// Decisão do Kenui (24/11, cartão no garimpo): "Sim, kit a US$49,99" (projetor + pelúcia). Só a frente de cachorro tem fornecedor.
return [
  // centavos
  'precos' => ['one' => 4999, 'two' => 8999, 'bump' => 999, 'upsell' => 3999, 'downsell' => 799],
  'downsell_kind' => 'plush',
  'testes' => [
    'active' => '',
    'list' => [
      'T3-preco-front'      => ['split' => 50, 'B' => ['one' => 5999, 'two' => 10999]],
      'T4-bump-preco'       => ['split' => 50, 'B' => ['bump' => 799]],
      'T5-upsell-preco'     => ['split' => 50, 'B' => ['upsell' => 3499]],
      'T6-downsell-digital' => ['split' => 50, 'B' => ['downsell' => 900, 'downsell_kind' => 'guide']],
    ],
  ],
  // custo entregue (CJ, 24/11): projetor US$6,74 + pelúcia ~US$1,50 (cotação pendente) + frete CJPacket Sensitive do kit US$10,53 = US$18,77.
  // Pelúcias extras (bump/downsell): 2 × US$1,50 + ~US$1,50 de frete a mais = US$4,50 (estimativa até a cotação da pelúcia).
  'custos' => ['kit' => 1877, 'bump' => 450, 'downsell_plush' => 450, 'downsell_guide' => 0],
  'prazo' => '7–15 days',
];
