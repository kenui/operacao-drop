# Projetor pet: plano de testes (v2, 24/11/2026)

Regra do Kenui: **teste A/B de tudo, um por vez, inclusive preço**; decide o lucro. Protocolo geral no [manual de funil, Parte 8](../../../../conhecimento/funil/manual-funil.md).

## Como liga um teste
1. Em `05-pagina/site/public_html/config.js`: `tests.active = "<id>"`. Se o teste muda cobrança (preço, tipo de downsell), o mesmo id em `config.php` (`testes.active`).
2. Conferir as duas versões com `?v=A` e `?v=B` (só para conferir; nunca em anúncio).
3. Rodar até a régua. Nada de espiar e parar cedo.
4. Desligar (`active = ""`), passar o vencedor para o padrão do config, registrar em `../../registro-de-ganhos.csv` e em `conhecimento/funil/aprendizados.md`.

## Régua (igual para todos)
- Um teste por vez, 50/50, sorteio fixo por visitante.
- Mínimo **7 dias** e **300 visitantes por versão**; decisão bayesiana com **≥ 90%** de chance de a versão B ser melhor na métrica de decisão.
- Fase de pouca verba (< 200 compras/mês): decidir por métrica de topo quando o teste é de página; teste de **preço** decide por **lucro por visitante** e só roda quando houver volume (ver T3).
- Guarda-corpos que anulam uma vitória: reembolso, contestação (> 0,5%), e-mails de reclamação, velocidade.
- Depois de adotar: comparar 2 semanas depois com 2 antes. Se o ganho sumir, registrar "não se confirmou".

## A fila (ordem = ganho esperado ÷ custo; a ordem muda com dado real)

| # | id | O que muda | Hipótese (de onde vem) | Métrica de decisão | Amostra estimada | Quando |
|---|---|---|---|---|---|---|
| T1 | `T1-angulo` | ângulo do anúncio (A×B no gato, D×E no cachorro) | o buraco "captura" bate o controle (Biblioteca do Meta: ninguém usa) | custo por início de checkout, depois por compra, por ângulo | ~1.000 impressões por anúncio | já no lançamento (vem do `?a=`, não do sorteio) |
| T2 | `T2-kit2-mix` | kit 2 destacado "Mix cat + dog · good for gifts" | destacar sobe a participação do kit 2 de 20% para 30% (persona: presente, casas com os dois pets) | ticket médio do front | ~700 por versão | 1º teste com sorteio |
| T3 | `T3-preco-front` | kit 1 US$34,99 → **US$39,99** (kit 2 US$59,99 → 69,99) | o kit com pelúcia e caixa sustenta preço acima da cópia; B empata se a conversão cair até 18% | **lucro por visitante** (receita − custo − taxas por visitante) | precisa de ~150 compras por versão para ver 18% de diferença; rodar quando houver ~40 compras/semana | quando houver volume |
| T4 | `T4-bump-preco` | bump US$7,99 → US$5,99 | preço menor sobe o aceite o bastante para dar mais lucro por comprador | lucro do bump por comprador | longo (precisa de compradores) | junto da fila de compradores |
| T5 | `T5-upsell-preco` | upsell US$24,99 → US$19,99 | dobra o aceite e compensa a margem menor | lucro do upsell por comprador | longo | idem |
| T6 | `T6-downsell-digital` | downsell pelúcias (físico) → **Indoor Hunt Playbook** (PDF, US$9) | **a dúvida do Kenui**: digital tem valor percebido maior para esta persona? Hoje a evidência diz que não (conteúdo grátis abundante), então o padrão é físico; o teste decide | lucro do downsell por quem o vê | longo | só depois de escrever o PDF; não escrever antes de chegar aqui |
| T7 | (a criar) | primeira tela: vídeo do fornecedor × foto parada | vídeo do efeito real faz entender em 2 s (persona §5: o pet reagindo) | clique no botão | ~690 por versão | quando chegar o vídeo da CJ |
| T8 | (a criar) | prova antes ou depois da oferta | — | clique no botão | ~690 | quando houver prova real |

**Por que preço não é o primeiro:** com R$500 de verba, ver 18% de diferença em conversão exige centenas de compras. Testes de página (T2, T7) decidem com métrica de topo em ~700 visitantes por versão. O preço espera o volume, mas o motor já está pronto.

## Registro
Cada teste encerrado vira uma linha em `operacoes/drop-eua/registro-de-ganhos.csv` (data, id, versão vencedora, métrica, ganho em lucro por visitante, amostra, confirmou em 2 semanas?).

## Histórico
- v1 (24/11): 5 testes.
- v2 (24/11): ids ligados ao motor de A/B, régua única, preço entra com regra de volume, T6 testa o digital pago contra o físico.
