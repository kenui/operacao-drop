# Projetor pet: oferta e funil (v2, 24/11/2026)

Feito pelo agente Funil e oferta (prompt v2), a partir do [dossiê](02-dossie.md), da [persona](03-persona.md), dos [ângulos](04-angulos.md) e do [manual de funil](../../../../conhecimento/funil/manual-funil.md) (Partes 7 e 8 novas).

**Regra do Kenui para esta versão (24/11):** oferta irresistível, funil de especialista com régua de lucro, teste A/B de tudo (um por vez, inclusive preço), e **produto digital só onde a persona mostra valor percebido maior**: "a gente não faz nada porque acha". Cada peça abaixo tem o motivo e a evidência; o que não tem evidência entra como **teste**, nunca como padrão.

**Fase 0:** o produto ainda **não passou na trava** (CJ: CJSPU961146136 e CJSPU961146540). Custo e prazo são `[HIPÓTESE]` e moram em `05-pagina/site/public_html/config.js` e `05-pagina/site/config.php`.

---

## 1. A oferta (equação de valor de Hormozi, sem número inventado)

| Alavanca | O que a persona diz | O que a oferta faz |
|---|---|---|
| Resultado sonhado | "ela no sofá com a caneca, o pet brincando, depois cansado" (persona §2) | o título promete a caça **e** a captura; o kit traz o fim da caça |
| Probabilidade percebida | objeção nº 1 "Is this legit?", nº 2 "my dog will ignore it" (persona §6) | limites ditos antes do preço; reembolso de 30 dias "even if your pet just stares at it"; guia grátis com o que fazer se o pet ignorar; FAQ do golpe com fatos |
| Tempo até o resultado | prazo da China é objeção nº 6 | prazo real escrito perto do botão (campo da CJ) |
| Esforço | dono cansado, "play independently while I get stuff done" (F16) | um toque; 10 minutos; sem varinha |

**A pilha da oferta (o que vai na caixa e no e-mail):**
1. Projetor de chão.
2. A pelúcia que termina a caça (ratinho com catnip ou esquilo que apita). **Só nosso.**
3. Caixa de presente.
4. **First Hunt Guide** (PDF grátis por e-mail, gato ou cachorro). Ver decisão D1.
5. Frete grátis e reembolso de 30 dias sem precisar devolver.

Sem "valor de US$X" para o guia e sem preço riscado: não existe preço anterior real (FTC).

## 2. Decisões, uma por peça, com o porquê

| # | Peça | Decisão | Evidência (persona/dossiê) | Status |
|---|---|---|---|---|
| D1 | **Bônus digital grátis: First Hunt Guide** | **Entra** | Responde às objeções nº 2 ("will ignore it / lose interest", F26, F30) e nº 4 ("frustrated / bark at it", F4, F7), as duas que viram reembolso. Custo zero. Não é vendido: não precisa passar no teste "pagaria sozinho?". Mede-se pelo reembolso (guarda-corpo) e pelo download. | **Pronto**: `05-pagina/site/digital/first-hunt-guide-cat.pdf` e `-dog.pdf` (3 páginas cada) |
| D2 | Kit 1 a US$34,99 | Entra (controle) | Acima da mediana US$29,99 porque o kit tem a pelúcia e a caixa; 61% compram brinquedo todo mês, 31% compram brinquedo caro (Insurify, persona §4). | controle do T3 |
| D3 | Kit 2 a US$59,99, misturando gato e cachorro | Entra | "gift for someone who loves their dog" em dezembro (persona §6); casas com os dois pets; Pouncy vende kit de 2 e 3. | T2 testa o destaque |
| D4 | **Bump: pelúcias extras** (3 ratinhos ou 2 esquilos), US$7,99 | Entra (físico, não digital) | Compra de brinquedo é mensal e por impulso (persona §4); "went absolutely mad for this for about ten minutes" (F26): pelúcia nova renova o interesse; esquilo perde o apito. Bump digital **não**: a informação de "como cansar o pet" é gratuita em páginas da AAHA, PetMD, AARP (persona §4, "pesquisas solitárias"), então o valor percebido de um PDF pago no checkout é baixo. | T4 testa o preço |
| D5 | Upsell: 2º kit por US$24,99, na mesma caixa | Entra | Presente de dezembro; "buy one for my sister's dog too" (fraca); 37% cuidam do pet dos filhos (Talker). | T5 testa o preço |
| D6 | **Downsell: pelúcias extras por US$7,99**, só para quem recusou o upsell **e não pegou o bump** | Entra (físico) | Mesma evidência de D4. Quem já pegou o bump não vê nada (não repetir oferta). | controle do T6 |
| D7 | Downsell digital pago (**Indoor Hunt Playbook**, US$9) | **Não entra como padrão.** Vira o braço B do T6 | Valor percebido **não comprovado**: há interesse em "how to tire out dog indoors" mas há conteúdo grátis de sobra. A dúvida só se resolve com teste. O PDF só é escrito quando o T6 chegar na fila (não gastar antes). | sumário pronto, PDF não |
| D9 | **Marca: Little Catch Co.** (littlecatchco.com) | Decidido pela IA (regra de 24/11) | Diz o diferencial (a captura) para gato e cachorro; não lembra Pouncy, PounceBeam nem PawLumen; .com livre na checagem da Hostinger em 24/11 (confiança do comprador americano). Comprar o domínio pede o aval do Kenui (gasto). | a comprar |
| D10 | **Políticas**: reembolso em até 2 dias úteis, sem precisar devolver o produto (etiqueta paga se pedirmos), resposta em 1 dia útil | Decidido pela IA | Objeção nº 1 "Is this legit?" (persona §6); devolver da China custa mais que o kit; reembolso custa menos que contestação (US$15 + venda). Guarda-corpo: se reembolso passar de 8%, rever. | no site |
| D11 | **Rodapé sem endereço físico**: marca, "Operated by" com o nome legal, e-mail | Decidido pela IA | Não há endereço nos EUA; endereço de casa no Brasil não vai a público. Lei americana não exige endereço numa loja online; contato rápido por e-mail cobre a confiança. | falta só o nome legal |
| D12 | **Bônus 2 grátis: Rainy Day Play Plan** (cachorro; 12 jogos com coisas de casa + semana de rodízio) | **Entra** (24/11, pedido do Kenui para deixar a oferta mais atraente) | Ângulo "dia de chuva sem culpa" (persona §7) e objeção nº 2 "vai enjoar" (rodízio). Capa e mockup feitos por código, sem crédito. Sem "valor de US$X" (FTC). Sobrepõe parte do Indoor Hunt Playbook do T6: se o T6 entrar na fila, o playbook precisa de outro conteúdo. Método: `conhecimento/oferta/manual-bonus.md`. | **Pronto**: `05-pagina/site/digital/rainy-day-play-plan-dog.pdf` |
| D13 | **Oferta extra para quem ACEITA o upsell: pelúcias para o 2º kit**, US$7,99, mesma caixa | **Entra** (24/11, pedido do Kenui: "se a pessoa já comprou o upsell, também você poderia usar um downsell") | Quem aceita o upsell acabou de dizer sim duas vezes (é o comprador mais quente do funil). O 2º kit vem com uma pelúcia só; a mesma evidência de D4 (pelúcia perde o apito, some, renova o interesse) vale para ele. Custo conhecido (US$4,50, o mesmo do bump), não cria item novo na CJ. Aparece também para quem pegou o bump, porque o bump é do 1º kit (o texto diz isso). Margem ~US$2,91 por aceite. | no ar; T8 testa o preço (US$9,99) |
| D14 | **Downsell como preço menor do upsell** (o 2º kit por US$29,99) | **Braço B do T7**, contra as pelúcias (A) | É o que o Kenui entendia por downsell, e é o desenho clássico. A conta: kit a US$29,99 deixa ~US$9,32 de margem (custo US$18,77); pelúcias deixam ~US$2,91. B vence se o aceite ficar acima de ~31% do aceite das pelúcias. Também aparece para quem pegou o bump (no A, quem pegou o bump não vê nada). Preço riscado US$49,99 é o preço real de 1 kit (FTC ok). | T7 no ar (sorteio pelo pedido) |
| D8 | Embrulho, processamento prioritário, proteção de pacote | Fora | Kit já vem em caixa de presente; prioridade não muda o prazo do fornecedor; proteção só quando houver reenvio real. | — |

## 3. Funil (como roda)
```
Anúncio (ângulo, pet) → página ?pet=&a= → kits 1/2 → checkout na página (+ bump pelúcias, desmarcado)
  → Upsell: 2º kit US$39,99 (um clique, cartão salvo com consentimento)
      ├ sim → Oferta extra: pelúcias para o 2º kit US$7,99 (D13; T8 testa US$9,99) → Obrigado
      └ não → Downsell (T7): A = pelúcias US$7,99 (só sem bump) · B = 2º kit por US$29,99 (todos) → Obrigado
  → E-mail de confirmação: cada cobrança numa linha + link assinado do First Hunt Guide
  → Purchase no Meta uma vez, com o total (pixel + API, mesmo event_id)
```

## 4. A conta (manual, Parte 5)
Hipóteses: 80% kit 1, 20% kit 2; bump 10%; upsell 5%; downsell 3% de quem o vê; reembolso 5%; contestação 0,5%.

| Item | Base | Pessimista (−30%) | Preço B do T3 (US$39,99 / 69,99) |
|---|---|---|---|
| Ticket bruto por comprador | US$42,24 | US$41,73 | US$48,24 |
| **CPA de empate** | **US$23,83** | **US$22,84** | **US$29,14** |

- Lucro por visitante = conversão × CPA de empate − custo por visitante. Precisa de ~2,5% de conversão com clique a US$0,50.
- **Preço B (US$39,99) empata no lucro mesmo se a conversão cair até 82% da do A** (queda de 18%). É por isso que o teste de preço vale a pena.
- Alavancas, da maior para a menor: custo na CJ (US$1 = US$1 no CPA de empate) → preço do kit 1 → participação do kit 2 → bump/upsell/downsell juntos (~US$1,20).

## 5. Testes
A ordem, a hipótese, a métrica e a régua de cada um ficam em [testes/fila.md](testes/fila.md). O motor está pronto: um teste ativo por vez em `config.js` e `config.php` (`tests.active`), sorteio fixo por visitante, a versão vai em todo evento e em cada pedido.

## 6. Textos
Em [05-pagina/copy.md](05-pagina/copy.md).

## Histórico
- v3 (24/11, noite): D13 (oferta para quem aceita o upsell) e D14 (downsell = 2º kit mais barato, como teste T7). Testes do pós-compra ganham camada própria, sorteada pelo pedido, que roda junto com o teste da página.
- v1 (24/11, 05:00): primeira versão, downsell = guia PDF pago.
- v2 (24/11): pedido do Kenui. Decisões D1 a D8 pela persona; guia grátis como bônus (pronto); downsell físico; digital pago só como teste T6; motor de A/B com preço; conta refeita.
