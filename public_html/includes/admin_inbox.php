<?php
require_once __DIR__.'/ouvidoria_queries.php';
function scl_inbox_channels(): array {return ['contatos'=>['Contatos históricos','contato'],'ouvidoria'=>['Ouvidoria','ouvidoria'],'curriculos'=>['Currículos','trabalhe_conosco'],'doacoes'=>['Doações','doacoes'],'pesquisa'=>['Pesquisa de atendimento','pesquisa_atendimento']];}
function scl_inbox_columns(string $channel): array {
 $base=['nome'=>'Nome','email'=>'E-mail','cidade'=>'Cidade','data_cadastro'=>'Recebido em','mensagem'=>'Mensagem'];
 if($channel==='pesquisa')return ['data_cadastro'=>'Recebido em','tempo_espera'=>'Tempo de espera','nota_atendimento'=>'Nota do atendimento','resolucao_problema'=>'Resolução do problema','preparo_atendimento'=>'Preparo da equipe','informacoes_passadas'=>'Informações recebidas','perguntas_respondidas'=>'Perguntas respondidas','experiencia'=>'Experiência','mensagem'=>'Mensagem'];
 if($channel==='doacoes')$base+=['tipo'=>'Forma de doação','assunto'=>'Assunto'];
 if($channel==='curriculos')unset($base['mensagem']);
 if($channel==='ouvidoria'||$channel==='contatos')$base+=['assunto'=>'Assunto','razao'=>'Razão'];
 return $base;
}
function scl_inbox_query(string $channel,array $filters): array {
 $channels=scl_inbox_channels();if(!isset($channels[$channel]))throw new InvalidArgumentException('Canal inválido.');
 $table=$channels[$channel][1];[$sql,$encoded]=scl_ouvidoria_query($filters);parse_str($encoded,$params);
 $sql=str_replace([PREFIX.'ouvidoria','C.id_ouvidoria'],[PREFIX.$table,'C.id_'.$table],$sql);
 if(isset($filters['q'])&&!is_string($filters['q']))throw new InvalidArgumentException('Busca inválida.');
 $q=trim((string)($filters['q']??''));if(mb_strlen($q)>150)throw new InvalidArgumentException('A busca deve ter até 150 caracteres.');
 if($q!==''){$fields=$channel==='pesquisa'?['mensagem','experiencia']:($channel==='curriculos'?['nome','email']:['nome','email','mensagem']);$parts=[];foreach($fields as $i=>$field){$parts[]='C.'.$field.' LIKE :search'.$i;$params['search'.$i]='%'.$q.'%';}$sql=str_replace(' ORDER BY',' AND ('.implode(' OR ',$parts).') ORDER BY',$sql);}
 return [$sql,http_build_query($params)];
}
function scl_inbox_read(string $channel,array $filters): array {[$sql,$params]=scl_inbox_query($channel,$filters);$read=new Read();$read->fullRead($sql,$params);if($read->getResult()===null)throw new RuntimeException('Consulta indisponível.');return $read->getResult()?:[];}
function scl_inbox_export(string $channel): never {
 try{$rows=scl_inbox_read($channel,$_GET);}catch(InvalidArgumentException $e){http_response_code(400);exit($e->getMessage());}
 $columns=scl_inbox_columns($channel);header('Content-Type: text/csv; charset=UTF-8');header('Content-Disposition: attachment; filename="relatorio-'.$channel.'-'.date('Y-m-d').'.csv"');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
 $out=fopen('php://output','w');fwrite($out,"\xEF\xBB\xBF");fputcsv($out,array_values($columns),';','"','');
 foreach($rows as $row){$values=[];foreach($columns as $key=>$label){$value=(string)($row[$key]??'');if(preg_match('/^[\s]*[=+@-]/u',$value))$value="'".$value;$values[]=$value;}fputcsv($out,$values,';','"','');}fclose($out);exit;
}
