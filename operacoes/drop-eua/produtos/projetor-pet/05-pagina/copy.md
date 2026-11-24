# Projetor pet: copy da página e do funil (v1, 24/11/2026)

Inglês americano. Fonte única dos textos montados em `site/public_html/` (o objeto `COPY` no `index.html` e as páginas do funil). Mudou aqui, muda lá.
Base: [dossiê](../02-dossie.md), [ângulos](../04-angulos.md), [funil](../07-funil.md). Princípios 1 a 3 de `conhecimento/copy/PRINCIPIOS.md`.

## Ordem dos blocos (wireframe, 390 px)
| # | ID | Objeção que responde | Mede |
|---|---|---|---|
| 1 | `hero` | o que é, quanto custa, é pro meu pet? (botões cat/dog) | `block_view`, `cta_click: hero` |
| 2 | `problema` | isso é pra mim? (troca por `?a=`) | `block_view` |
| 3 | `demonstracao` | funciona? como uso? tamanho | `block_view` |
| 4 | `mecanismo` | por que a pelúcia (a captura) | `block_view` |
| 5 | `limite` | vou me arrepender? (sobe para antes da oferta: limite cedo) | `block_view` |
| 6 | `oferta-kits` | qual levo? | `kit_select` |
| 7 | `checkout` + `bump` | como pago? | `initiate_checkout`, `bump_toggle`, `add_payment_info` |
| 8 | `garantia` | e se não gostar? | `block_view` |
| 10 | `objecoes` | golpe? laser? dia? Amazon mais barato? | `block_view` |
| 11 | `envio` | chega quando? | `block_view` |
| 12 | `cta-final` | resumo | `cta_click: final` |
| — | `prova` | **fora**: não temos avaliação nem vídeo real. Volta quando houver prova verdadeira. | — |

## Links por anúncio (o mesmo ângulo do vídeo abre a página)
| Anúncio | Link | Título |
|---|---|---|
| A gato captura | `?pet=cat&a=pega` (padrão do gato) | Let them chase the light. Then let them catch something real. |
| B gato cansado | `?pet=cat&a=cansado` | Ten minutes of hunting for him. Ten minutes on the couch for you. |
| C gato demonstração (reserva) | `?pet=cat&a=caca` | Little glowing mice. One tap. Full hunting mode. |
| D cachorro sozinho (controle) | `?pet=dog&a=sozinho` (padrão do cachorro) | The toy that plays with your dog while you sit down. |
| E cachorro captura | `?pet=dog&a=pega` | Let him chase the critters. Then give him something real to shake. |

Subtítulos e blocos `problema` de cada ângulo: ver o objeto `COPY` em `site/public_html/index.html` (lá estão literais).

## Textos fixos importantes
- **Mecanismo (gato):** "A laser dot moves, then vanishes. Your cat never gets the part where she actually pins something down. Many vets and cat behaviorists suggest ending light play with a toy your cat can catch. We put that toy in the box, so you don't have to remember to buy one."
- **Limites:** low light needed; some cats just stare (30-day refund); best for small and medium dogs; some dogs bark at first, "We can't promise quiet"; 10 to 15 minute sessions, not all day.
- **Garantia:** "30 days to decide… You don't need a reason, and you don't need to argue with anyone."
- **FAQ do golpe:** "Fair worry. You pay through Stripe, you get an email receipt right away with every charge listed… If anything goes wrong, email us before you call your bank. A refund is faster that way."
- **Consentimento do cartão (acima do botão):** "By completing your purchase you agree we may charge this card for any add-on you accept on the next page. No subscriptions."
- **Bump gato:** "Add 3 spare catnip mice, $7.99. They end up under the couch faster than you'd think." **Cachorro:** "Add 2 spare squeaky squirrels, $7.99. For when the first one loses its squeak." Caixinha sempre desmarcada.
- **Upsell:** "Want a second kit for someone else's pet? Add one more full kit for $24.99 instead of $34.99. It goes in the same box as your order, so there's no extra shipping and nothing to type." Botões: "Yes, add a second kit for $24.99" / "No thanks, finish my order".
- **Downsell (desligado até o PDF existir):** "No problem. Want the 10-minute play plan instead?"
- **Obrigado:** cobranças em linhas separadas, prazo, dica da primeira noite, "Please write to us before calling your bank. It's faster for you."
- **Rodapé:** "Pets and scenes in our videos may be created with AI to show how the product is used."

## E-mails pós-compra (rascunho, para o envio automático depois)
1. **Confirmação (na hora).** Assunto: "Your hunt kit order #{n}". Corpo: o que foi cobrado, linha por linha (kit, bump, upsell, cada um com o valor); "It ships from {origem} and should arrive in {prazo}. You'll get tracking as soon as it leaves."; como pedir reembolso; nome no extrato do cartão.
2. **Envio.** "It's on the way." Link de rastreio; "Tracking can take a day or two to update."
3. **Dica (dia da entrega).** "Tonight's first hunt": luz baixa, chão liso, 10 minutos, termine com a pelúcia. "If your pet just stares at it, reply to this email. Some take a few nights, some never do, and that's what the refund is for."
4. **Avaliação real (7 dias depois da entrega).** "How did the first week go?" Pedido de resposta honesta, sem desconto em troca (regra da FTC de 2024).

## Passada de voz humana
Tiques procurados: "unleash", "instinct", "game-changer", "ultimate", "revolutionary", "purr-fect", "Imagine", três adjetivos, pergunta atrás de pergunta. Nenhum ficou.
Antes e depois:
1. "Satisfies your cat's natural hunting instincts" → "Cats are built to finish the hunt by pinning something down. A dot never lets them."
2. "Hours of hands-free fun" → "This gives him something to chase in the living room for ten minutes, without you throwing anything." (escala honesta: 10 minutos, não horas)
3. "Trusted by thousands of happy pet parents" → removido. Não temos clientes. Entrou o FAQ do golpe com fatos verificáveis (Stripe, recibo, rastreio, e-mail).
Testes: troca (as frases citam ponto vermelho, catnip, apito, parede vazia: só servem para este produto), mesa de bar (ok), especialista (sem promessa de saúde; "vets suggest" é conselho geral, com fonte no dossiê), densidade (cada bloco tem objeto ou número), "como você sabe?" (cada frase de dor tem origem no dossiê #1 a #31).

## Conformidade
Sem avaliação, contagem de clientes, estoque, contador ou preço riscado falso (o riscado do upsell é o preço real do kit na própria página). Sem "vet approved". "Not a laser" só aparece se `specs.ledNotLaser = true` no config. Prazo e potência só aparecem quando preenchidos com a ficha da CJ.
