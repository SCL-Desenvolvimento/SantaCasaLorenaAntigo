<?php

class Create extends Conn{
		
	private $Tabela;
	private $Dados;
	private $Result;
	private $Create;
	private $Conn;

	public function ExeCreate($Tabela, array $Dados){
		$this->Tabela = (String) $Tabela;
		$this->Dados = $Dados; 
		
		$this->getSyntax();
		$this->Execute();
	}
	
	public function getResult(){
		return $this->Result;
	}
	
	private function Connect(){
		$this->Conn = parent::getConn();
		$this->Create = $this->Conn->prepare($this->Create);
	}

	private function getSyntax(){
		$Filds = implode(', ', array_keys($this->Dados));
		$Places = ':'.implode(', :', array_keys($this->Dados));
		$this->Create = "INSERT INTO {$this->Tabela} ({$Filds}) VALUES ({$Places})";
	}
	
	private function Execute(){
		$this->Connect();
		try{
			$this->Create->execute($this->Dados);
			$this->Result = $this->Conn->lastInsertId();
		}catch (PDOException $e){
			$this->Result = null;
			SystemErro("", "<b> Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
		}
	}
	
} 

?>