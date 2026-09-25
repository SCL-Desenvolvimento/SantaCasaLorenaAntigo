-- Align field lengths with the administrative forms; back up before applying.
-- Legacy zero-date defaults require only a temporary session-level adjustment.
SET @scl_previous_mode = @@SESSION.sql_mode;
SET SESSION sql_mode = REPLACE(REPLACE(@@SESSION.sql_mode, 'NO_ZERO_DATE', ''), 'NO_ZERO_IN_DATE', '');
ALTER TABLE scl_usuario MODIFY nome VARCHAR(150) NOT NULL DEFAULT '',
  MODIFY usuario VARCHAR(100) NOT NULL DEFAULT '', MODIFY email VARCHAR(254) NOT NULL DEFAULT '';
ALTER TABLE scl_galeria MODIFY nome VARCHAR(150) NULL;
ALTER TABLE scl_ouvidoria MODIFY nome VARCHAR(150) NULL, MODIFY email VARCHAR(254) NULL, MODIFY cidade VARCHAR(150) NULL;
ALTER TABLE scl_doacoes MODIFY nome VARCHAR(150) NULL, MODIFY email VARCHAR(254) NULL, MODIFY cidade VARCHAR(150) NULL;
ALTER TABLE scl_trabalhe_conosco MODIFY nome VARCHAR(150) NULL, MODIFY email VARCHAR(254) NULL, MODIFY cidade VARCHAR(150) NULL;
ALTER TABLE scl_ouvidoria MODIFY mensagem TEXT NULL;
ALTER TABLE scl_doacoes MODIFY mensagem TEXT NULL;
ALTER TABLE scl_pesquisa_atendimento MODIFY mensagem TEXT NULL;
SET SESSION sql_mode = @scl_previous_mode;
