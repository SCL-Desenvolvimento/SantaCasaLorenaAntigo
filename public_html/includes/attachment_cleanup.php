<?php
/** Delete only upload files belonging to removed records and no longer referenced. */
function scl_attachment_candidates(array $rows): array {
    $paths = [];
    array_walk_recursive($rows, function ($value) use (&$paths) {
        if (!is_string($value)) return;
        $value = html_entity_decode(str_replace('\\/', '/', $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        preg_match_all('~(?:arquivos|private/curriculuns)/[^\s<>"\'?#]+~u', $value, $matches);
        foreach ($matches[0] as $path) $paths[] = rawurldecode($path);
    });
    return array_values(array_unique($paths));
}

function scl_attachment_path(string $path): string|false {
    if (str_contains($path, "\0") || str_contains($path, '\\') || preg_match('~(?:^|/)\.{1,2}(?:/|$)~', $path)) return false;
    if (str_starts_with($path, 'private/curriculuns/') || str_starts_with($path, 'arquivos/curriculuns/')) {
        require_once __DIR__ . '/private_files.php';
        return scl_resume_path($path);
    }
    if (!str_starts_with($path, 'arquivos/') || !preg_match('~\.(?:jpe?g|png|gif|webp|avif|pdf|docx?|xlsx?|pptx?|zip|mp4|webm|mp3|ogg)$~iD', $path)) return false;
    $base = realpath(DIR . 'arquivos');
    $file = realpath(DIR . $path);
    if (!$base || !$file || !is_file($file) || !str_starts_with(strtolower($file), strtolower($base . DIRECTORY_SEPARATOR))) return false;
    // Refuse symlink/junction traversal, even if its target is another upload.
    $part = DIR;
    foreach (explode('/', $path) as $segment) {
        $part = rtrim($part, '/\\') . DIRECTORY_SEPARATOR . $segment;
        if (is_link($part)) return false;
    }
    return $file;
}

function scl_attachment_columns(PDO $db): array {
    $columns = [];
    if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
        $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            if (!str_starts_with($table, PREFIX) || !preg_match('/^[a-zA-Z0-9_]+$/D', $table)) continue;
            foreach ($db->query('PRAGMA table_info(`' . $table . '`)')->fetchAll(PDO::FETCH_ASSOC) as $column) {
                if (preg_match('/char|text|clob|^$/i', $column['type'])) $columns[$table][] = $column['name'];
            }
        }
    } else {
        $rows = $db->query("SELECT TABLE_NAME,COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND DATA_TYPE IN ('char','varchar','tinytext','text','mediumtext','longtext','json')")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) if (str_starts_with($row['TABLE_NAME'], PREFIX)) $columns[$row['TABLE_NAME']][] = $row['COLUMN_NAME'];
    }
    return $columns;
}

function scl_attachment_referenced(PDO $db, string $path, array $columns): bool {
    // Basename also catches absolute URLs, encoded URLs and HTML/JSON references.
    $name = basename($path);
    foreach ($columns as $table => $fields) {
        foreach ($fields as $field) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/D', $table . $field)) throw new RuntimeException('Invalid attachment reference schema.');
            $q = $db->prepare("SELECT 1 FROM `$table` WHERE `$field` LIKE ? OR `$field` LIKE ? LIMIT 1");
            $q->execute(['%' . $name . '%', '%' . rawurlencode($name) . '%']);
            if ($q->fetchColumn()) return true;
        }
    }
    return false;
}

function scl_cleanup_attachments(PDO $db, array $paths): void {
    if ($db->inTransaction()) return; // Never remove files from an uncommitted operation.
    try {
        $columns = scl_attachment_columns($db);
        foreach (array_unique($paths) as $path) {
            $file = scl_attachment_path($path);
            if (!$file || scl_attachment_referenced($db, $path, $columns)) continue;
            if (!unlink($file)) error_log('SCL: unable to remove an unreferenced attachment.');
        }
    } catch (Throwable $e) {
        // When a reference cannot be checked, preserve the file.
        error_log('SCL: attachment cleanup could not verify references.');
    }
}

function scl_queue_attachment_cleanup(PDO $db, array $rows): void {
    $paths = scl_attachment_candidates($rows);
    if (!$paths) return;
    register_shutdown_function(function () use ($db, $paths) { scl_cleanup_attachments($db, $paths); });
}

/** Remove attachment metadata only when no gallery still uses it. */
function scl_release_gallery_attachments(PDO $db, array $ids): array {
    $rows = [];
    foreach (array_unique($ids) as $id) {
        $q = $db->prepare('SELECT * FROM ' . PREFIX . 'anexo WHERE id_anexo=? AND NOT EXISTS (SELECT 1 FROM ' . PREFIX . 'galeria_anexo WHERE id_anexo=?)');
        $q->execute([(int)$id, (int)$id]);
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if (!$row) continue;
        $rows[] = $row;
        $q = $db->prepare('DELETE FROM ' . PREFIX . 'anexo WHERE id_anexo=?');
        $q->execute([(int)$id]);
    }
    return $rows;
}
