const {spawn}=require('child_process');
const fs=require('fs'),os=require('os'),path=require('path'),assert=require('assert/strict');
const php=process.argv[2];
if(!php) throw Error('Usage: node tests/security-http.cjs /path/to/php.exe');
const root=path.resolve(__dirname,'..');
const temporary=fs.mkdtempSync(path.join(os.tmpdir(),'scl-security-'));
const port=18096,base=`http://127.0.0.1:${port}`;
const server=spawn(php,['-d','extension_dir=ext','-d','extension=pdo_sqlite','-d','extension=fileinfo','-d','extension=gd','-S',`127.0.0.1:${port}`,'-t',root,path.join(__dirname,'security-router.php')],{cwd:path.dirname(php),env:{...process.env,SCL_SECURITY_TEST:path.join(temporary,'fixture'),SCL_HOME:base+'/'},windowsHide:true,stdio:'ignore'});
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
(async()=>{
 try {
  let ready=false;for(let i=0;i<40;i++){try{await fetch(base+'/__fixture');ready=true;break;}catch{await new Promise(r=>setTimeout(r,100));}} assert(ready,'test server ready');
  const endpoints=['/admin/atendimento-api.php','/admin/galeria-api.php','/admin/diagnostico.php','/admin/galerias.php','/admin/painel.php','/admin/curriculo.php','/admin/media.php','/admin/includes/tim.php',...['contatos','doacoes','ouvidoria','pesquisa_atendimento','trabalhe_conosco'].map(x=>'/admin/relatorio-'+x+'.php'),...['usuario','banner','galeria','noticias','paginas','paginas/servicos','paginas/institucional','paginas/instalacoes','paginas/fale-conosco'].map(x=>'/admin/webservices/'+x+'/servico.php')];
  for(const endpoint of endpoints) {
   await verify(endpoint,401);
   await verify(endpoint,401,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'acao=excluiUser&id_usuario=1'});
  }
  for(const template of ['usuario/create','usuario/update','paginas/fale-conosco','noticias/create','home'])await verify('/admin/system/'+template+'.php',403);
  await verify('/admin/index.php',200);
  await verify('/admin/index.php',403,{method:'POST',body:new URLSearchParams({action:'login',usuario:'admin',senha:'fixture-pass'})});
  for(const account of [2,3,4]) {
   const state=await fixture(account);
   const r=await post('/admin/webservices/usuario/servico.php',{acao:'createUser',nome:'Forbidden'},state.csrf);assert.equal(r.status,401);checks++;
  }
  await fixture(1,true);await verify('/admin/webservices/usuario/servico.php',401);
  const state=await fixture(1);
  for(const endpoint of endpoints)await verify(endpoint,403,{method:'POST',body:new URLSearchParams({acao:'excluiUser',id_usuario:'2'})});
  const endpoint='/admin/webservices/usuario/servico.php';
  for(const token of [null,'forged']){const r=await post(endpoint,{acao:'excluiUser',id_usuario:'2'},token);assert.equal(r.status,403);checks++;}
  const listing=await post(endpoint,{acao:'listUsers'},state.csrf);assert.equal(listing.status,200);const users=await listing.json();assert(users.length===2 && users.every(u=>!('senha'in u)&&!('security_version'in u)));checks++;
  let r=await post(endpoint,{acao:'createUser',nome:'Synthetic administrator',email:'new@example.invalid',usuario:'new-admin',senha:'Testing secure passphrase',status:'1',nivel:'1',security_version:'999'},state.csrf);
  assert.equal(r.status,200,await r.clone().text());const newId=await r.text();assert(Number(newId)>4);checks++;
  r=await post(endpoint,{acao:'getUser',id_usuario:newId},state.csrf);const newUser=await r.json();assert.equal(newUser.nivel,3);assert(!('senha'in newUser));checks++;
  r=await post(endpoint,{acao:'updateUser',id_usuario:newId,nome:'Updated fixture',email:'new@example.invalid',usuario:'new-admin',senha:'',status:'1',nivel:'1'},state.csrf);assert.equal(r.status,200);checks++;
  r=await post(endpoint,{acao:'excluiUser',id_usuario:'1'},state.csrf);assert.equal(r.status,403);checks++;
  r=await post(endpoint,{acao:'excluiUser',id_usuario:newId},state.csrf);assert.equal(r.status,200);assert.equal(await r.text(),'1');checks++;
  r=await post(endpoint,{acao:'getUser',id_usuario:'1 OR 1=1'},state.csrf);assert.equal(r.status,400);checks++;
  await verify('/admin/relatorio-trabalhe_conosco.php?data_inicio=1%27%20OR%201=1',400);
  await verify('/admin/curriculo.php?id=../../_app/Config.inc.php',400);
  r=await post('/admin/painel.php',{LogOff:'1',_csrf:state.csrf});assert.equal(r.status,303);checks++;
  await verify(endpoint,401);
  const after=await (await request('/__fixture')).json();assert.equal(after.users,state.users);checks++;
  const pdf=Buffer.from('%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF');
  for(const [name,type,content,folder,expected] of [['document.pdf','application/pdf',pdf,'documentos',true],['document.jpg','image/jpeg',pdf,'documentos',false],['payload.php','application/pdf',pdf,'documentos',false],['document.pdf','application/pdf',pdf,'../../escape',false],['document.pdf','application/pdf',Buffer.from('<?php echo 1;'),'documentos',false]]) {
    const body=new FormData();body.set('file',new Blob([content],{type}),name);body.set('folder',folder);
    const response=await request('/tests/security-upload.php',{method:'POST',body});const result=await response.json();assert.equal(result.success,expected,name+': '+JSON.stringify(result));if(expected)assert(result.random);checks++;
  }
  for(const bad of [false,true]) {
    const body=new FormData();body.set('form','trabalhe_conosco');body.set('community_token','offline-test');body.set('g-recaptcha-response','offline');body.set('nome','Fixture candidate');body.set('email','candidate@example.invalid');body.set('cidade','Fixture city');body.set('curriculum-tc',new Blob([bad?'not a PDF':pdf],{type:'application/pdf'}),'fixture.pdf');
    const response=await request('/tests/community-upload.php',{method:'POST',body});const result=await response.json();assert.equal(result.success,!bad,JSON.stringify(result));if(!bad)assert(result.stored&&result.random_pdf&&result.mime_attachment);checks++;
  }
  console.log(`OK: ${checks} HTTP security checks; synthetic SQLite database, no production data or emails.`);
 } finally {server.kill();await new Promise(resolve=>{if(server.exitCode!==null)return resolve();server.once('exit',resolve);});function clean(dir){for(const file of fs.readdirSync(dir)){const target=path.join(dir,file);if(fs.lstatSync(target).isDirectory())clean(target);else fs.unlinkSync(target);}fs.rmdirSync(dir);}if(!temporary.startsWith(path.join(os.tmpdir(),'scl-security-')))throw Error('Unexpected cleanup path');clean(temporary);}
})().catch(error=>{console.error(error);process.exitCode=1;});
