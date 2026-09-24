# Base tecnológica e publicação na K2Host

Atualizado em 23/09/2026. Alterações aplicadas e testadas localmente; nenhuma publicação ou conexão ao banco da hospedagem foi realizada.

A etapa 4 acrescenta uma migração obrigatória de galerias e tabelas transacionais. Leia também [ADMIN.md](ADMIN.md) antes de publicar a versão atual.

## Versões e decisões

| Componente | Estado preparado |
|---|---|
| PHP | Mínimo 8.4; testes locais em 8.4.25 e 8.5.11. Preferir 8.5 com o patch disponível no provedor após homologação. |
| Banco e servidor web | Versões reais da K2Host ainda não verificadas. A aplicação usa PDO MySQL; os testes isolados usam SQLite. |
| PHPMailer | 7.1.1, instalação Composer com lockfile e autoload único para formulários públicos e recuperação administrativa. |
| Bootstrap | 5.3.8; painel próprio substitui o AdminLTE 2. |
| jQuery | 4.0.0, usado somente no administrativo pelos controladores existentes. Site público sem dependência de jQuery. |
| DataTables | 3.1.1 com integração Bootstrap 5 e mensagens em português. |
| Seletores | Tom Select 2.6.2 substitui Select2. |
| Editor | TinyMCE 8.9.2 local, tradução pt-BR 26.8.2; sem dependência de conta/API de editor na nuvem. |
| Arquivos e galerias | Seletor autenticado com CSRF; integração por mensagem de mesma origem; catálogo autenticado de galerias. Mantém os marcadores de galerias já gravados. |
| Diálogos e checkboxes | Dialog nativo e Bootstrap 5; controles nativos substituem Bootbox e iCheck. |
| Calendários antigos | Não há chamadas ativas nas telas atuais. Datepicker, daterangepicker, FullCalendar e demais plugins antigos ficam fora da publicação. Nenhum calendário adicional é necessário para os campos atuais. |
| TimThumb | Permanece o leitor restrito de imagens locais implantado na etapa 2, sem execução do TimThumb antigo. |
| Slim | Não foram encontradas chamadas, inicialização ou controladores ativos. Retirada a dependência `slim/slim: 2.*` do Composer. |

As versões estão fixadas em `package-lock.json`, `_app/composer.lock` e `resources/vendor/versions.json`. O TinyMCE é distribuído no modo GPL local; as licenças e avisos dos fornecedores acompanham os arquivos. O Composer alerta sobre versão exata e ausência de licença declarada do projeto: a fixação é intencional, e não foi atribuída uma licença nova ao código da instituição.

Referências: [PHP suportado](https://www.php.net/supported-versions.php), [migração PHP 8.5](https://www.php.net/manual/en/migration85.php), [Bootstrap](https://getbootstrap.com/docs/5.3/getting-started/introduction/), [PHPMailer](https://github.com/PHPMailer/PHPMailer/releases), [TinyMCE local e licença](https://www.tiny.cloud/docs/tinymce/latest/license-key/).

## Obter as versões reais do servidor

Após instalar em homologação, entrar no painel e abrir **Diagnóstico do servidor**. O endpoint `admin/diagnostico.php` exige administrador ativo e informa PHP/SAPI, servidor web, driver/versão do banco, extensões e limites. Não expõe credenciais nem executa `phpinfo()`.

Com terminal, executar `php deploy/inventory.php` e, com configuração do banco, `php deploy/inventory.php --database`. O resultado CLI não confirma a versão utilizada pelo site: consultar também o diagnóstico autenticado pela web. Os utilitários em `deploy` são de manutenção, não estão no pacote público e têm acesso HTTP negado.

Extensões necessárias: PDO MySQL, Fileinfo, GD, Mbstring, OpenSSL, DOM e Iconv. Confirmar HTTPS, regras de reescrita e permissões de gravação; não assumir que o plano da K2Host oferece uma versão específica.

## Configuração com as credenciais existentes

Há duas alternativas. Variáveis do servidor têm precedência:

1. Configurar as variáveis indicadas em `deploy/environment.example` no ambiente do PHP.
2. Se o painel não permitir variáveis, copiar `deploy/scl-config.example.php` para **`scl-config.php`, um nível acima de `public_html`**, preencher privadamente os valores já utilizados e limitar a leitura ao usuário do PHP. O site carrega somente as chaves permitidas desse arquivo. Não colocar segredos dentro da pasta publicada.

Estrutura esperada na hospedagem:

```text
pasta-da-conta/
  scl-config.php              # credenciais, fora da web
  santa-casa-private/          # currículos, fora da web
  public_html/                # conteúdo do pacote limpo
```

Se o provedor restringir `open_basedir`, solicitar permissão para esses dois caminhos externos. Definir `SCL_HOME` com o endereço HTTPS completo, incluindo subpasta se houver. As credenciais não foram alteradas nem reintroduzidas no código.

## Gerar e copiar o pacote

Os arquivos de produção das dependências já estão incluídos. Não é necessário instalar Node ou Composer na K2Host. Para reconstruir na máquina de desenvolvimento:

```powershell
npm.cmd ci --ignore-scripts
npm.cmd run vendor
php composer.phar install --working-dir=_app --no-dev --no-interaction --no-scripts --prefer-dist
node scripts/package-release.cjs CAMINHO_NOVO_FORA_DO_PROJETO
```

O último comando também funciona sem argumento para somente conferir a seleção. O destino deve ser uma pasta nova. Copiar **o conteúdo desse pacote** para um diretório público limpo, preservando os arquivos ocultos, especialmente `.htaccess`. Não enviar `node_modules`, testes, cópias antigas, o repositório inteiro ou credenciais.

As bibliotecas antigas continuam na cópia de trabalho para referência, mas não são carregadas e são excluídas do pacote: `resources/plugins`, Bootstrap 3, AdminLTE 2, scripts públicos abandonados, PHPMailer antigo e a cópia manual duplicada do mailer. A exclusão física em lote da cópia de trabalho foi bloqueada pela revisão automática; nenhum arquivo foi apagado por uma alternativa.

Antes da troca, executar a migração de banco e a cópia verificada de currículos descritas em [SECURITY.md](SECURITY.md). O pacote não contém currículos. Publicar somente PHP novo não substitui a migração do esquema nem a configuração de armazenamento privado. Preservar backup do banco, dos arquivos públicos e dos currículos; não sobrepor a hospedagem antiga com arquivos legados expostos.

## Validação realizada e conclusão restante

- Em cada runtime PHP 8.4.25 e 8.5.11: 733 verificações públicas, 58 de segurança, 92 por HTTP, 11 de configuração/diagnóstico e 51 de renderização/recursos cobrindo as 17 telas do painel. Sem conexão ao banco real e sem envio de e-mail.
- 12 verificações JavaScript do ciclo do editor: conteúdo original sem alteração, sincronização de edições, inicialização assíncrona, destruição e fallback.
- Conferência no navegador com dados fictícios: criação/edição de notícias, tabela, categorias, editor em português, marcador de galeria, abas, modal de manual, cancelamento de exclusão, Ouvidoria e menu móvel.
- Auditorias npm e Composer sem vulnerabilidades conhecidas reportadas na data da execução. Isso não equivale a auditoria completa do código.

Na K2Host, ainda é necessário validar as versões reais, a migração SQL com MySQL/MariaDB, gravação/edição com dados de homologação, upload e download privados, reescrita/bloqueios HTTP, SMTP, recuperação de senha e CAPTCHA com o domínio definitivo. Somente depois desses testes a homologação da hospedagem pode ser considerada concluída.
