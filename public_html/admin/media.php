<?php
require __DIR__ . '/../_app/Config.inc.php';
scl_admin_require();
$picker = (string) ($_GET['picker'] ?? '');
if (!preg_match('/^[a-zA-Z0-9-]{1,80}$/D', $picker)) $picker = '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload = new Upload('arquivos');
    $file = $_FILES['arquivo'] ?? [];
    if (($_POST['type'] ?? '') === 'pdf') $upload->File($file, null, 'documentos');
    else $upload->Image($file, null, 1920, 'editor');
    $message = $upload->getResult() ? 'Arquivo enviado. Selecione-o na lista abaixo.' : $upload->getError();
}
$files = [];
foreach (['editor', 'documentos', 'noticia', 'banner', 'imagens'] as $folder) {
    $base = DIR . 'arquivos/' . $folder;
    if (!is_dir($base)) continue;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)) as $file) {
        if ($file->isLink() || !$file->isFile() || !preg_match('/\.(jpe?g|png|pdf)$/iD', $file->getFilename())) continue;
        $relative = str_replace('\\', '/', substr($file->getPathname(), strlen(DIR)));
        $files[] = ['path' => $relative, 'time' => $file->getMTime()];
    }
}
usort($files, fn($a, $b) => $b['time'] <=> $a['time']);
function media_escape($text) { return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Arquivos do site</title><style>body{font:16px system-ui;color:#19393d;max-width:900px;margin:auto;padding:24px}form{background:#eef4f5;padding:20px;border-radius:12px}label{display:block;margin-bottom:12px}button{padding:10px;cursor:pointer}ul{padding:0;list-style:none;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px}li button{width:100%;height:180px;background:white;border:1px solid #aab;border-radius:8px;overflow-wrap:anywhere}img{display:block;max-width:100%;height:120px;object-fit:contain;margin:auto}small{font-size:11px}</style></head><body><h1>Arquivos do site</h1>
<?php if ($message): ?><p role="status"><?= media_escape($message) ?></p><?php endif ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="_csrf" value="<?= media_escape(scl_csrf_token()) ?>"><label>Tipo <select name="type"><option value="image">Imagem JPG ou PNG</option><option value="pdf">Documento PDF</option></select></label><label>Arquivo de até 10 MB <input type="file" name="arquivo" accept=".jpg,.jpeg,.png,.pdf" required></label><button>Enviar arquivo</button></form>
<p>Selecione um dos 200 arquivos mais recentes para inserir no conteúdo.</p><ul>
<?php foreach (array_slice($files, 0, 200) as $file): $url = ROOT . $file['path']; ?>
<li><button type="button" data-url="<?= media_escape($url) ?>"><?php if (!str_ends_with($url, '.pdf')): ?><img src="<?= media_escape($url) ?>" alt="" loading="lazy"><?php else: ?>Documento PDF<br><?php endif ?><small><?= media_escape(basename($url)) ?></small></button></li>
<?php endforeach ?></ul><script>
document.addEventListener('click', function (event) {
  var button = event.target.closest('[data-url]');
  if (!button || !window.opener || window.opener.location.origin !== location.origin) return;
  window.opener.postMessage({type:'scl-media',id:<?= json_encode($picker) ?>,url:button.dataset.url},location.origin);
  window.close();
});
</script></body></html>
