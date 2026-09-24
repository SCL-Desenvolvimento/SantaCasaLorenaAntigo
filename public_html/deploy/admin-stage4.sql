-- Execute once in staging, after backup. Adjust scl_ if the prefix differs.
ALTER TABLE scl_galeria_anexo ADD COLUMN ordem INT NOT NULL DEFAULT 0,
  ADD COLUMN legenda VARCHAR(255) NULL DEFAULT NULL;

-- Transações de galerias e atualização de páginas exigem InnoDB.
ALTER TABLE scl_galeria ENGINE=InnoDB;
ALTER TABLE scl_galeria_anexo ENGINE=InnoDB;
ALTER TABLE scl_anexo ENGINE=InnoDB;
ALTER TABLE scl_paginas ENGINE=InnoDB;
ALTER TABLE scl_paginas_historico ENGINE=InnoDB;
ALTER TABLE scl_pagina_sobre ENGINE=InnoDB;
ALTER TABLE scl_pagina_humanizacao ENGINE=InnoDB;
ALTER TABLE scl_pagina_programa_nacional_seguranca ENGINE=InnoDB;
ALTER TABLE scl_pagina_acoes_sociais_ambientais ENGINE=InnoDB;
ALTER TABLE scl_pagina_localizacao ENGINE=InnoDB;
ALTER TABLE scl_pagina_doacao ENGINE=InnoDB;
ALTER TABLE scl_pagina_especialidades ENGINE=InnoDB;
ALTER TABLE scl_pagina_capacidade_instalacao_producao ENGINE=InnoDB;
ALTER TABLE scl_pagina_manual_paciente_visitante ENGINE=InnoDB;
ALTER TABLE scl_pagina_unidade_internacao ENGINE=InnoDB;
ALTER TABLE scl_pagina_pronto_atendimento ENGINE=InnoDB;
ALTER TABLE scl_pagina_hotelaria ENGINE=InnoDB;
ALTER TABLE scl_pagina_clinica_emilia ENGINE=InnoDB;
ALTER TABLE scl_pagina_centro_diagnostico_por_imagem ENGINE=InnoDB;
