# Controle de cena: como ter precisão no vídeo de IA

Pedido do Kenui (25/11/2026): parar de perder o controle das criações, com pesquisa e método, porque as grandes produções controlam cada detalhe, inclusive em cenas longas.

## A ideia central

O modelo de vídeo é um ótimo renderizador e um péssimo diretor. Ele não guarda o espaço em 3D, não conta objetos e decide de novo tudo o que não foi decidido antes. Cada coisa que deixamos para o prompt "pedir" é uma coisa que ele pode mudar.

Por isso o método tira do modelo tudo o que precisa ser exato e deixa para ele só o que ele faz bem: pele, pelo, tecido, luz natural e movimento orgânico.

Fontes: [Flick, Blender para cinema com IA](https://flick.art/blog/blender-ai-filmmaking), [Primores, composição e quadros-chave para produto](https://primores.org/wiki/marketing/ai-product-video-fidelity/), [Turbogen, fluxo com imagem aprovada antes do vídeo](https://dev.to/turbogen_app/consistent-characters-in-ai-video-the-still-first-workflow-seedance-25-kling-40-grok-veo-2pme), [Magic Hour, consistência de produto](https://magichour.ai/blog/how-to-keep-products-consistent-in-ai-videos), [MindStudio, Seedance 2.5 e Kling Omni](https://www.mindstudio.ai/blog/seedance-2-5-vs-kling-omni-director-ai-video-control), [MindStudio, mapa de profundidade como storyboard](https://www.mindstudio.ai/blog/depth-maps-as-storyboards-ai-video-generation).

## Diagnóstico do V5b (25/11), o caso que motivou o método

| Desvio | Causa | Camada que resolve |
|---|---|---|
| Sala escureceu sozinha depois do clique | O modelo associa "luz ligou" a "sala escura". Só havia quadro inicial, nada segurando o fim | 2 (quadro final) e 5 (correção da luz) |
| Anéis passaram de 3 para 2 e o esquilo sumiu | Modelo não conta nem segura forma gráfica por 11 s | 4 (efeito na pós) |
| Aparelho menor no chão do que na mão | Escala sem referência fixa, objeto trocou de lugar dentro do clipe | 1 (cena 3D com medida real) e 2 |
| Enquadramento e sala diferentes da referência (V5) | Composição descrita em texto | 1 e 2 |
| Avatar mudando | Uma tomada longa com muitas ações | 0 (cortar em tomadas curtas) e fichas |

Um clipe de 11 s pedia quatro mudanças de estado (tirar da caixa, pôr no chão, ligar, brincar). Cada mudança é uma chance de desvio.

## As seis camadas de controle

### 0. Roteiro em tomadas curtas, uma mudança de estado por tomada
- Cada tomada tem de 3 a 6 s, uma ação, um movimento de câmera e no máximo uma mudança de estado.
- **Por quê:** a fidelidade cai com a duração, e duas ações num prompt fazem o modelo ignorar uma ou deformar a cena. O corte é de graça e o UGC real é cheio de cortes.
- **Cena longa:** encadear tomadas pelo último quadro aprovado, por no máximo 2 ou 3 elos. Depois disso, cortar para outro ângulo em vez de insistir. A emenda vai num momento de pausa, nunca no meio de um movimento rápido.

### 1. Cena 3D por código com medidas reais (ferramenta `cena-3d`)
- O produto entra com o tamanho real (17 cm), a câmera com altura e lente de celular, o animal com o caminho exato e o olhar travado na presa.
- **Saída:** um vídeo-guia de movimento e os quadros-chave de composição de cada tomada.
- **Por quê:** no 3D os objetos ficam onde foram postos e a escala não muda. Os profissionais fazem isso no Blender com formas simples, porque detalhe demais confunde o modelo.
- Custo zero de crédito.

### 2. Quadro inicial e quadro final aprovados para cada tomada
- O quadro do 3D vira foto realista num editor de imagem (Nano Banana Pro ou GPT Image), com as fichas de pessoa, animal, produto e sala anexadas. A instrução trava a composição: "keep composition, camera position, framing and pose 100% unchanged".
- Quando o modelo aceitar, fazer também o **quadro final** da tomada: mesma sala, mesma luz, estado final.
- **Por quê:** com só o quadro inicial o modelo inventa o caminho e o fim (foi assim que a sala escureceu). Com início e fim fixos ele só interpola entre dois estados que nós definimos.
- **Ficha de sala:** fazer uma ficha do cômodo, de 2 ou 3 ângulos, com a mesma hora do dia. Metade das reclamações de "personagem mudou" é o fundo mudando.
- **Regra do Primores:** a imagem para vídeo só anima formas que já estão no quadro. Se o efeito tem que aparecer, ele precisa estar no quadro final, ou entra na pós.
- Cada quadro custa de 1 a 4 créditos. Alinhar todos lado a lado antes de animar qualquer um.

### 3. Movimento guiado por referência, quando o movimento importa
- **Seedance 2.5 (omni reference):** quadro inicial + vídeo-guia do 3D, com prompt curto: "Reference the character's actions and camera language from @Video."
- **Kling 3.0 Omni:** vídeo de referência de 3 a 10 s para câmera e movimento.
- **Topview:** conferir na própria ferramenta quais modelos aceitam quadro final ou vídeo de referência. Onde não aceitar, usar as camadas 0, 2 e 4, que valem em qualquer modelo.
- **Por quê:** o caminho do cachorro e o olhar dele saem iguais ao 3D, sem o prompt precisar descrever.

### 4. O que precisa ser exato entra na pós, não no modelo (ferramenta `pos-controle/projetar_no_chao.py`)
- A projeção (quantos anéis, onde, qual bicho, quando liga) é desenhada por código e presa ao piso por rastreamento. Ela é somada como luz, que é o que um projetor faz: clareia o chão e o que estiver em cima, inclusive o cachorro.
- **Como gerar o clipe para isso:** pedir o cachorro caçando um pequeno ponto de luz laranja no chão (laser), que os modelos fazem bem. Depois desenhar os anéis ao longo do caminho do ponto, para o cachorro pular exatamente neles.
- **Antes de ligar** não existe luz nenhuma, por construção (`liga_em_s`). A ordem dos estados nunca falha.
- **Produto com forma ou logo críticos:** em tomada parada, colar a foto real recortada (o modelo nunca redesenha), com sombra de contato. O produto fica sempre apoiado em superfície, nunca flutuando, e nada passa na frente dele.
- **Por quê:** é o detalhe que o comprador confere, e é o que o modelo erra a cada geração. E é mais honesto: o padrão mostrado é o padrão real do aparelho (FTC e Meta).
- Custo zero de crédito.

### 5. Conferência automática antes de mostrar (ferramenta `pos-controle/luz_clipe.py`)
- Mede a luz da sala quadro a quadro, sem contar sombras fundas nem o brilho do efeito, e aponta onde caiu mais de 12% em relação ao trecho de base.
- Com `--corrigir`, devolve a sala ao brilho de antes por curva de gama, que levanta médios e sombras e quase não mexe nos brilhos.
- **Atenção:** a base precisa ter enquadramento parecido. Quando a câmera aponta para um chão mais escuro, a medida cai sem ser erro.
- Somar à conferência que já existe (quadros por segundo, transcrição, `ver-video`): contar anéis, medir o tamanho do produto em relação à mão e checar a identidade nos quadros inicial, do meio e final.

### 6. Prompt curto quando as referências carregam a informação
- Com quadro inicial, quadro final e vídeo-guia, o prompt descreve só a ação desta tomada, a câmera, a fala e a textura.
- Os bloqueios (AVATAR, ANIMAL, PRODUCT e ROOM LOCK) continuam, ditos uma vez e em frase positiva ("the room brightness stays constant, the floor lamp stays on, exposure locked like a phone with AE lock").
- Evitar listas de "não deforme": alguns modelos rejeitam esse vocabulário, e frase positiva funciona melhor.
- Escrever "auto exposure locked" ajuda contra o escurecimento, mas não substitui o quadro final nem a correção.
- **Por quê:** cada palavra a mais é uma decisão que o modelo pode reinterpretar.

## Receita padrão para o próximo vídeo do projetor

1. Quebrar o roteiro em tomadas de 3 a 6 s, cada uma com no máximo uma mudança de estado. Exemplo: tirar da caixa, depois pôr no chão, depois o clique em close, depois o cachorro caçando, depois a reação.
2. Montar a cena 3D com as medidas reais e renderizar o guia e os quadros-chave (grátis).
3. Fazer os quadros inicial e final de cada tomada a partir do 3D, com as fichas e a ficha de sala (cerca de 1 a 4 créditos cada). Mostrar ao Kenui lado a lado.
4. Gerar cada tomada curta com início e fim, ou com vídeo-guia onde houver. A tomada do cachorro sai com ponto de luz, não com anéis.
5. Na pós: projeção exata presa ao piso, luz da sala travada, foto real do produto onde a forma importa.
6. Rodar a conferência automática, montar, e só então mostrar com o custo real.

## Teste de 25/11 (custo zero, sobre o clipe A do V5b)

- **Luz:** a sala caiu de 124 para cerca de 55 na escala de 0 a 255 depois do clique. Corrigida, ficou entre 115 e 119 sem tocar no efeito. Arquivos em [testes/controle-pos](testes/controle-pos/): v5b-luz-antes-depois.png e v5b-clipe-a-luz-travada.mp4.
- **Projeção:** 3 anéis desenhados por código ficaram presos ao piso durante 2,5 s de câmera na mão, com 93% dos pontos rastreados válidos, e a luz caiu sobre o cachorro. Arquivos: teste-projecao-antes-depois.png e teste-projecao-travada.mp4.
- O teste também mostrou o limite: por cima de anéis já gerados aparecem dois padrões. Por isso a tomada precisa ser gerada sem o efeito.

## Quando não usar
- **Ideia ainda em exploração:** um rascunho barato basta. O controle completo vale para o criativo que vai para o ar.
- **Câmera com muito movimento sobre o piso, ou piso sem textura:** o rastreamento perde a referência. Nesses casos, câmera mais parada na tomada da projeção, ou mais textura no piso (madeira, tapete).
