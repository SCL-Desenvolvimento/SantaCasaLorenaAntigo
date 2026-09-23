<?php
function scl_private_directory(): string {
    $directory = getenv('SCL_PRIVATE_DIR');
    if (!$directory) throw new RuntimeException('Private storage is not configured.');
    if (!is_dir($directory) && !mkdir($directory, 0750, true)) throw new RuntimeException('Private storage unavailable.');
    $real = realpath($directory);
    $documentRoot = realpath(DIR);
    if (!$documentRoot) throw new RuntimeException('Document root unavailable.');
    $root = rtrim(str_replace('\\', '/', $documentRoot), '/') . '/';
    if (!$real || str_starts_with(strtolower(str_replace('\\', '/', $real) . '/'), strtolower($root))) throw new RuntimeException('Private storage must be outside the document root.');
    return $real;
}
function scl_resume_path(string $stored): string|false {
    if (preg_match('#^private/curriculuns/([a-f0-9]{48}\.pdf)$#D', $stored, $match)) {
        $base = scl_private_directory() . DIRECTORY_SEPARATOR . 'curriculuns';
        $file = $base . DIRECTORY_SEPARATOR . $match[1];
    } elseif (preg_match('#^arquivos/curriculuns/((?:[a-zA-Z0-9_-]+/)*[^/\\\\]+\.pdf)$#iD', $stored, $match)) {
        $base = DIR . 'arquivos/curriculuns';
        if (getenv('SCL_PRIVATE_DIR')) {
            $migrated = scl_private_directory() . '/legacy/curriculuns';
            if (is_file($migrated . '/' . $match[1])) $base = $migrated;
        }
        $file = $base . '/' . $match[1];
    } else return false;
    $realBase = realpath($base);
    $real = realpath($file);
    if (!$real || !$realBase || !is_file($real) || !str_starts_with(strtolower($real), strtolower($realBase . DIRECTORY_SEPARATOR))) return false;
    if ((new finfo(FILEINFO_MIME_TYPE))->file($real) !== 'application/pdf') return false;
    return $real;
}
