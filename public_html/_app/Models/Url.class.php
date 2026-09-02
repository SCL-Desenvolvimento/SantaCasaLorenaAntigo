<?php

class Url{
	private $Param;
	private $r_URL;
	private $r_DIR;

	//Paginação
	private $Termos;
	private $Places;
	private $urlPag;
	private $Page;
    private $Limit;
    private $Offset;

	private $Erro;
	private $Resultado;
	private $ResultadoPost;
	private $noticiaTag;
	private $ResultadoBusca;
	private $ResultadoBuscaProduto;

	public function getRUrl(){
		return $this->r_URL;
	}

	public function getPaginaContent(){
		return $this->Resultado;
	}

	public function getPosts(){
		return $this->ResultadoPost;
	}

	public function getBusca(){
		return $this->ResultadoBusca;
	}

	public function getTermos(){
		return str_replace("LIMIT :limit offset :offset", "", $this->Termos);
	}

	public function getPlaces(){
		$Places = explode("&", $this->Places);
		array_pop($Places);
		array_pop($Places);
		return implode("&", $Places);
	}

	public function getNoticiaTag(){
		return $this->noticiaTag;
	}

	public function getUrlPag(){

		return $this->urlPag;
	}

	public function getPage(){
		return $this->Page;
	}

	public function getLimit(){
		return $this->Limit;
	}

	public function getOffset(){
		return $this->Offset;
	}

	public function setUrlAmigavel($Param){
		
		//Quebra a URL em vários dados para analise
		$r_URLTemp = explode("/", $Param);
		//var_dump($r_URLTemp);

		//Seta a QueryString
		$this->r_URL = array();

		$i = 0;

		foreach($r_URLTemp AS $dados):

			//if($i == 0){

				$getSessao = new Read();
				$getSessao->fullRead("SELECT * FROM ".PREFIX."paginas WHERE sessao_url = :sessao", "sessao=".$dados);
				if(!$getSessao->getResult() || $dados >= 1){

					if($dados != "" && $dados != "teste" && $dados != "home" && !preg_match("/file-/",$dados)):
						$this->r_URL[$i] = $dados;
						$i++;
					endif;
				}
			//}
		endforeach;

		//Valida a área de acesso
		if(count($this->r_URL) > 0 && $this->r_URL[0] != "home"):

			if(file_exists(DIR."includes/paginas/".str_replace("-", "_", $this->r_URL[0]).".php")):

				$this->r_DIR['page'] = $this->r_URL[0];
			else:
				$this->r_DIR['page'] = "404";
				$this->r_DIR['info']['titulo'] = "Erro 404";
				$this->r_DIR['info']['sub_titulo'] = "Página não encontrada";
				$this->r_DIR['info']['descricao'] = "Página não encontrada";

				return $this->r_DIR;
			endif;
		endif;

		if(isset($this->r_DIR['page']) && $this->r_DIR['page'] != "404"):

			if(preg_match('/^[0-9]*$/', $this->r_URL[count($this->r_URL) - 1])){

				$this->r_DIR['paginacao']['pag'] = $this->r_URL[count($this->r_URL) - 1];
				$paginado = true;
			}

			if($this->r_DIR['page'] == "noticias"){

				if($this->r_URL[count($this->r_URL) - 1] != "" && !isset($this->r_URL[2])){

					$getPost = new Read();
					$getPost->fullRead("SELECT N.*, T.nome as tag, T.url as url_tag, U.nome AS criador
										FROM ".PREFIX."noticia AS N 
										LEFT JOIN ".PREFIX."tag_noticia AS TNR ON (TNR.id_noticia = N.id_noticia)
										LEFT JOIN ".PREFIX."tag AS T ON (T.id_tag = TNR.id_tag)
										INNER JOIN ".PREFIX."usuario AS U ON (N.criador = U.id_usuario)
										WHERE N.link = :link AND N.status = 1 LIMIT 1", "link=".$this->r_URL[count($this->r_URL) - 1]);

					if($getPost->getResult()){

						$this->r_DIR['page'] = "noticia";
						$this->r_DIR['noticia'] = $getPost->getResult()[0];
						$this->r_DIR['info']['titulo'] = $getPost->getResult()[0]['titulo']." - Notícias";
						$this->r_DIR['info']['descricao'] = $getPost->getResult()[0]['subtitulo'];
						$this->r_DIR['info']['imagem'] = $getPost->getResult()[0]['img'];
						return $this->r_DIR;
					}
				}

				if($this->r_URL[count($this->r_URL) - (isset($paginado) ? 2 : 1 )] != "" && $this->r_URL[count($this->r_URL) - (isset($paginado) ? 2 : 1 )] != $this->r_DIR['page']){

					$getTag = new Read();
					$getTag->fullRead("SELECT * FROM ".PREFIX."tag WHERE url = :url", "url=".$this->r_URL[count($this->r_URL) - (isset($paginado) ? 2 : 1 )]);
					if($getTag->getResult()){

						$this->noticiaTag = $this->r_URL[count($this->r_URL) - (isset($paginado) ? 2 : 1 )];
					}else{

						$this->r_DIR['page'] = "404";
						return $this->r_DIR;
					}
				}

				$this->ExePaginacao((isset($this->r_DIR['paginacao']['pag']) ? $this->r_DIR['paginacao']['pag'] : $this->r_DIR['paginacao']['pag'] = 1 ), 5);

				$whereNoticia = "";
				$whereNoticiaPlaces = ""; 
				$params = "";

				$uri = explode("?", $_SERVER['REQUEST_URI']);

				if(isset($uri[1])){
					parse_str($uri[1], $GET);	
					$this->urlPag = $GET;
				}

				$whereNoticia .= (isset($this->noticiaTag) ? "(TN.id_tag = {$getTag->getResult()[0]['id_tag']}) AND " : "" );
				$whereNoticiaPlaces .= (isset($GET['tipo']) && $GET['tipo'] != "" ? "tipo={$GET['tipo']}&" : "");

				$this->Termos = "SELECT 
									N.*, 
									ANY_VALUE(T.nome) as tag, 
									ANY_VALUE(T.url) as url_tag
								FROM ".PREFIX."noticia AS N
								".(isset($this->noticiaTag) ? "INNER JOIN ".PREFIX."tag_noticia AS TN ON (TN.id_noticia = N.id_noticia)" : "" )."
								LEFT JOIN ".PREFIX."tag_noticia AS TNR ON (TNR.id_noticia = N.id_noticia)
								LEFT JOIN ".PREFIX."tag AS T ON (T.id_tag = TNR.id_tag)
								WHERE ".$whereNoticia." 
								N.status = 1 
								GROUP BY N.id_noticia 
								ORDER BY N.data_criacao DESC 
								LIMIT :limit OFFSET :offset";

				$this->Places = $whereNoticiaPlaces."limit={$this->Limit}&offset={$this->Offset}";

				//var_dump($this->Places);
				$readNoticias = new Read;
				$readNoticias->fullRead($this->Termos, $this->Places);

				if($readNoticias->getResult()):
					//$this->r_DIR['filtro'] .= "/pagina/#pagina#".(isset($uri[1]) ? "?".$uri[1] : "" );
					$this->r_DIR['info']['titulo'] = "Notícias";
					$this->r_DIR['info']['sub_titulo'] = "Novidades na <br> Santa Casa de Lorena ";
					$this->r_DIR['info']['descricao_pagina'] = "Confira os últimos acontecimentos entre nossas ações e melhorias, bem como novidades importantes na área.";
					$this->r_DIR['info']['descricao'] = "";
					$this->r_DIR['limit'] = $this->Limit;
					$this->r_DIR['offset'] = $this->Offset;
					$this->r_DIR['termos'] = $this->Termos;
					$this->r_DIR['places'] = $this->Places;
					$this->r_DIR['noticias'] = $readNoticias->getResult();
				else:
					$this->r_DIR['info']['titulo'] = "Notícia não encontrada - Notícias";
					$this->r_DIR['info']['sub_titulo'] = "A notícia ou categoria inserida não existe ou foi desativada";
					$this->r_DIR['info']['descricao_pagina'] = "";
					$this->r_DIR['info']['descricao'] = "";
				endif;
			}else{

				$getPagina = new Read();
				$getPagina->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = :url_amigavel", "url_amigavel=".str_replace("-", "_", $this->r_URL[count($this->r_URL) - 1]));

				if($getPagina->getResult()){

					$this->r_DIR['pagina'] = $getPagina->getResult()[0];
					$this->r_DIR['info']['titulo'] = $getPagina->getResult()[0]['titulo'];
					$this->r_DIR['info']['sessao'] = $getPagina->getResult()[0]['sessao'];
					$this->r_DIR['info']['sub_titulo'] = $getPagina->getResult()[0]['sub_titulo'];
					$this->r_DIR['info']['descricao_pagina'] = $getPagina->getResult()[0]['descricao'];
					$this->r_DIR['info']['imagem'] = $getPagina->getResult()[0]['img_principal'];
					$this->r_DIR['info']['descricao'] = $getPagina->getResult()[0]['seo'];
					$this->r_DIR['info']['seo'] = $getPagina->getResult()[0]['seo'];
					return $this->r_DIR;
				}
			}
		endif;

		if($this->r_DIR['page'] == '404'){

			$this->r_DIR['info']['titulo'] = "Erro 404";
			$this->r_DIR['info']['sub_titulo'] = "Página não encontrada";
			$this->r_DIR['info']['descricao'] = "Página não encontrada";
		}
		
		return $this->r_DIR;
	}

	public function ExePaginacao($Page, $Limit){

        $this->Page = ( (int) $Page ? $Page : 1 );
        $this->Limit = (int) $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;
    }
}