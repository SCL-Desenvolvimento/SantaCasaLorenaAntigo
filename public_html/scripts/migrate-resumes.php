<?php
/** Copy legacy resumes to external private storage; leave originals intact. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require DIR . 'includes/private_files.php';
$copy = in_array('--copy', $argv, true);
$source = realpath(DIR . 'arquivos/curriculuns');
if (!$source) throw new RuntimeException('Legacy resume directory not found.');
$target = scl_private_directory() . '/legacy/curriculuns';
$count = $bytes = $existing = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS)) as $file) {
    if ($file->isLink()) throw new RuntimeException('Unexpected symbolic link.');
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'pdf') continue;
    $relative = substr($file->getPathname(), strlen($source) + 1);
    $destination = $target . '/' . $relative;
    ++$count; $bytes += $file->getSize();
    if (is_file($destination)) {
        if (!hash_equals(hash_file('sha256', $file->getPathname()), hash_file('sha256', $destination))) throw new RuntimeException('Destination contains a conflicting file. Nothing was overwritten.');
        ++$existing; continue;
    }
    if (!$copy) continue;
    if (!is_dir(dirname($destination)) && !mkdir(dirname($destination), 0750, true)) throw new RuntimeException('Private directory unavailable.');
    $input = fopen($file->getPathname(), 'rb'); $output = fopen($destination, 'xb');
    if (!$input || !$output || stream_copy_to_stream($input, $output) !== $file->getSize()) throw new RuntimeException('Copy failed.');
    fclose($input); fclose($output); chmod($destination, 0640);
    if (!hash_equals(hash_file('sha256', $file->getPathname()), hash_file('sha256', $destination))) throw new RuntimeException('Copy verification failed.');
}
echo json_encode(['mode'=>$copy?'copied':'dry-run','files'=>$count,'bytes'=>$bytes,'already_verified'=>$existing], JSON_PRETTY_PRINT) . PHP_EOL;
