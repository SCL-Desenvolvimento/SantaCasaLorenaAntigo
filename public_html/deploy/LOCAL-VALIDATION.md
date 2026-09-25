# Validação com o banco local — 25/09/2026

A navegação e os fluxos abaixo foram verificados com o MySQL local 8.0.44 e PHP 8.4.25. A K2Host não foi acessada nem atualizada.

## Correções aplicadas

- URLs amigáveis agora são resolvidas também pelo caminho da requisição, sem depender do rewrite do Apache. A causa da repetição da página inicial era iniciar o servidor PHP embutido sem rewrite.
- Router de desenvolvimento com bloqueio de diretórios internos, arquivos privados e caminhos inválidos. Para iniciar: **npm run serve**; endereço http://127.0.0.1:8000.
- Migrações de autenticação e galerias aplicadas ao banco local, com backups SQL prévios fora de public_html, na pasta ../backups (ignorada pelo Git).
- Colunas de nomes, e-mails, cidades, galerias e mensagens ampliadas para os limites que os formulários aceitam. Mensagens maiores que 255 caracteres antes falhavam no MySQL.
- Relatório de doações corrigido para a chave id_doacao, em vez de id_doacoes.
- Consultas sem registros agora retornam JSON válido; galerias vazias deixam de interromper o carregamento dos formulários.
- Histórico de páginas sem chave autoincremento deixa de ser tratado como erro de gravação; categorias de notícias preenchem a descrição obrigatória; registros de conteúdo recebem o usuário autor; edição de balanços deixa de tentar gravar uma coluna inexistente.
- Desativação de usuário interpreta corretamente status=0. Limites de caracteres foram alinhados com o banco.
- Blocos antigos extensos permanecem como texto completo, com título curto de seção, em dez páginas públicas. Nenhum texto foi cortado ou regravado no banco.
- O comando local e .user.ini configuram 10 MB por arquivo, 64 MB por requisição, até 40 arquivos e 256 MB de memória. O PHP local estava limitado a 2 MB. Na hospedagem, confira se PHP-FPM/CGI carrega .user.ini ou configure os mesmos valores no painel.

## Evidência de verificação

- 20 páginas públicas, incluindo notícia individual, e 182 recursos locais verificados, sem arquivos ausentes.
- 19 rotas administrativas renderizadas com sessão autenticada e MySQL.
- 138 verificações HTTP de autenticação, consultas, relatórios, gravação dos quatro grupos de páginas, cadastros/edições/exclusões, upload de JPG/PNG/PDF, imagens de internação/capacidade, galerias, categorias, usuários e leitura pública das legendas. Inclui uma imagem maior que 2 MB para conferir o limite de upload corrigido.
- 8 verificações de persistência pública com mensagens longas e MySQL. Nesses testes, CAPTCHA e e-mail usam funções de teste injetadas; não houve consulta ao Google nem envio de mensagens externas.
- Regressões offline: 733 verificações públicas, 58 de segurança, 11 de configuração, 98 HTTP de segurança, 56 de telas/recursos, 97 de operações SQLite, 13 de rotas/conteúdo e 12 do editor.
- Os testes destrutivos usaram cópias temporárias de 50 tabelas, com prefixo próprio, dentro do banco local. Essas tabelas, o usuário temporário e os arquivos de teste foram removidos ao terminar. As contas reais e os conteúdos originais não foram usados nos testes de alteração/exclusão.

## O que ainda depende de configuração

A configuração local não contém SCL_RECAPTCHA_SECRET nem SCL_SMTP_HOST. Por isso **o envio real protegido por CAPTCHA e a entrega de e-mails/recuperação de senha ainda não foram homologados**. É necessário configurar o CAPTCHA para os domínios usados e o transporte de e-mail autorizado. Nenhuma proteção foi desativada para contornar essa pendência.

## Atualização na K2Host

Faça backup e aplique as migrações pendentes na ordem: security.sql, admin-stage4.sql e admin-compatibility.sql. Não repita ADD COLUMN de migrações que já foram aplicadas. Ajuste o prefixo conforme a configuração. Execute o preflight e confirme SMTP, CAPTCHA, HTTPS, bloqueios HTTP, limites de upload e armazenamento privado. O script scripts/migrate-local.php é restrito a banco em loopback e não deve ser usado contra produção.

O pacote de 25/09 substitui o pacote anterior. Publique somente seu diretório public_html; mantenha a configuração, backups, instruções e currículos privados fora da raiz pública. Preserve uploads mais recentes que existam apenas na hospedagem.
