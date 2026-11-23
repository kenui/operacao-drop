# Operação drop

Memória e agentes da operação de dropshipping gerida por IA.

- `conhecimento/<tema>/`: o que vale para todas as operações. Cada tema tem `JEITO-CERTO.md` (o padrão que venceu, com histórico e prova) e `aprendizados.md`.
- `agentes/<agente>/`: prompt de cada agente, com versão (`prompt-v1.md`, `prompt-v2.md`...).
- `operacoes/<operacao>/`: preferências, estado, registro de ganhos e uma pasta por produto modelado.

Agentes: `dossie-e-copy` (pesquisa e copy), `funil-e-oferta` (kits, bump, upsell, produto digital, lucro por cliente), `landing-page` (página HTML com checkout Stripe). Conhecimento por tema: `copy`, `funil`, `landing-page`, `checkout`, `metricas`, `operacao`, `garimpo`, `video-ia`.
Régua de tudo: lucro por visitante, medido e testado sem parar (`conhecimento/metricas/plano-de-medicao.md`).

Imagens e vídeos ficam no Google Drive, com a mesma árvore de pastas.
Desenho completo: Claude Doc "Operação de dropshipping com IA: desenho v1".

**Regra de commits:** memória vai direto para o `main`; código e mudanças grandes vão por pull request.
