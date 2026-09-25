<?php
function admin_fixture(PDO $db): void {
    $db->exec('CREATE TABLE IF NOT EXISTS admin_fixture_state (id INTEGER PRIMARY KEY)');
    if ($db->query('SELECT COUNT(*) FROM admin_fixture_state')->fetchColumn()) return;
    $db->exec('INSERT INTO admin_fixture_state VALUES(1)');
    $db->exec('CREATE TABLE scl_galeria (id_galeria INTEGER PRIMARY KEY AUTOINCREMENT,nome TEXT)');
    $db->exec('CREATE TABLE scl_anexo (id_anexo INTEGER PRIMARY KEY AUTOINCREMENT,url TEXT,titulo TEXT,descricao TEXT,nome TEXT,tipo TEXT,mime_type TEXT,id_usuario INTEGER,data TEXT)');
    $db->exec('CREATE TABLE scl_galeria_anexo (id_galeria INTEGER,id_anexo INTEGER,ordem INTEGER DEFAULT 0,legenda TEXT)');
    $db->exec("INSERT INTO scl_galeria VALUES(1,'Galeria de demonstração'),(2,'Outra galeria'); INSERT INTO scl_anexo(id_anexo,url,descricao) VALUES(1,'resources/img/icon-logo.png','Legenda original'),(2,'resources/img/icon-logo.png','Segunda imagem'); INSERT INTO scl_galeria_anexo VALUES(1,1,0,NULL),(1,2,1,NULL),(2,1,0,NULL)");
    $db->exec('CREATE TABLE scl_banner (id_banner INTEGER PRIMARY KEY AUTOINCREMENT,titulo TEXT,link TEXT,img TEXT,status INTEGER)');
    $db->exec('CREATE TABLE scl_noticia (id_noticia INTEGER PRIMARY KEY AUTOINCREMENT,titulo TEXT,subtitulo TEXT,descricao TEXT,link TEXT,img TEXT,status INTEGER,criador INTEGER,alterador INTEGER,data_criacao TEXT,data_alteracao TEXT)');
    $db->exec('CREATE TABLE scl_tag (id_tag INTEGER PRIMARY KEY AUTOINCREMENT,nome TEXT,status INTEGER,url TEXT,descricao TEXT)');
    $db->exec('CREATE TABLE scl_tag_noticia (id_tag_noticia INTEGER PRIMARY KEY AUTOINCREMENT,id_tag INTEGER,id_noticia INTEGER)');
    foreach (['contato','ouvidoria','trabalhe_conosco','doacoes','pesquisa_atendimento'] as $table) {
        $db->exec('CREATE TABLE scl_'.$table.' (id_'.$table.' INTEGER PRIMARY KEY,nome TEXT,email TEXT,cidade TEXT,data_cadastro TEXT,mensagem TEXT,assunto TEXT,razao TEXT,tipo TEXT,curriculum TEXT,tempo_espera TEXT,nota_atendimento TEXT,resolucao_problema TEXT,preparo_atendimento TEXT,informacoes_passadas TEXT,perguntas_respondidas TEXT,experiencia TEXT)');
        $db->exec("INSERT INTO scl_$table (id_$table,nome,email,cidade,data_cadastro,mensagem,experiencia) VALUES (1,'Registro sintético','fixture@example.invalid','Lorena','2026-09-23 00:00:00','=FORMULA teste','Boa'),(2,'Outro registro','other@example.invalid','Lorena','2026-09-24 10:00:00','Texto de teste','Regular')");
    }
    $db->exec('ALTER TABLE scl_doacoes RENAME COLUMN id_doacoes TO id_doacao');
}
