## Verificação com banco local — 25/09/2026

Navegação, compatibilidade do esquema MySQL, gravação administrativa e apresentação dos conteúdos reais corrigidas. Migrações aplicadas **somente ao banco local**, com backup. Veja [validação e pendências de configuração](deploy/LOCAL-VALIDATION.md). O envio real por CAPTCHA/SMTP ainda depende das configurações ausentes.

# Atualização — painel administrativo (24/09/2026)

A etapa 4 foi aplicada ao código: acesso e navegação responsivos, página inicial com indicadores, formulários de usuários/banners/notícias, galerias com uploads/legendas/ordenação, edição unificada das páginas e central de atendimento com cinco relatórios CSV. As correções incluem carregamento e sincronização do editor, seleção inicial de abas, prévias e validação dos formulários.

**Antes de publicar:** aplicar a migração [deploy/admin-stage4.sql](deploy/admin-stage4.sql) e seguir [deploy/ADMIN.md](deploy/ADMIN.md). Nenhum banco real, serviço de e-mail ou hospedagem foi alterado. O roteiro contém a cobertura de telas, diferenças dos relatórios e a homologação necessária na K2Host.

Validação local: **1.053 verificações em PHP 8.4.25 e outras 1.053 em PHP 8.5.11**, mais 12 do editor. A suíte de operações usa banco sintético e os testes públicos conferem os documentos locais.

Também foi corrigida a seleção de pastas vazias duplicadas que ocultava documentos no Portal da Transparência. Os 211 documentos locais voltaram a ser encontrados pela verificação de integridade.

# Relatório de modernização — inventário e pendências

## Atualização de execução — etapa 3 aplicada ao código em 23/09/2026

A base foi atualizada para PHP 8.4+ e validada localmente em PHP 8.4.25 e 8.5.11. **A versão de PHP, banco e servidor da K2Host ainda precisa ser medida na hospedagem. Nenhum deploy foi realizado.** Esta atualização prevalece sobre os itens históricos de tecnologias abaixo.

| Área | Resultado |
|---|---|
| Painel | Bootstrap 5.3.8, estrutura própria sem AdminLTE 2, jQuery 4.0.0, DataTables 3.1.1 e Tom Select 2.6.2. Modais/abas adaptados; diálogos e checkboxes modernizados. |
| Editor | TinyMCE 8.9.2 local em português substitui CKEditor; integração de imagens/PDFs autenticada e catálogo de galerias preservando os marcadores existentes. |
| E-mail | PHPMailer 7.1.1 centralizado com Composer e lockfile. Fluxos públicos e administrativos usam o mesmo carregamento. |
| PHP | Corrigidas chamadas depreciadas de imagens e do adaptador SQLite de testes; suíte pública e de segurança aprovada nas duas versões. |
| Dependências | npm/Composer com versões fixadas, arquivos locais e licenças incluídos. Slim removido do manifesto por ausência de uso. Bibliotecas antigas sem chamadas e calendários abandonados excluídos do pacote. |
| K2Host | Diagnóstico autenticado de PHP/banco/servidor/extensões; alternativa de configuração externa a public_html com as credenciais existentes; pacote limpo sem currículos ou ferramentas antigas. |

Validação: **945 verificações em cada versão de PHP** (733 públicas + 58 segurança + 92 HTTP + 11 configuração/diagnóstico + 51 painel/recursos), mais **12 verificações do editor** em JavaScript e conferência visual dos principais componentes com dados fictícios. Nenhum e-mail real ou dado de produção foi alterado. As bibliotecas antigas permanecem na cópia de trabalho, sem carregamento, e não entram no pacote; sua remoção física em lote foi bloqueada pela revisão automática.

Verificação sintática: 153 arquivos PHP e 18 JavaScript aprovados. Pacote gerado com 1.460 arquivos, dependências modernas e licenças; nenhum currículo incluído.

**Para concluir na hospedagem:** seguir [deploy/TECHNOLOGY.md](deploy/TECHNOLOGY.md) e [deploy/SECURITY.md](deploy/SECURITY.md), confirmar runtime/extensões, aplicar migração do banco, configurar segredos e armazenamento privado, publicar o pacote em pasta limpa e homologar MySQL/MariaDB, gravações, SMTP, CAPTCHA e regras HTTP. Copiar arquivos não atualiza o PHP nem o servidor da K2Host.

## Atualização de execução — etapa 2 aplicada ao código em 23/09/2026

Foram aplicadas as correções de autenticação, acesso administrativo e proteção dos dados. **A ativação na hospedagem depende da migração do banco e da configuração do servidor; não foi realizado deploy.** Para os itens desta etapa, esta atualização prevalece sobre os achados históricos abaixo.

| Item | Resultado no código |
|---|---|
| Autenticação | Bloqueio com encerramento em todos os nove webservices, cinco relatórios, painel e downloads administrativos. Templates não podem ser chamados diretamente. |
| Permissões | Administrador ativo de nível 3 pode consultar, cadastrar, editar, excluir e exportar. Níveis legados 1/2, inativos, sessões antigas e expiradas não acessam o administrativo. Não há elevação automática de nível; a própria conta não pode ser excluída/desativada pelo operador. |
| CSRF | Token por sessão para POST administrativo, integração com AJAX/formulários e logout somente por POST. |
| Senhas | Novas senhas usam password_hash/password_verify. MD5 permanece apenas como leitura de compatibilidade e é migrado após login válido. Nenhum hash é retornado na API de usuários. |
| Recuperação | Token aleatório, resumo no banco, validade de 30 minutos, consumo transacional e uso único. Não altera a senha ao solicitar e não envia senhas por e-mail. |
| Sessões e tentativas | Regeneração do identificador, cookie protegido, 30 minutos de inatividade/8 horas de duração máxima, consulta de status/versão a cada acesso e limites persistentes por conta/IP. |
| Currículos | Novos arquivos fora da pasta pública; antigos acessíveis por download autenticado, preservando subpastas ano/mês. Script de cópia verificada para armazenamento privado, sem apagar originais. |
| Uploads | Validação de tamanho, extensão e MIME real, nomes aleatórios, reprocessamento de imagens e bloqueio de arquivos ativos. Seletor protegido substitui KCFinder; leitura local de imagens substitui TimThumb. |
| Configuração e pacote | Credenciais de execução removidas dos arquivos; variáveis de ambiente; erros internos não expostos. Gerador de pacote exclui currículos, logs, testes, backups e ferramentas legadas. |
| Proteção complementar | Lista de rotas permitidas no painel, validação de identificadores/datas, escape dos dados públicos exibidos no administrativo e proteção de células exportadas contra fórmulas. |

Validação local: **733 verificações da suíte pública, 58 de segurança e 86 por HTTP** aprovadas; 146 arquivos PHP e quatro arquivos JavaScript passaram pela verificação sintática. A seleção de publicação contém 2.232 arquivos e nenhum currículo. Login e abertura da recuperação foram conferidos no navegador. Os testes de segurança usam banco SQLite e dados sintéticos; não substituem homologação com MySQL/MariaDB, SMTP e regras do servidor real. Nenhum e-mail real foi enviado e nenhum usuário real foi modificado.

Auditoria do acervo local: 5.601 PDFs, dos quais 5.565 currículos; nenhuma extensão executável em `arquivos`. Foram encontrados 23 arquivos vazios e 19 PDFs sem assinatura inicial reconhecida; nenhum foi apagado. A varredura não é antivírus. Continuam pendentes a análise pelo scanner da instituição e a revisão de retenção dos documentos.

**Para ativar:** seguir [deploy/SECURITY.md](deploy/SECURITY.md), executar a migração SQL em homologação, configurar segredos/HTTPS/armazenamento/SMTP, copiar os currículos antigos e validar os bloqueios HTTP antes de publicar em uma pasta limpa. Credenciais antigas precisam ser rotacionadas no provedor; a remoção no código não limpa histórico/backups. Perfis separados por departamento ainda exigem uma matriz de permissões aprovada e implementação própria. Bibliotecas antigas do restante do painel continuam na lista de modernização.

## Atualização de execução — etapa 1 aplicada em 22/09/2026

As correções da etapa 1 foram autorizadas e aplicadas no código. O inventário posterior registra os achados anteriores; para esses itens, prevalece o estado desta atualização.

| Item | Resultado aplicado | Validação restante |
|---|---|---|
| CAPTCHA | Script carregado fora de $needsLegacy, uma única vez no contato/doações; a prévia continua sem integrações externas. | Chaves/domínio e verificação real na hospedagem. |
| Ouvidoria | Entrada antiga redireciona diretamente ao canal contato. | Testar no domínio definitivo. |
| Trabalhe conosco / Pesquisa | Redirecionamentos HTTP diretos, aliases com hífen/underscore e exit; seleção não depende de flags da sessão. | Homologar os recebimentos reais. |
| Localização | Entrada antiga aponta para #localizacao; seção recebe foco e respeita o cabeçalho fixo. | Validar endereço e mapa reais. |
| Menu / rodapé | Atalhos diretos dos quatro destinos, indicação do canal ativo e fechamento do menu móvel ao navegar. | Revisão editorial de contatos. |
| Ouvidoria — dados | Formulário, listagem e relatório usam a definição compartilhada de tabela ouvidoria; contatos históricos permanecem separados. | Confirmar esquema e dados no banco da hospedagem. |
| Ouvidoria — relatório | Filtros usam data_cadastro com parâmetros vinculados, datas válidas, período ordenado e inclusão do dia inteiro. | Conferir exportação com o acervo real. |

A listagem da Ouvidoria passa a inserir nome/e-mail como texto, trata lista vazia e falha de carregamento. O relatório escapa os campos de texto. O endpoint de contato e o relatório de Ouvidoria encerram a execução quando a autenticação falha. Não foram habilitadas novas ações de exclusão nem alterados registros do banco. A revisão abrangente de segurança do administrativo permanece na etapa 2.

Validação: 733 verificações automatizadas aprovadas em PHP 8.4.25; inclui 42 verificações de canais, carga do CAPTCHA fora da prévia, links em subdiretório, independência da sessão e filtros de relatório. Seis entradas/aliases testados por HTTP retornaram 302 com os destinos corretos. No navegador, foram conferidos os quatro destinos, seleção do canal, foco na localização, menu móvel e ausência de rolagem horizontal a 390 px. Nenhum e-mail real enviado, nenhum CAPTCHA resolvido e nenhum deploy realizado. Os testes de conteúdo/query usam dados isolados; não substituem a homologação integrada com o banco.

## Situação consolidada do inventário — antes da execução da etapa 1

**O projeto ainda não está 100% modernizado.** A maior parte da interface pública foi renovada, mas há falhas funcionais confirmadas, acessos antigos incompletos, administrativo legado e homologação de infraestrutura pendente. Interface modernizada não significa fluxo aprovado em produção.

O inventário foi inicialmente produzido sem alterar código. Posteriormente, o usuário autorizou a etapa 1, cujos resultados constam acima. As demais etapas continuam pendentes; não houve atualização da hospedagem ou publicação.

Escopo: 24 templates diretamente em `includes/paginas`, a página inicial, componentes compartilhados, 17 templates de conteúdo administrativo em `admin/system`, login/recuperação, cinco relatórios administrativos e bibliotecas identificadas no código. As abas e operações desses templates estão discriminadas abaixo. Documentos de transparência são acervo, não telas adicionais. Sistemas externos não fazem parte da aplicação auditada.

Método: inspeção estática de código, manifestos e versões declaradas; consulta a fontes oficiais para suporte e referências atuais. Não houve acesso ao banco, login no painel, inspeção de produção ou teste ofensivo. Os 689 testes e três cenários de upload da etapa anterior não foram repetidos nesta revisão documental e não cobrem todos os problemas agora encontrados.

### Pendências confirmadas nos canais de contato

| Item | Situação comprovada | Ajuste proposto, não aplicado |
|---|---|---|
| Ouvidoria | `includes/paginas/ouvidoria.php` está vazio. O formulário modernizado está no contato, como canal `contato`. | Encaminhar o acesso antigo diretamente a `/fale-conosco?canal=contato#formulario`, preservando aliases. |
| Trabalhe conosco | `trabalhe_conosco.php` inicia sessão, define `tb` e redireciona; a configuração geral já inicia sessão. | Usar redirecionamento direto ao canal `trabalhe_conosco`, com encerramento da execução, e padronizar links. |
| Pesquisa de atendimento | `pesquisa_atendimento.php` repete o mecanismo por sessão com `pa`. | Abrir diretamente o canal `pesquisa`, sem depender de sessão ou de JavaScript para selecionar o formulário. |
| Localização | `localizacao.php` está vazio; endereço e mapa estão no contato, sem destino específico da seção para os links antigos. | Criar identificador para a seção e direcionar os acessos antigos e links de localização até ela. |
| CAPTCHA — bloqueador | Em `includes/footer.php:34–43`, o script do reCAPTCHA fica dentro de `if ($needsLegacy)`. Contato e doações definem essa variável como falsa. | Desvincular o CAPTCHA das bibliotecas antigas; testar sua renderização no modo normal e homologar o widget real. |
| Ouvidoria no administrativo | `includes/community_forms.php` grava em `ouvidoria`; `listOuvidoria` em `admin/webservices/paginas/fale-conosco/servico.php` consulta `contato`; `admin/relatorio-ouvidoria.php` consulta `ouvidoria`. | Alinhar gravação, listagem, detalhes, exclusão e relatório após verificar o esquema real. A divergência de código é confirmada; o impacto nos registros depende de homologação. |

Integrar os quatro canais ao Fale conosco é válido. Não é necessário criar quatro layouts independentes para concluir a modernização, mas cada acesso precisa abrir a opção correta e funcionar de ponta a ponta. A afirmação anterior de que as telas estavam prontas deve ser entendida como renovação visual local, não como conclusão destas correções.

## Todas as telas e entradas públicas

**Modernizada:** nova interface implementada. **Correção:** falha/acesso incompleto confirmado. **Parcial:** ainda mantém parte da estrutura antiga. Todas precisam também da homologação transversal indicada neste relatório. Os arquivos são as referências canônicas; prefixos de seção e aliases devem ser validados no roteamento real.

| # | Tela / referência | Estado | Falta para concluir |
|---|---|---|---|
| 1 | Inicial — `includes/home.php` | Modernizada | Homologar banners, campanhas, notícias, convênios, imagens e destinos reais. |
| 2 | Sobre a Santa Casa — `sobre_a_santa_casa.php` | Modernizada | Conferir história, valores, provedores e galeria do CMS. |
| 3 | Humanização — `humanizacao.php` | Modernizada | Homologar os quatro blocos, atualidade editorial e galeria. |
| 4 | Ações sociais e ambientais — `acoes_sociais_ambientais.php` | Modernizada | Conferir conteúdo, voluntariado, galeria e SQL de notícias relacionadas. |
| 5 | Programa de segurança do paciente — `programa_nacional_seguranca.php` | Modernizada | Validar textos, imagem e links com a área responsável. |
| 6 | Portal da transparência — `portal_transparencia.php` | Modernizada | Homologar atualização do acervo, classificação, títulos, downloads e acessibilidade dos documentos. |
| 7 | Pronto atendimento SUS — `pronto_atendimento.php` | Modernizada | Conferir informações assistenciais, registros e fotos reais. |
| 8 | Hotelaria — `hotelaria.php` | Modernizada | Validar todos os ambientes, descrições e imagens. |
| 9 | Clínica Emília — `clinica_emilia.php` | Modernizada | Homologar conteúdo, ambientes, galeria e contatos. |
| 10 | Diagnóstico por imagem — `centro_diagnostico_por_imagem.php` | Modernizada | Conferir serviços descritos, imagens e registros cadastrados. |
| 11 | Unidades de internação — `unidades_de_internacao.php` | Modernizada | Validar unidades e associação de fotos com o banco real. |
| 12 | Particular / Convênio — `particular_convenio.php` | Modernizada, acervo pendente | Recuperar/substituir com aprovação institucional as 18 imagens externas que retornaram 404 na etapa anterior; validar planos e conteúdo. Referência: `tests/particular-legacy-images.json`. |
| 13 | Convênios — `convenios.php` | Modernizada | Homologar nomes/logos, busca e convênio adicional preservado; conferir cobertura com a instituição. |
| 14 | Especialidades — `especialidades.php` | Modernizada | Validar especialidades disponíveis, conteúdo e busca. |
| 15 | Capacidade de instalação e produção — `capacidade_instalacao_producao.php` | Modernizada | Conferir números, períodos e associação de fotos sem inventar indicadores. |
| 16 | Manual do paciente e visitante — `manual_do_paciente_e_visitantes.php` | Modernizada | Homologar orientações, documentos atuais, pesquisa e download por identificador. |
| 17 | Listagem de notícias — `noticias.php` | Modernizada | Validar totais, categorias, paginação, mais lidas e estados vazios com publicações reais. |
| 18 | Notícia individual — `noticia.php` | Modernizada | Homologar HTML histórico, mídias, tabelas, galerias, relacionados, compartilhamento e contador. |
| 19 | Fale conosco — `fale_conosco.php` | Modernizada com correções | Corrigir CAPTCHA e homologar gravação, notificação, erros e os três formulários. |
| 20 | Doações — `doacoes.php` | Modernizada com correções | Corrigir CAPTCHA; conferir dados bancários oficiais, modalidades e notificações. Não há geração automática de boleto nem processamento de pagamento implementados. |
| 21 | Ouvidoria — `ouvidoria.php` | Correção | Resolver entrada vazia, abrir o canal correto e alinhar formulário/listagem/relatório. |
| 22 | Trabalhe conosco — `trabalhe_conosco.php` | Correção | Trocar redirecionamento por sessão; homologar PDF, armazenamento, acesso administrativo e notificação ao RH. |
| 23 | Pesquisa de atendimento — `pesquisa_atendimento.php` | Correção | Corrigir acesso direto; homologar sete respostas, observação opcional, gravação, listagem e relatório. |
| 24 | Localização — `localizacao.php` | Correção | Resolver entrada vazia e acesso à seção; validar endereço, telefones e mapa. |
| 25 | Página não encontrada — `404.php` | Parcial | Conteúdo já tem o visual novo, mas a rota ainda carrega bibliotecas antigas por `$needsLegacy`; isolar assets e testar HTTP 404. |

### Componentes e estados compartilhados

| Componente / fluxo | Pendência |
|---|---|
| Cabeçalho e menus | Teclado, foco, menu móvel, estado ativo, prefixos e todos os links diretos/antigos. |
| Rodapé | Corrigir condição do CAPTCHA; padronizar canais e conferir possível duplicação do telefone cadastrado com o fixo. |
| Newsletter | Homologar persistência e erros/duplicidade; inscrição no banco não comprova integração com plataforma de disparos. |
| Galerias e imagens | Acervo real, textos alternativos, legendas, fallback, orientação, tamanho e controles por teclado. |
| Metadados e indexação | Validar domínio público, títulos, descrições e imagem social; definir canonical, sitemap e indexação. |
| Erros | Cobrir falha de banco/e-mail/CAPTCHA, upload acima do limite, sessão expirada, conteúdo ausente e links inválidos. |
| Downloads | Validar caminhos, tipos, cabeçalhos e autorização quando aplicável, incluindo o controlador legado de `index.php`. |
| Arquivos antigos na raiz | Revisar `index.htm`, `readme.html`, logs e cópias antigas no pacote de produção. Nada foi removido nesta revisão. |
| Exames e Emendômetro | São sistemas externos. Validar destinos/disponibilidade; sua tecnologia não foi modernizada nem auditada aqui. |

## Todas as telas do painel administrativo

**O administrativo continua legado.** Usa AdminLTE 2, Bootstrap 3, jQuery 2 e plugins antigos. Cada item abaixo precisa de interface responsiva, acessibilidade, validação no servidor, permissões, tratamento de erros e compatibilidade com o PHP escolhido. Exclusão, publicação e ordenação são fluxos das telas correspondentes, não páginas separadas.

### Acesso e gestão

| Tela / fluxo | Localização | Trabalho pendente |
|---|---|---|
| Login | `admin/index.php`, `admin/includes/login.php` | Renovar formulário/estados, sessão, proteção contra tentativas repetidas e armazenamento de senhas. |
| Recuperação de senha | `admin/index.php` | Migrar para recuperação por token temporário, revisar mensagens e substituir mailer antigo. |
| Estrutura do painel e saída | `admin/painel.php`, `admin/includes/` | Menu/cabeçalho/perfil, zoom e redirecionamentos com encerramento adequado. |
| Inicial do painel | `admin/system/home.php` | Arquivo vazio: definir e implementar conteúdo útil baseado em dados disponíveis. |
| Usuários — listagem | `admin/system/usuario/index.php` | Renovar tabela, filtros, paginação e permissões. |
| Usuários — cadastro | `admin/system/usuario/create.php` | Renovar formulário, senha e validação. |
| Usuários — edição / meu perfil | `admin/system/usuario/update.php` | Validar alteração de senha, foto e autorização por usuário. |
| Banners — listagem | `admin/system/banner/index.php` | Renovar tabela, publicação, ordem e ações. |
| Banners — cadastro | `admin/system/banner/create.php` | Renovar campos, destino do link e upload. |
| Banners — edição | `admin/system/banner/update.php` | Substituição de imagem, prévia, publicação e tratamento de erros. |
| Notícias — listagem | `admin/system/noticias/index.php` | Filtros, status, paginação e ações. |
| Notícias — cadastro | `admin/system/noticias/create.php` | Migrar editor, mídia, categorias, uploads e galerias. |
| Notícias — edição | `admin/system/noticias/update.php` | Preservar HTML existente, slug, metadados e publicação. |
| Galerias — listagem | `admin/system/galeria/index.php` | Pesquisa, organização e ações. |
| Galerias — cadastro | `admin/system/galeria/create.php` | Upload, associação, legendas e ordenação. |
| Galerias — edição | `admin/system/galeria/update.php` | Associação com notícias e exclusão/substituição segura de arquivos. |
| Seletor de arquivos/imagens | `resources/plugins/ckeditor/kcfinder/` | Migrar gerenciador, autenticação, permissões, tipos e diretórios. |
| Editor / inserção de galeria | `resources/plugins/ckeditor/config.js`, plugin `rpcgaleria` | Migrar integração personalizada e compatibilidade do HTML; não basta substituir o JavaScript do editor. |

### Edição de páginas — cada aba precisa ser modernizada

| Tela / aba | Template administrativo | Pendência específica além da renovação comum |
|---|---|---|
| Sobre a Santa Casa | `admin/system/paginas/institucional.php` | Blocos institucionais, provedores e galeria. |
| Humanização | Mesmo template institucional | Quatro blocos e galeria. |
| Ações sociais e ambientais | Mesmo template institucional | Blocos e imagens. |
| Programa de segurança do paciente | Mesmo template institucional | Textos e imagem. |
| Portal da transparência | Mesmo template institucional | Cadastro/classificação de documentos e correspondência com o acervo público. |
| Pronto atendimento | `admin/system/paginas/instalacoes.php` | Registros e fotos. |
| Hotelaria | Mesmo template de instalações | Registros e galeria. |
| Clínica Emília | Mesmo template de instalações | Registros e galeria. |
| Diagnóstico por imagem | Mesmo template de instalações | Blocos e imagens. |
| Unidades de internação | Mesmo template de instalações | Unidades e associação/ordenação de fotos. |
| Convênios | `admin/system/paginas/servicos.php` | Nomes, logos e consistência com a busca pública. |
| Especialidades | Mesmo template de serviços | Conteúdo e ordenação. |
| Capacidade de instalação e produção | Mesmo template de serviços | Indicadores, períodos e fotos. |
| Manual do paciente e visitante | Mesmo template de serviços | Orientações, documentos e download. |
| Contatos — listagem histórica | `admin/system/paginas/fale-conosco.php` | Esclarecer finalidade e separar registros da Ouvidoria. |
| Ouvidoria — conteúdo e recebimentos | Mesmo template de contato | Corrigir divergência entre `contato` e `ouvidoria`. |
| Trabalhe conosco — conteúdo e currículos | Mesmo template de contato | Revisar acesso a PDFs e limpar o trecho duplicado comentado de `listTrabalheConosco`; não se trata de duas funções ativas. |
| Doações — conteúdo e recebimentos | Mesmo template de contato | Preservar instruções oficiais e modalidades nos registros. |
| Pesquisa — conteúdo e respostas | Mesmo template de contato | Conferir listagem/relatório das sete respostas e acesso pelo menu. |
| Localização — edição | Mesmo template de contato | Endereço, telefone, e-mail e correspondência com o público. |

Não foi encontrada aba específica de Particular / Convênio nos templates e menus inspecionados: a página pública contém texto no código e consulta convênios. Decidir se é necessário editor próprio. Também não foi identificada uma tela administrativa própria de newsletter; esclarecer a operação esperada antes de adicionar funcionalidades. Estes recursos ausentes não são apresentados como telas existentes.

### Relatórios administrativos

| Relatório | Arquivo | Pendência |
|---|---|---|
| Contatos | `admin/relatorio-contatos.php` | Renovar filtros/exportação, permissões, campos e codificação. |
| Ouvidoria | `admin/relatorio-ouvidoria.php` | Mesmo trabalho e alinhamento com a listagem e o formulário. |
| Trabalhe conosco | `admin/relatorio-trabalhe_conosco.php` | Conferir campos e acesso restrito aos dados de candidatos. |
| Doações | `admin/relatorio-doacoes.php` | Validar modalidades, datas e recebimentos. |
| Pesquisa de atendimento | `admin/relatorio-pesquisa_atendimento.php` | Perguntas, valores, datas e exportação com acentuação correta. |

## Tecnologias defasadas e situação real do PHP

**O PHP da hospedagem não foi atualizado nesta modernização.** A evidência disponível é a execução local anterior em PHP 8.4.25. A versão real de produção, suas extensões e o banco ainda precisam ser levantados.

PHP 8.4 tem suporte ativo até 31/12/2026 e de segurança até 31/12/2028. PHP 8.5 é uma linha mais recente, com suporte ativo até 31/12/2027. Portanto, 8.4 não está sem suporte, mas também não é a linha mais recente. Homologar a aplicação inteira antes de trocar o runtime. [Ciclo de suporte oficial](https://www.php.net/supported-versions.php).

| Tecnologia / prática | Evidência / uso | Situação e trabalho proposto |
|---|---|---|
| PHP | Testes anteriores em 8.4.25; produção desconhecida | Avaliar PHP 8.5 com patch estável atual, depois de homologar painel, uploads, e-mail e banco. Não afirmar que a hospedagem já foi atualizada. |
| Bootstrap 3.3.5 | `resources/bootstrap`, painel e fallback público | Defasado; Bootstrap 3 encerrou suporte em 24/07/2019. Remover do público convertido e migrar/reconstruir o painel. [Fonte oficial](https://getbootstrap.com/docs/3.4/getting-started/). |
| jQuery 2.1.4 | Login, painel e fallback | Defasado; jQuery 4.0.0 foi lançado em 2026. Preferir JavaScript nativo onde viável; migrar plugins antes de trocar a versão. [Lançamento oficial](https://blog.jquery.com/2026/01/17/jquery-4-0-0/). |
| jQuery UI 1.11.4 | CDN do painel/login | Versão antiga; substituir componentes usados ou migrar em conjunto com os plugins dependentes. |
| AdminLTE 2.3.2 | `resources/dist/css/AdminLTE.css` | Tema antigo acoplado ao Bootstrap 3. Renovar o painel com base mantida ou componentes próprios; conferir a [distribuição oficial](https://adminlte.io/) ao executar. |
| CKEditor 4.5.1 | `resources/plugins/ckeditor/ckeditor.js` | Editor antigo. CKEditor 4 aberto encerrou suporte em junho de 2023. Migrar para editor mantido com licença adequada, preservando plugins e conteúdo. [Fonte oficial](https://ckeditor.com/blog/ckeditor-4-end-of-life/). |
| KCFinder 3.20-test2 | Constante do uploader e configuração do CKEditor | Gerenciador legado com versão identificada como teste. Migrar/substituir com revisão de uploads e autorização. O cabeçalho menciona 3.12; o inventário usa a constante de versão. |
| PHPMailer 5.2.10 | `_app/PHPMailer-master`, recuperação administrativa | Ainda usado no legado. Migrar e testar recuperação/notificações. O projeto oficial informa que a linha 5.2 não recebe suporte. [Fonte oficial](https://github.com/PHPMailer/PHPMailer). |
| PHPMailer 7.1.1 | `_app/vendor/phpmailer`, contato e doações | Já incorporado nessas telas, não em todo o sistema. Homologar transporte e consolidar dependências. |
| Slim 2.* | `_app/composer.json` | Dependência antiga declarada; as buscas no PHP da aplicação não encontraram bootstrap ativo do Slim. Confirmar uso: remover se abandonado ou migrar se necessário. A linha oficial consultada é Slim 4, com release 4.15.3. [Fonte oficial](https://www.slimframework.com/). |
| DataTables 1.10.7 | Listagens administrativas | Renovar tabelas, filtros/paginação e integração visual, preservando permissões e exportações. |
| Select2 4.0.0 | Campos administrativos | Versão antiga; revisar necessidade e atualizar/substituir junto ao painel. |
| Bootbox 4.4.0 / iCheck 1.0.1 | Diálogos e campos do painel | Substituir por componentes acessíveis atuais ou controles nativos. |
| Moment.js 2.10.2 | CDN referenciada no painel | Versão antiga; rever datas, locale/fuso e avaliar APIs nativas nos usos simples. |
| Owl Carousel 2.0.0 / Magnific Popup | Acervo de plugins e código legado | Substituídos nas galerias públicas novas; mapear usos restantes antes de remover. A versão do Magnific Popup não foi confirmada. |
| Font Awesome 4.4.0 / Ionicons 2.0.1 | Login/painel e fallback | Consolidar ícones em solução atual; reutilizar SVGs do público onde adequado. |
| TimThumb 1.28 | `admin/includes/tim.php`, fotos de perfil/menu | Redimensionador legado: substituir por processamento controlado e validado. Não foi realizado teste de exploração. |
| Classe ReCaptcha antiga | `_app/Helpers/ReCaptcha.class.php` | Construtor com nome da classe não funciona como construtor no PHP 8. Os formulários novos usam outra verificação; eliminar usos restantes após inventário e corrigir carregamento do widget. |
| `utf8_encode` / `utf8_decode` | `Check.class.php`, recuperação e legado | Depreciadas desde PHP 8.2. Adotar UTF-8 consistente e conversão explícita somente onde necessária. [Manual oficial](https://www.php.net/manual/en/function.utf8-decode.php). |
| MD5 para senhas | `_app/Models/Login.class.php:72`, `admin/index.php:83` | Migrar para `password_hash`/`password_verify`, com estratégia para contas existentes e recuperação segura. [API oficial](https://www.php.net/manual/en/function.password-hash.php). |
| Bibliotecas copiadas manualmente | `resources/plugins`, dois mailers e manifesto antigo | Consolidar inventário, versões fixadas, licenças e atualização/auditoria reproduzível. Presença de arquivo não comprova uso ativo. |
| MySQL/MariaDB | Camada PDO e SQL existentes | Versão desconhecida: não há evidência de atualização nem base para declarar obsolescência. Levantar versão, charset, modos SQL, índices, backup e restauração. |
| Apache / sistema operacional / TLS | Configuração local e URLs; hospedagem não inspecionada | Versões desconhecidas. Validar manutenção, HTTPS, certificados e configuração de publicação. |

Outras pastas presentes, sem certificação de versão/uso ativo: `aguia-gallery`, `bootstrap-slider`, `bootstrap-wysihtml5`, `chartjs`, `client`, `code-house-back-to-top`, `colorpicker`, `datepicker`, `daterangepicker`, `drag-menu`, `fastclick`, `flot`, `fullcalendar`, `imagesloaded`, `input-mask`, `ionslider`, `jquery-filer`, `jquery-sortable`, `jvectormap`, `knob`, `mapa`, `morris`, `nice-select`, `pace`, `PagSeguroLibrary`, `slimScroll`, `sparkline`, `swiper`, `timepicker`, `weather`, `youtube-thumbnail`. Confirmar chamadas, versões e compatibilidade antes de atualizar ou retirar. A pasta PagSeguro não comprova pagamento integrado às doações. Alguns scripts do painel aparecem em trechos comentados ou com caminhos relativos antigos: separar uso efetivo de exemplos antes da limpeza.

## Prioridades e critérios para concluir a modernização

### Prioridade 1 — funcionamento e proteção antes da publicação

- [x] Corrigir CAPTCHA e testar sua renderização fora de `SCL_PREVIEW` (homologação externa ainda pendente).
- [x] Resolver Ouvidoria/Localização vazias e redirecionamentos por sessão, preservando aliases e links.
- [x] Alinhar gravação, listagem e relatório de Ouvidoria no código; validar o esquema e os registros reais na homologação.
- [ ] Corrigir autorização nos endpoints: em `admin/webservices/paginas/fale-conosco/servico.php:7–15`, o código redireciona quando o login falha, mas não encerra a execução antes de processar `acao`. Revisar também os outros endpoints; um cabeçalho Location não interrompe PHP.
- [ ] Revisar CSRF e autorização por ação/registro nos cadastros, alterações, exclusões e relatórios. O endpoint de contato inspecionado não apresenta validação CSRF; não houve auditoria exaustiva de todos os endpoints.
- [ ] Migrar MD5 e recuperação de senha; revisar sessão, cookies, expiração e tentativas repetidas.
- [ ] Migrar mailer administrativo e retirar credenciais embutidas/configurações de desenvolvimento do pacote publicado, sem expor segredos no relatório ou logs.
- [ ] Homologar gravação, consulta e notificação ponta a ponta; mensagem de sucesso não comprova entrega de e-mail.
- [ ] Revisar autorização de leitura dos currículos, uploads históricos e gerenciador do editor. Nome aleatório e MIME validado não substituem controle de acesso aos documentos.

### Prioridade 2 — concluir as telas e o conteúdo

- [ ] Modernizar todas as telas/abas administrativas inventariadas, incluindo editor, gerenciador e relatórios.
- [ ] Remover dependências antigas da página 404 e carregar apenas os recursos necessários por rota.
- [ ] Recuperar o acervo de Particular / Convênio e homologar conteúdo/documentos das demais páginas.
- [ ] Conferir conteúdo rico histórico: o editor permite mais tipos de mídia que os templates novos. Mapear casos reais e definir preservação/conversão segura, sem liberar HTML irrestrito.
- [ ] Consolidar componentes, estados vazios, feedback, erros e acessibilidade.
- [ ] Inventariar endpoints de `includes/servicos` e `admin/webservices`, padronizando validação, autorização, resposta e tratamento de falha.

### Prioridade 3 — operação e critério de “100%”

- [ ] Documentar e homologar versões reais de PHP, banco, servidor e extensões. Atualizar código não atualiza a hospedagem.
- [ ] Preparar configuração por ambiente, domínio/HTTPS, credenciais fora do conteúdo público, logs adequados e dependências reproduzíveis.
- [ ] Publicar pacote controlado, sem testes/harnesses, logs e material de desenvolvimento indevidamente acessíveis.
- [ ] Automatizar regressão de rotas, formulários, permissões, uploads, CRUD e exportações administrativas com dados isolados.
- [ ] Validar responsividade e acessibilidade com conteúdo real: foco, teclado, contraste, zoom, leitura de erros, controles e documentos.
- [ ] Medir desempenho com imagens e volume reais; otimizar mídia, consultas, cache e carregamento com base nos resultados.
- [ ] Validar metadados, sitemap, indexação, links e HTTP 404 no domínio definitivo.
- [ ] Documentar backup/restauração testada, implantação, reversão e monitoramento; realizar homologação institucional final.

**Critério de conclusão:** todos os itens aplicáveis aprovados, nenhuma falha bloqueadora conhecida, fluxos públicos e administrativos homologados com dados reais e implantação validada no ambiente definitivo. Isso define a entrega desta modernização, não o fim da manutenção futura. Não foi atribuído percentual numérico: telas, segurança e infraestrutura têm pesos diferentes, e parte da produção ainda é desconhecida.

## Histórico das etapas anteriores

Os registros seguintes descrevem o trabalho de cada etapa; suas conclusões são complementadas pelas pendências confirmadas no inventário acima.

A interface foi reorganizada com uma página inicial nova, navegação responsiva, atalhos para pacientes, apresentação de serviços, campanhas, notícias, convênios e doações. Cabeçalho, rodapé e introdução das páginas internas compartilham o mesmo sistema visual.

## Organização

- `includes/ui.php`: escape de saída, URLs e ícones SVG.
- `includes/home.php`: página inicial integrada às tabelas existentes de notícias, banners e convênios.
- `includes/header.php`, `navbar.php`, `footer.php`, `topo_paginas.php`: componentes compartilhados.
- `resources/css/modern.css`: cores, tipografia, espaçamento, CSS Grid/Flexbox, estados de foco e layouts responsivos.
- `resources/js/modern.js`: menu móvel, navegação por teclado e integração com a aba de currículos.

A página inicial não carrega jQuery, Bootstrap, Owl Carousel, jQuery UI ou SDK do Facebook. As páginas internas ainda não convertidas carregam Bootstrap 3, jQuery 2 e Owl Carousel para manter o funcionamento dos formulários e galerias existentes. A substituição integral dessas dependências e do painel administrativo **não está concluída**.

O formulário de newsletter ganhou validação no servidor, token contra requisições forjadas e lista explícita dos campos gravados. Notícias e metadados são escapados na nova interface. O rastreador Universal Analytics antigo foi removido; nenhuma nova medição foi configurada.

## PHP e implantação

Foram corrigidos o autoload removido no PHP 8, a sintaxe antiga de acesso a strings em CPF/CNPJ e a comparação numérica no roteamento. O caminho de arquivos passa a ser derivado do projeto. A leitura da query string agora preserva parâmetros separados. O Apache prioriza `index.php`, evitando a página antiga de hospedagem em `index.htm`.

Isso não certifica todo o sistema legado como compatível com PHP 8: o painel, as bibliotecas de terceiros, o envio de e-mail e os endpoints precisam de homologação com o banco e a infraestrutura reais antes de trocar o runtime de produção.

Não há alteração de esquema do banco, instalação de framework, processo de build obrigatório ou publicação automática. As configurações de conexão e URL existentes continuam no arquivo `_app/Config.inc.php`.

## Verificação

```sh
php tests/modernization.php
node --check resources/js/modern.js
```

Os testes usam dados isolados, sem acessar o banco. Cobrem URLs, escape, links de campanhas, validadores de CPF/CNPJ, rotas com prefixo de seção, estado vazio da página inicial e validação/CSRF da newsletter.

Validação visual feita no navegador em desktop e celular com dados de demonstração isolados do site: menu móvel, submenus, Escape, imagens, rodapé e página interna. O conteúdo de demonstração não faz parte dos templates de produção.

Antes da publicação, homologar com o banco de dados: conteúdo de todas as páginas, abertura dos PDFs de transparência, galerias, envio de contato e currículo, reCAPTCHA, newsletter e fluxos de doação. Os links do portal de exames e emendômetro foram preservados; os sistemas externos não foram alterados.

## Prévia visual sem banco de dados

```sh
php -S 127.0.0.1:8094 -t . tests/preview.php
```

Abra `http://127.0.0.1:8094/`. Esse roteador é exclusivo para o servidor de desenvolvimento, usa notícias demonstrativas e não testa os endpoints reais. Ele exibe a página inicial, Sobre a Santa Casa, Humanização, Ações sociais e ambientais, Programa de segurança do paciente e a página 404 com os componentes compartilhados; não substitui a homologação integrada. Não use esse comando para publicar o site.

## Sobre a Santa Casa — página convertida

A página pública foi reestruturada por completo: história, navegação por seções, galeria, missão, visão, valores, provedores e acesso a contato/transparência. Os mesmos campos das tabelas pagina_sobre, galeria_sobre e provedor continuam sendo utilizados, sem alteração de esquema ou edição dos registros.

Esta página não carrega Bootstrap, jQuery, Owl Carousel, Font Awesome ou o CSS legado. Usa CSS responsivo e JavaScript nativo, em resources/css/about.css e resources/js/about.js. A galeria mantém links de imagens utilizáveis sem JavaScript e ganha controles, contador e ampliação em diálogo nativo nos navegadores compatíveis. Não há reprodução automática.

A formatação básica do conteúdo é preservada por uma lista de elementos permitidos; scripts, estilos e atributos executáveis são removidos. Sem a extensão DOM do PHP, o conteúdo continua legível como texto. Ausência de conteúdo, foto única, provedores sem retrato e períodos incompletos são tratados.

Validação: php tests/about.php — 27 verificações gerais e 18 específicas. Verificadas no navegador: telas de desktop e celular, navegação da galeria, ampliação, Escape, restauração do foco, estado vazio e imagem única. A homologação com o banco real permanece pendente.

Na prévia local, abra /institucional/sobre-a-santa-casa. Acrescente ?fixture=empty ou ?fixture=single para verificar os estados alternativos. Os textos em tests/fixtures/about.php são exclusivamente demonstrativos e não são carregados pela aplicação de produção.

## Humanização — página convertida

A página pública Humanização usa os quatro blocos originais da tabela pagina_humanizacao e os registros da galeria_humanizacao, com a ordenação anterior. O contexto histórico de 2010 e a seção Sempre evoluindo foram preservados. Não houve alteração do banco ou do painel.

A interface ganhou navegação por seções, leitura responsiva, galeria única para desktop e celular, legendas, ampliação e controles por teclado. Bootstrap, jQuery, Owl Carousel, Font Awesome e CSS legado não são carregados nesta página.

O componente includes/institutional_gallery.php é compartilhado com Sobre a Santa Casa; a extração preserva os controles e o comportamento anterior. A apresentação específica está em resources/css/humanization.css. Casos sem conteúdo, sem galeria, com foto única e com blocos parcialmente preenchidos são tratados.

Teste: php tests/humanization.php — 27 verificações gerais, 18 de Sobre a Santa Casa e 16 de Humanização. Prévia: /institucional/humanizacao; estados alternativos: ?fixture=empty e ?fixture=single. Dados demonstrativos ficam apenas em tests/fixtures/humanization.php. A validação com o banco real segue pendente antes da publicação.

## Ações sociais e ambientais — página convertida

A página preserva os quatro blocos de pagina_acoes_sociais_ambientais, a imagem img1 e a galeria_acao. Apoio religioso, voluntariado e notícias continuam presentes; não foram criadas iniciativas ou informações institucionais fictícias nos templates de produção.

A interface usa navegação por seções, conteúdo formatado com segurança, imagem responsiva, galeria compartilhada com ampliação e cartões de notícias. Não carrega Bootstrap, jQuery, Owl Carousel, Font Awesome ou CSS legado. Os estilos específicos estão em resources/css/social-actions.css.

A consulta de notícias mantém status publicado, categoria acoes-sociais, ordem decrescente de publicação e limite de três registros. Usa EXISTS para evitar duplicações por associação de tags e não depende de ANY_VALUE. A execução SQL com o banco real ainda precisa ser homologada; os testes locais verificam o contrato da consulta e renderização com dados isolados.

Teste: php tests/social-actions.php — 83 verificações (27 gerais, 18 de Sobre, 16 de Humanização e 22 desta página). Verificação visual em desktop e celular: ausência de overflow, imagens, diálogo, avanço da galeria, Escape e retorno do foco. Casos vazios, dados parciais, datas inválidas e imagem única tratados.

Prévia: /institucional/acoes-sociais-ambientais. ?fixture=empty e ?fixture=single exercitam estados alternativos. Os dados de tests/fixtures/social-actions.php são demonstrativos; notícias e serviços externos não estão conectados na prévia. Sem publicação em produção.

## Programa de segurança do paciente — página convertida

A página mantém os blocos bloco1 e bloco2 e a imagem img1 da tabela pagina_programa_nacional_seguranca, consultando o registro mais recente como antes. Os títulos Programa nacional de segurança do paciente e Núcleo de segurança do paciente foram preservados. Nenhuma orientação clínica ou protocolo foi acrescentado aos conteúdos de produção.

A interface usa atalhos de seção, leitura responsiva, imagem sem recorte com link para tamanho original e acessos ao manual do paciente e aos canais de contato já existentes. Não carrega Bootstrap, jQuery, Owl Carousel, Font Awesome, CSS legado ou JavaScript de galeria. Estilos específicos: resources/css/patient-safety.css.

Teste: php tests/patient-safety.php — 101 verificações, sendo 18 específicas desta página. Casos de dados ausentes, apenas programa, apenas núcleo, apenas imagem, HTML inseguro, imagens inválidas e alias com underscore tratados. Layout desktop e móvel, imagem original, ausência de imagem e estado vazio verificados no navegador.

Prévia: /institucional/programa-nacional-seguranca. Estados alternativos: ?fixture=empty e ?fixture=no-image. Os textos demonstrativos estão isolados em tests/fixtures/patient-safety.php e não representam recomendações clínicas. A homologação com o banco real permanece pendente antes da publicação.

## Portal da transparência

Tela modernizada com o padrão visual institucional, CSS próprio e JavaScript nativo, sem carregar jQuery, Bootstrap ou OwlCarousel nesta rota. Preserva a classificação e os 211 documentos do acervo local. Busca combina palavras, ignora acentos e considera títulos dos grupos; filtro por categoria, contagens atualizadas, limpeza de filtros e controles de expansão. Grupos nativos continuam acessíveis sem JavaScript.

Validação: `php tests/transparency.php` passou com 434 verificações do portal, além das 101 anteriores. Inventário comparado aos arquivos físicos, sem documentos ausentes ou duplicados; todos os 211 links responderam HTTP 200 na prévia local. Navegador: busca combinada, estado sem resultados, categoria, limpeza, expansão e recolhimento; larguras 390 e 1280 sem overflow horizontal; console sem erros. Conteúdo dos documentos e sua vigência não foram alterados nem auditados.

Prévia: `http://127.0.0.1:8095/institucional/portal-transparencia`. Usa o acervo real local; banco e envios permanecem desconectados. Alterações aplicadas no projeto local, sem publicação em produção.

## Pronto atendimento SUS

Página modernizada com navegação por seções, destaque do conteúdo institucional, quatro cartões de classificação identificados por texto e cor, galeria com ampliação e navegação por teclado, links para guia e contato. Preservados os cinco campos do CMS, a consulta do registro mais recente, a ordem e os títulos das imagens, além das quatro categorias e tempos da página original. Dependências legadas removidas apenas desta rota; a página particular/convênios permanece independente.

Prévia: `http://127.0.0.1:8095/instalacoes/pronto-atendimento` (também aceita underscore). Textos demonstrativos isolados em `tests/fixtures/urgent-care.php`, imagens do acervo local; banco real não conectado. Antes de publicar, homologar o conteúdo do CMS e a vigência das informações assistenciais com a instituição. Nenhuma mudança de protocolo clínico foi proposta.

Validação: `php tests/urgent-care.php` passou com 19 verificações novas e 535 anteriores. Cobertura de conteúdo, consultas, classificação, URLs em subdiretório, galeria unitária, estado vazio e sanitização. Navegador em 1280 e 390 pixels sem rolagem horizontal; imagens sem falhas observadas, console sem erros, ampliação/próxima foto/Escape e restauração do foco conferidos. Aplicado localmente, sem publicação em produção.

## Hotelaria

Página modernizada com apresentação em duas colunas no desktop, navegação por seções, galeria ampliável com legendas do campo `titulo`, contador de imagens e atalhos para guia do paciente e contato. Preservadas as consultas a `pagina_hotelaria` e `hotelaria`, os dois campos de conteúdo e a ordem por `data_criacao`. Removido o carregamento de jQuery, Bootstrap e OwlCarousel nesta rota; galeria reutiliza o componente nativo institucional.

Prévia: `http://127.0.0.1:8095/instalacoes/hotelaria`; conteúdo demonstrativo isolado em `tests/fixtures/hospitality.php`, com imagens do acervo local. Banco real e envios não conectados. Homologação com conteúdo real permanece necessária antes da publicação; nenhuma alteração em produção.

Validação: `php tests/hospitality.php` passou com 14 verificações novas e 554 anteriores. Cobertura de consultas, textos, títulos das fotos, caminhos em subdiretório, conteúdo parcial, galeria unitária, ausência de dados e sanitização. Teste legado de `tests/about.php` agora usa a página ainda não convertida `particular_convenio`. Navegador em 1280 e 390 pixels sem overflow horizontal; galeria ampliada, próxima imagem, fechamento por Escape e restauração de foco conferidos; sem erros no console ou imagens quebradas observadas.

## Clínica Emília

Tela modernizada com apresentação responsiva, navegação por seções, galeria ampliável com legendas e contador, guia do paciente e contato. Mantidos os campos `bloco1`/`bloco2`, as tabelas `pagina_clinica_emilia` e `clinica_emilia`, a seleção do registro mais recente e a ordem das fotos. As rotas com hífen e underscore carregam os recursos modernos sem jQuery, Bootstrap ou OwlCarousel.

Prévia: `http://127.0.0.1:8095/instalacoes/clinica-emilia`. Textos demonstrativos isolados em `tests/fixtures/emilia.php`, imagens do acervo local; banco real e envios desconectados. Homologar conteúdo real antes da publicação. Alterações locais, sem deploy.

Validação: `php tests/emilia.php` passou com 14 verificações da clínica e 568 anteriores, incluindo campos, consultas, caminhos, galeria unitária, conteúdo parcial/ausente e sanitização. Navegador em 1280 e 390 pixels sem overflow horizontal, sem erros no console ou imagens quebradas observadas; ampliação, próxima imagem, Escape e restauração de foco conferidos.

## Diagnóstico por imagem, Unidades de internação e Particular / Convênio

Três páginas convertidas para o padrão responsivo institucional, com navegação por seções, conteúdo sanitizado, atalhos de orientação e contato, e sem jQuery, Bootstrap ou OwlCarousel nessas rotas. CSS compartilhado em `resources/css/facilities.css`.

Diagnóstico mantém os dois campos e as consultas originais de página/galeria. Internação mantém os três campos de apresentação, todas as unidades, títulos, descrições e fotos na ordem original. Consulta de imagens usa identificador validado e parâmetro vinculado por unidade. Galeria nativa agora inicializa todas as instâncias e associa cada uma ao próprio diálogo; cálculo de navegação considera a posição real dos slides, inclusive layouts que exibem parte da próxima foto.

Particular / Convênio conserva o parágrafo original e passa a consultar a tabela existente `convenio`, sem usar a consulta antiga e não utilizada à página do pronto atendimento. As 18 imagens externas em `agenciamd.com/fotos-santa-casa/02/` retornaram HTTP 404 em 22/09/2026. Os endereços foram preservados em `tests/particular-legacy-images.json` para futura recuperação. Não são mais carregados na página. Recuperar os arquivos originais continua pendente; nenhuma foto de outro serviço foi apresentada como substituta. Cobertura de planos e conteúdo assistencial precisam de homologação institucional.

Prévia local nas rotas `/instalacoes/centro-diagnostico-por-imagem`, `/instalacoes/unidades-de-internacao` e `/instalacoes/particular_convenio`; aliases com hífen/underscore aceitos na prévia e nos assets. Fixtures em `tests/fixtures/facilities.php` usam textos, nomes de unidades, associações de fotos e convênios demonstrativos. Não representam cadastro real nem confirmação de cobertura. Banco e envios desconectados; sem publicação em produção.

Validação: `php tests/facilities.php` passou com 33 verificações novas e 582 anteriores. Inclui isolamento das imagens por unidade, parâmetros SQL, conteúdo parcial/ausente, sanitização, aliases e convênios. Navegador: três telas em 1280 e 390 pixels sem overflow horizontal; galerias A/B abrem seus próprios diálogos; próxima imagem e Escape conferidos; sem erros de console ou imagens quebradas observadas. O teste de dependências legadas usa agora a rota ainda não convertida `convenios`.

## Convênios, Especialidades, Capacidade de instalação e produção e Manual

Quatro telas convertidas para o padrão moderno sem jQuery, Bootstrap ou OwlCarousel. Convênios e Especialidades têm busca textual sem distinção de acentos, contagem e estado sem resultados. O convênio adicional SINEEVALI publicado no template antigo foi preservado, evitando duplicação quando presente no cadastro. `includes/convenios.php` permanece intacto para usos legados; a nova página tem renderização própria.

Capacidade conserva os campos de apresentação, títulos, descrições, valores e ordem de registros/imagens. Galerias por registro com identificador validado e consulta parametrizada. Nenhum indicador foi inventado ou recalculado.

Manual tem assuntos em `details` nativos, busca no título e conteúdo, expansão/recolhimento e documentos em seção própria. Os links usam o campo `pdf` já cadastrado, em nova aba com `noopener`; registros sem caminho mantêm a rota legada por ID válido maior que 1 (limite do controlador existente). Sem endereço válido, o título permanece visível com indicação de indisponibilidade. A rota direta evita depender do controlador antigo para os documentos com arquivo cadastrado; não altera arquivos nem banco.

Prévia nas rotas `/servicos/convenios`, `/servicos/especialidades`, `/servicos/capacidade-instalacao-producao` e `/servicos/manual-do-paciente-e-visitantes`. Fixtures em `tests/fixtures/services.php`; nomes, orientações, associações e números são demonstrativos, sem substituir o CMS real. Banco e envios desconectados. Homologar informações e documentos atuais com a instituição antes da publicação. Alterações locais, sem deploy.

Validação: `php tests/services.php` passou com 31 verificações novas e 615 anteriores. Inclui sanitização, arquivos locais, links legados, aliases, conteúdo vazio, preservação de números e convênio adicional. Quatro páginas inspecionadas em 1280 e 390 pixels sem overflow horizontal. Busca de planos, busca sem acento, resultado vazio/limpeza, ampliação de capacidade, busca no corpo do manual, expansão/recolhimento e Enter nos assuntos conferidos. Console sem erros na sessão de validação. Dois PDFs demonstrativos retornaram HTTP 200 com Content-Type application/pdf. Servidor local reiniciado em 127.0.0.1:8095.

## Listagem de notícias

Listagem modernizada com cartões responsivos, imagens com alternativa visual quando ausentes, categoria, data, título e resumo. Navegação por categorias publicadas e seção Mais lidas preservando ordenação por acessos. Paginação acessível com página atual, anterior/próxima e manutenção do caminho da categoria. Usa os registros resolvidos por `Url`, preservando filtro status=1, ordenação, limite e rotas de artigos; total calculado sobre a consulta original sem LIMIT/OFFSET. Página individual e bloco legado de notícias relacionadas não foram alterados.

Prévia em `http://127.0.0.1:8095/noticias`, com categorias `institucional` e `acoes-sociais` e páginas numéricas. Notícias e links de artigos demonstrativos não representam publicações reais; o detalhe de artigo não é simulado nesta prévia. Banco real não conectado e nenhuma publicação em produção. Validar consultas e conteúdo real em homologação.

Validação: `php tests/news-listing.php` passou com 10 verificações novas e 646 anteriores. Inclui paginação, categoria, última página, ausência de notícias/imagem, datas inválidas e escape de metadados. Navegador: categoria Ações sociais, página 2 (um registro de seis), retorno a Todas as notícias; desktop 1280 e mobile 390 sem overflow horizontal; console sem erros. Servidor local reiniciado na porta 8095.

## Notícia individual, Fale conosco e Doações

As três telas usam o padrão visual institucional responsivo, sem jQuery, Bootstrap, OwlCarousel ou Magnific Popup. Os estilos e comportamentos ficam em `resources/css/community.css` e `resources/js/community.js`.

A notícia preserva título, resumo, imagem, autoria, data válida, contador de acessos, assuntos e notícias relacionadas publicadas. Conteúdo do editor passa por sanitização que preserva títulos, listas, tabelas, imagens e vídeos incorporados de YouTube/Vimeo. Galerias `ck-galleria` são consultadas no servidor com identificadores validados e parâmetros vinculados, usando os diálogos acessíveis existentes. Imagem principal e imagens do texto também podem ser ampliadas. Compartilhamento por link/Facebook, retorno à listagem e relacionados funcionais. O link compartilhado no navegador acompanha o domínio em que a página é acessada; configurar HOME corretamente continua necessário para o fallback sem JavaScript.

Fale conosco conserva Ouvidoria, Trabalhe conosco e Pesquisa de atendimento. Cada opção tem um formulário próprio selecionável por URL, inclusive sem JavaScript; links antigos por fragmento e sessões `tb`/`pa` continuam atendidos. Endereço, e-mail e telefone vêm do cadastro, com o telefone adicional já existente preservado. O mapa foi substituído por um link de localização, eliminando a API antiga de geocodificação. Pesquisa mantém as sete perguntas e valores existentes; o enunciado da nota foi corrigido para 1–5, que são as opções históricas, e observações são opcionais.

Doações conserva `bloco1`, `bloco2`, `bloco3` e as modalidades `deposito`/`boleto`. Os dados bancários do CMS não são substituídos por dados inventados. No celular, as orientações aparecem antes do formulário. O texto esclarece que a mensagem solicita orientação e não gera boleto automaticamente.

Processamento compartilhado com lista permitida de campos, limites de tamanho, validação de e-mail e opções, CSRF, proteção contra reenvio imediato e retenção dos valores nos erros. Currículo exige upload real, extensão PDF, MIME application/pdf e limite de 5 MB, recebe nome aleatório e é removido quando o registro falha. O cadastro usa as tabelas e colunas existentes. Notificações de Ouvidoria/doações seguem para a Secretaria e currículos para o RH; a pesquisa, que tinha destinatário indefinido no código antigo, passa a notificar a Secretaria. Sucesso significa registro no banco; falha posterior na notificação é registrada no log e não induz um novo cadastro duplicado.

PHPMailer 7.1.1 incorporado de https://github.com/PHPMailer/PHPMailer/tree/v7.1.1, com licença e procedência em `_app/vendor/phpmailer/`. As telas públicas novas usam essa versão namespaced e UTF-8, sem as credenciais de desenvolvimento embutidas nos antigos templates. O mailer legado do administrativo não foi migrado nesta etapa. A validação reCAPTCHA usa HTTPS diretamente, substituindo a classe com construtor antigo.

Prévia: `/noticias/noticia-demonstrativa-1`, `/fale-conosco` e `/doacoes` em `http://127.0.0.1:8095`. Os artigos demonstrativos da listagem agora abrem seu detalhe e relacionados. Conteúdo fictício está identificado; não há banco real conectado nem envio real na prévia.

Validação em PHP 8.4.25: `php tests/community.php` passou com 33 verificações novas e 656 anteriores (689 no total). Verificados também sintaxe dos 14 arquivos PHP envolvidos, JavaScript e `git diff --check`. O harness `tests/community-upload.php`, servido isoladamente em loopback com fileinfo habilitado, passou em três cenários HTTP: PDF válido com armazenamento aleatório e anexo MIME sem envio; texto disfarçado de PDF rejeitado; remoção do arquivo após falha de cadastro. Os arquivos sintéticos foram removidos e o servidor do harness encerrado. Navegador em 1280 e 390 pixels: sem overflow horizontal; opções de contato e boleto, galeria, ampliação da capa, Escape/restauração de foco, feedback de copiar link e navegação para notícia relacionada conferidos. Sem erros de console na sessão revisada.

Antes de publicar: homologar os textos e galerias reais, consultas e gravações no banco, chaves/domínio do reCAPTCHA e transporte de e-mail no servidor. O ambiente precisa de DOM, fileinfo, HTTPS/OpenSSL com certificados e acesso de saída ao reCAPTCHA, além do driver de banco e serviço de e-mail; uploads requerem permissão no diretório de currículos e limites PHP coerentes com 5 MB. A prévia mínima não habilita essas integrações. Nenhuma alteração de versão do PHP da hospedagem, envio externo ou deploy foi realizado nesta etapa.
