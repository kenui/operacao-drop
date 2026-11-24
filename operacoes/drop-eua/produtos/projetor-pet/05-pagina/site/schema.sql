CREATE TABLE IF NOT EXISTS pedidos (
  token VARCHAR(32) PRIMARY KEY,
  criado VARCHAR(20), pago_em VARCHAR(20), funil_fim VARCHAR(20),
  status VARCHAR(20),
  pi_front VARCHAR(64), customer VARCHAR(64), payment_method VARCHAR(64),
  pet VARCHAR(10), kit VARCHAR(10), mix VARCHAR(20), bump INTEGER DEFAULT 0, angulo VARCHAR(20), variante VARCHAR(40), email_enviado INTEGER DEFAULT 0,
  valor_front INTEGER DEFAULT 0, custo_front INTEGER DEFAULT 0,
  upsell VARCHAR(20), pi_upsell VARCHAR(64), upsell_pet VARCHAR(10), valor_upsell INTEGER DEFAULT 0, custo_upsell INTEGER DEFAULT 0,
  downsell VARCHAR(20), pi_downsell VARCHAR(64), valor_downsell INTEGER DEFAULT 0, custo_downsell INTEGER DEFAULT 0,
  reembolsado INTEGER DEFAULT 0, contestado INTEGER DEFAULT 0,
  email VARCHAR(190), nome VARCHAR(190), cep VARCHAR(20), cidade VARCHAR(80), estado VARCHAR(10), endereco TEXT,
  ip VARCHAR(45), ua VARCHAR(255), ctx TEXT
);
CREATE TABLE IF NOT EXISTS eventos (
  criado VARCHAR(20), visitante_id VARCHAR(40), sessao_id VARCHAR(40), evento VARCHAR(40), event_id VARCHAR(80),
  produto VARCHAR(40), pet VARCHAR(10), angulo VARCHAR(20), variante VARCHAR(20), pagina VARCHAR(80), disp VARCHAR(10),
  utm TEXT, dados TEXT
);
CREATE TABLE IF NOT EXISTS tentativas (ip VARCHAR(45), criado VARCHAR(20));
CREATE TABLE IF NOT EXISTS webhooks (id VARCHAR(80) PRIMARY KEY, criado VARCHAR(20))
