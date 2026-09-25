## Verificação com banco local — 25/09/2026

Navegação, compatibilidade do esquema MySQL, gravação administrativa e apresentação dos conteúdos reais corrigidas. Migrações aplicadas **somente ao banco local**, com backup. Veja [validação e pendências de configuração](LOCAL-VALIDATION.md). O envio real por CAPTCHA/SMTP ainda depende das configurações ausentes.

# Painel administrativo — etapa 4

Atualizado em 24/09/2026. Implementado no projeto; não publicado na K2Host.

## Telas entregues

| Grupo | Alterações |
|---|---|
| Acesso | Login responsivo, recuperação integrada, perfil e saída protegida; navegação por áreas, menu móvel e atalho para conteúdo. |
| Inicial | Visão geral com contadores do banco, atalhos de publicação e nove áreas de trabalho. |
| Usuários | Listagem com busca/filtro, cadastro, edição e meu perfil; validação, feedback e confirmação de exclusão. |
| Banners | Listagem, imagem com prévia, cadastro, edição e controle de publicação. |
| Notícias | Listagem, cadastro, edição, resumo, endereço, categorias com criação, editor visual e publicação. |
| Galerias | Listagem, cadastro, edição, envio múltiplo, legendas por galeria, botões de ordenação e remoção de vínculos. |
| Institucional | Sobre, Humanização, Ações sociais, Segurança do paciente e Transparência na estrutura unificada de formulários e modais. |
| Instalações | Pronto atendimento, Hotelaria, Clínica Emília, Diagnóstico e Internação. |
| Serviços | Convênios, Especialidades, Capacidade e Manual. |
| Atendimento | Central para contatos históricos, Ouvidoria, Currículos, Doações e Pesquisa; textos e Localização em edição própria. |
| Relatórios | Cinco canais, período, busca, paginação e CSV; detalhes legíveis e acesso autenticado aos currículos. |

Os destinos externos de Trabalhe conosco e Pesquisa permanecem os configurados na etapa 1. A central mostra os registros históricos existentes no banco local; não importa respostas de serviços externos. O acervo do Portal da Transparência continua organizado nas pastas existentes; a edição do painel mantém os conteúdos que já eram gerenciáveis. Não foi criado um importador de acervo nem de formulários externos.

## Publicação e migração obrigatória

1. Faça backup completo do banco e dos arquivos, incluindo uploads e armazenamento privado.
2. Em uma cópia de homologação do banco MySQL/MariaDB, aplique primeiro deploy/security.sql se ainda não foi aplicado.
3. Aplique **deploy/admin-stage4.sql uma única vez**, ajustando o prefixo scl_ se necessário. Ele acrescenta ordem e legenda em galeria_anexo e converte as tabelas envolvidas nas transações para InnoDB. Confira as tabelas existentes antes de executar; a conversão pode bloquear tabelas e deve ocorrer em janela de manutenção. O script não apaga registros ou imagens.
4. As novas galerias públicas também consultam essas colunas. **Não publique os novos arquivos antes da migração.** Se as colunas já existem, não repita os comandos ADD COLUMN.
5. Execute php deploy/preflight.php a partir desta cópia do projeto com a configuração da homologação. Confere extensões, segredos obrigatórios, campos e motores transacionais.
6. Publique o pacote gerado em pasta pública limpa, com a configuração privada e os currículos conforme SECURITY.md e TECHNOLOGY.md. Preserve todos os uploads atuais da hospedagem; a cópia local pode não conter arquivos enviados depois da sua extração.
7. Valide na hospedagem: login/recuperação via SMTP, novo/editar/excluir em cada cadastro, upload JPG/PNG/PDF, categorias, texto rico, ordem/legendas públicas, cinco relatórios e downloads privados. Valide CAPTCHA e HTTPS no domínio definitivo.

Para reverter uma publicação, restaure a versão dos arquivos e o backup compatível do banco. Nenhuma migração foi executada no banco real por esta etapa.

## Comportamentos relevantes

- Remover foto de uma galeria remove somente o vínculo ao salvar. Excluir galeria preserva os arquivos de imagem e os anexos, pois podem estar em uso em outro conteúdo. Faça limpeza de arquivos somente após auditoria.
- As legendas novas pertencem ao vínculo da galeria. Legendas antigas do anexo continuam como alternativa até serem editadas. A ordem inicial usa o identificador da foto como desempate.
- Os endereços antigos dos relatórios continuam protegidos e retornam CSV UTF-8 com BOM e separador ponto e vírgula. A exportação anterior era HTML apresentado como XLS. Valores que poderiam executar fórmulas são neutralizados.
- O editor mantém os marcadores de galerias existentes; os campos são sincronizados antes do envio. O painel avisa ao sair com alterações pendentes.
- Os testes de gravação usam SQLite isolado. Eles não substituem a homologação do esquema MySQL/MariaDB real, SMTP ou limites de upload da K2Host.

## Verificação local

Executáveis PHP 8.5.11 e 8.4.25; as suítes estão em tests/. Principais comandos:

- php tests/community.php
- php tests/security.php
- php tests/technology.php
- node tests/security-http.cjs CAMINHO_PHP
- node tests/admin-http.cjs CAMINHO_PHP
- node tests/admin-operations.cjs CAMINHO_PHP
- node tests/security-lint.cjs CAMINHO_PHP
- node tests/editor.cjs

A suíte de operações cobre CRUD de banners/notícias, categorias, galerias, ordem, legendas isoladas, rejeição de referências inválidas e filtros/exportação dos cinco canais, sem dados de produção ou envio de e-mail.

Resultados: 1.053 verificações em cada runtime (733 públicas, 58 de segurança, 11 de configuração, 98 HTTP de segurança, 56 de telas/recursos e 97 de operações), mais 12 do ciclo de vida do editor. Análise sintática de 161 PHP e 22 JavaScript. Conferência visual de login, editor de notícia, páginas institucionais, galerias e detalhes de atendimento; layout móvel conferido em 390 px.
