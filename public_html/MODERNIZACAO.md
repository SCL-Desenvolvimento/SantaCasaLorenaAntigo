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
