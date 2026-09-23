-- Execute once, with a backup, BEFORE publishing this version.
-- Adjust scl_ if your configured table prefix is different. MySQL/MariaDB, InnoDB.
ALTER TABLE scl_usuario MODIFY senha VARCHAR(255) NOT NULL;
ALTER TABLE scl_usuario ENGINE=InnoDB;
ALTER TABLE scl_usuario ADD COLUMN security_version INT UNSIGNED NOT NULL DEFAULT 0;
CREATE TABLE scl_password_reset (
  id_usuario INT NOT NULL PRIMARY KEY,
  token_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
  expires_at BIGINT NOT NULL
) ENGINE=InnoDB;
CREATE TABLE scl_auth_attempt (
  bucket CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL PRIMARY KEY,
  window_start BIGINT NOT NULL,
  attempts INT UNSIGNED NOT NULL DEFAULT 0,
  INDEX (window_start)
) ENGINE=InnoDB;
