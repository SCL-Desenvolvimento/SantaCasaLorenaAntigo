<?php

class Paginacao {

    private $Page;
    private $Limit;
    private $Offset;
    private $Tabela;
    private $Termos;
    private $Places;
    private $Rows;
    private $Link;
    private $MaxLinks;
    private $First;
    private $Last;
    private $Paginator;
    private $PaginatorAdmin;
    private $nPages;


    function __construct($Link, $First = null, $Last = null, $MaxLinks = null) {
        $this->Link = (string) $Link;
        $this->First = ( (string) $First ? $First : 'Primeira Página' );
        $this->Last = ( (string) $Last ? $Last : 'Última Página' );
        $this->MaxLinks = ( (int) $MaxLinks ? $MaxLinks : 5);
    }

    public function ExePaginacao($Page, $Limit) {
        $this->Page = ( (int) $Page ? $Page : 1 );
        $this->Limit = (int) $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;
    }

    public function ReturnPage() {
        if ($this->Page > 1):
            $nPage = $this->Page - 1;
            header("Location: {$this->Link}/{$nPage}");
        endif;
    }

    public function getPage() {
        return $this->Page;
    }

    public function getLimit() {
        return $this->Limit;
    }

    public function getOffset() {
        return $this->Offset;
    }

    public function getPages() {
        return $this->nPages;
    }

    public function ExePaginator($Tabela = null, $Termos = null, $ParseString = null) {

        //echo $Termos."<br>";
        //echo $ParseString."<br>";

        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        return $this->getSyntax();
    }

    public function ExePaginatorAdmin($Tabela = null, $Termos = null, $ParseString = null, $Param = null) {

        //echo $Termos."<br>";
        //echo $ParseString."<br>";

        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        $this->getSyntaxAdmin($Param);
    }

    public function getPaginator() {
        return $this->Paginator;
    }

    public function getPaginatorAdmin() {
        return $this->PaginatorAdmin;
    }

    private function getSyntax() {

        /*
        echo $this->Tabela."<br><br>";
        echo $this->Termos."<br><br>";
        echo $this->Places."<br><br>";
        */

        $read = new Read;

        if($this->Tabela == null):
            $read->fullRead($this->Termos, $this->Places);
        else:
             $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        endif;

        $this->Rows = $read->getRowCount();

        //echo $this->Rows;
        //echo $this->Limit;

        if ($this->Rows > $this->Limit):
            $Paginas = ceil($this->Rows / $this->Limit);
            $this->nPages = $Paginas;
            $MaxLinks = $this->MaxLinks;
            
            $this->Paginator =  "<nav aria-label='Page navigation'><ul class='pagination'>";
            $this->Paginator .= "<li><a class='txtPaginacaoRDB marginLeftRight5px' title=\"{$this->First}\" href=\"".(preg_match("/#pagina#/", $this->Link) ? str_replace("#pagina#", "1", $this->Link)  : $this->Link."1" )."\" aria-label='Prev'><span aria-hidden='true'>{$this->First}</span></a></li>";

            for ($iPag = $this->Page - $MaxLinks; $iPag <= $this->Page - 1; $iPag ++):
                if ($iPag >= 1):
                    $this->Paginator .= "<li><a class='txtPaginacaoRDB marginLeftRight5px' title=\"Página {$iPag}\" href=\"".(preg_match("/#pagina#/", $this->Link) ? str_replace("#pagina#", $iPag, $this->Link)  : $this->Link.$iPag )."\">{$iPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><a class='txtPaginacaoRDB marginLeftRight5px pagActive'>{$this->Page}</a></li>";

            for ($dPag = $this->Page + 1; $dPag <= $this->Page + $MaxLinks; $dPag ++):
                if ($dPag <= $Paginas):
                    $this->Paginator .= "<li><a class='txtPaginacaoRDB marginLeftRight5px' title=\"Página {$dPag}\" href=\"".(preg_match("/#pagina#/", $this->Link) ? str_replace("#pagina#", $dPag, $this->Link)  : $this->Link.$dPag )."\">{$dPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><a class='txtPaginacaoRDB marginLeftRight5px' title=\"{$this->Last}\" href=\"".(preg_match("/#pagina#/", $this->Link) ? str_replace("#pagina#", $Paginas, $this->Link)  : $this->Link.$Paginas )."\" aria-label='Next'> <span aria-hidden='true'>{$this->Last}</span> </a></li>";
            $this->Paginator .= "</ul></nav>";


            return $this->Paginator;

            /* Paginação do admin */
            /*
            $this->PaginatorAdmin =  "<div class='row' id='paging-admin'>
                <div class='col-sm-5'>
                    <div class='dataTables_info dataTables_info_paging' id='data-list_info' role='status' aria-live='polite'>
                        Mostrando 31 até {$this->Limit} de {$this->Rows} resultados
                    </div>
                </div>
                <div class='col-sm-7'>
                    <div class='dataTables_paginate paging_simple_numbers' id='data-list_paginate'>
                        <ul class='pagination'>";

            ($this->Page != 1 ? $this->PaginatorAdmin .= "<li class='paginate_button previous' id='data-list_previous'><a title=\"{$this->First}\" href=\"{$this->Link}".($this->Page-1)."\" aria-controls='data-list' data-dt-idx='0' tabindex='0'>{$this->First}</a></li>" : "");
            ($this->Page != 1 && (($this->Page - ($MaxLinks + 1)) >= 1) ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"Página 1\" href=\"{$this->Link}1\" >1...</a></li>" : "");
            for ($iPag = $this->Page - $MaxLinks; $iPag <= $this->Page - 1; $iPag ++):
                if ($iPag >= 1):
                    $this->PaginatorAdmin .= "<li class='paginate_button'><a aria-controls='data-list' data-dt-idx='1' tabindex='0' title=\"Página {$iPag}\" href=\"{$this->Link}{$iPag}\">{$iPag}</a></li>";
                endif;
            endfor;

            $this->PaginatorAdmin .= "<li class='paginate_button active'><a>{$this->Page}</a></li>";

            for ($dPag = $this->Page + 1; $dPag <= $this->Page + $MaxLinks; $dPag ++):
                if ($dPag <= $Paginas):
                    $this->PaginatorAdmin .= "<li class='paginate_button'><a aria-controls='data-list' data-dt-idx='1' tabindex='0' title=\"Página {$dPag}\" href=\"{$this->Link}{$dPag}\">{$dPag}</a></li>";
                endif;
            endfor;

            ($Paginas != ($dPag - $MaxLinks) && $dPag < $Paginas ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"{$Paginas}\" href=\"{$this->Link}{$Paginas}\" >...{$Paginas}</a></li>" : "");
            ($this->Page != $Paginas ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"Página {$this->Last}\" href=\"{$this->Link}".($this->Page+1)."\" >{$this->Last}</a></li>" : "");
            $this->PaginatorAdmin .= "</ul>
                        </div>
                    </div>
                </div>";
            */

        endif;
    }

    private function getSyntaxAdmin($Param){

        $read = new Read;

        if($this->Tabela == null):
            $read->fullRead($this->Termos, $this->Places);
        else:
             $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        endif;

        $this->Rows = $read->getRowCount();


        if ($this->Rows > $this->Limit):
            $Paginas = ceil($this->Rows / $this->Limit);
            $this->nPages = $Paginas;
            $MaxLinks = $this->MaxLinks;

            /* Paginação do admin */

            $this->PaginatorAdmin =  "<div class='row' id='paging-admin'>
                <div class='col-sm-5'>
                    <div class='dataTables_info dataTables_info_pg' id='data-list_info' role='status' aria-live='polite'>
                        Mostrando {$this->Offset} até ".($this->Offset+$this->Limit)." de {$this->Rows} resultados
                    </div>
                </div>
                <div class='col-sm-7'>
                    <div class='dataTables_paginate paging_simple_numbers' id='data-list_paginate'>
                        <ul class='pagination'>";

            ($this->Page != 1 ? $this->PaginatorAdmin .= "<li class='paginate_button previous' id='data-list_previous'><a title=\"{$this->First}\" href=\"{$this->Link}".($this->Page-1).($Param != null ? "&".$Param : "")."\" aria-controls='data-list' data-dt-idx='0' tabindex='0'>{$this->First}</a></li>" : "");
            ($this->Page != 1 && (($this->Page - ($MaxLinks + 1)) >= 1) ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"Página 1\" href=\"{$this->Link}1".($Param != null ? "&".$Param : "")."\" >1...</a></li>" : "");
            for ($iPag = $this->Page - $MaxLinks; $iPag <= $this->Page - 1; $iPag ++):
                if ($iPag >= 1):
                    $this->PaginatorAdmin .= "<li class='paginate_button'><a aria-controls='data-list' data-dt-idx='1' tabindex='0' title=\"Página {$iPag}\" href=\"{$this->Link}{$iPag}".($Param != null ? "&".$Param : "")."\">{$iPag}</a></li>";
                endif;
            endfor;

            $this->PaginatorAdmin .= "<li class='paginate_button active'><a>{$this->Page}</a></li>";

            for ($dPag = $this->Page + 1; $dPag <= $this->Page + $MaxLinks; $dPag ++):
                if ($dPag <= $Paginas):
                    $this->PaginatorAdmin .= "<li class='paginate_button'><a aria-controls='data-list' data-dt-idx='1' tabindex='0' title=\"Página {$dPag}\" href=\"{$this->Link}{$dPag}".($Param != null ? "&".$Param : "")."\">{$dPag}</a></li>";
                endif;
            endfor;

            ($Paginas != ($dPag - $MaxLinks) && $dPag < $Paginas ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"{$Paginas}\" href=\"{$this->Link}{$Paginas}".($Param != null ? "&".$Param : "")."\" >...{$Paginas}</a></li>" : "");
            ($this->Page != $Paginas ? $this->PaginatorAdmin .= "<li class='paginate_button next' id='data-list_next'><a aria-controls='data-list' data-dt-idx='8' tabindex='0' title=\"Página {$this->Last}\" href=\"{$this->Link}".($this->Page+1)."".($Param != null ? "&".$Param : "")."\" >{$this->Last}</a></li>" : "");
            $this->PaginatorAdmin .= "</ul>
                        </div>
                    </div>
                </div>";

        endif;
    }

}
