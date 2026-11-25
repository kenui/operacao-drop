# Arsenal de técnicas de vídeo com IA: qual usar, por quê e quanto custa (v1, 24/11/2026)

Pedido do Kenui (24/11): ter um arsenal de técnicas no repositório, em documento e em código, e misturar as técnicas onde cada uma resolve melhor, sempre com o porquê registrado. Ideias dele: refazer viral, cena 3D por código renderizada pelo modelo de vídeo, imagens na maior qualidade e fichas de personagem.

Toda escolha de técnica entra no bloco DECISÃO CRIATIVA (`realismo-de-celular.md`, seção 4b) com o motivo e o teste.

## Matriz de decisão: problema, técnica, por quê, custo
| Problema | Técnica | Por quê | Custo (créditos Higgsfield, 24/11) | Ferramenta |
|---|---|---|---|---|
| Não sabemos qual estrutura prende | **Refazer viral** (molde de estrutura) | Copia o que já foi provado em público (ritmo, ordem, tempo de cada função); reduz chute | 0 para o molde; o vídeo custa o normal | `ferramentas/ver-video` + `ferramentas/refazer-viral` |
| Produto errado (forma, estado, luz antes de ligar, gira) | **Cena 3D por código** como guia de movimento e quadros | Posição, ordem de estados e escala ficam exatas por construção; o modelo só pinta a aparência | 0 para a cena (CPU, ~5 min); depois o vídeo com referência | `ferramentas/cena-3d` (Blender) |
| Animal não olha a presa / movimento sem lógica | **Cena 3D** (olhar travado por restrição) + GAZE LOCK no prompt | A cabeça segue a presa em todos os quadros do guia | 0 + vídeo | `cena-3d` |
| Personagem muda de rosto ou de pelagem entre cortes | **Ficha de personagem** (pessoa, animal, produto) | Referência fixa de vários ângulos + bloco de identidade igual em todo prompt | pessoa ~0,12 + ficha ~1,25; animal ~3,5; produto ~1,25 | `conhecimento/imagem-ia/guia-imagem-realista.md` |
| Pele de cera, cara de IA | **Imagem-base na maior qualidade** + bloco de realismo | O defeito da imagem passa para o vídeo; gastar aqui economiza vídeo | Nano Banana Pro 4k = 4 por imagem | guia de imagem |
| Efeito some ou aparece fora de hora | **Quadro inicial no estado certo** (imagem) + clipe por estado | O modelo anima o que já está no quadro; não precisa "criar" o efeito | 2 a 4 por quadro | guia de imagem + `realismo-de-celular.md` |
| Voz falsa, boca fora de sincronia | **Áudio nativo com conversa** ou **voz gravada nossa + lip sync** | Fala natural com pausas e ambiente; voz real quando a nativa falhar | lip sync: orçar na hora | `realismo-de-celular.md` seção 2 |
| Cena simples, sem produto complicado | **Prompt direto** (longo, em blocos) | Mais barato e rápido quando não há risco de erro de produto | só o vídeo | `realismo-de-celular.md` seção 5 |
| Contagem ou forma do efeito muda (anéis, bicho de luz) | **Efeito desenhado por código e preso ao piso na pós** | O modelo não conta nem segura forma gráfica; o padrão real fica exato e a luz cai sobre o animal | 0 | `pos-controle/projetar_no_chao.py` |
| Sala escurece ou clareia sozinha, avatar ou escala derivam numa tomada longa | **Tomadas de 3 a 6 s com quadro inicial e final** + medição e correção da luz | Com início e fim fixos o modelo só interpola; a correção salva o clipe sem regerar | quadros 1 a 4 cada; correção 0 | `controle-de-cena.md` + `pos-controle/luz_clipe.py` |
| Não sabemos se funciona antes de gastar | **Rascunho 480p + ver-video** | Pega erro antes do final | ~30-40% do final | `ver-video` |

Método completo de precisão, com as seis camadas e a receita: [controle-de-cena.md](controle-de-cena.md).

## Como misturar (receita padrão, do mais barato ao mais caro)
1. **Pesquisa:** `ver-video` nos anúncios e virais do nicho; tabela de verdade do produto (`cenas-de-referencia.md`).
2. **Estrutura:** se há um viral do mesmo público e da mesma dor, `refazer-viral` gera o molde; senão, estrutura base de 15 s do `realismo-de-celular.md`.
3. **Exatidão:** se o produto tem estado, luz, movimento ou interação com animal, monte a `cena-3d` com as medidas da ficha (grátis). Use o vídeo dela como referência de movimento e os quadros dela como composição dos quadros iniciais.
4. **Personagens:** fichas da pessoa, do animal e do produto (alguns créditos).
5. **Quadros iniciais:** uma imagem por clipe no melhor modelo, com as fichas como referência e o prompt longo de imagem.
6. **Vídeo:** o prompt longo (escrito pelo Fable 5.2), com as fichas, o quadro inicial e o guia 3D como referências. Rascunho 480p → `ver-video` → final.
7. **Registro:** a ficha do vídeo guarda cada técnica usada e o porquê; o resultado do anúncio volta para o painel e para o JEITO-CERTO.

## Refazer viral: quando vale a pena
Vale quando **todos** forem verdade:
- o vídeo tem prova pública de alcance: muitas visualizações para o tamanho do perfil, ou anúncio ativo há 14 dias ou mais com várias cópias;
- o público e a dor são os mesmos do nosso produto, ou muito próximos;
- o momento que viralizou é algo que o nosso produto faz de verdade (o efeito cabe na mesma função de cena);
- dá para recriar sem o rosto, a voz, o áudio, a música, o texto ou a marca do original.

Não vale quando:
- o viral depende de uma pessoa famosa ou de uma piada que não é nossa;
- depende de um som em alta sem licença para anúncio;
- a graça vem de um produto diferente do nosso;
- só daria para fazer copiando trechos do original.

Como fazer:
1. `ver_video.py VIRAL.mp4 --saida estudo/analises/<id>`
2. `molde_viral.py estudo/analises/<id> --produto "<produto>"`
3. Preencher a coluna "NOSSA cena" com o mesmo tempo e a mesma função de cada cena original.

## Cena 3D por código: como e quando
- **O que faz:** `cena_3d.py SPEC.json` monta no Blender, por código, o cômodo, o produto feito de peças com as medidas da ficha, o efeito (que só aparece depois do clique), o animal em blocos seguindo e olhando a presa, e uma câmera de celular baixa com tremida. Ela gera `guia_movimento.mp4`, `quadro_inicial.png`, `quadro_efeito.png` e `cena.blend`.
- **Por que:** os erros que tivemos (luz antes de ligar, aparelho girando, cachorro sem olhar) são de posição e de lógica, e isso o código faz exato. A aparência (pele, pelo, textura) fica com o modelo de vídeo.
- **Como entra no vídeo:**
  - o guia vai como **vídeo de referência de movimento** (Kling 3.0 Omni aceita 3 a 10 s; Seedance 2.5 omni_reference aceita vídeo de referência; Kling Motion Control para movimento de corpo);
  - os quadros 3D servem de **composição** para os quadros iniciais (imagem com a referência 3D + fichas → quadro fotorrealista).
- **Custo:** zero crédito para montar e renderizar (CPU, cerca de 3 a 5 min para 8 s em 360x640). O Higgsfield também tem um construtor de cena 3D (3D-jutsu); orçar antes de usar.
- **Quando não usar:** cena só de pessoa falando, sem produto com estado nem interação. Nesse caso, prompt direto com quadro inicial.
- **Limite:** o animal e as peças são blocos, e o guia mostra só posição, tempo e olhar. A textura sempre vem do prompt e das fichas.

## Lip sync e voz
Usar quando o rosto do criador fala por mais de 3 s seguidos e o áudio nativo não sincroniza. Opções: a voz nativa do Seedance ou do Kling com o diálogo escrito no prompt (primeira escolha), ou a voz gravada por nós, com lip sync por cima. Orçar antes.
