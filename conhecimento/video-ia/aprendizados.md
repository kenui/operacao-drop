# Aprendizados: vídeo com IA

Cada linha: o que funciona ou falha, e o link da ficha que provou.

## Funciona
- Declarar o aparelho no início ("A real phone photo, auto HDR off") e fechar com "Natural color, low contrast, no sharpening, no glow" (Kenui, Higgsfield 16/11/2026).
- Referências numeradas com papel e "use nothing else from them" mantêm pessoa, carro e lugar iguais entre cenas (Kenui, 16/11).
- "no macro pores at this distance" em plano aberto evita pele exagerada (Kenui, 16/11).
- No vídeo: "Same face, X and Y throughout" + "subtle handheld shake, focus settles a little late, exposure breathes slightly" (Kenui, Kling 3.0, 16/11).
- (A provar, fonte pública Riley/PromptWise 23/11) Pessoa que não segura o celular deixa as duas mãos livres para o produto; reflexo dos olhos coerente com a luz é o teste rápido de falso; descrever comportamento rende mais que rótulo de emoção.

## Falha
- Linguagem de cinema (shallow depth of field, sun flare, camera arc, epic, triumphant mood, glow, pulsos de luz) deixa com cara de comercial de IA; abandonada entre 12/11 e 16/11 (Kenui).
- (Fonte pública Riley 23/11, não testado por nós) Pedir emoção por rótulo ("happy", "surprised") gera cara de modelo posando; pele "perfeita" e poros uniformes entregam IA.
- **Efeito do produto antes da ação que o liga** (projetor pet D-v1, 24/11, Kenui reprovou): numa tomada única de 15 s com a linha do tempo "3-5 s aperta o botão, 5-10 s aparecem os anéis", o Kling pôs os anéis de luz no chão já aos 3 s, com o aparelho ainda na mão. O modelo não respeita ordem de tempo dentro de um prompt longo. Correção: (1) dividir em tomadas, uma por estado do produto (desligado → ligado), com corte no clique; (2) a imagem inicial da tomada "desligado" com o chão explicitamente vazio ("the floor is completely plain, no light, no shapes"); (3) a tomada "ligado" começa de uma imagem já com o efeito; (4) conferir quadro a quadro contra a sequência de uso da ficha antes de mostrar. Ver [ficha](../../operacoes/drop-eua/produtos/projetor-pet/videos/D-v1.md).
- **Bonito mas parado não é UGC** (D-v1, Kenui 24/11): uma tomada de ponto de vista sem rosto, cachorro pequeno no fundo, ficou realista mas "não trend". Precisa de pessoa com rosto e reação, cortes a cada 1 a 3 s, o bicho perto da câmera e um formato que o público reconhece do feed.

- **Forma do produto suposta em vez de vista** (D-v1 e D-v2, Kenui 24/11): o projetor saiu deitado numa base cinza e, no v2, girou sozinho; a luz não parecia sair dele. O real (anúncios da Biblioteca do Meta) é uma pata creme em pé numa base preta com lente na frente, projetando para a frente e para baixo. Correção: ver o produto real antes de escrever, imagem-base só do produto, PRODUCT LOCK com "never rotates, spins, tilts or slides" e a física da projeção escrita. Ver [regras-consistencia.md](regras-consistencia.md).
- **Pessoa "bonita" de imagem genérica não é ultra real** (D-v2): usar o bloco de pele do Riley (poros, brilho na testa, mechas soltas, roupa usada) e gerar a pessoa uma vez como imagem-base de referência.
- Projetor pet D-v1 e D-v2 (24/11): sem cena real como prova, a IA inventou comportamento (luz no chão antes de ligar, aparelho girando, aparelho deitado). Regra nova: comportamento do produto só com prova de vídeo real (cenas-de-referencia.md).
- **Folha de contato não basta para conferir o produto** (teste do ver-video, 24/11): a folha comum pegou a luz antes do clique do D-v1, mas não o giro do D-v2, porque o aparelho ocupa poucos pixels. Conferir sempre com `--recorte` no produto e citar o quadro que prova "passou". Ver [teste](testes/2026-11-24_teste-ver-video.md).
- Projetor pet D-v3 (24/11, Kenui): imagem limpa demais (1080p do Seedance), voz gerada soa falsa, efeito de luz não apareceu e o cachorro não interagiu; pelúcia roubou a cena. Correções em realismo-de-celular.md.
