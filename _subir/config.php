<?php
// Copie para config.php FORA da pasta pública (um nível acima de public_html) e preencha.
// Nunca suba config.php para o Git (.gitignore já bloqueia).
return [
  'modo'            => 'teste',          // 'teste' ou 'real'. Real só com o aval do Kenui.
  'stripe_secret'   => '__SK__',    // chave secreta de TESTE
  'stripe_webhook'  => '__WHSEC__',      // segredo do webhook (painel da Stripe > Webhooks)
  'meta_pixel_id'   => '',               // vazio = API de Conversões desligada
  'meta_capi_token' => '',
  'meta_test_code'  => '',               // código de teste do Gerenciador de Eventos (opcional)
  'db_dsn'          => 'sqlite:' . __DIR__ . '/dados-teste.sqlite',  // Hostinger: 127.0.0.1
  'db_user'         => '',
  'db_pass'         => '',
  'site_url'        => 'https://littlecatchco.com',
  'descritor'       => 'LITTLECATCHCO',     // aparece no extrato do cartão (até 22 letras)

  // PREÇOS EM CENTAVOS. O servidor é quem cobra: precisam bater com public_html/config.js.
  'precos' => ['one' => 3499, 'two' => 5999, 'bump' => 799, 'upsell' => 2499, 'downsell' => 799],
  'downsell_kind' => 'plush',          // 'plush' (pelúcias extras, só para quem não pegou o bump) ou 'guide' (PDF)
  // TESTE A/B ativo: o mesmo id de public_html/config.js. Só preços/tipos que mudam a cobrança precisam estar aqui.
  'testes' => [
    'active' => '',
    'list' => [
      'T3-preco-front'      => ['split' => 50, 'B' => ['one' => 3999, 'two' => 6999]],
      'T4-bump-preco'       => ['split' => 50, 'B' => ['bump' => 599]],
      'T5-upsell-preco'     => ['split' => 50, 'B' => ['upsell' => 1999]],
      'T6-downsell-digital' => ['split' => 50, 'B' => ['downsell' => 900, 'downsell_kind' => 'guide']],
    ],
  ],
  // CUSTO ENTREGUE EM CENTAVOS [HIPÓTESE até a cotação da CJ]: gravado em cada pedido para o lucro por visitante sair do banco.
  'custos' => ['kit' => 1050, 'bump' => 200, 'downsell_plush' => 200, 'downsell_guide' => 0],
  'downsell_on' => true,
  // E-MAIL de confirmação (PHP mail() da Hostinger; criar a caixa no hPanel). Vazio = não envia.
  'email_from'  => 'help@littlecatchco.com',  // criar a caixa no hPanel depois de comprar o domínio
  'email_nome'  => 'Little Catch Co.',
  'bonus_on'    => true,               // manda o link do First Hunt Guide (PDF) no e-mail e no obrigado
  'prazo'       => '',                 // o mesmo prazo de config.js, para o e-mail. Vazio = e-mail diz "tracking soon".

  'limite_tentativas_ip_10min' => 8,
];
