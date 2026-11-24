# Projetor pet: QA da página (v1, 24/11/2026)

Rodado localmente (servidor PHP embutido + Playwright, 390 px). Prints em `prints/`.

| Item | Resultado |
|---|---|
| Peso da página (HTML + CSS + JS, sem Stripe.js) | ~60 KB, sem imagens (ilustração em SVG). Meta < 500 KB: ok |
| Erros de JavaScript | nenhum, nos 3 ângulos de gato e 2 de cachorro |
| `?pet=` e `?a=` trocam hero e problema antes de pintar | ok |
| Botões cat/dog trocam textos, limites, bump e URL | ok |
| Kit 2 com mistura cat + dog | ok, resumo e servidor recebem `cat+dog` |
| Bump soma no total e no PaymentIntent | ok |
| Funil de demonstração (sem chave): checkout → upsell → obrigado com recibo | ok |
| Servidor com Stripe simulado: checkout, atualizar, upsell off-session, pedido pago pela reserva do upsell, Purchase com total, idempotência | ok (custo gravado por pedido: US$23,00 no kit 2 + bump) |
| Webhook: assinatura certa, duplicada e errada | ok / ignora / 400 |
| Reembolso pelo webhook grava o valor | ok |
| Arquivos `_lib.php` e `_pago.php` bloqueados na web | `.htaccess` (Apache/LiteSpeed da Hostinger) |

## Falta (precisa de chave de teste da Stripe e do domínio)
- Compra de teste real no modo de teste: cartão 4242, cartão recusado (4000 0000 0000 0002), cartão com autenticação (4000 0025 0000 3155) no front e no upsell.
- Apple Pay (exige domínio registrado na Stripe).
- Eventos chegando no Gerenciador de Eventos do Meta uma vez só (pixel + API com o mesmo `event_id`).
- PageSpeed com o vídeo real.
