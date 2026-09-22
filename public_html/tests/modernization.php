<?php
/** Offline regression checks. Run: php tests/modernization.php */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
error_reporting(E_ALL);
set_error_handler(function ($level, $message, $file, $line) {
    throw new ErrorException($message, 0, $level, $file, $line);
});
define('ROOT', '/hospital/');
define('HOME', '/hospital/');
define('DIR', dirname(__DIR__) . '/');
define('PREFIX', 'scl_');
require DIR . 'includes/ui.php';
require DIR . '_app/Helpers/Check.class.php';
require DIR . '_app/Models/Url.class.php';
class Read {
    public static $fixtures = array();
    public static $queries = array();
    private $result = array();
    public function fullRead($sql, $params = '') {
        self::$queries[] = array('sql'=>$sql, 'params'=>$params);
        parse_str($params ?? '', $values);
        $this->result = array();
        if (str_contains($sql, PREFIX.'unidade_internacao_imagem ')) {
            $this->result = array_values(array_filter(self::$fixtures['unidade_internacao_imagem'] ?? array(), fn($row)=>(string)$row['id_unidade_internacao'] === (string)($values['unit'] ?? ''))); return;
        }
        if (str_contains($sql, PREFIX.'capacidade_imagem ')) {$this->result=array_values(array_filter(self::$fixtures['capacidade_imagem'] ?? array(),fn($row)=>(string)$row['id_capacidade']===(string)($values['unit'] ?? '')));return;}
        if(str_contains($sql,'AS listing_total')){$this->result=self::$fixtures['listing_total'] ?? array();return;}
        if(str_contains($sql,'SELECT DISTINCT T.nome')){$this->result=self::$fixtures['news_categories'] ?? array();return;}
        foreach (self::$fixtures as $table => $rows) {
            if (str_contains($sql, PREFIX . $table)) { $this->result = $rows; return; }
        }
        if (isset($values['sessao']) && in_array($values['sessao'], array('institucional', 'servicos', 'instalacoes'), true)) {
            $this->result = array(array('sessao_url' => $values['sessao']));
        } elseif (isset($values['url_amigavel'])) {
            $this->result = array(array('titulo'=>'Página de teste', 'sessao'=>'Institucional', 'sub_titulo'=>'Subtítulo', 'descricao'=>'Descrição', 'img_principal'=>'', 'seo'=>''));
        }
    }
    public function getResult() {return $this->result;}
}
class Create {
    public static $data;
    public static $table;
    public function ExeCreate($table, $data) { self::$data = $data; self::$table = $table; }
    public function getResult() {return 1;}
}
$count = 0;
function verify($condition, $message) {
    global $count;
    if (!$condition) throw new RuntimeException($message);
    $count++;
}
verify(scl_escape('<script>"&') === '&lt;script&gt;&quot;&amp;', 'Escape HTML');
verify(scl_url('servicos/convenios') === '/hospital/servicos/convenios', 'Subdirectory URL');
verify(scl_link('javascript:alert(1)') === '', 'Reject executable links');
verify(scl_link('//example.com') === '', 'Reject protocol-relative links');
verify(scl_link('https://example.com') === 'https://example.com', 'Allow HTTPS links');
verify(scl_link('noticias') === '/hospital/noticias', 'Normalize CMS links');
verify(Check::validarCPF('52998224725') === true, 'CPF valid checksum');
verify(Check::validarCPF('52998224726') === false, 'CPF invalid checksum');
verify(Check::validarCNPJ('11222333000181') === true, 'CNPJ valid checksum');
verify(Check::validarCNPJ('11222333000182') === false, 'CNPJ invalid checksum');
verify((new Url())->setUrlAmigavel('') === null, 'Homepage route');
foreach (array('institucional/sobre-a-santa-casa'=>'sobre-a-santa-casa', 'servicos/convenios'=>'convenios', 'instalacoes/unidades-de-internacao'=>'unidades-de-internacao') as $route=>$page) {
    verify((new Url())->setUrlAmigavel($route)['page'] === $page, 'PHP 8 section routing: ' . $route);
}
verify((new Url())->setUrlAmigavel('inexistente')['page'] === '404', 'Unknown route');
$r_DIR = null;
$localizacao = array('localizacao'=>'Lorena', 'telefone'=>'(12) 3159-3349');
$_SESSION = array('newsletter_token' => str_repeat('a',64));
$_POST = array();
ob_start(); require DIR.'includes/header.php'; require DIR.'includes/navbar.php'; require DIR.'includes/home.php'; require DIR.'includes/footer.php'; $html = ob_get_clean();
verify(!str_contains($html, 'jQuery-2.1.4'), 'Homepage without legacy jQuery');
verify(!str_contains($html, 'bootstrap.min'), 'Homepage without Bootstrap');
verify(substr_count($html, '<h1>') === 1, 'One primary heading');
verify(str_contains($html, 'As novidades da Santa Casa'), 'News empty state');
verify(str_contains($html, 'name="newsletter_token"'), 'Newsletter CSRF token');
$_POST = array('newsletter'=>'test@example.org', 'newsletter_token'=>'invalid');
ob_start(); require DIR.'includes/footer.php'; $html=ob_get_clean();
verify(Create::$data === null && str_contains($html, 'Atualize a página'), 'Reject invalid CSRF token');
$_POST = array('newsletter'=>'invalid', 'newsletter_token'=>$_SESSION['newsletter_token']);
ob_start(); require DIR.'includes/footer.php'; $html=ob_get_clean();
verify(Create::$data === null && str_contains($html, 'e-mail válido'), 'Validate email server-side');
$_POST = array('newsletter'=>'test+hospital@example.org', 'newsletter_token'=>$_SESSION['newsletter_token'], 'unexpected'=>'value');
ob_start(); require DIR.'includes/footer.php'; $html=ob_get_clean();
verify(Create::$data['email'] === 'test+hospital@example.org' && count(Create::$data) === 2, 'Whitelist newsletter insert fields');
verify(str_contains($html, 'Cadastro realizado'), 'Success feedback');
Read::$fixtures = array(
    'noticia'=>array(array('titulo'=>'<script>alert(1)</script>', 'subtitulo'=>'<p>Resumo</p>', 'img'=>'resources/img/no-image.png', 'link'=>'teste', 'data_criacao'=>'2026-01-01')),
    'banner'=>array(array('img'=>'resources/img/no-image.png', 'titulo'=>'Campanha', 'link'=>'javascript:alert(1)')),
    'convenio'=>array(array('img'=>'resources/img/no-image.png', 'nome'=>'Plano de teste'))
);
ob_start(); require DIR.'includes/home.php'; $html=ob_get_clean();
verify(!str_contains($html, '<script>alert(1)</script>') && str_contains($html, '&lt;script&gt;'), 'Escape CMS news titles');
verify(!str_contains($html, 'javascript:'), 'Do not render unsafe campaign links');
verify(str_contains($html, 'Plano de teste'), 'Render live plan records');
echo "OK: $count checks, no database connection.\n";
