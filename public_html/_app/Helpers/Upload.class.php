<?php
class Upload {
    private string $base;
    private string|false $result = false;
    private ?string $error = null;
    public function __construct($base = 'arquivos') {
        if (trim($base, '/') !== 'arquivos') throw new InvalidArgumentException('Invalid upload directory.');
        $this->base = 'arquivos';
    }
    public function getResult() { return $this->result; }
    public function getError() { return $this->error; }
    public function Image(array $file, $name = null, $width = null, $folder = null) { $this->store($file, $folder ?: 'imagens', 10, true, $width ?: 1920); }
    public function File(array $file, $name = null, $folder = null, $max = null) { $this->store($file, $folder ?: 'documentos', min((int) ($max ?: 10), 20), false); }
    public function Media(array $file, $name = null, $folder = null, $max = null) { $this->result = false; $this->error = 'Envio de mídia não habilitado. Use imagens JPG/PNG ou documentos PDF.'; }
    private function store(array $file, string $folder, int $max, bool $image, int $width = 1920): void {
        $this->result = false;
        try {
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])) throw new RuntimeException('Selecione um arquivo válido.');
            $tmp = $file['tmp_name'];
            if (filesize($tmp) < 1 || filesize($tmp) > $max * 1024 * 1024) throw new RuntimeException('O arquivo excede o limite de tamanho.');
            $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
            $allowed = $image ? ['jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'png'=>'image/png'] : ['pdf'=>'application/pdf'];
            if (!isset($allowed[$extension]) || $allowed[$extension] !== $mime) throw new RuntimeException('Formato de arquivo não permitido.');
            $folder = trim($folder, '/');
            if (!preg_match('#^[a-zA-Z0-9_-]+$#D', $folder)) throw new RuntimeException('Destino de upload inválido.');
            $relative = $this->base . '/' . $folder . '/' . date('Y/m');
            $directory = DIR . $relative;
            if (!is_dir($directory) && !mkdir($directory, 0750, true)) throw new RuntimeException('Não foi possível preparar o upload.');
            $base = realpath(DIR . $this->base);
            if (!str_starts_with(realpath($directory) . DIRECTORY_SEPARATOR, $base . DIRECTORY_SEPARATOR)) throw new RuntimeException('Destino de upload inválido.');
            $relative .= '/' . bin2hex(random_bytes(24)) . '.' . ($extension === 'jpeg' ? 'jpg' : $extension);
            $destination = DIR . $relative;
            if ($image) {
                if (!function_exists('imagecreatefromstring')) throw new RuntimeException('Processamento de imagens indisponível.');
                $size = getimagesize($tmp);
                if (!$size || $size[0] < 1 || $size[1] < 1 || $size[0] * $size[1] > 16000000) throw new RuntimeException('Dimensões da imagem não permitidas.');
                $source = imagecreatefromstring(file_get_contents($tmp));
                if (!$source) throw new RuntimeException('Imagem inválida.');
                $w = max(1, min($size[0], $width, 1920)); $h = max(1, (int) round($w * $size[1] / $size[0]));
                $target = imagecreatetruecolor($w, $h);
                imagealphablending($target, false); imagesavealpha($target, true);
                imagecopyresampled($target, $source, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
                $ok = $mime === 'image/png' ? imagepng($target, $destination, 8) : imagejpeg($target, $destination, 90);
                unset($source, $target);
            } else $ok = move_uploaded_file($tmp, $destination);
            if (!$ok) throw new RuntimeException('Não foi possível salvar o arquivo.');
            chmod($destination, 0640);
            $this->result = $relative; $this->error = null;
        } catch (Throwable $e) { $this->error = $e instanceof RuntimeException ? $e->getMessage() : 'Falha no processamento do arquivo.'; }
    }
}
