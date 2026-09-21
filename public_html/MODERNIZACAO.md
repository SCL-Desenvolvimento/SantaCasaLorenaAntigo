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

Abra `http://127.0.0.1:8094/`. Esse roteador é exclusivo para o servidor de desenvolvimento, usa notícias demonstrativas e não testa os endpoints reais. Ele exibe a página inicial, Sobre a Santa Casa e a página 404 com os componentes compartilhados; não substitui a homologação integrada. Não use esse comando para publicar o site.

## Sobre a Santa Casa — página convertida

A página pública foi reestruturada por completo: história, navegação por seções, galeria, missão, visão, valores, provedores e acesso a contato/transparência. Os mesmos campos das tabelas pagina_sobre, galeria_sobre e provedor continuam sendo utilizados, sem alteração de esquema ou edição dos registros.

Esta página não carrega Bootstrap, jQuery, Owl Carousel, Font Awesome ou o CSS legado. Usa CSS responsivo e JavaScript nativo, em resources/css/about.css e resources/js/about.js. A galeria mantém links de imagens utilizáveis sem JavaScript e ganha controles, contador e ampliação em diálogo nativo nos navegadores compatíveis. Não há reprodução automática.

A formatação básica do conteúdo é preservada por uma lista de elementos permitidos; scripts, estilos e atributos executáveis são removidos. Sem a extensão DOM do PHP, o conteúdo continua legível como texto. Ausência de conteúdo, foto única, provedores sem retrato e períodos incompletos são tratados.

Validação: php tests/about.php — 27 verificações gerais e 18 específicas. Verificadas no navegador: telas de desktop e celular, navegação da galeria, ampliação, Escape, restauração do foco, estado vazio e imagem única. A homologação com o banco real permanece pendente.

Na prévia local, abra /institucional/sobre-a-santa-casa. Acrescente ?fixture=empty ou ?fixture=single para verificar os estados alternativos. Os textos em tests/fixtures/about.php são exclusivamente demonstrativos e não são carregados pela aplicação de produção.
