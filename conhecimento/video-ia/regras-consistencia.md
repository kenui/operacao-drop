# Regras de consistência: pessoa, animal e produto (v1, 24/11/2026)

Pedido do Kenui (24/11, depois do D-v2): "botar regras muito claras para as coisas que são importantes, tipo o avatar, o cachorro e o produto". Valem para **todo vídeo**, e são conferidas duas vezes: no papel antes de gerar e quadro a quadro depois.

## 0. Antes de gerar (portão do papel)
1. Ler, nesta ordem:
   - [JEITO-CERTO.md](JEITO-CERTO.md);
   - [aprendizados.md](aprendizados.md);
   - o guia completo (https://claude.ai/artifact/BLavwj65DLQWSpSJKHZjrV), com o modelo A (pessoa), o modelo C (clipe Seedance) e as travas;
   - a referência da capinha;
   - [retencao-viralidade-credibilidade.md](retencao-viralidade-credibilidade.md);
   - a ficha de uso do produto;
   - a persona.
   Regra do Kenui: sempre que precisar de uma função, ler a documentação dela e aplicar.
2. Ver o produto real funcionando antes de escrever qualquer coisa: filmagem do fornecedor ou os anúncios reais estudados. Anotar forma, orientação, de onde sai a luz ou o efeito, até onde ele chega e como o produto se comporta parado.
3. Escrever no plano a trava de cada um dos três (seções 1 a 3) e a tabela de estados.
4. Um estado do produto por clipe. A mudança de estado acontece no corte entre dois clipes, nunca dentro de um clipe.
5. Gerar primeiro um rascunho barato (Seedance `draft` 480p) e conferir. Finalizar na resolução boa só o que passou.

## 1. Pessoa (AVATAR LOCK)
- Gerada **uma vez** como imagem-base, com o modelo A do guia e o bloco de pele do Riley:
  - pele real sem retoque, mechas de cabelo soltas, roupa usada;
  - nunca "hyper-realistic", "8K" nem "pretty";
  - "high model facial features" só como o template manda.
- Toda imagem e todo clipe seguintes usam essa imagem como referência numerada com papel: "@image1 defines only her face, hair and outfit".
- Fixos, escritos em todo prompt: idade, cabelo (cor e penteado), 2 marcas do rosto, as 2 peças de roupa com as cores, unhas.
- No vídeo:
  - "Same face, [marcas] and [roupa] throughout";
  - a boca só mexe quando ela fala;
  - 2 a 3 palavras por segundo;
  - pisca e respira;
  - "a little shine on the forehead".
- Câmera: quem segura o celular fica escrito (CAMERA OWNERSHIP LOCK). Se o celular está apoiado, ela não segura celular nenhum.
- Reprova se:
  - o rosto mudou entre cortes;
  - a pele está lisa ou de cera, ou os dentes parecem de porcelana;
  - o reflexo nos olhos não bate com a luz;
  - a mão não tem 5 dedos;
  - a roupa mudou.

## 2. Animal (ANIMAL LOCK)
- Um animal só, com raça, cor, marcas e acessório fixos (ex.: "ONE small gray French bulldog, white patch on the chest, red collar, bat ears").
- Imagem-base do animal aprovada e usada como referência em tudo.
- Anatomia: 4 patas, 1 rabo, o tamanho não muda e nunca se estica no movimento. Sem borrão que deforme o corpo: pedir "real speed, motion stays sharp enough to read the body".
- O comportamento vem da ficha de uso (cachorro: fareja, play bow, late curto, bate a pata, sacode o brinquedo).
- Reprova se a raça, a cor ou a coleira mudaram, se o corpo esticou ou fundiu, ou se surgiu um segundo animal.

## 3. Produto (PRODUCT LOCK)
- A forma e a orientação vêm do produto real (ficha seção 2), nunca de suposição.
- Uma imagem-base do produto, só dele, com o fundo neutro, usada como referência em tudo.
- Escrever sempre:
  - "ONE [produto], never duplicates";
  - "same size and same spot in every shot";
  - "it never rotates, spins, tilts or slides on its own; it only moves when a hand moves it".
- Estados só avançam (desligado → ligado). Cada estado tem a lista do que **não pode** aparecer nele.
- Se o produto emite algo (luz, vapor, som), a física da emissão fica escrita:
  - de onde sai;
  - para onde vai;
  - até onde chega;
  - o que acontece quando algo entra na frente.
- Reprova se:
  - o produto girou ou deslizou sozinho, cresceu ou duplicou;
  - o efeito apareceu fora de hora;
  - o efeito não parece sair do produto;
  - o produto ficou maior que a escala real.

## 4. Depois de gerar (portão do quadro a quadro)
- Extrair 2 quadros por segundo e conferir cada item das seções 1 a 3 e a tabela de estados. Ouvir a fala com transcrição automática.
- Um quadro reprovado num ponto de trava = refazer ou cortar fora na montagem. Nunca mostrar ao Kenui com defeito de trava sem dizer qual é.
- Registrar na ficha do vídeo o que passou e o que falhou, e a lição em `aprendizados.md`.

## Histórico
| Data | O que gerou a regra |
|---|---|
| 24/11 | D-v1: luz no chão antes de ligar → um estado por clipe. |
| 24/11 | D-v2: a pessoa não estava ultra realista, o aparelho girou e a luz não parecia sair dele → seções 1 a 3. Além disso, o produto da v1 e da v2 estava deitado, e o real é uma pata em pé que projeta para a frente (anúncios da Biblioteca do Meta). |
