<?php
/** Local-only component verification. No production database and no writes. */
if(PHP_SAPI!=='cli-server'||!in_array($_SERVER['REMOTE_ADDR']??'', ['127.0.0.1','::1'],true)){http_response_code(404);exit;}
$project=dirname(__DIR__);
$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
if(str_starts_with($path,'/resources/')&&preg_match('/\.(js|css|woff2?|ttf|png|jpe?g|svg|ico)$/i',$path)&&!str_contains(rawurldecode($path),'..'))return false;
require __DIR__.'/security-db.php';
Conn::$db=new SecurityTestPDO();security_fixture(Conn::$db);
require __DIR__.'/admin-db.php';admin_fixture(Conn::$db);
require $project.'/includes/security.php';scl_security_boot();
if($path==='/__preview-login'){
    scl_login_session(Conn::$db->query('SELECT * FROM scl_usuario WHERE id_usuario=1')->fetch());
    header('Location: /admin/painel.php');exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'&&!preg_match('/^(get|list|Validar)/i',(string)($_POST['acao']??''))){scl_deny();}
class Read {
    private array $result=[];
    public function ExeRead($table,$terms=null,$params=null){$this->fullRead('SELECT * FROM '.$table.' '.$terms,$params);}
    public function getResult(){return $this->result;}
    public function getRowCount(){return count($this->result);}
    public function fullRead($sql,$params=null){
        if(str_contains($sql,'COUNT(*) AS total')){$this->result=[['total'=>1]];return;}
        $fixture=['id_usuario'=>1,'nome'=>'Registro de demonstração','email'=>'fixture@example.invalid','usuario'=>'admin','status'=>1,'data_cadastro'=>'2026-09-23 10:00:00','data_formatada'=>'23/09/2026 às 10h00','data_criacao'=>'2026-09-23 10:00:00','data_alteracao'=>'2026-09-23 10:00:00','titulo'=>'Conteúdo de demonstração','subtitulo'=>'Resumo para homologação local','descricao'=>'<p>Texto preservado com <strong>destaque</strong>, <a href="https://example.invalid">link</a> e tabela.</p><table><tr><td>Exemplo</td></tr></table>','link'=>'demonstracao','img'=>'resources/img/icon-logo.png','url'=>'resources/img/icon-logo.png','pdf'=>'','tipo'=>'image/jpeg','id_banner'=>1,'id_noticia'=>1,'id_galeria'=>1,'id_anexo'=>1,'id_trabalhe_conosco'=>1,'id_ouvidoria'=>1,'id_doacoes'=>1,'id_contato'=>1,'id_tag'=>1,'assunto'=>'Demonstração','cidade'=>'Lorena','mensagem'=>'Mensagem fictícia','curriculum'=>'private/curriculuns/fixture.pdf'];
        if(preg_match('/FROM\s+scl_(\w+)/i',$sql,$match)){
            $table=$match[1];
            if($table==='paginas'){$this->result=[];return;}
            if(str_starts_with($table,'pagina_')){$fixture+=['bloco1'=>'<p>Conteúdo de demonstração.</p>','bloco2'=>'','bloco3'=>'','bloco4'=>'','missao'=>'Missão','visao'=>'Visão','valor'=>'Valores','provedor'=>'Provedor','localizacao'=>'Lorena','telefone'=>'(12) 0000-0000'];}
            if(str_starts_with($table,'galeria_'))$fixture['id_'.$table]=1;
        }
        $this->result=[$fixture];
    }
}
$file=realpath($project.$path);
if(!$file||!str_starts_with($file,$project.DIRECTORY_SEPARATOR)||!str_starts_with($path,'/admin/')||pathinfo($file,PATHINFO_EXTENSION)!=='php'){http_response_code(404);exit;}
$_SERVER['SCRIPT_FILENAME']=$file;chdir(dirname($file));require $file;
