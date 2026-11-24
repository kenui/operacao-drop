<?php
// Copie para config.php FORA da pasta pública (um nível acima de public_html) e preencha.
// Nunca suba config.php para o Git (.gitignore já bloqueia).
return [
  'modo'            => 'teste',          // 'teste' ou 'real'. Real só com o aval do Kenui.
  'stripe_secret'   => 'sk_test_...',    // chave secreta de TESTE
  'stripe_webhook'  => 'whsec_...',      // segredo do webhook (painel da Stripe > Webhooks)
  'meta_pixel_id'   => '',               // vazio = API de Conversões desligada
  'meta_capi_token' => '',
  'meta_test_code'  => '',               // código de teste do Gerenciador de Eventos (opcional)
  'db_dsn'          => 'mysql:host=127.0.0.1;dbname=NOME;charset=utf8mb4',  // Hostinger: 127.0.0.1
  'db_user'         => '',
  'db_pass'         => '',
  'site_url'        => 'https://SEU-DOMINIO.com',
  'descritor'       => 'CATCHLIGHT',     // aparece no extrato do cartão (até 22 letras)

  // PREÇOS EM CENTAVOS. O servidor é quem cobra: precisam bater com public_html/config.js.
  'precos' => ['one' => 3499, 'two' => 5999, 'bump' => 799, 'upsell' => 2499, 'downsell' => 700],
  // CUSTO ENTREGUE EM CENTAVOS [HIPÓTESE até a cotação da CJ]: gravado em cada pedido para o lucro por visitante sair do banco.
  'custos' => ['kit' => 1050, 'bump' => 200, 'downsell' => 0],
  'downsell_on' => false,
  'limite_tentativas_ip_10min' => 8,
];
