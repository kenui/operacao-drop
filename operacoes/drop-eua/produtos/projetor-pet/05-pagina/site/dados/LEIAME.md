# dados/ (publicado em public_html/_dados/ no ramo deploy)

`ads-gasto.json`: gasto diário do Meta por campanha, lido pela thread do Meta pelo conector e commitado aqui E no ramo `deploy-littlecatchco` em `_dados/ads-gasto.json`. Só números, nada de dado pessoal nem token. O painel lê esse arquivo quando não existe `meta_ads.php` (token ads_read) no servidor.

Formato e regras: ver o comentário de `gasto_ads_arquivo()` em `public_html/api/_ads.php`. Fica fora de `public_html/` no Git de propósito, para a cópia do site (`cp -a public_html/.`) nunca sobrescrever um gasto mais novo no ramo deploy.
