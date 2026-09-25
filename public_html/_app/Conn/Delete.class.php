<?php


class Delete extends Conn {
	
	private $Tabela;
	private $Termos;
	private $Places;
	private $Result;
    private $Delete;
    private $Conn;

    public function ExeDelete($Tabela, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        parse_str($ParseString, $this->Places);
        
        $this->getSyntax();
        $this->Execute();
    }

    
    public function getResult() {
        return $this->Result;
    }

   
    public function getRowCount() {
        return $this->Delete->rowCount();
    }

   
    public function setPlaces($ParseString) {
    	parse_str($ParseString, $this->Places);
    	$this->getSyntax();
    	$this->Execute();
    }

    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    private function getSyntax() {
       $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    private function Execute() {
        $this->Connect();
        require_once dirname(__DIR__, 2) . '/includes/attachment_cleanup.php';
        $ownsTransaction = !$this->Conn->inTransaction();
        try {
            if ($ownsTransaction) $this->Conn->beginTransaction();
            $snapshot = $this->Conn->prepare("SELECT * FROM {$this->Tabela} {$this->Termos}");
            $snapshot->execute($this->Places);
            $removed = $snapshot->fetchAll(PDO::FETCH_ASSOC);
            $children = [
                PREFIX . 'unidade_internacao' => ['unidade_internacao_imagem', 'id_unidade_internacao'],
                PREFIX . 'capacidade' => ['capacidade_imagem', 'id_capacidade'],
                PREFIX . 'noticia' => ['tag_noticia', 'id_noticia'],
            ];
            if (isset($children[$this->Tabela])) {
                [$child, $key] = $children[$this->Tabela];
                foreach ($removed as $row) {
                    $query = $this->Conn->prepare('SELECT * FROM ' . PREFIX . $child . " WHERE $key=?");
                    $query->execute([$row[$key]]);
                    $removed = array_merge($removed, $query->fetchAll(PDO::FETCH_ASSOC));
                    $query = $this->Conn->prepare('DELETE FROM ' . PREFIX . $child . " WHERE $key=?");
                    $query->execute([$row[$key]]);
                }
            }
        	$this->Delete->execute($this->Places);
            if ($ownsTransaction) $this->Conn->commit();
            scl_queue_attachment_cleanup($this->Conn, $removed);
        	$this->Result = true;
        } catch (PDOException $e) {
            if ($ownsTransaction && $this->Conn->inTransaction()) $this->Conn->rollBack();
            $this->Result = null;
            error_log('SCL: database operation failed.');
        }
    }

}
