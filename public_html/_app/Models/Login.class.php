<?php
class Login {
    private int $Nivel;
    private bool $Resultado = false;
    private array $Erro = [];
    public function __construct($nivel) { $this->Nivel = (int) $nivel; }
    public function ExeLogin(array $data) {
        $this->Resultado = scl_authenticate((string) ($data['usuario'] ?? ''), (string) ($data['senha'] ?? ''));
        $this->Erro = ['', $this->Resultado ? 'Acesso autorizado.' : 'Não foi possível entrar. Confira seus dados ou tente novamente mais tarde.', $this->Resultado ? System_ACCEPT : System_ALERT];
    }
    public function GetErro() { return $this->Erro; }
    public function GetResultado() { return $this->Resultado; }
    public function GetNivel() { return $this->Nivel; }
    public function CheckLogin() { $user = scl_admin_user(); return $user && (int) $user['nivel'] >= $this->Nivel; }
    public function CheckLogista($id) { return false; }
}
