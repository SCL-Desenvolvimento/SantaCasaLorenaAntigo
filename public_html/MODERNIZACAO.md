# Modernização do site público

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
