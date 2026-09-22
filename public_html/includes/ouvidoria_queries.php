<?php
/** One persistence contract for the public form, admin list and report. */
function scl_ouvidoria_table() { return PREFIX . 'ouvidoria'; }

function scl_ouvidoria_query($filters = array(), $formatted = false) {
    $where = array('C.id_ouvidoria > 0');
    $params = array();
    foreach (array('data_inicio'=>'start', 'data_fim'=>'end') as $field=>$parameter) {
        $value = $filters[$field] ?? '';
        if ($value === '') continue;
        if (!is_string($value)) throw new InvalidArgumentException('Informe datas no formato dia/mês/ano.');
        $date = DateTimeImmutable::createFromFormat('!d/m/Y', $value);
        if (!$date || $date->format('d/m/Y') !== $value) throw new InvalidArgumentException('Informe datas válidas no formato dia/mês/ano.');
        $params[$parameter] = $date->format('Y-m-d') . ($parameter === 'start' ? ' 00:00:00' : ' 23:59:59');
        $where[] = 'C.data_cadastro ' . ($parameter === 'start' ? '>=' : '<=') . ' :' . $parameter;
    }
    if (isset($params['start'], $params['end']) && $params['start'] > $params['end']) throw new InvalidArgumentException('A data inicial deve ser anterior ou igual à final.');
    $columns = 'C.*' . ($formatted ? ", DATE_FORMAT(C.data_cadastro, '%d/%m/%Y às %Hh%i') AS data_formatada" : '');
    return array('SELECT ' . $columns . ' FROM ' . scl_ouvidoria_table() . ' AS C WHERE ' . implode(' AND ', $where) . ' ORDER BY C.data_cadastro DESC', http_build_query($params));
}
