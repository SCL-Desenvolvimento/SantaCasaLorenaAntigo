<?php

class Login{
	private $Nivel;
	private $Login;
	private $Senha;
	private $Erro;
	private $Resultado;

	function __construct($Nivel){
		$this->Nivel = (int) $Nivel;
	}

	public function ExeLogin(array $DadosLogin){
		$this->Login = (string) strip_tags(trim($DadosLogin['usuario']));
		$this->Senha = (string) strip_tags(trim($DadosLogin['senha']));
		$this->SetLogin();
	}

	public function GetErro(){
		return $this->Erro;
	}

	public function GetResultado(){
		return $this->Resultado;
	}

	public function GetNivel(){
		return $this->Nivel;
	}

	public function CheckLogin(){
		if(empty($_SESSION['UsuarioLogin']) || $_SESSION['UsuarioLogin']['nivel'] < $this->Nivel || $_SESSION['UsuarioLogin']['status'] == 0):
			unset($_SESSION['UsuarioLogin']);
			return false;
		else:
			return true;
		endif;
	}

	public function CheckLogista($id_loja){

		$read = new Read;
		$read->fullRead("SELECT L.* FROM ".PREFIX."loja AS L
                        	INNER JOIN ".PREFIX."acesso_lojista AS AL ON ((AL.id_usuario = {$_SESSION['UsuarioLogin']['id_usuario']} AND AL.status = 1) AND AL.id_loja = L.id_loja)
							WHERE L.id_loja = :id_loja", "id_loja={$id_loja}");

		if($read->getResult()):
			return true;
		else:
			return false;
		endif;
	}

	private function SetLogin(){
		if(!$this->Login || !$this->Senha):
			$this->Erro = ["", 'Informe seu Login e Senha.',System_ALERT];
			return false;
		elseif(!$this->GetUsuario()):
			$this->Erro = ["", 'Os dados informados estão errados ou não conferem com nenhum usuário.',System_ALERT];
			return false;
		elseif($this->Resultado['nivel'] < $this->Nivel):
			$this->Erro = ["", "Você não tem acesso a esta área",System_ALERT];
			return false;
		else:
			$this->Execute();
		endif;
	}

	private function GetUsuario(){
		
		$this->Senha = md5($this->Senha);

		$read = new Read();
			$read->ExeRead(PREFIX."usuario", "WHERE usuario = :u AND senha = :s","u={$this->Login}&s={$this->Senha}");
			if($read->getResult()):
				$this->Resultado = $read->getResult()[0];
				$this->Resultado['nivel'] = (int) $this->Resultado['nivel'];
				return true;
			else:
				return false;
			endif;
	}

	private function Execute()
	{
		if(!session_id()):
			session_start();
		endif;
		$_SESSION['UsuarioLogin'] = $this->Resultado;
		$this->Erro = ["", "Olá {$this->Resultado['nome']}{$this->Resultado['nome_fantasia']}, seja bem vindo. Aguarde redirecionamento.",System_ACCEPT];
		$this->Resultado = true;
	}

}