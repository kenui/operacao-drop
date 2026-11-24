# Site do projetor pet (HTML puro + PHP, Hostinger)

```
site/
  config.exemplo.php   -> copiar para config.php (FORA da pasta pública), chaves e preços em centavos
  schema.sql           -> tabelas (criadas sozinhas na primeira chamada)
  cron-finalizar.php   -> cron a cada 5 min: fecha funis parados há 15 min, manda o Purchase e o e-mail
  digital/             -> PDFs entregues por link assinado (FORA da pasta pública). fonte/gerar_guias.py refaz os PDFs
  public_html/
    config.js          -> TUDO que é trocável na página: preços, prazo, origem, tamanho, energia, chaves públicas
    index.html         -> landing (copy no objeto COPY), ?pet=cat|dog e ?a=<ângulo>
    upsell.html downsell.html obrigado.html
    shipping returns privacy terms contact track-order .html
    assets/  site.css app.js track.js funil.js pages.js
    api/     checkout atualizar upsell webhook evento pedido (.php)
```

## Antes de publicar (cada item precisa do Kenui ou da CJ)
1. **CJ:** custo do kit (gato e cachorro), prazo real até a porta, origem (armazém EUA ou China), tamanho, energia (USB ou recarregável), se é LED e não laser, vídeo real da projeção. Preencher em `config.js` (`delivery`, `shipsFrom`, `specs`, `heroVideo`) e o custo em `config.php` (`custos`). Refazer a conta de `07-funil.md`.
2. **Nome da loja e domínio** (`brand`; hoje "Catchlight" é provisório).
3. **Rodapé legal:** nome de quem vende, endereço de contato e e-mail de suporte. Sem empresa nos EUA, o vendedor é o Kenui (CPF ou CNPJ): decidir com o contador.
4. **Promessas de atendimento** nas políticas: reembolso em até 2 dias úteis, resposta em 1 dia útil, reembolso sem devolver o produto (etiqueta paga se pedirmos). Confirmar ou mudar.
5. **Stripe (modo teste primeiro):** chave `pk_test` no `config.js`, `sk_test` e segredo do webhook no `config.php`; webhook apontando para `https://DOMINIO/api/webhook.php` com os eventos `payment_intent.succeeded`, `payment_intent.payment_failed`, `charge.refunded`, `charge.dispute.created`, `radar.early_fraud_warning.created`; registrar o domínio para Apple Pay; descritor da fatura.
6. **Meta:** ID do pixel no `config.js` e no `config.php`, token da API de Conversões no `config.php`. **Clarity:** ID no `config.js`.
7. `preview: false` no `config.js` (some a faixa amarela). Nenhum campo amarelo pode sobrar.
8. Chaves reais (`sk_live`/`pk_live`) só com o aval do Kenui, depois da compra de teste completa (ver `../qa.md`).

## Teste A/B
Um teste por vez: `tests.active` em `config.js` e, se mexe na cobrança, `testes.active` em `config.php` (mesmo id). Conferir com `?v=A` e `?v=B`. Plano e régua: `../../testes/fila.md`.

## E-mail de confirmação
`email_from` no `config.php` (criar a caixa no hPanel). Sai uma vez, quando o funil fecha: cada cobrança numa linha, prazo, link do First Hunt Guide e como pedir reembolso.

## Publicar na Hostinger (plano compartilhado)
- Banco MySQL no hPanel; DSN `mysql:host=127.0.0.1;dbname=...`.
- Subir `public_html/` para a pasta do domínio, e `config.php`, `schema.sql`, `cron-finalizar.php` e a pasta `digital/` (só os PDFs) um nível acima.
- Cron: `php /home/USUARIO/domains/DOMINIO/cron-finalizar.php` a cada 5 minutos.
- PHP 8.1+ com cURL e PDO (padrão da Hostinger). Não usa Composer.

## Testar localmente
`cd public_html && php -S 127.0.0.1:8099` e abrir `http://127.0.0.1:8099/index.html?pet=cat&a=pega`. Sem chave da Stripe, o checkout vira demonstração e o funil inteiro dá para clicar.
