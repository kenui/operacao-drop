# Como a IA mexe na conta de anúncios do Meta (v1, 24/11/2026)

**Caminho escolhido pelo Kenui (24/11):** Marketing API direta, com usuário do sistema no Gerenciador de Negócios dele. Não depende de assinatura de terceiros (o conector do Higgsfield foi descartado).

## 1. Peças
| Peça | O que é | Onde fica |
|---|---|---|
| App "Little Catch IA" | app do Meta for Developers ligado ao portfólio, modo desenvolvimento (só contas próprias) | developers.facebook.com |
| Usuário do sistema "IA Little Catch" | "funcionário robô" do Business, função Admin; ativos: conta de anúncios (controle total), pixel 545543757913721, Página, Instagram, app | business.facebook.com > Configurações > Usuários do sistema |
| **Token A** (gestão) | ads_management, ads_read, business_management, pages_show_list, pages_read_engagement, pages_manage_ads; não expira | **Network secrets** do ambiente de nuvem do projeto (host `graph.facebook.com`, cabeçalho `Authorization: Bearer …`) ou variável `META_ACCESS_TOKEN`. A IA nunca vê o valor quando é Network secret. |
| **Token B** (leitura) | só ads_read; não expira | servidor da Hostinger, fora do public_html, gravado pela página de uso único da thread do painel |

**Por que dois tokens:** menor privilégio. O servidor compartilhado é o ponto mais exposto; com o Token B, quem invadir só lê números. Gestão só acontece de dentro do ambiente da IA.
**Regra:** token nunca no chat, nunca no Git, nunca em arquivo do repositório.

## 2. Passo a passo que o Kenui fez (para refazer em outro negócio)
1. https://developers.facebook.com/apps/creation/ → caso de uso de anúncios / Marketing API → ligar ao portfólio.
2. https://business.facebook.com/latest/settings/system_users → Adicionar → Admin.
3. Atribuir ativos: conta de anúncios (controle total), conjunto de dados/pixel, Página, Instagram, app.
4. Gerar token A e token B (validade "Nunca"), permissões da tabela acima.
5. Token A → Project settings > ambiente de nuvem > engrenagem > Network secrets. Token B → página de uso único do servidor.
Uma sessão nova da IA passa a enxergar o Token A.

## 3. Como a IA chama a API
- Base: `https://graph.facebook.com/v23.0/` (subir a versão quando a Meta descontinuar; a versão em uso fica anotada aqui).
- Conferir acesso: `GET me/adaccounts?fields=id,name,currency,account_status,amount_spent,spend_cap,timezone_name`.
- Ler: `GET act_<id>/insights?level=ad&date_preset=last_7d&fields=campaign_id,adset_id,ad_id,ad_name,spend,impressions,reach,frequency,cpm,inline_link_clicks,outbound_clicks,actions,action_values,video_play_actions,video_thruplay_watched_actions,purchase_roas&time_increment=1`.
- Escrever: `POST act_<id>/campaigns`, `.../adsets`, `.../adcreatives`, `.../ads`, `POST <id>` com `status`, `daily_budget`, `spend_cap`. Valores de orçamento vão em **centavos da moeda da conta**.
- Regras automáticas da Meta: `POST act_<id>/adrules_library` (corte duro, alerta de CPA). Rodam do lado da Meta mesmo sem a IA acordada.

## 4. O que a IA faz sozinha e o que pede aval
| Sozinha (dentro de um plano aprovado) | Pede o aval do Kenui |
|---|---|
| ler métricas, montar relatório, comparar com o painel | **ativar** campanha nova (status ACTIVE) |
| criar campanha/anúncio **pausado** para ele revisar | subir orçamento acima do total aprovado ou mexer no spend cap |
| pausar anúncio pela régua de corte (§6 do manual) | abrir país novo, campanha de remarketing ou produto novo |
| redistribuir orçamento dentro do total aprovado (+20% em escala, se o plano autorizar) | qualquer gasto fora do plano |
| criar regras automáticas que só **pausam** ou avisam | mudar forma de pagamento, usuários ou permissões da conta |
Pausar é sempre permitido: reduz gasto, nunca aumenta.

## 5. Rotina da IA depois do lançamento
- **Diária (9h de Cuiabá):** ler insights do dia anterior por anúncio; casar com o painel (pedidos, lucro); aplicar a régua; anotar decisões em `operacoes/drop-eua/produtos/<produto>/trafego/diario.md` (data, número, regra, ação).
- **Semanal (segunda):** relatório curto ao Kenui com lucro depois do anúncio, MER, CPA, o que foi pausado/escalado, próximo criativo pedido à thread de vídeos.
- **Mensal:** revisar o manual nas fontes (§12 do manual) e o aprendizado no `aprendizados.md`.

## 6. Moeda e imposto
- Se a conta for em BRL, a API devolve gasto em BRL: converter pelo câmbio do dia para comparar com o painel (USD).
- Conta faturada no Brasil: custo real = gasto da API × 1,1383 (PIS/Cofins + ISS por fora desde 01/01/2026). Combinado com a thread do painel.

## 7. Enquanto o caminho direto não existe (desde 24/11): conector Higgsfield
O Meta bloqueou a criação do app (dispositivo não confiável). Por enquanto a IA gerencia pelo conector Higgsfield (`meta_ads_tools/read/write`, login OAuth do Meta). As mesmas regras de aval da §4 valem.
**Gasto no painel sem Token B** (combinado com a thread do painel): na rotina diária a IA lê o gasto por campanha e por dia pelo conector e grava `_dados/ads-gasto.json` no ramo `deploy-littlecatchco` (pasta bloqueada por .htaccess). Uma cópia vai para o histórico em `operacoes/drop-eua/produtos/projetor-pet/05-pagina/site/dados/ads-gasto.json` no main. Formato:
```json
{"atualizado":"2026-11-25T10:00:00Z","fonte":"conector","moeda":"BRL","acrescimo":0.1383,
 "linhas":[{"campaign_id":"120...","campaign_name":"...","adset_id":"120...","adset_name":"...",
            "ad_id":"120...","ad_name":"...","date":"2026-11-24","spend":12.34}]}
```
**Uma linha por anúncio e dia** (desde 25/11: o painel mostra gasto e ROAS por anúncio, conjunto, campanha e ângulo). Nunca misturar linhas por campanha e por anúncio no mesmo dia, porque o painel somaria duas vezes. Referência do formato: comentário em `public_html/api/_ads.php` no ramo de deploy. Commitar o arquivo no ramo `deploy-littlecatchco`, porque cada publicação apaga do servidor o que não está no Git. `spend` vai na moeda da conta e `date` no fuso da conta. Pode mandar o arquivo inteiro a cada vez: vale a última linha de cada anúncio e dia. Nunca vai token nem dado pessoal. Se o Token B existir, ele tem prioridade. Gravar no ramo de deploy pede a autorização do Kenui uma vez, antes da primeira gravação.

## Histórico
- v1 (24/11/2026): caminho direto escolhido pelo Kenui; dois tokens; rotina.
