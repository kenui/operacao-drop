# Teste do método de controle: tomada da caça (25/11/2026)

**Por quê:** o V5b foi reprovado (cachorro perdeu o olhar, plástico atravessou o produto, 29,6 créditos). O Kenui aprovou um teste de uma tomada usando o método de `conhecimento/video-ia/controle-de-cena.md` de verdade e, no mesmo dia, pediu que os vídeos sejam sempre feitos assim.

## O que foi feito

| Camada | Como | Custo |
|---|---|---|
| Cena 3D guia | `ferramentas/cena-3d/cena_3d.py teste-controle/cena-3d.json` (câmera a 1 m, do sofá, produto e cachorro medidos) | 0 |
| Quadro inicial 4K | edição do último quadro do clipe A2 (mesma sala) + ficha do cachorro: sem anéis, sem caixa, um ponto de luz laranja, cachorro à esquerda olhando o ponto | 1,43 |
| Quadro final 4K | edição do quadro inicial mudando só o cachorro: reverência de brincar, patas dos dois lados do ponto, focinho no ponto | 1,43 |
| Clipe T1 5 s, 720p | `start_frame` + `end_frame`, prompt de uma ação só, bloco LIGHT LOCK, ponto fixo | 5 |
| Anéis e esquilo | `ferramentas/pos-controle/projetar_no_chao.py` com `teste-controle/padrao.json` (5 anéis, esquilo no anel do ponto) | 0 |
| Conferência | folha de 10 quadros + `luz_clipe.py` | 0 |

Custo real: 7,80 créditos (saldo 56,54 para 48,74).

## Conferência

- Olhar: focinho e olhos no ponto nos 10 quadros amostrados (0 a 5 s). Passa.
- Luz da sala: variação máxima 0,8% (limite 12%). Passa.
- Produto: parado, pata creme em pé na base preta, igual do início ao fim. Passa.
- Anéis: 5 do início ao fim, presos ao piso (rastreamento com 97% de pontos válidos). Passa.
- Boca do cachorro vazia, nada mole em cena. Passa.
- Fracos: o cachorro chega ao ponto em 1,5 s e fica quase parado nos 3,5 s seguintes (cortar para uns 3 s na montagem ou pedir mais movimento). O esquilo desenhado por código é um rascunho de silhueta e, achatado no chão, lê pouco como esquilo; trocar pelo desenho real do padrão do produto. Um anel passa por cima das patas do cachorro como luz, o que é correto para projeção, mas a projeção real sai da lente do aparelho e não de cima.

## Lições

1. `end_frame` no T1 funciona e é o que segura o olhar: o modelo só preenche o caminho. Os dois marcadores `<<@img_N>>` precisam estar no prompt.
2. Fazer o quadro final editando o quadro inicial (e não do zero) mantém sala, produto e ponto idênticos.
3. O retângulo `piso_px` do padrão precisa cobrir todos os anéis com folga, senão o anel é cortado na borda.
4. Um ponto de luz parado é alvo melhor do que presa em movimento: uma mudança por tomada.

## Vídeo inteiro por tomadas (estimativa)

Quatro tomadas de 3 a 5 s, com o quadro final de uma servindo de quadro inicial da seguinte: abertura com o produto já fora do saco (4 s), clique e anéis acendendo (4 s), caça (5 s, esta), final no sofá (4 s). Quadros em 1K (0,8 cada, o vídeo sai em 720p) e 1 crédito por segundo: cerca de 14 a 16 créditos para completar, cada tomada aprovada antes da seguinte. As tomadas aprovadas se recombinam em variações de gancho sem novo custo.

Arquivos: `/mnt/project-files/operacoes/drop-eua/produtos/projetor-pet/videos/teste-controle/`.
