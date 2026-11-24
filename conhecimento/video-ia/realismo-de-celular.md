# Realismo de celular: imagem "ruim", voz real e produto protagonista (v1, 24/11/2026)

Esta página nasceu do retorno do Kenui sobre o vídeo D-v3 do projetor, em 24/11:
- ainda não parece real, a imagem está limpa demais e precisa parecer gravada no celular, com detalhes e defeitos;
- a voz soa falsa;
- o aparelho ficou ótimo, mas o cachorro não aparece brincando com a projeção e os efeitos de luz não aparecem;
- a pelúcia no final ficou natural, mas parece melhor que o produto;
- já se gastou bastante crédito.

## 1. Imagem: o modelo entrega 1080p limpo, então a sujeira entra depois (sem crédito)
Diagnóstico: Seedance e Kling devolvem um vídeo nítido, com cor de comercial e movimento suave. Pedir "phone footage" no prompt não basta, porque o modelo limpa o resultado.
Regras:
1. **Pós-processar sempre** com `ferramentas/celularizar/celularizar.sh ENTRADA SAIDA medio`. A ferramenta faz isto:
   - imagem: perde resolução e volta, aplica a nitidez exagerada de celular, granulado que muda a cada quadro, uma pequena tremida de mão e exposição que respira; deixa a cor levemente lavada e salva em 30 fps;
   - som: microfone de celular, eco curto de cômodo e chiado baixo;
   - compressão dupla, como num vídeo repostado.
   Roda antes do texto na tela (o texto fica nítido, como no TikTok). Use "forte" em cena noturna ou de pouca luz.
2. **Gerar o rascunho em 480p** e só finalizar o que passar na conferência (a nitidez de 1080p nem é aproveitada depois da sujeira).
3. **Luz de casa de verdade**: abajur ou lâmpada amarela no teto, luz misturada (janela azulada + lâmpada amarela), cantos escuros, um ponto estourado. Nada de luz uniforme.
4. **No prompt** (Riley + Kenui v0): "real phone video, slightly soft focus, visible digital noise in the dark areas, auto exposure adjusts late, autofocus hunts once, micro-jitter, the phone is held by someone standing, not a gimbal". Proibido: cinematic, crisp, 4K, sharp, smooth.
5. **Enquadramento imperfeito**: corte de cabeça ou de pé, horizonte um pouco torto, um reenquadramento tardio.

## 2. Voz: a voz gerada é a parte que mais denuncia a IA
Ordem de preferência:
1. **Sem narração.** Texto na tela + sons reais da cena: clique do botão, unhas do cachorro no piso, latido curto, risada fora de quadro. Os anúncios do projetor que estão rodando (Biblioteca do Meta, 24/11) são assim.
2. **Só reações curtas** (até 3 palavras), faladas para o pet e não para a câmera: "oh my god", "get it!", "buddy...", risada. Interjeição soa real; frase longa soa locução.
3. **Voz própria gravada no celular** (o Kenui ou alguém autorizado lê 2 ou 3 frases em inglês), usada como áudio e, se preciso, trocada de timbre com voice change. É material nosso e o mais natural.
4. Se usar voz gerada: frase quebrada, com hesitação ("okay so, um, he's been staring at me"), respiração, fala por cima de outro som, volume que varia, microfone a 1 a 2 m (eco do cômodo). Depois passar pelo celularizar.
Nunca: locução limpa e contínua, dicção perfeita, voz centralizada sem ambiente.

## 3. O produto é o protagonista
1. **O momento uau precisa de três coisas no mesmo quadro**: o aparelho, o efeito acontecendo e o pet reagindo ao efeito. Se uma faltar, a cena não cumpre o papel.
2. **O efeito tem de estar no quadro inicial.** O quadro inicial (imagem, ~1,5 crédito) já mostra os anéis acesos no piso e o cachorro com a pata em cima de um anel. O vídeo só anima a partir daí. Pedir para o modelo "fazer aparecer" a luz é o que faz ela sumir.
3. **Interação concreta, tirada das cenas reais**: fareja o anel, bate a pata, a luz cai por cima da pata, pula para o anel seguinte, faz play bow, late curto. No prompt: "his paw lands on a ring and the orange light falls on top of his paw".
4. **Ambiente na penumbra** (abajur aceso, sem luz do dia), para o efeito aparecer de verdade. É também o limite honesto do produto.
5. **O efeito fica no centro e em primeiro plano** por pelo menos 4 s seguidos. Pelo menos um plano baixo, na altura do pet.
6. **A pelúcia é coadjuvante**: entra só nos últimos 2 a 3 s, menor no quadro, depois que a projeção já brilhou. A última imagem antes da chamada volta para o projetor ligado (ou para os dois juntos). Se a pelúcia rouba a cena, ela é cortada da versão principal e vira um teste A/B.

## 4. Economia de crédito
- Reaproveitar o que foi aprovado (aqui, o clipe A desligado e as imagens-base); gerar só o que mudou.
- Testar o quadro inicial antes (imagem é barata) e só animar o quadro aprovado.
- Rascunho em 480p, conferido com `ver-video` (cenas, efeito presente, pet interagindo), e só então o final.
- Pós-processamento, som e montagem não gastam crédito.
- Orçar antes e pedir o aval do Kenui para o gasto.

## Conferência antes de mostrar
- [ ] Parece celular depois do celularizar (granulado, nitidez de celular, tremida, compressão)?
- [ ] O efeito aparece com o pet interagindo, no mesmo quadro, por 4 s ou mais?
- [ ] O produto termina como protagonista?
- [ ] A voz é ausente, curta ou gravada por nós, e tem eco do cômodo?
- [ ] Nada promete o que a ficha não autoriza?
