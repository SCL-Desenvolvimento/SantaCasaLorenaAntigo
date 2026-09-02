<?php
/**
 * Classe Breadcrumb
 * --------------------------------------------------------------------------------------------------------
 * Implementa o caminho da url na página corrente, direcionando cada parte para seu endereço dentro do site
 * --------------------------------------------------------------------------------------------------------
 * @author Josean Matias
 * @link http://www.joseanmatias.com.br
 * @copyright 2011
 * @version 1.0.2
 * @example
 * A seguinte url: www.dominio.com.br/noticias/titulo-da-noticia
 * Será automaticamente particionada pela classe para apontamento de link
 * Onde:
 * www.dominio.com.br   -> apontará para a página inicial
 * noticias             -> apontará para a página de listagem de noticias
 * titulo-da-noticia    -> será a página específica da notícia, neste caso não tem link.
 * -------------------------------
 * Caso a url não esteja no formato amigável, os fragmentos e links devem ser passados utilizando o método set()
 * Sendo:
 * $crumb->set( array(
 *      'www.dominio.com.br'=>'Home',
 *      'noticias'          =>'Posts',
 *      ''                  =>'Título da notícia'
 * ) )
 */
class Breadcrumb {

    /**
     * Query String
     * @var <array>
     */
    private $r_QS;

    /**
     * o URL atual no endereço do navegador, sem o protocolo
     * @var <string>
     */
    private $current_url    = '';

    /**
     * o protocolo de navegação. Testados apenas HTTP e HTTPS
     * @var <string>
     */
    private $protocol       = '';

    /**
     * o separador dos crumbs
     * especificamente um caractere
     * @var <string>
     */
    private $separator      = '';

    /**
     * os fragmentos da url
     * @var <array>
     */
    private $crumbs         = array();

    /**
     * O conteúdo HTML gerado pela classe e colocado na saída dos dados
     * @var <string>
     */
    private $html           = '';

    /**
     * método Construtor
     * faz todo o trabalho sujo da classe, chamando os métodos internos e confeccionando os crumbs
     * por fim chama o método makeCrumbs() que gera o HTML de saída
     * @param <string> $separator
     */
    public function  __construct( $separator = '>', array $r_QueryString ) {

        $this->r_QS = $r_QueryString;
        $this->separator = $separator;
        $this->protocol = $this->getProtocol();

        $this->current_url = $this->getCurrentUrl();

        $this->crumbs = $this->fragmentUrl( $this->current_url );

        $this->makeCrumbs();
    }

    /**
     * método set()
     * utilizado para passar parâmetros para criar os crumbs
     * @example
     * Sendo:
     * $crumb->set( array(
     *      'www.dominio.com.br'=>'Home',
     *      'noticias'          =>'Posts',
     *      ''                  =>'Título da notícia'
     * ) )
     * @param array $crumbs
     */
    public function set( array $crumbs ) {

        $this->crumbs = $crumbs;

        $this->makeCrumbs();
    }

    /**
     * método get()
     * resgata os crumbs gerados
     * @return <array>
     */
    public function get() {

        return $this->crumbs;
    }

    /**
     * método getProtocol()
     * Resgata o protocolo utilizado no acesso, testados HTTP e HTTPS
     * @return string
     */
    public function getProtocol() {

        if( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] === 'on' ) {

            $protocol = 'https://';
        } else {

            $protocol = 'http://';
        }
        return $protocol;
    }

    /**
     * método getCurrentUrl()
     * Resgata o URL atual no endereço do navegador, sem o protocolo
     * @return <string>
     */
    public function getCurrentUrl() {

        return $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }

    /**
     * método fragmentUrl()
     * Particiona um URL para montar os crumbs
     * Para criar crumbs personalizados utilize o método set()
     * @param <string> $url
     * @return array
     */
    public function fragmentUrl( $url ) {

        $fragments  = array();
        $crumbs     = array();

        $_url = preg_replace( array( "/[http]s?:\/\//", "/\/$/" ), array( "", "" ), $url );

        $fragments = explode( "/", $_url );

        foreach( $fragments as $fragment ) {

            $crumbs[$fragment] = $fragment;
        }

        return $crumbs;
    }

    /**
     * método makeCrumbs()
     * A mágica final de criação dos breadcrumbs
     * Percorre os crumbs e gera o HTML  de saída
     */
    private function makeCrumbs() {
        //var_dump($this->crumbs);
        $href = '';

        $crumbs_count = sizeof( $this->crumbs );
        $i = 1;

        $this->html = '
        <div class="conteudo-breadcrumb">
        <div class="breadcrumb">';
        foreach( $this->crumbs as $link => $inner ) {
            if(isset($end)) unset($end);
            $link = ($i == 3 && isset($this->r_QS[2]) ? str_replace("matricula","matricula/{$this->r_QS[2]}", $link) : $link);
            $link = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno" ? str_replace("curso-online", "minha-conta", $link) : $link);
            $link = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno" ? str_replace("curso-video", "minha-conta", $link) : $link);
            $link = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno" ? str_replace("curso-presencial", "minha-conta", $link) : $link);
            $href .= ( $i === 1 ) ? $this->protocol . $link : "/$link";

            //Trata a string de exibição
            //echo strripos($inner, 'finalizar-compra');
            $inner = ($i == 1 ? $inner = "Home" : $inner);
            $inner = ($i == 2 ? str_replace("curso","Cursos", $inner) : $inner);
            $inner = ($i == 2 ? str_replace("presencial","Presenciais", $inner) : $inner);
            $inner = ($i == 2 ? str_replace("video","em Vídeo", $inner) : $inner);
            $inner = str_replace("-"," ", $inner);
            $inner = ucwords(strtolower($inner));
            $inner = ($i == 2 ? str_replace("Em Vídeo","em Vídeo", $inner) : $inner);
            $inner = ($i == 3 ? str_replace("Matricula","Matrícula", $inner) : $inner);
            $inner = (strripos($inner, 'Compra') > 0 && !isset($Pagina['aluno'])? "Finalizar Compra" : $inner);
            $inner = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno"? str_replace("Cursos Online", "Minha Página", $inner) : $inner);
            $inner = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno"? str_replace("Cursos em Vídeo", "Minha Página", $inner) : $inner);
            $inner = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno"? str_replace("Cursos Presenciais", "Minha Página", $inner) : $inner);
            if($i == 3 && $inner == "Aluno") $end = true;
            if(Check::isInteger($inner)) $end = true;


            if(($i === $crumbs_count && !isset($end)) || $inner == "One Two One"){
                $this->html .= "<span>$inner</span>";
            } elseif(!isset($end)){
                $this->html .= "<a href=\"$href\" title=\"$inner\">$inner</a> {$this->separator} ";
            }

            $href = ($i == 2 && isset($this->r_QS[1]) && $this->r_QS[1] == "aluno" ? str_replace("minha-conta", $this->r_QS[0], $href) : $href);

            $i++;
        }
        $this->html .= '</div></div>';
    }

    /**
     * método out()
     * saída do HTML gerado pela classe
     */
    public function out() {

        echo $this->html;
    }
}
?>