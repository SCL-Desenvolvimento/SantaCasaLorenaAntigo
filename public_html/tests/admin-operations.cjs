const {spawn}=require('child_process');
const fs=require('fs'),os=require('os'),path=require('path'),assert=require('assert/strict');
const php=process.argv[2];
if(!php) throw Error('Usage: node tests/security-http.cjs /path/to/php.exe');
const root=path.resolve(__dirname,'..');
const temporary=fs.mkdtempSync(path.join(os.tmpdir(),'scl-stage4-'));
const port=18103,base=`http://127.0.0.1:${port}`;
const server=spawn(php,['-d','extension_dir=ext','-d','extension=pdo_sqlite','-d','extension=fileinfo','-d','extension=gd','-d','extension=mbstring','-S',`127.0.0.1:${port}`,'-t',root,path.join(__dirname,'security-router.php')],{cwd:path.dirname(php),env:{...process.env,SCL_STAGE4_TEST:'1',SCL_SECURITY_TEST:path.join(temporary,'fixture'),SCL_HOME:base+'/'},windowsHide:true,stdio:['ignore','ignore','pipe']});
let serverLog='';server.stderr.on('data',data=>serverLog+=data);
let cookie='',checks=0;
async function request(url,options={}) {
 const headers={...options.headers}; if(cookie)headers.Cookie=cookie;
 const response=await fetch(base+url,{...options,headers,redirect:'manual'});
 const set=response.headers.get('set-cookie'); if(set)cookie=set.split(';')[0];
 return response;
}
async function verify(url,status,options={}){const r=await request(url,options);assert.equal(r.status,status,url+': '+await r.text());checks++;return r;}
async function fixture(account,expired=false){const r=await request('/__fixture?account='+account+(expired?'&expired=1':''));return r.json();}
async function post(url,data,token){return request(url,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded',...(token?{'X-CSRF-Token':token}:{})},body:new URLSearchParams(data)});}
(async()=>{try{
 let ready=false;for(let i=0;i<40;i++){try{await fetch(base+'/__fixture');ready=true;break;}catch{await new Promise(r=>setTimeout(r,100));}}assert(ready);
 const state=await fixture(1);const api='/admin/galeria-api.php';
 async function json(url){const r=await request(url);assert.equal(r.status,200,await r.clone().text());checks++;return r.json();}
 async function mutate(data,status=200){const r=await post(api,data,state.csrf);assert.equal(r.status,status,await r.clone().text());checks++;return r.json();}
 const before=await json(api+'?acao=get&id_galeria=1');assert.equal(before.photos.length,2);checks++;
 await mutate({acao:'save',id_galeria:1,nome:'Atualizada',photos:JSON.stringify([{id_anexo:2,legenda:'Segunda primeiro'},{id_anexo:1,legenda:'Legenda local'}])});
 let result=await json(api+'?acao=get&id_galeria=1');assert.deepEqual(result.photos.map(p=>p.id_anexo),[2,1]);assert.equal(result.photos[1].legenda,'Legenda local');checks+=2;
 result=await json(api+'?acao=get&id_galeria=2');assert.equal(result.photos[0].legenda,'Legenda original');checks++;
 for(const photos of [[{id_anexo:999,legenda:'X'}],[{id_anexo:1,legenda:'X'},{id_anexo:1,legenda:'X'}],[{id_anexo:1,legenda:'x'.repeat(256)}]])await mutate({acao:'save',id_galeria:1,nome:'Inválido',photos:JSON.stringify(photos)},422);
 result=await json(api+'?acao=get&id_galeria=1');assert.equal(result.gallery.nome,'Atualizada');assert.equal(result.photos.length,2);checks+=2;
 await mutate({acao:'save',id_galeria:1,nome:'Uma foto',photos:JSON.stringify([{id_anexo:2,legenda:'Final'}])});
 result=await json(api+'?acao=get&id_galeria=2');assert.equal(result.photos.length,1);checks++;
 const created=await mutate({acao:'create',nome:'Nova galeria'});assert(created.id>2);checks++;
 await mutate({acao:'create',nome:''},422);await mutate({acao:'delete',id_galeria:created.id});await verify(api+'?acao=get&id_galeria='+created.id,404);
 await mutate({acao:'upload',id_galeria:1},422);await verify(api+'?acao=delete&id_galeria=1',405);
 for(const channel of ['contatos','ouvidoria','curriculos','doacoes','pesquisa']){
  const endpoint='/admin/atendimento-api.php?canal='+channel;
  let data=await json(endpoint);assert.equal(data.total,2);checks++;
  data=await json(endpoint+'&data_inicio=23/09/2026&data_fim=23/09/2026');assert.equal(data.total,1);assert.equal(data.rows[0].data_cadastro,'2026-09-23 00:00:00');checks+=2;
  data=await json(endpoint+'&q='+encodeURIComponent(channel==='pesquisa'?'Regular':'other@example.invalid'));assert.equal(data.total,1);checks++;
  const csv=await request(endpoint+'&exportar=csv');assert.equal(csv.status,200);assert(csv.headers.get('content-type').includes('text/csv'));const bytes=Buffer.from(await csv.arrayBuffer());assert.equal(bytes.subarray(0,3).toString('hex'),'efbbbf');if(channel!=='curriculos')assert(bytes.toString('utf8').includes("'=FORMULA teste"));checks+=4;
 }
 await verify('/admin/atendimento-api.php?canal=unknown',400);await verify('/admin/atendimento-api.php?q[]=x',400);await verify('/admin/atendimento-api.php?data_inicio=invalid',400);
 for(const kind of ['banner','noticias']){
 const endpoint='/admin/webservices/'+kind+'/servico.php',news=kind==='noticias',entity=news?'Noticia':'Banner';
 const fields={titulo:'Título de teste',link:'',img:'resources/img/icon-logo.png',status:'1',...(news?{subtitulo:'Resumo',descricao:'<p>Texto com destaque</p>','id_tag[]':'Categoria de teste'}:{})};
 let response=await post(endpoint,{...fields,acao:'New'+entity},state.csrf);assert.equal(response.status,200,kind+' '+serverLog.split('\n').filter(line=>/SCL|Fatal/.test(line)).join('\n'));const id=Number(await response.text());assert(id>0);checks+=2;
 response=await post(endpoint,{acao:'Get'+entity,['Id'+entity]:id},state.csrf);let rows=await response.json();assert.equal(rows[0].titulo,fields.titulo);checks++;
 response=await post(endpoint,{...fields,titulo:'Editado',acao:'Update'+entity,[news?'id_noticia':'id_banner']:id,status:'0'},state.csrf);assert.equal(await response.text(),'1');checks++;
 response=await post(endpoint,{acao:'Get'+entity,['Id'+entity]:id},state.csrf);rows=await response.json();assert.equal(rows[0].titulo,'Editado');assert.equal(rows[0].status,0);checks+=2;
 if(news){response=await post(endpoint,{acao:'getTagsNoticia',id_noticia:id},state.csrf);assert.equal((await response.json()).length,1);checks++;}
 response=await post(endpoint,{acao:'Delete'+entity,['Id'+entity]:id},state.csrf);assert.equal(await response.text(),'ok');checks++;
 }
 console.log('OK: '+checks+' admin operation checks on isolated SQLite; no production writes.');
}finally{server.kill();await new Promise(resolve=>{if(server.exitCode!==null)return resolve();server.once('exit',resolve);});if(!temporary.startsWith(path.join(os.tmpdir(),'scl-stage4-')))throw Error('Unexpected cleanup path');fs.rmSync(temporary,{recursive:true});}})().catch(e=>{console.error(e);process.exitCode=1;});
