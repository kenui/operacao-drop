# Modelo: ficha de uso do produto no vídeo (v1, 23/11/2026)

Uma ficha por produto, salva em `operacoes/<op>/produtos/<produto>/uso-no-video.md`.
É feita antes de qualquer prompt de vídeo. O agente de criativos lê a ficha, preenche o bloco PRODUCT LOCK e monta a lista de cenas a partir dela.
Cada vídeo aprovado ou reprovado volta para a seção 9 da ficha. A ficha nunca é apagada, só ganha versões.

## Como pesquisar (só fontes gratuitas; material de terceiros é estudo, não entra no anúncio)
1. Página do fornecedor e manual: medidas, peso, material, cores, partes móveis, voltagem, capacidade.
2. Biblioteca de Anúncios do Meta: nos anúncios que escalam, qual momento de uso aparece nos 3 primeiros segundos e qual é o efeito mostrado.
3. Vídeos reais de uso (TikTok, YouTube, Reels, avaliações em vídeo): como as pessoas seguram, onde erram, o que as faz reagir, que som o produto faz.
4. Avaliações escritas (Amazon, AliExpress): o que surpreendeu ("didn't expect it to be this...") e o que decepcionou. O que decepciona vira limite: não mostrar.
5. Filmagem própria ou licenciada do fornecedor: 3 a 10 s do efeito real, para usar como referência de vídeo no Kling Omni.

## 1. Identidade
- Nome interno:
- O que é, em uma frase simples:
- O que ele faz de verdade (comprovado por filmagem real):
- O que ele NÃO faz (limite da promessa):

## 2. Física do produto
| Campo | Valor real | Como a IA vai entender (comparação visual) |
|---|---|---|
| Tamanho (cm) | | ex.: "about the size of a smartphone", "fits in one palm" |
| Peso (g) | | comportamento: "light, she lifts it with two fingers" / "heavy, wrist dips, uses both hands" |
| Material e acabamento | | como a luz bate: "matte soft-touch plastic, no shine" / "brushed metal, small soft highlight" |
| Cor exata | | "warm off-white, not pure white" |
| Partes móveis e estados | | ex.: tampa aberta/fechada, vazio/cheio, desligado/ligado |
| Som | | ex.: "low motor hum", "soft magnetic click" |
| Luz própria | | ex.: "small blue LED ring, dim in daylight" |
| Rótulo e marca | | Em geral "blank surface, no readable text" (a IA erra letras) |

## 3. Como se segura
- Mão principal e pegada (dedos, palma, pinça):
- O que a outra mão faz:
- Postura e distância do corpo:
- Erros comuns de quem usa (de vídeos reais):

## 4. Sequência de uso real (passo a passo, com tempo)
| Passo | Ação física | Estado antes → depois | Tempo real |
|---|---|---|---|
| 1 | | | |
| 2 | | | |

## 5. Dor e efeito uau
- A dor visível (o momento antes, que a câmera consegue mostrar):
- O efeito uau: a UMA mudança visível que prova que funciona (antes → depois na mesma tomada):
- Por que surpreende (o que a pessoa espera e o que acontece):
- Reação natural de quem testa (comportamento, não emoção): ex.: "pauses, raises eyebrows, leans closer, tries it again"
- Filmagem real de referência desse efeito (link na mídia):

## 6. Física que a IA costuma errar com este produto
- Gravidade e queda (líquido escorre para baixo, pó assenta, objeto pesado não flutua):
- Líquidos, espuma, vapor, fumaça:
- Deformação (o que amassa, o que é rígido):
- Cabos, alças, tampas (não somem nem duplicam):
- Escala (o produto não cresce nem encolhe entre cortes):

## 7. Referências para o Higgsfield
- Imagens por ângulo: frente, lado, cima, na mão (escala)
- Vídeo real de 3 a 10 s do efeito
- Papel de cada referência e o que NÃO herdar de cada uma

## 8. Bloco PRODUCT LOCK (gerado da ficha, em inglês, entra no prompt mestre)
```
PRODUCT LOCK: ONE [produto], never duplicates, same size in every shot: [comparação de tamanho].
Weight: [comportamento do peso]. Material: [como a luz bate]. Color: [cor exata]. Blank surface, no readable text.
Grip: [mão e pegada]; the other hand [ação].
States only move forward: [estado 1] -> [estado 2] -> [estado 3]. Never reverses.
Physics: [regras de gravidade/líquido/deformação deste produto].
Sound: [som real] only when [ação]; no sound otherwise.
WOW BEAT: [mudança visível antes -> depois], in one continuous shot, real speed, no slow motion, matching reference video @[n].
Reaction: [comportamento natural]. She does not claim long-term results.
```

## 9. Histórico de vídeos deste produto
| id | versão do jeito certo | o que mudou | nota interna | taxa de gancho | custo por compra | resultado |
|---|---|---|---|---|---|---|
