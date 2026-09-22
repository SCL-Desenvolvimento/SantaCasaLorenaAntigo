<?php
$newsFixture=array();
for($i=1;$i<=12;$i++)$newsFixture[]=array('id_noticia'=>$i,'titulo'=>'Notícia demonstrativa '.$i.' · Acompanhe a Santa Casa','subtitulo'=>'Resumo demonstrativo para revisar a apresentação das notícias. O conteúdo oficial será lido do cadastro existente.','img'=>$i===3?'':'resources/img/santa-casa-home/pronto-atendimento.png','link'=>'noticia-demonstrativa-'.$i,'data_criacao'=>'2026-09-'.str_pad(22-$i,2,'0',STR_PAD_LEFT),'tag'=>$i%2?'Institucional':'Ações sociais','url_tag'=>$i%2?'institucional':'acoes-sociais');
return $newsFixture;
