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

        if (preg_match('/^CONV(?:Ê|E)NIO\s+(\d+)[\.\s]+(20\d{2})$/iu', $normalized, $matches)) {
            return 'Convênio ' . $matches[1] . '/' . $matches[2];
        }

        if (preg_match('/^CONV(?:Ê|E)NIO\s+(\d+)(20\d{2})$/iu', $normalized, $matches)) {
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
            <span class="tp-document-icon" aria-hidden="true"><span class="fa <?php echo tpEscape(tpDocumentIcon($absolutePath)); ?>"></span></span>
            <span class="tp-document-text">
                <span class="tp-document-title"><?php echo tpEscape($label); ?></span>
                <span class="tp-document-meta"><?php echo tpEscape($documentType); ?> <span aria-hidden="true">&bull;</span> <?php echo tpEscape(tpHumanSize(filesize($absolutePath))); ?></span>
            </span>
            <span class="fa fa-external-link tp-external" aria-hidden="true"></span>
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
            <span class="tp-document-icon" aria-hidden="true"><span class="fa <?php echo tpEscape(tpDocumentIcon($document['file'])); ?>"></span></span>
            <span class="tp-document-text">
                <span class="tp-document-title"><?php echo tpEscape($document['title']); ?></span>
                <span class="tp-document-meta"><?php echo tpEscape($documentType); ?> <span aria-hidden="true">&bull;</span> <?php echo tpEscape($size); ?></span>
            </span>
            <span class="fa fa-external-link tp-external" aria-hidden="true"></span>
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

<style>
    #portal-transparencia {
        color: #23313b;
        font-family: 'Montserrat', sans-serif;
    }

    #portal-transparencia * { box-sizing: border-box; }
    #portal-transparencia .tp-shell { max-width: 1120px; margin: 0 auto; }

    #portal-transparencia .tp-intro {
        display: flex;
        align-items: center;
        gap: 22px;
        margin-bottom: 26px;
        padding: 24px 26px;
        border: 1px solid #d9edf7;
        border-left: 5px solid #29b6f6;
        border-radius: 6px;
        background: #f5fbfe;
    }

    #portal-transparencia .tp-intro-icon {
        flex: 0 0 54px;
        width: 54px;
        height: 54px;
        border-radius: 50%;
        color: #fff;
        background: #29b6f6;
        text-align: center;
        line-height: 54px;
        font-size: 23px;
    }

    #portal-transparencia .tp-intro h2 {
        margin: 0 0 5px !important;
        padding: 0 !important;
        color: #1b3443;
        font-size: 21px;
        font-weight: 600;
    }

    #portal-transparencia .tp-intro p {
        margin: 0;
        color: #52636e;
        font-family: 'Domine', serif;
        font-size: 15px;
        line-height: 1.6;
    }

    #portal-transparencia .tp-tools {
        display: flex;
        align-items: flex-end;
        gap: 18px;
        margin-bottom: 38px;
    }

    #portal-transparencia .tp-search-wrap { position: relative; flex: 1 1 auto; }
    #portal-transparencia .tp-search-label { display: block; margin-bottom: 7px; color: #1b3443; font-size: 13px; font-weight: 600; }
    #portal-transparencia .tp-search-icon { position: absolute; left: 16px; bottom: 14px; color: #5c7280; font-size: 16px; }

    #portal-transparencia .tp-search {
        width: 100%;
        height: 48px;
        padding: 0 48px 0 44px;
        border: 1px solid #cbd6dc;
        border-radius: 5px;
        background: #fff;
        color: #23313b;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    #portal-transparencia .tp-search:focus { border-color: #29b6f6; box-shadow: 0 0 0 3px rgba(41, 182, 246, .15); }

    #portal-transparencia .tp-search-clear {
        position: absolute;
        right: 8px;
        bottom: 7px;
        display: none;
        width: 34px;
        height: 34px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        color: #5c7280;
        background: transparent;
        font-size: 20px;
        line-height: 34px;
        cursor: pointer;
    }

    #portal-transparencia .tp-search-clear:hover,
    #portal-transparencia .tp-search-clear:focus { color: #e53935; background: #f7f7f7; outline: none; }

    #portal-transparencia .tp-result-count {
        flex: 0 0 auto;
        min-width: 150px;
        height: 48px;
        padding: 0 18px;
        border-radius: 5px;
        color: #315166;
        background: #eef5f8;
        font-size: 13px;
        font-weight: 600;
        line-height: 48px;
        text-align: center;
        white-space: nowrap;
    }

    #portal-transparencia .tp-section { margin-bottom: 42px; }
    #portal-transparencia .tp-section-heading { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }

    #portal-transparencia .tp-section-icon {
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 5px;
        color: #168fc6;
        background: #e7f6fc;
        font-size: 17px;
        line-height: 38px;
        text-align: center;
    }

    #portal-transparencia .tp-section h2 { margin: 0 !important; padding: 0 !important; color: #0f3d53; font-size: 21px; font-weight: 600; }
    #portal-transparencia .tp-section-description { margin: -3px 0 17px 50px; color: #647680; font-family: 'Domine', serif; font-size: 14px; line-height: 1.55; }

    #portal-transparencia .tp-disclosure { margin: 0; border: 1px solid #d6dde1; border-bottom: 0; background: #fff; }
    #portal-transparencia .tp-disclosure:first-of-type { border-radius: 5px 5px 0 0; }
    #portal-transparencia .tp-disclosure:last-of-type { border-bottom: 1px solid #d6dde1; border-radius: 0 0 5px 5px; }
    #portal-transparencia .tp-disclosure:only-of-type { border-radius: 5px; }

    #portal-transparencia .tp-disclosure > summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        min-height: 54px;
        padding: 14px 20px;
        color: #123f55;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        list-style: none;
        cursor: pointer;
        transition: color .2s, background .2s;
    }

    #portal-transparencia .tp-disclosure > summary::-webkit-details-marker { display: none; }
    #portal-transparencia .tp-disclosure > summary:hover,
    #portal-transparencia .tp-disclosure > summary:focus { color: #087dac; background: #f7fbfd; outline: none; }
    #portal-transparencia .tp-disclosure > summary:focus-visible { box-shadow: inset 0 0 0 2px #29b6f6; }
    #portal-transparencia .tp-disclosure[open] > summary { color: #087dac; background: #f2f9fc; border-bottom: 1px solid #dce5e9; }
    #portal-transparencia .tp-summary-side { display: flex; align-items: center; gap: 16px; }
    #portal-transparencia .tp-count { color: #71818a; font-size: 11px; font-weight: 500; white-space: nowrap; }

    #portal-transparencia .tp-chevron {
        display: block;
        width: 9px;
        height: 9px;
        margin: -4px 3px 0 0;
        border-right: 2px solid #2b7899;
        border-bottom: 2px solid #2b7899;
        transform: rotate(45deg);
        transition: transform .2s;
    }

    #portal-transparencia .tp-disclosure[open] > summary .tp-chevron { margin-top: 4px; transform: rotate(225deg); }
    #portal-transparencia .tp-panel { padding: 16px 18px 18px; background: #fff; }

    #portal-transparencia .tp-subgroup {
        margin: 10px 0 0;
        border: 1px solid #dce3e7;
        border-radius: 4px !important;
    }

    #portal-transparencia .tp-subgroup > summary { min-height: 48px; padding: 11px 16px; background: #fafcfd; font-size: 13px; }
    #portal-transparencia .tp-subgroup .tp-panel { padding: 14px; }
    #portal-transparencia .tp-document-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }

    #portal-transparencia .tp-document {
        display: flex;
        align-items: center;
        min-width: 0;
        min-height: 68px;
        padding: 12px 14px;
        border: 1px solid #e0e6e9;
        border-radius: 4px;
        color: #244858;
        background: #fff;
        transition: border-color .2s, box-shadow .2s, transform .2s;
    }

    #portal-transparencia .tp-document:hover,
    #portal-transparencia .tp-document:focus {
        color: #087dac;
        border-color: #8ed2ef;
        box-shadow: 0 3px 10px rgba(27, 74, 94, .09);
        outline: none;
        transform: translateY(-1px);
    }

    #portal-transparencia .tp-document-icon {
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        margin-right: 12px;
        border-radius: 4px;
        color: #e53935;
        background: #fff1f0;
        font-size: 17px;
        line-height: 36px;
        text-align: center;
    }

    #portal-transparencia .tp-document-text { display: block; flex: 1 1 auto; min-width: 0; }
    #portal-transparencia .tp-document-title { display: block; overflow-wrap: anywhere; font-size: 12px; font-weight: 500; line-height: 1.45; }
    #portal-transparencia .tp-document-meta { display: block; margin-top: 3px; color: #87949b; font-size: 10px; font-weight: 500; letter-spacing: .03em; text-transform: uppercase; }
    #portal-transparencia .tp-external { flex: 0 0 auto; margin-left: 10px; color: #9babb2; font-size: 11px; }

    #portal-transparencia .tp-no-results {
        display: none;
        margin: 12px 0 40px;
        padding: 28px;
        border: 1px dashed #bdcbd2;
        border-radius: 5px;
        color: #52636e;
        background: #fafcfd;
        font-family: 'Domine', serif;
        font-size: 14px;
        line-height: 1.6;
        text-align: center;
    }

    #portal-transparencia .tp-archive-alert { margin-bottom: 30px; padding: 16px 18px; border: 1px solid #f1d59a; border-radius: 5px; color: #6a521d; background: #fffaf0; font-size: 13px; }

    @media (max-width: 767px) {
        #portal-transparencia .tp-shell { padding: 0 15px; }
        #portal-transparencia .tp-intro { align-items: flex-start; padding: 19px; }
        #portal-transparencia .tp-intro-icon { flex-basis: 44px; width: 44px; height: 44px; line-height: 44px; font-size: 19px; }
        #portal-transparencia .tp-intro h2 { font-size: 18px; }
        #portal-transparencia .tp-tools { display: block; margin-bottom: 32px; }
        #portal-transparencia .tp-result-count { display: inline-block; height: 36px; min-width: 0; margin-top: 10px; padding: 0 13px; line-height: 36px; }
        #portal-transparencia .tp-section { margin-bottom: 34px; }
        #portal-transparencia .tp-section h2 { font-size: 18px; }
        #portal-transparencia .tp-section-description { margin-left: 0; }
        #portal-transparencia .tp-disclosure > summary { min-height: 52px; padding: 13px 14px; }
        #portal-transparencia .tp-count { display: none; }
        #portal-transparencia .tp-summary-side { gap: 8px; }
        #portal-transparencia .tp-panel,
        #portal-transparencia .tp-subgroup .tp-panel { padding: 12px; }
        #portal-transparencia .tp-document-list { grid-template-columns: 1fr; }
        #portal-transparencia .tp-document { min-height: 62px; padding: 10px 11px; }
    }

    @media print {
        #portal-transparencia .tp-tools,
        #portal-transparencia .tp-intro-icon,
        #portal-transparencia .tp-external { display: none !important; }
        #portal-transparencia .tp-disclosure > .tp-panel { display: block !important; }
    }
</style>

<section class="bloco-conteudo" id="portal-transparencia">
    <div class="bloco-conteudo-padding bloco-conteudo-conteudo">
        <div class="tp-shell">
            <div class="tp-intro">
                <div class="tp-intro-icon" aria-hidden="true"><span class="fa fa-university"></span></div>
                <div>
                    <h2>Transparência que aproxima</h2>
                    <p>Consulte documentos institucionais, convênios, termos aditivos, planos de trabalho e relatórios de atividades da Santa Casa de Lorena.</p>
                </div>
            </div>

            <div class="tp-tools" role="search">
                <div class="tp-search-wrap">
                    <label class="tp-search-label" for="tp-search">Buscar no portal</label>
                    <span class="fa fa-search tp-search-icon" aria-hidden="true"></span>
                    <input class="tp-search" id="tp-search" type="search" placeholder="Digite um ano, convênio ou documento" autocomplete="off" aria-describedby="tp-result-count">
                    <button class="tp-search-clear" id="tp-search-clear" type="button" aria-label="Limpar busca">&times;</button>
                </div>
                <div class="tp-result-count" id="tp-result-count" aria-live="polite"><?php echo $tpTotalCount; ?> documentos</div>
            </div>

            <?php if (!is_dir($tpArchiveDirectory)): ?>
                <div class="tp-archive-alert" role="alert">O acervo de convênios está temporariamente indisponível. Os documentos institucionais continuam acessíveis abaixo.</div>
            <?php endif; ?>

            <div id="tp-sections">
                <section class="tp-section">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon fa fa-folder-open-o" aria-hidden="true"></span>
                        <h2>Documentos institucionais</h2>
                    </div>
                    <p class="tp-section-description">Estatuto, atas, dirigentes, demonstrações financeiras, certificações e programas.</p>
                    <div class="tp-accordion">
                        <?php foreach ($tpInstitutionalGroups as $group): ?>
                            <?php tpRenderStaticGroup($group); ?>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="tp-section">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon fa fa-map-o" aria-hidden="true"></span>
                        <h2>Convênios estaduais</h2>
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

                <section class="tp-section">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon fa fa-building-o" aria-hidden="true"></span>
                        <h2>Convênios municipais</h2>
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

                <section class="tp-section">
                    <div class="tp-section-heading">
                        <span class="tp-section-icon fa fa-file-text-o" aria-hidden="true"></span>
                        <h2>Termos de fomento</h2>
                    </div>
                    <p class="tp-section-description">Instrumentos, planos de trabalho, termos aditivos e relatórios de atividades.</p>
                    <div class="tp-accordion">
                        <?php if ($tpFomentoDirectory): ?>
                            <?php tpRenderDirectory($tpFomentoDirectory, $tpFomentoRelative, 'Termos de fomento', 1); ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="tp-no-results" id="tp-no-results" role="status">
                <span class="fa fa-search" aria-hidden="true"></span><br>
                Nenhum documento corresponde à sua busca. Tente usar apenas o número do convênio, o ano ou uma palavra do título.
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        'use strict';

        var portal = document.getElementById('portal-transparencia');
        if (!portal) return;

        var search = portal.querySelector('#tp-search');
        var clearButton = portal.querySelector('#tp-search-clear');
        var resultCount = portal.querySelector('#tp-result-count');
        var noResults = portal.querySelector('#tp-no-results');
        var documents = Array.prototype.slice.call(portal.querySelectorAll('.tp-document'));
        var disclosures = Array.prototype.slice.call(portal.querySelectorAll('.tp-disclosure'));
        var sections = Array.prototype.slice.call(portal.querySelectorAll('.tp-section'));
        var filtering = false;

        function normalize(value) {
            value = (value || '').toLocaleLowerCase();
            return value.normalize ? value.normalize('NFD').replace(/[\u0300-\u036f]/g, '') : value;
        }

        function restoreDisclosureState() {
            disclosures.forEach(function (disclosure) {
                disclosure.hidden = false;
                if (disclosure.hasAttribute('data-tp-was-open')) {
                    disclosure.open = disclosure.getAttribute('data-tp-was-open') === '1';
                    disclosure.removeAttribute('data-tp-was-open');
                }
            });
            sections.forEach(function (section) { section.hidden = false; });
        }

        function filterDocuments() {
            var term = normalize(search.value.trim());
            var visibleCount = 0;
            clearButton.style.display = term ? 'block' : 'none';

            if (term && !filtering) {
                disclosures.forEach(function (disclosure) {
                    disclosure.setAttribute('data-tp-was-open', disclosure.open ? '1' : '0');
                });
                filtering = true;
            }

            documents.forEach(function (documentLink) {
                var matches = !term || normalize(documentLink.getAttribute('data-search')).indexOf(term) !== -1;
                documentLink.hidden = !matches;
                if (matches) visibleCount++;
            });

            if (!term) {
                restoreDisclosureState();
                filtering = false;
            } else {
                disclosures.slice().reverse().forEach(function (disclosure) {
                    var hasVisibleDocument = Array.prototype.some.call(
                        disclosure.querySelectorAll('.tp-document'),
                        function (documentLink) { return !documentLink.hidden; }
                    );
                    disclosure.hidden = !hasVisibleDocument;
                    disclosure.open = hasVisibleDocument;
                });

                sections.forEach(function (section) {
                    section.hidden = !Array.prototype.some.call(
                        section.querySelectorAll('.tp-document'),
                        function (documentLink) { return !documentLink.hidden; }
                    );
                });
            }

            resultCount.textContent = visibleCount + (visibleCount === 1 ? ' documento' : ' documentos');
            noResults.style.display = visibleCount ? 'none' : 'block';
        }

        search.addEventListener('input', filterDocuments);
        search.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && search.value) {
                search.value = '';
                filterDocuments();
            }
        });

        clearButton.addEventListener('click', function () {
            search.value = '';
            filterDocuments();
            search.focus();
        });
    }());
</script>
