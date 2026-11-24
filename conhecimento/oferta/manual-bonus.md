# Manual de bônus da oferta (v1, 24/11/2026)

Pedido do Kenui (24/11): "gostei do bônus que você colocou, que é o PDF [...] deixar a oferta mais atraente [...] criar uma imagem para o bônus [...] aumentar o valor percebido [...] criar um modelo disso para já ficar preparado para sempre fazer isso, quando vale a pena, de acordo com a estratégia, a persona".

Regra: **bônus só entra se responder a uma objeção ou a um ângulo da persona do produto.** Bônus enfeite (que não mexe em nenhuma objeção) não entra, porque pesa na página e não vende.

## 1. Quando vale a pena
Entra quando pelo menos um destes vale:
- Uma objeção da persona vira reembolso ou carrinho abandonado e um material resolve ("vai ignorar", "não sei usar", "vai enjoar").
- Um ângulo forte do anúncio pede uma continuação (ex.: "dia de chuva sem culpa" pede um plano para dia de chuva).
- O preço está acima do que a pessoa acha na Amazon e o bônus ajuda a mostrar que é um kit, não o aparelho solto.
Não entra quando:
- O conteúdo repete um produto pago do funil (upsell ou downsell digital). Se repetir, muda um dos dois.
- Depende de promessa de saúde, resultado garantido ou "vet approved" sem prova.
- Custa estoque ou frete (bônus físico só se a margem aguentar e o fornecedor colocar na caixa).

## 2. Passo a passo por produto
1. Abrir o dossiê de persona e listar as objeções em ordem e os ângulos que estão no ar.
2. Para cada objeção forte, escrever em uma linha o material que a resolve. Ficar com no máximo 3. Dois costuma bastar.
3. Escrever o bônus de verdade (PDF de 2 a 4 páginas, inglês americano, voz humana, fontes públicas citadas no rodapé). Nada de PDF vazio para encher lista.
4. Capa e mockup **por código** (HTML + Playwright), na paleta do produto: custo zero. Imagem de IA só com o aval do Kenui para os créditos.
5. Na página: um bloco "Free With Every Order" perto dos kits, com a imagem dos bônus, uma linha por bônus dizendo o problema que resolve, e uma linha por bônus no resumo do checkout com miniatura e "Free". Uma linha curta no topo, perto do preço ("+ 2 free bonus guides").
6. Entrega: link assinado na página de obrigado e no e-mail de confirmação (só oferece arquivo que existe).
7. Registrar a decisão na tabela de funil do produto (`07-funil.md`), com a objeção que cada bônus responde.
8. Virar teste: braço A com os bônus, braço B sem ou com um só. Métrica principal: lucro por visitante. Guarda-corpo: taxa de reembolso.

## 3. Valor do bônus: o que pode e o que não pode (FTC)
- **Não** escrever "valor de US$X" ou preço riscado no bônus se ele nunca foi vendido de verdade, por esse preço, em volume real. É preço de referência enganoso (FTC, Guides Against Deceptive Pricing, 16 CFR 233).
- Pode: "Free", "yours to keep", o número de páginas, e o que tem dentro. O valor percebido vem do conteúdo concreto e da imagem, não de um número inventado.
- Se um dia o material for vendido separado (ex.: downsell digital) e vender de verdade, aí dá para dizer "sold separately for $9".
- Nunca chamar de "limited time" se não for.

## 4. Modelo de bloco (copiar e trocar)
```
Free With Every Order
N Bonus Guides, Sent the Minute You Order
[imagem dos bônus]
Bonus 1: <nome>  · <problema que resolve, em uma frase>
Bonus 2: <nome>  · <problema que resolve, em uma frase>
PDFs, yours to keep. Download links on the thank-you page and in your receipt email.
```

## 5. Exemplos aplicados
| Produto | Bônus | Objeção ou ângulo | Arquivos |
|---|---|---|---|
| Projetor pet (cachorro) | First Hunt Guide | nº 2 "vai ignorar/enjoar" e nº 4 "vai latir/ficar frustrado" | `operacoes/drop-eua/produtos/projetor-pet/05-pagina/site/digital/` |
| Projetor pet (cachorro) | Rainy Day Play Plan (12 jogos + semana de rodízio) | ângulo "dia de chuva sem culpa" e nº 2 "vai enjoar" | mesmo lugar; mockup em `digital/fonte/mockup-bonus.html` |

## Histórico
- v1 (24/11/2026): criado a partir do pedido do Kenui e aplicado ao projetor pet.
