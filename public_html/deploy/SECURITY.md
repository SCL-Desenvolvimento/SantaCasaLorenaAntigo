# Ativação da etapa 2 — segurança

As alterações estão no código. Não foi efetuado deploy, acesso ao banco da hospedagem, alteração de usuários reais ou envio de e-mail real. Não publique apenas os arquivos PHP sem preparar o banco e as variáveis abaixo.

## Permissões aplicadas

| Perfil | Consultar painel/dados pessoais | Cadastrar/editar/excluir conteúdo | Gerenciar usuários | Exportar/baixar currículos |
|---|---|---|---|---|
| Visitante, sessão antiga/expirada ou conta inativa | Não | Não | Não | Não |
| Níveis legados 1 e 2 | Não | Não | Não | Não |
| Administrador ativo, nível 3 | Sim | Sim, com CSRF | Sim, com CSRF | Sim |

Foi adotada a restrição ao nível 3 já usado nos módulos administrativos. Não existe no esquema disponível uma matriz de permissões individuais por departamento. Níveis 1/2 não são elevados automaticamente. Caso sejam necessários perfis RH, editor ou consulta, será preciso especificar e implementar essa matriz em uma etapa própria. Administradores não podem excluir ou desativar a própria conta. Campos como nível, criador, senha e versão de sessão não são aceitos indiscriminadamente pelo cadastro. Listagens nunca retornam hashes de senha.

Todos os nove webservices, cinco relatórios, painel, seletor de mídia, imagens de perfil e download de currículo exigem autenticação. A negação encerra a execução. Templates e includes administrativos não podem ser abertos diretamente. Operações POST exigem token CSRF por cabeçalho ou campo oculto; o logout também usa POST. Consultas GET e exportações não modificam registros.

## Preparação e publicação

1. Faça backup verificável do banco e dos arquivos. Em homologação, confirme os nomes/tipos das colunas reais. Execute `deploy/security.sql` **uma única vez**, antes de publicar o código. Ajuste `scl_` se necessário. A alteração do mecanismo da tabela pode exigir janela de manutenção em tabelas grandes. A migração amplia `senha`, adiciona a versão de segurança e cria as tabelas de tokens e limites de tentativas; não redefine senhas existentes.
2. Configure as variáveis de `deploy/environment.example` no serviço PHP/servidor ou gerenciador de segredos. O exemplo não é carregado automaticamente. Use o endereço HTTPS definitivo em `SCL_HOME`; o código não confia no cabeçalho Host para montar links. `SCL_PRIVATE_DIR` deve apontar para um diretório fora do document root e ser gravável apenas pelo serviço e operadores autorizados. Configure SMTP com TLS e autenticação se necessário, ou valide o transporte local de e-mail.
3. Habilite PDO MySQL, Fileinfo, GD, mbstring, OpenSSL, DOM e iconv. O código requer PHP 8.4+; os testes locais foram executados em PHP 8.4.25. Use uma versão com suporte na hospedagem. Desative `display_errors`, mantenha logs fora da pasta pública e limite `upload_max_filesize`/`post_max_size` conforme os limites da aplicação. Não configure permissões 0777.
4. Rode `php deploy/preflight.php` no ambiente preparado. Ele só consulta o esquema. Não imprime credenciais. Confira também HTTPS, cookies Secure/HttpOnly/SameSite e redirecionamento HTTP→HTTPS no proxy/servidor.
5. Preserve os currículos antigos: configure `SCL_PRIVATE_DIR` e execute `php scripts/migrate-resumes.php` para conferir os totais; use `php scripts/migrate-resumes.php --copy` para copiar. O script verifica SHA-256, não sobrescreve arquivos divergentes e não apaga originais. As pastas ano/mês são preservadas e o banco não precisa ter seus caminhos alterados. O download passa a procurar primeiro a cópia privada e, enquanto disponível, o caminho antigo protegido. Mantenha o backup e a retenção definidos pela instituição.
6. Gere uma pasta nova com `node scripts/package-release.cjs CAMINHO_FORA_DO_PROJETO`. Sem argumento, o comando apenas valida a seleção. O pacote exclui currículos, segredos locais, logs, testes, documentação, cópias de configuração, PHPMailer legado, KCFinder, exemplos PHP e diretórios antigos. Publique em um document root limpo, sem sobrepor uma pasta contendo arquivos antigos expostos. Preserve os arquivos públicos atuais e migre os currículos antes da troca.
7. Apache 2.4: habilite `mod_rewrite`, `AllowOverride` compatível com as regras fornecidas e teste os retornos 403. Nginx/IIS não leem `.htaccess`: aplique regras equivalentes **antes** de publicar. Bloqueie HTTP para `_app`, `tests`, `scripts`, `deploy`, `admin/system`, diretórios de backup, `arquivos/curriculuns`, arquivos ocultos/logs/configuração e PHP em `resources`. Permita em `includes` apenas o endpoint público `servicos/galeria.php`. Nunca execute PHP/CGI ou arquivos ativos enviados em `arquivos`.
8. Invalide sessões antigas, homologue login/migração MD5, criação/edição/exclusão de uma conta de teste, bloqueio dos níveis 1/2, logout, expiração, CSRF inválido, relatório, download e upload com dados fictícios. Teste um link real de recuperação, expiração e reutilização. O endereço direto de um currículo deve ser negado, mesmo conhecendo o nome completo. Confirme acesso pelo download autenticado. Faça essas verificações com o banco MySQL/MariaDB real de homologação.
9. Troque no provedor as credenciais que constavam dos arquivos legados e remova cópias antigas da hospedagem. Remover o texto do código atual não revoga segredos já presentes no histórico Git ou em backups. Nenhuma rotação foi realizada automaticamente.

## Senhas, sessões e recuperação

- Novos cadastros/redefinições usam `password_hash(PASSWORD_DEFAULT)` e `password_verify`. Senhas novas exigem entre 12 e 72 bytes; a interface informa os limites. MD5 existe apenas na verificação de compatibilidade. Um login válido atualiza o hash com comparação do valor anterior para evitar sobrescrever uma redefinição concorrente. Senhas antigas mais longas que o limite do algoritmo precisam ser redefinidas.
- Sessão expira após 30 minutos sem atividade administrativa ou 8 horas desde o login. O identificador muda no login/logout. Cada acesso consulta a conta atual; bloqueio, exclusão, alteração de senha ou incremento da versão invalidam as sessões existentes. Edição administrativa de usuário também revoga tokens de recuperação. A edição do próprio perfil exige novo login.
- Limites persistem no banco, inclusive após limpar cookies: login, 30 tentativas por IP e 10 por usuário a cada janela de 15 minutos; recuperação, 10 por IP e 3 por e-mail; consumo do token, 10 por IP. Os identificadores dos limites são resumos SHA-256. Não se confia em `X-Forwarded-For` fornecido pelo cliente; com proxy, configure corretamente o endereço real no servidor.
- Recuperação usa token aleatório de 256 bits, apenas o resumo no banco, validade de 30 minutos, consumo transacional e uso único. Uma nova solicitação substitui o token anterior. A resposta pública não confirma a existência da conta e nenhuma senha é enviada por e-mail. Solicitar recuperação não altera a senha atual.

## Uploads e acervo

Currículos novos são PDFs de até 5 MB, em armazenamento privado, com nome aleatório. Currículos antigos são servidos apenas após autenticação, resolução de caminho dentro das pastas autorizadas e verificação de MIME. O download usa nome neutro, `attachment`, `nosniff`, `sandbox` e `no-store`.

Uploads administrativos validam arquivo realmente recebido, tamanho, extensão e MIME de servidor. Imagens JPG/PNG são reprocessadas com GD, limitadas a 16 milhões de pixels e nomes aleatórios; documentos aceitam PDF. O seletor `admin/media.php` substitui os caminhos do KCFinder e oferece upload protegido e seleção de imagens/PDFs. TimThumb foi substituído por leitura restrita de imagens locais, sem URLs remotas. Arquivos antigos não são apagados quando uma imagem é substituída.

Auditoria local de 23/09/2026: 5.601 PDFs no diretório de arquivos, 5.565 currículos em subpastas; nenhuma extensão executável encontrada ali. Foram encontrados 23 arquivos vazios e 19 PDFs sem assinatura `%PDF-` nos primeiros 1.024 bytes. Nenhum foi apagado. O download privado recusa conteúdo que Fileinfo não reconhece como PDF. A inspeção de extensão/assinatura não é antivírus; o acervo ainda deve passar pelo scanner da instituição e pela revisão de retenção. `node scripts/audit-uploads.cjs` produz apenas totais, sem expor nomes/conteúdo dos currículos.

## Evidência e limites dos testes

- Suíte pública: `php tests/community.php`, 733 verificações.
- Unidade de segurança: `php -d extension=pdo_sqlite -d extension=fileinfo tests/security.php`, 58 verificações.
- HTTP: `node tests/security-http.cjs CAMINHO_PHP`, 92 verificações; requer PDO SQLite, Fileinfo e GD na pasta `ext` do runtime de teste.
- Os testes usam SQLite temporário com adaptação da sintaxe MySQL, sessões e dados fictícios. Validam bloqueio antes de operar, CSRF, não exposição de hashes, upload forjado, caminho inválido, migração de hash, expiração, revogação, token único e rollback. Não substituem o teste de concorrência/DDL em MySQL/MariaDB, entrega SMTP real ou aplicação das regras Apache/Nginx/IIS.
- A etapa 3 atualizou as dependências administrativas; consultar TECHNOLOGY.md para versões, pacote limpo e configuração externa na K2Host. A homologação local não certifica ausência de vulnerabilidades nem substitui os testes no servidor.
