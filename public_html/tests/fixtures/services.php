<?php
$fixtureRoot=dirname(__DIR__,2);
$capacityImages=array_map(fn($file)=>array('id_capacidade'=>1,'img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1))),array_slice(glob($fixtureRoot.'/arquivos/capacidade_imagem/2018/01/*.jpg'),0,4));
$manualFiles=array_slice(glob($fixtureRoot.'/arquivos/download_manual_paciente/*/*/*.pdf'),0,2);
return array(
 'pagina_especialidades'=>array(array('bloco1'=>'Conheça as especialidades cadastradas.')),
 'especialidade'=>array_map(fn($name)=>array('nome'=>$name),array('Especialidade demonstrativa · Cardiologia','Especialidade demonstrativa · Clínica médica','Especialidade demonstrativa · Ortopedia','Especialidade demonstrativa · Pediatria','Especialidade demonstrativa · Neurologia','Especialidade demonstrativa · Ginecologia')),
 'pagina_capacidade_instalacao_producao'=>array(array('bloco1'=>'Estrutura e produção da Santa Casa.','bloco2'=>'<p>Texto demonstrativo: a apresentação institucional e os números cadastrados serão exibidos aqui no ambiente conectado.</p>')),
 'capacidade'=>array(array('id_capacidade'=>1,'titulo'=>'Estrutura demonstrativa','descricao'=>'<p>Descrição demonstrativa da estrutura, acompanhada das imagens do acervo local. Os indicadores oficiais não foram preenchidos nesta prévia.</p>'),array('id_capacidade'=>2,'titulo'=>'Produção demonstrativa','descricao'=>'<p>Este registro demonstra a apresentação de conteúdo sem imagens. A descrição original será preservada no ambiente conectado.</p>')),
 'capacidade_imagem'=>$capacityImages,
 'pagina_manual_paciente_visitante'=>array(array('bloco1'=>'Informações para pacientes e visitantes.')),
 'manual_paciente'=>array(array('titulo'=>'Orientações para pacientes · demonstração','descricao'=>'<p>Texto demonstrativo: aqui serão exibidas as orientações cadastradas pela Santa Casa para pacientes.</p>'),array('titulo'=>'Visitas e acompanhantes · demonstração','descricao'=>'<p>Texto demonstrativo: os horários e as orientações oficiais para visitantes serão apresentados conforme o cadastro institucional.</p>'),array('titulo'=>'Documentos · demonstração','descricao'=>'<p>Consulte os arquivos disponibilizados pela instituição na seção de documentos.</p>')),
 'download_manual_paciente'=>array_map(fn($file,$index)=>array('id_download_manual_paciente'=>$index+2,'titulo'=>'Documento do acervo · título demonstrativo '.($index+1),'pdf'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1))),$manualFiles,array_keys($manualFiles))
);
