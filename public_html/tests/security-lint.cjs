const fs=require('fs'),path=require('path'),vm=require('vm'),{spawnSync}=require('child_process');
const php=process.argv[2];if(!php)throw Error('Supply PHP executable');
const root=path.resolve(__dirname,'..');
const walk=d=>fs.readdirSync(d,{withFileTypes:true}).flatMap(e=>e.isDirectory()?walk(path.join(d,e.name)):[path.join(d,e.name)]);
const files=['_app','admin','includes','deploy','tests','scripts'].flatMap(d=>walk(path.join(root,d))).filter(p=>p.endsWith('.php')&&!p.includes('PHPMailer-master')&&!p.includes(path.sep+'vendor'+path.sep));
let errors=0;
for(const file of files){const result=spawnSync(php,['-l',file],{encoding:'utf8',windowsHide:true});if(result.status!==0){errors++;console.error(result.stdout,result.stderr);}}
const jsFiles=['resources/js','admin/system'].flatMap(d=>walk(path.join(root,d))).filter(p=>p.endsWith('.js')&&!['app.js','activeMenu.js','scripts.js'].includes(path.relative(path.join(root,'resources/js'),p)));
for(const file of jsFiles){
 const source=fs.readFileSync(file,'utf8');
 const chunks=source.includes('<script')?[...source.matchAll(/<script(?:\s[^>]*)?>([\s\S]*?)<\/script>/g)].map(m=>m[1]):[source];
 for(const script of chunks)try{new vm.Script(script,{filename:file});}catch(e){errors++;console.error(file,e.message);}
}
if(errors)process.exitCode=1;else console.log(`OK: ${files.length} PHP files and ${jsFiles.length} JavaScript files parsed.`);
