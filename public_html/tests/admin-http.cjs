const fs=require('fs'),path=require('path'),assert=require('assert/strict'),vm=require('vm'),{spawn}=require('child_process');
const php=process.argv[2];if(!php)throw Error('Supply PHP executable');
const root=path.resolve(__dirname,'..'),base='http://127.0.0.1:18102';
const server=spawn(php,['-d','extension_dir=ext','-d','extension=pdo_sqlite','-d','extension=fileinfo','-S','127.0.0.1:18102','-t',root,path.join(__dirname,'admin-preview.php')],{cwd:path.dirname(php),windowsHide:true,stdio:'ignore'});
let checks=0;
(async()=>{try{
 let login;
 for(let i=0;i<40;i++){try{login=await fetch(base+'/__preview-login',{redirect:'manual'});break;}catch{await new Promise(r=>setTimeout(r,100));}}
 assert(login&&login.status===302,'Isolated server available');
 const headers={Cookie:login.headers.get('set-cookie').split(';')[0]};
 const assets=new Set();
 const routes=['atendimento','relatorios','home','usuario/index','usuario/create','usuario/update','noticias/index','noticias/create','noticias/update','banner/index','banner/create','banner/update','galeria/index','galeria/create','galeria/update','paginas/institucional','paginas/servicos','paginas/instalacoes','paginas/fale-conosco'];
 for(const route of routes){
   const response=await fetch(base+'/admin/painel.php?exe='+route+'&id_usuario=1&id_noticia=1&id_banner=1&id_galeria=1',{headers});
   const html=await response.text();assert.equal(response.status,200,route);checks++;
   assert(html.includes('resources/vendor/bootstrap/bootstrap.min.css')&&!/resources\/(plugins|bootstrap|dist)\//.test(html),route+': modern dependencies only');checks++;
   for(const match of html.matchAll(/<script\b([^>]*)>([\s\S]*?)<\/script>/gi)){
     const src=match[1].match(/src=["']([^"']+)/i);
     if(src)assets.add(src[1]);else new vm.Script(match[2],{filename:route});
   }
   for(const match of html.matchAll(/<link\b[^>]*href=["']([^"']+)/gi))assets.add(match[1]);
 }
 for(const asset of assets){if(!asset.startsWith('../resources/'))continue;const response=await fetch(new URL(asset,base+'/admin/painel.php'));assert.equal(response.status,200,asset);checks++;}
 for(const [endpoint,expected] of [['diagnostico.php','php'],['galerias.php','Galeria de demonstração']]){
   const response=await fetch(base+'/admin/'+endpoint,{headers});assert.equal(response.status,200);assert(JSON.stringify(await response.json()).includes(expected),endpoint);checks++;
 }
 const denied=await fetch(base+'/admin/webservices/noticias/servico.php',{method:'POST',headers,body:new URLSearchParams({acao:'NewNoticia'})});
 assert.equal(denied.status,403,'Preview refuses writes');checks++;
 console.log(`OK: ${checks} admin rendering/asset checks; 19 routes, no production DB or writes.`);
}finally{server.kill();}})().catch(error=>{console.error(error);process.exitCode=1;});
