# Método: cenas da pesquisa como referência para o vídeo com IA (v1, 24/11/2026)

Pedido do Kenui (24/11): os vídeos que já encontramos na pesquisa do produto viram material para (1) conhecer as características reais do produto, (2) entender o que está funcionando nos anúncios e (3) servir de referência para a criação por IA.
Motivo: os dois primeiros vídeos do projetor pet (D-v1 e D-v2) erraram o comportamento do aparelho. A luz aparecia no chão antes de ligar, o aparelho girava e estava deitado. Os vídeos reais mostravam o contrário. Este método existe para que nenhum prompt invente o comportamento do produto.

## 1. Juntar os vídeos (no garimpo e no dossiê)
- Para cada vídeo achado (Biblioteca do Meta, TikTok, YouTube, página do fornecedor), registre em `operacoes/<op>/produtos/<produto>/estudo/videos.md`:
  link, anunciante, data de início, dias ativo, nº de anúncios com o mesmo criativo, duração.
- O arquivo do vídeo vai para `estudo/videos/<fonte>-<id>.mp4` (mídia pesada fica no Drive com a mesma árvore; no repositório só relatório e quadros).
- Como pegar o arquivo: link direto com a ferramenta `ver-video` quando a rede deixa; senão o Kenui baixa pelo navegador e manda no chat.
- Prioridade: anúncios ativos há mais tempo e com mais cópias do mesmo criativo (sinal de que dá lucro), depois vídeos orgânicos com muita visualização, depois filmagem do fornecedor.

## 2. Ver e ouvir cada vídeo
    python3 ferramentas/ver-video/ver_video.py estudo/videos/<arquivo>.mp4 --saida estudo/analises/<arquivo>
Gera cenas com tempo, quadro por cena, folhas de 2 quadros por segundo, mapa do som e transcrição.
O agente olha os quadros e preenche, por cena: o que acontece, estado do produto, física.

## 3. Tabela de verdade do produto (vai para a ficha `uso-no-video.md`)
Cada comportamento do produto que vai entrar em prompt precisa de uma cena que prove, com fonte e tempo:
| Comportamento | O que os vídeos reais mostram | Prova (vídeo, cena, tempo) |
|---|---|---|
| Como liga | ex.: um toque no botão, só o botão acende primeiro | meta-1814586786477559, cena 3, 4,2 s |
| Estado desligado | ex.: chão liso, nenhuma luz | |
| De onde sai o efeito | | |
| Se mexe? | ex.: parado, não gira, só a luz muda | |
| Tamanho em relação à mão e ao pet | | |
| Som | | |
| Em que luz funciona | | |
Regra: comportamento sem prova fica marcado [suposição] e não pode ser o momento uau do vídeo.

## 4. O que está funcionando (padrão entre os anúncios)
Para cada vídeo, anote: gancho de 0 a 3 s (imagem e texto), em que segundo o produto aparece, em que segundo o efeito acontece, ângulos e altura da câmera, quem aparece (mão, rosto, pet), texto na tela, chamada final, duração, música ou som natural.
Depois, monte uma tabela comparando os vídeos e pese pelo tempo ativo. O que se repete nos anúncios mais antigos é o padrão a seguir. Um buraco que ninguém ocupa (ex.: a captura no projetor) é o ângulo para nos diferenciar.

## 5. Transformar a cena em referência para a IA
- A cena real é usada como **descrição e conferência**: tipo de plano, altura da câmera, tempo de cada passo, comportamento do produto escrito no bloco PRODUCT LOCK. Ela não é copiada.
- Material de terceiros (anúncios, vídeos de criadores) **não** é enviado como entrada da geração e não entra no anúncio. É só estudo.
- Filmagem do fornecedor, nossa ou licenciada pode ser usada como referência de movimento no Kling 3.0 Omni (3 a 10 s do efeito real).
- Cada cena do nosso roteiro cita a cena real que a sustenta (ex.: "WOW BEAT igual a meta-1814…, cena 4: anéis no piso, aparelho parado").

## 6. Conferir o vídeo gerado antes de aprovar
Rode o `ver-video` no nosso vídeo gerado e compare cena por cena com a tabela de verdade. Reprova se:
- o produto muda de estado fora da ordem (ex.: luz antes do clique);
- o produto se mexe quando o real fica parado, ou vice-versa;
- o tamanho, o formato ou o lado de onde sai o efeito diferem do real;
- o produto duplica ou some;
- a fala transcrita promete algo que a ficha não autoriza.
Cada reprovação vira uma linha em `aprendizados.md` e na seção 9 da ficha do produto.
