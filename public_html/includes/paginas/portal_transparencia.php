<?php
/**
 * Portal da Transparência
 *
 * Os documentos recebidos são mantidos na estrutura original do arquivo ZIP.
 * A listagem é montada a partir das pastas para facilitar futuras inclusões.
 */

$tpPageDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'transparencia';
$tpArchiveDirectory = $tpPageDirectory . DIRECTORY_SEPARATOR . 'documentos';
$tpPageUrl = rtrim(ROOT, '/') . '/includes/paginas/transparencia/';
$tpArchiveUrl = $tpPageUrl . 'documentos/';

if (!function_exists('tpEscape')) {
    function tpEscape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('tpEncodedPath')) {
    function tpEncodedPath($path)
    {
        $parts = explode('/', str_replace('\\', '/', $path));
        $encoded = array();

        foreach ($parts as $part) {
            $encoded[] = rawurlencode($part);
        }

        return implode('/', $encoded);
    }
}

if (!function_exists('tpHumanSize')) {
    function tpHumanSize($bytes)
    {
        if (!$bytes || $bytes < 1024) {
            return ($bytes ? $bytes : 0) . ' B';
        }

        $units = array('KB', 'MB', 'GB');
        $size = $bytes / 1024;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return number_format($size, $size >= 10 ? 0 : 1, ',', '.') . ' ' . $units[$unit];
    }
}

if (!function_exists('tpDocumentLabel')) {
    function tpDocumentLabel($fileName)
    {
        $label = pathinfo($fileName, PATHINFO_FILENAME);
        $label = str_replace(array('_', '  '), array(' ', ' '), $label);
        $label = preg_replace('/\s+/', ' ', trim($label));

        if (function_exists('mb_convert_case')) {
            $label = mb_convert_case($label, MB_CASE_TITLE, 'UTF-8');
        } else {
            $label = strtr($label, array(
                'Á' => 'á', 'À' => 'à', 'Â' => 'â', 'Ã' => 'ã',
                'É' => 'é', 'Ê' => 'ê', 'Í' => 'í', 'Ó' => 'ó',
                'Ô' => 'ô', 'Õ' => 'õ', 'Ú' => 'ú', 'Ç' => 'ç'
            ));
            $label = ucwords(strtolower($label));
        }

        $replacements = array(
            '/\bConvenio\b/ui' => 'Convênio',
            '/\bRelatorio\b/ui' => 'Relatório',
            '/\bPlano De Trabalho\b/ui' => 'Plano de trabalho',
            '/\bTermo De Convenio\b/ui' => 'Termo de convênio',
            '/\bTermo De Fomento\b/ui' => 'Termo de fomento',
            '/\bTermo Aditivo\b/ui' => 'Termo aditivo',
            '/\bIrmandade Da Santa Casa De Misericordia De Lorena\b/ui' => 'Irmandade da Santa Casa de Misericórdia de Lorena',
            '/\bProc\b/ui' => 'Processo',
            '/\bSus\b/u' => 'SUS',
            '/\bTa\b/u' => 'TA',
            '/\bTf\b/u' => 'TF',
            '/\bPs\b/u' => 'PS',
            '/\bRx\b/u' => 'RX',
            '/\bCme\b/u' => 'CME',
            '/\bIac\b/u' => 'IAC',
            '/\bCv\b/u' => 'CV',
            '/\bAo\b/u' => 'ao'
        );

        foreach ($replacements as $pattern => $replacement) {
            $label = preg_replace($pattern, $replacement, $label);
        }

        return $label;
    }
}

if (!function_exists('tpDirectoryLabel')) {
    function tpDirectoryLabel($directoryName)
    {
        $normalized = str_replace(array('_', '  '), array(' ', ' '), trim($directoryName));
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        if (preg_match('/^CONVENIOS?\s+(20\d{2})$/iu', $normalized, $matches)) {
            return $matches[1];
        }

        // O caractere acentuado pode chegar em uma codificacao diferente da
        // pagina quando o nome vem do sistema de arquivos. A correspondencia
        // abaixo usa somente os trechos ASCII e sempre gera o rotulo em UTF-8.
        if (preg_match('/^CONV.*NIO\s+(\d+)[\.\s]+(20\d{2})$/i', $normalized, $matches)) {
            return 'Convênio ' . $matches[1] . '/' . $matches[2];
        }

        if (preg_match('/^CONV.*NIO\s+(\d+)(20\d{2})$/i', $normalized, $matches)) {
            return 'Convênio ' . $matches[1] . '/' . $matches[2];
        }

        if (stripos($normalized, 'PRO SC E SUSTENTAVEL') !== false) {
            return 'Pró Santa Casa e Santas Casas Sustentáveis';
        }

        if (stripos($normalized, 'PLANOS DE TRABALHO') !== false) {
            return 'Planos de trabalho';
        }

        if (stripos($normalized, 'TERMO DE FOMENTO') !== false) {
            return 'Termos de fomento';
        }

        return tpDocumentLabel($normalized);
    }
}

if (!function_exists('tpMunicipalDirectoryLabel')) {
    function tpMunicipalDirectoryLabel($directoryName)
    {
        if (preg_match('/\b(20\d{2})\b/u', $directoryName, $matches)) {
            return $matches[1];
        }

        return tpDirectoryLabel($directoryName);
    }
}

if (!function_exists('tpNormalizeKey')) {
    function tpNormalizeKey($value)
    {
        $value = strtr($value, array(
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ç' => 'c', 'Ç' => 'C'
        ));

        $value = function_exists('mb_strtoupper')
            ? mb_strtoupper($value, 'UTF-8')
            : strtoupper($value);

        return preg_replace('/[^A-Z0-9]+/', ' ', trim($value));
    }
}

if (!function_exists('tpIsFomentoDirectory')) {
    function tpIsFomentoDirectory($directoryName)
    {
        return strpos(tpNormalizeKey($directoryName), 'TERMO DE FOMENTO') !== false;
    }
}

if (!function_exists('tpIsDocumentFile')) {
    function tpIsDocumentFile($fileName)
    {
        return (bool) preg_match('/\.(?:pdf|docx?|xlsx?|ods|csv|pptx?|odt|rtf|txt|zip)$/i', $fileName);
    }
}

if (!function_exists('tpDocumentType')) {
    function tpDocumentType($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        return $extension ? strtoupper($extension) : 'ARQUIVO';
    }
}

if (!function_exists('tpDocumentIcon')) {
    function tpDocumentIcon($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return 'fa-file-pdf-o';
        }

        if (in_array($extension, array('doc', 'docx', 'odt', 'rtf'), true)) {
            return 'fa-file-word-o';
        }

        if (in_array($extension, array('xls', 'xlsx', 'ods', 'csv'), true)) {
            return 'fa-file-excel-o';
        }

        if (in_array($extension, array('ppt', 'pptx'), true)) {
            return 'fa-file-powerpoint-o';
        }

        return 'fa-file-o';
    }
}

if (!function_exists('tpDirectoryEntries')) {
    function tpDirectoryEntries($directory)
    {
        $directories = array();
        $files = array();

        if (!is_dir($directory)) {
            return array($directories, $files);
        }

        foreach (scandir($directory) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $fullPath = $directory . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($fullPath)) {
                $directories[] = $entry;
            } elseif (is_file($fullPath) && tpIsDocumentFile($entry)) {
                $files[] = $entry;
            }
        }

        natcasesort($directories);
        natcasesort($files);

        return array(array_values($directories), array_values($files));
    }
}

if (!function_exists('tpCountDocuments')) {
    function tpCountDocuments($directory)
    {
        if (!is_dir($directory)) {
            return 0;
        }

        $count = 0;
        list($directories, $files) = tpDirectoryEntries($directory);
        $count += count($files);

        foreach ($directories as $subdirectory) {
            $count += tpCountDocuments($directory . DIRECTORY_SEPARATOR . $subdirectory);
        }

        return $count;
    }
}

if (!function_exists('tpRenderDocument')) {
    function tpRenderDocument($absolutePath, $relativePath, $label)
    {
        global $tpArchiveUrl;

        $url = $tpArchiveUrl . tpEncodedPath($relativePath);
        $searchText = $label . ' ' . str_replace(array('/', '_'), ' ', $relativePath);
        $documentType = tpDocumentType($absolutePath);
        ?>
        <a class="tp-document" href="<?php echo tpEscape($url); ?>" target="_blank" rel="noopener"
           data-search="<?php echo tpEscape($searchText); ?>"
           aria-label="<?php echo tpEscape($label); ?>, abrir arquivo em nova aba">
            <span class="tp-document-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
            <span class="tp-document-text">
                <span class="tp-document-title"><?php echo tpEscape($label); ?></span>
                <span class="tp-document-meta"><?php echo tpEscape($documentType); ?> <span aria-hidden="true">&bull;</span> <?php echo tpEscape(tpHumanSize(filesize($absolutePath))); ?></span>
            </span>
            <span class="tp-external" aria-hidden="true">↗</span>
        </a>
        <?php
    }
}

if (!function_exists('tpRenderDirectory')) {
    function tpRenderDirectory($absolutePath, $relativePath, $label, $level)
    {
        $count = tpCountDocuments($absolutePath);

        if ($count === 0) {
            return;
        }

        list($directories, $files) = tpDirectoryEntries($absolutePath);
        $className = $level === 1 ? 'tp-year' : 'tp-subgroup';
        ?>
        <details class="tp-disclosure <?php echo $className; ?>">
            <summary>
                <span class="tp-summary-title"><?php echo tpEscape($label); ?></span>
                <span class="tp-summary-side">
                    <span class="tp-count"><?php echo $count; ?> <?php echo $count === 1 ? 'documento' : 'documentos'; ?></span>
                    <span class="tp-chevron" aria-hidden="true"></span>
                </span>
            </summary>
            <div class="tp-panel">
                <?php if (!empty($files)): ?>
                    <div class="tp-document-list">
                        <?php foreach ($files as $file): ?>
                            <?php tpRenderDocument(
                                $absolutePath . DIRECTORY_SEPARATOR . $file,
                                $relativePath . '/' . $file,
                                tpDocumentLabel($file)
                            ); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($directories as $directory): ?>
                    <?php tpRenderDirectory(
                        $absolutePath . DIRECTORY_SEPARATOR . $directory,
                        $relativePath . '/' . $directory,
                        tpDirectoryLabel($directory),
                        $level + 1
                    ); ?>
                <?php endforeach; ?>
            </div>
        </details>
        <?php
    }
}

if (!function_exists('tpExistingStaticDocuments')) {
    function tpExistingStaticDocuments($documents)
    {
        global $tpPageDirectory;

        $existingDocuments = array();

        foreach ($documents as $document) {
            $path = $tpPageDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $document['file']);

            if (is_file($path)) {
                $existingDocuments[] = $document;
            }
        }

        return $existingDocuments;
    }
}

if (!function_exists('tpAppendGroupDocument')) {
    function tpAppendGroupDocument(&$groups, $groupTitle, $document)
    {
        foreach ($groups as &$group) {
            if ($group['title'] === $groupTitle) {
                $group['documents'][] = $document;
                return;
            }
        }
    }
}

if (!function_exists('tpRenderStaticDocument')) {
    function tpRenderStaticDocument($document)
    {
        global $tpPageDirectory, $tpPageUrl;

        $path = $tpPageDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $document['file']);
        $url = $tpPageUrl . tpEncodedPath($document['file']);
        $documentType = tpDocumentType($document['file']);
        $size = tpHumanSize(filesize($path));
        ?>
        <a class="tp-document" href="<?php echo tpEscape($url); ?>" target="_blank" rel="noopener"
           data-search="<?php echo tpEscape($document['title']); ?>"
           aria-label="<?php echo tpEscape($document['title']); ?>, abrir arquivo em nova aba">
            <span class="tp-document-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
            <span class="tp-document-text">
                <span class="tp-document-title"><?php echo tpEscape($document['title']); ?></span>
                <span class="tp-document-meta"><?php echo tpEscape($documentType); ?> <span aria-hidden="true">&bull;</span> <?php echo tpEscape($size); ?></span>
            </span>
            <span class="tp-external" aria-hidden="true">↗</span>
        </a>
        <?php
    }
}

if (!function_exists('tpRenderStaticGroup')) {
    function tpRenderStaticGroup($group)
    {
        $documents = tpExistingStaticDocuments($group['documents']);
        $count = count($documents);

        if ($count === 0) {
            return;
        }
        ?>
        <details class="tp-disclosure tp-year">
            <summary>
                <span class="tp-summary-title"><?php echo tpEscape($group['title']); ?></span>
                <span class="tp-summary-side">
                    <span class="tp-count"><?php echo $count; ?> <?php echo $count === 1 ? 'documento' : 'documentos'; ?></span>
                    <span class="tp-chevron" aria-hidden="true"></span>
                </span>
            </summary>
            <div class="tp-panel">
                <div class="tp-document-list">
                    <?php foreach ($documents as $document): ?>
                        <?php tpRenderStaticDocument($document); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </details>
        <?php
    }
}

$tpInstitutionalGroups = array(
    array(
        'title' => 'Estatuto',
        'documents' => array(
            array('title' => 'Estatuto da Santa Casa de Lorena — 2026', 'file' => 'estatuto-santa-casa-lorena-2026.pdf'),
            array('title' => 'Estatuto da Santa Casa de Lorena — 2021', 'file' => 'Estatuto SCL 05_05_2021.pdf')
        )
    ),
    array(
        'title' => 'Atas',
        'documents' => array(
            array('title' => 'Ata de eleição e posse — 2025', 'file' => 'ata-eleicao-posse-santa-casa-2025.pdf')
        )
    ),
    array(
        'title' => 'Quadro de dirigentes',
        'documents' => array(
            array('title' => 'Quadro de dirigentes — 2025 a 2028', 'file' => 'quadro-dirigentes-2025-2028.pdf'),
            array('title' => 'Quadro de dirigentes — 2023 a 2024', 'file' => 'quadro-de-dirigentes-santa-casa-de-Lorena-2023-2024.pdf'),
            array('title' => 'Quadro de dirigentes e conselheiros — 2022 a 2023', 'file' => 'Quadro_de_Dirigentes_e_Conselheiros2022-2023.pdf'),
            array('title' => 'Quadro de dirigentes e conselheiros — 2021 a 2022', 'file' => 'Quadro_de_Dirigentes_e_Conselheiros2021-2022.pdf'),
            array('title' => 'Quadro de dirigentes e conselheiros — 2018 a 2021', 'file' => 'Quadro_de_Dirigentes_e_Conselheiros2018-2021.pdf')
        )
    ),
    array(
        'title' => 'Demonstrações financeiras',
        'documents' => array(
            array('title' => 'Demonstrações financeiras — 2024 e 2023', 'file' => 'demonstracoes-financeiras-2024-2023.pdf'),
            array('title' => 'Demonstrações financeiras — 2023 e 2022', 'file' => 'demonstracoes-financeiras-2023-2022.pdf'),
            array('title' => 'Demonstrações financeiras — 2022', 'file' => 'demonstracoes-financeiras-2022.pdf'),
            array('title' => 'Demonstrações financeiras — 2021', 'file' => 'Demonstracoes_Financeiras_2021.pdf'),
            array('title' => 'Demonstrações financeiras — 2020', 'file' => 'Demonstracoes_Financeiras_2020.pdf'),
            array('title' => 'Demonstrações financeiras — 2019', 'file' => 'Demonstracoes_Financeiras_2019.pdf'),
            array('title' => 'Demonstrações financeiras — 2018', 'file' => 'Demonstracoes_Financeiras_2018.pdf'),
            array('title' => 'Demonstrações financeiras — 2017', 'file' => 'Demonstracoes_Financeiras_2017.pdf'),
            array('title' => 'Demonstrações financeiras — 2016', 'file' => 'Demonstracoes_Financeiras_2016.pdf')
        )
    ),
    array(
        'title' => 'CEBAS',
        'documents' => array(
            array('title' => 'Certificado CEBAS — validade até 31/12/2025', 'file' => 'cebas-validade-31-12-2025.pdf'),
            array('title' => 'Declaração de tempestividade CEBAS — 2025/2026', 'file' => 'declaracao-tempestividade-cebas-2025-2026.pdf')
        )
    ),
    array(
        'title' => 'Programa de Especialização em Medicina Intensiva (PEMI/AMIB)',
        'documents' => array(
            array('title' => 'Programa de Especialização em Medicina Intensiva — 2026 a 2029', 'file' => 'PEMI_2026-2029.pdf')
        )
    )
);

$tpStateStaticGroups = array(
    array(
        'title' => 'Convênios estaduais já publicados',
        'documents' => array(
            array('title' => 'Convênio estadual 383/2020', 'file' => 'convenio-383-2020.pdf')
        )
    ),
    array(
        'title' => 'Emendas parlamentares',
        'documents' => array(
            array('title' => 'Emendas parlamentares 166, 512 e 927 — creditadas em 2023', 'file' => 'emendas-parlamentares-166-512-927-creditadas-em-2023.pdf'),
            array('title' => 'Emenda parlamentar 349 — creditada em 2022', 'file' => 'emenda-parlamentar-349-creditada-em-2022.pdf'),
            array('title' => 'Emenda parlamentar 280 — creditada em 2021', 'file' => 'Convenio 000280_2021 emenda parlamentar.pdf')
        )
    )
);

$tpMunicipalStaticGroups = array(
    array(
        'title' => 'Convênio municipal e termos já publicados',
        'documents' => array(
            array('title' => 'Convênio municipal 01/2021', 'file' => 'convenio municipal 01_2021.pdf'),
            array('title' => 'Termo aditivo 23 — Convênio municipal 01/2021', 'file' => 'termo-aditivo-23-convenio-01-2021.pdf')
        )
    )
);

$tpListedStaticFiles = array();
foreach (array_merge($tpInstitutionalGroups, $tpStateStaticGroups, $tpMunicipalStaticGroups) as $group) {
    foreach ($group['documents'] as $document) {
        $tpListedStaticFiles[$document['file']] = true;
    }
}

list(, $tpAvailableStaticFiles) = tpDirectoryEntries($tpPageDirectory);
foreach ($tpAvailableStaticFiles as $file) {
    if (isset($tpListedStaticFiles[$file])) {
        continue;
    }

    $document = array('title' => tpDocumentLabel($file), 'file' => $file);
    $fileKey = tpNormalizeKey($file);

    if (strpos($fileKey, 'DEMONSTRACOES FINANCEIRAS') !== false) {
        tpAppendGroupDocument($tpInstitutionalGroups, 'Demonstrações financeiras', $document);
    } elseif (strpos($fileKey, 'QUADRO DE DIRIGENTES') !== false) {
        tpAppendGroupDocument($tpInstitutionalGroups, 'Quadro de dirigentes', $document);
    } elseif (strpos($fileKey, 'EMENDA PARLAMENTAR') !== false) {
        tpAppendGroupDocument($tpStateStaticGroups, 'Emendas parlamentares', $document);
    } else {
        tpAppendGroupDocument($tpStateStaticGroups, 'Convênios estaduais já publicados', $document);
    }
}

$tpStateDirectory = null;
$tpStateRelative = null;
$tpMunicipalDirectory = null;
$tpMunicipalRelative = null;
$tpMunicipalGroups = array();
$tpFomentoDirectory = null;
$tpFomentoRelative = null;

list($tpArchiveDirectories) = tpDirectoryEntries($tpArchiveDirectory);
foreach ($tpArchiveDirectories as $directory) {
    // Pastas vazias de extrações anteriores não devem ocultar o acervo existente.
    if (tpCountDocuments($tpArchiveDirectory . DIRECTORY_SEPARATOR . $directory) === 0) continue;
    $directoryKey = tpNormalizeKey($directory);

    if (strpos($directoryKey, 'MUNICIPAL') !== false) {
        $tpMunicipalDirectory = $tpArchiveDirectory . DIRECTORY_SEPARATOR . $directory;
        $tpMunicipalRelative = $directory;
    } elseif (strpos($directoryKey, 'ESTADUA') !== false) {
        $tpStateDirectory = $tpArchiveDirectory . DIRECTORY_SEPARATOR . $directory;
        $tpStateRelative = $directory;
    }
}

if ($tpMunicipalDirectory) {
    list($tpMunicipalDirectories) = tpDirectoryEntries($tpMunicipalDirectory);
} else {
    $tpMunicipalDirectories = array();
}

foreach ($tpMunicipalDirectories as $directory) {
    $relativePath = $tpMunicipalRelative . '/' . $directory;
    $absolutePath = $tpMunicipalDirectory . DIRECTORY_SEPARATOR . $directory;

    if (tpIsFomentoDirectory($directory)) {
        $tpFomentoDirectory = $absolutePath;
        $tpFomentoRelative = $relativePath;
        continue;
    }

    $tpMunicipalGroups[] = array(
        'absolutePath' => $absolutePath,
        'relativePath' => $relativePath,
        'label' => tpMunicipalDirectoryLabel($directory)
    );
}

usort($tpMunicipalGroups, function ($firstGroup, $secondGroup) {
    return strnatcmp($secondGroup['label'], $firstGroup['label']);
});

$tpStaticCount = 0;
foreach (array_merge($tpInstitutionalGroups, $tpStateStaticGroups, $tpMunicipalStaticGroups) as $group) {
    $tpStaticCount += count(tpExistingStaticDocuments($group['documents']));
}
$tpArchiveCount = tpCountDocuments($tpArchiveDirectory);
$tpTotalCount = $tpStaticCount + $tpArchiveCount;
?>



<section class="section-space" id="portal-transparencia">
    <div class="site-container">
        <div class="tp-shell">
            <div class="tp-intro">
                <div class="tp-intro-icon" aria-hidden="true"><?= scl_icon('shield') ?></div>
                <div>
                    <h2>Transparência que aproxima</h2>
                    <p>Consulte documentos institucionais, convênios, termos aditivos, planos de trabalho e relatórios de atividades da Santa Casa de Lorena.</p>
                </div>
            </div>

            <div class="tp-tools" role="search" aria-label="Consultar documentos" hidden>
                <div class="tp-search-wrap">
                    <label for="tp-search">O que você procura?</label>
                    <input id="tp-search" type="search" placeholder="Ex.: relatório 2024, estatuto, convênio…" autocomplete="off" aria-controls="tp-sections" aria-describedby="tp-result-count">
                </div>
                <div class="tp-category-wrap">
                    <label for="tp-category">Categoria</label>
                    <select id="tp-category" aria-controls="tp-sections">
                        <option value="">Todas as categorias</option>
                        <option value="institucionais">Documentos institucionais</option>
                        <option value="estaduais">Convênios estaduais</option>
                        <option value="municipais">Convênios municipais</option>
                        <option value="fomento">Termos de fomento</option>
                    </select>
                </div>
                <button class="tp-reset" id="tp-search-clear" type="button">Limpar filtros</button>
            </div>
            <div class="tp-results-bar">
                <p id="tp-result-count" role="status" aria-live="polite"><?php echo $tpTotalCount; ?> documentos disponíveis</p>
                <div class="tp-expand-tools" hidden><button type="button" id="tp-expand">Expandir grupos</button><button type="button" id="tp-collapse">Recolher grupos</button></div>
            </div>
            <p class="tp-hint">Selecione um grupo para consultar os arquivos. Cada documento abre em uma nova aba, com seu formato e tamanho indicados abaixo do título.</p>
            <noscript><p>A busca requer JavaScript. Você pode consultar todos os documentos pelos grupos abaixo.</p></noscript>

            <?php if (!is_dir($tpArchiveDirectory)): ?>
                <div class="tp-archive-alert" role="alert">O acervo de convênios está temporariamente indisponível. Os documentos institucionais continuam acessíveis abaixo.</div>
            <?php endif; ?>

            <div id="tp-sections">
                <section class="tp-section" data-category="institucionais" aria-labelledby="tp-heading-institucionais">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
                        <h2 id="tp-heading-institucionais">Documentos institucionais</h2>
                    </div>
                    <p class="tp-section-description">Estatuto, atas, dirigentes, demonstrações financeiras, certificações e programas.</p>
                    <div class="tp-accordion">
                        <?php foreach ($tpInstitutionalGroups as $group): ?>
                            <?php tpRenderStaticGroup($group); ?>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="tp-section" data-category="estaduais" aria-labelledby="tp-heading-estaduais">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
                        <h2 id="tp-heading-estaduais">Convênios estaduais</h2>
                    </div>
                    <p class="tp-section-description">Termos de convênio, planos de trabalho e relatórios organizados por ano e número do convênio.</p>
                    <div class="tp-accordion">
                        <?php
                        foreach ($tpStateStaticGroups as $group) {
                            tpRenderStaticGroup($group);
                        }

                        if ($tpStateDirectory) {
                            list($tpStateYears) = tpDirectoryEntries($tpStateDirectory);
                        } else {
                            $tpStateYears = array();
                        }

                        rsort($tpStateYears, SORT_NATURAL);
                        foreach ($tpStateYears as $yearDirectory):
                            tpRenderDirectory(
                                $tpStateDirectory . DIRECTORY_SEPARATOR . $yearDirectory,
                                $tpStateRelative . '/' . $yearDirectory,
                                tpDirectoryLabel($yearDirectory),
                                1
                            );
                        endforeach;
                        ?>
                    </div>
                </section>

                <section class="tp-section" data-category="municipais" aria-labelledby="tp-heading-municipais">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
                        <h2 id="tp-heading-municipais">Convênios municipais</h2>
                    </div>
                    <p class="tp-section-description">Termos aditivos, planos de trabalho e relatórios do convênio municipal, separados por exercício.</p>
                    <div class="tp-accordion">
                        <?php foreach ($tpMunicipalStaticGroups as $group): ?>
                            <?php tpRenderStaticGroup($group); ?>
                        <?php endforeach; ?>
                        <?php foreach ($tpMunicipalGroups as $group): ?>
                            <?php tpRenderDirectory(
                                $group['absolutePath'],
                                $group['relativePath'],
                                $group['label'],
                                1
                            ); ?>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="tp-section" data-category="fomento" aria-labelledby="tp-heading-fomento">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon" aria-hidden="true"><?= scl_icon('file') ?></span>
                        <h2 id="tp-heading-fomento">Termos de fomento</h2>
                    </div>
                    <p class="tp-section-description">Instrumentos, planos de trabalho, termos aditivos e relatórios de atividades.</p>
                    <div class="tp-accordion">
                        <?php if ($tpFomentoDirectory): ?>
                            <?php tpRenderDirectory($tpFomentoDirectory, $tpFomentoRelative, 'Termos de fomento', 1); ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="tp-no-results" id="tp-no-results" hidden>
                <?= scl_icon('search') ?><br>
                Nenhum documento corresponde à sua busca. Tente usar apenas o número do convênio, o ano ou uma palavra do título.
            </div>
        </div>
    </div>
</section>
