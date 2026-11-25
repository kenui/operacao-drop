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
- 15h: o Kenui mandou o vídeo "Don't let a DOG LOVER see this" (12 s, pata em pé, anéis laranja com grama e coelho, CTA "Comment TOY"). Conferido quadro a quadro: o efeito só aparece depois que ela liga o aparelho, a luz sai da lente e cai plana no piso à frente, igual aos anúncios reais da Biblioteca. Ressalvas: sala clara de dia com a projeção muito nítida (no real ela fica mais fraca com luz forte) e o bicho é coelho, não esquilo. Subido como `dog-lover-v6` (anúncio 120250955918220381), mesmo texto e UTM do controle.
  - Pausados por dados (acumulado desde o lançamento): chuva-v1 R$34,16 com 25 impressões e 0 clique; chuva-musica R$4,72, 0 clique; fake R$0,42, 0 clique; fake-musica R$4,05, 0 clique. Mantidos: controle (R$90,67, 14 cliques, CTR 7,7%) e controle-musica (R$42,18, 5 cliques, CTR 10,2%).
  - Gasto total até aqui ≈R$176 de mídia (≈R$200 com imposto), 0 compra. CPM segue muito alto (R$500 a R$1.500).
- 18h15 (Cuiabá): **campanha pausada** a pedido do Kenui até passar a Black Friday e a Cyber Monday (30/11). Total ao pausar: R$192,63 de mídia (≈R$219 com imposto), 274 impressões, 20 cliques, 10 visualizações da página, 0 checkout, 0 compra. Nada foi apagado e a estrutura ficou igual. Volta proposta para terça, 1/12, com o ok dele: a CJ leva de 8 a 12 dias para entregar nos EUA, então quem pedir até ~12/12 recebe antes do Natal. A leitura diária das 9h fica suspensa e o lembrete foi movido para 1/12.
