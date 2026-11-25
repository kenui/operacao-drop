# Diário de tráfego: projetor pet

Data no fuso de Cuiabá. Cada linha: métrica, regra do manual §6 / plano §4, ação.

## 24/11/2026
- 21h23: campanha `projetor_lanc_vendas_20261124` ligada (aval do Kenui "pode ligar" + R$850/7 dias). 6 anúncios (3 conceitos × com/sem música), R$106,70/dia, spend cap R$746,70. Regra de corte duro criada (R$216 sem compra → pausa). Dia 1 é parcial (começou 17h23 em LA).
- 21h30: conjunto trocado por `broad_us_adv` (atribuição padrão do Meta, 7d clique + 1d visualização); o antigo foi pausado sem gasto.

## 25/11/2026
- 10h (leitura das primeiras ~12 h, a maior parte na noite dos EUA): gasto R$80,93 de mídia (≈R$92 com imposto), 142 impressões, CPM ≈R$570 (≈US$105), 10 cliques no link, 6 visualizações da página, 0 início de checkout, 0 compra. O Meta pôs 80% no `aprovado-controle-v1` (R$64,65, 127 impressões, CTR de link 7%, custo por clique ≈R$7,20). Os outros 5 tiveram menos de 6 impressões.
  - Regra: "Esperar" (anúncio com menos de US$36 gastos e menos de 3 dias). Nenhuma ação. A regra de corte duro rodou a cada 30 min sem pausar nada.
  - Alerta: CPM 4 a 8 vezes acima da referência (US$12–25). É típico das primeiras horas de conta nova otimizando para compra, mas, se continuar acima de ~US$40 no fim do dia 2, investigar (público, horário, qualidade do criativo).
  - ads-gasto.json gravado no ramo de deploy por anúncio e dia.
