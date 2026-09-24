const fs=require('fs'),path=require('path');
const root=path.resolve(__dirname,'..'),out=path.join(root,'resources/vendor');
const copy=(source,target)=>{const to=path.join(out,target);fs.mkdirSync(path.dirname(to),{recursive:true});fs.cpSync(path.join(root,'node_modules',source),to,{recursive:true});};
for(const [source,target] of [
 ['bootstrap/dist/css/bootstrap.min.css','bootstrap/bootstrap.min.css'],['bootstrap/dist/js/bootstrap.bundle.min.js','bootstrap/bootstrap.bundle.min.js'],
 ['jquery/dist/jquery.min.js','jquery/jquery.min.js'],['datatables.net/js/dataTables.min.js','datatables/dataTables.min.js'],
 ['datatables.net-bs5/js/dataTables.bootstrap5.min.js','datatables/dataTables.bootstrap5.min.js'],['datatables.net-bs5/css/dataTables.bootstrap5.min.css','datatables/dataTables.bootstrap5.min.css'],
 ['tom-select/dist/js/tom-select.complete.min.js','tom-select/tom-select.complete.min.js'],['tom-select/dist/css/tom-select.bootstrap5.min.css','tom-select/tom-select.bootstrap5.min.css'],
 ['tinymce/tinymce.min.js','tinymce/tinymce.min.js'],['tinymce/icons','tinymce/icons'],['tinymce/models','tinymce/models'],['tinymce/themes','tinymce/themes'],['tinymce/skins','tinymce/skins']])copy(source,target);
for(const plugin of ['code','link','image','table','lists','autolink','nonbreaking','searchreplace','fullscreen'])copy('tinymce/plugins/'+plugin,'tinymce/plugins/'+plugin);
copy('tinymce-i18n/langs8/pt-BR.js','tinymce/langs/pt-BR.js');
const manifest={};
for(const name of [...Object.keys(require('../package.json').dependencies),'datatables.net','@popperjs/core']){
 const pkg=JSON.parse(fs.readFileSync(path.join(root,'node_modules',name,'package.json'),'utf8'));manifest[name]={version:pkg.version,license:pkg.license};
 for(const file of fs.readdirSync(path.join(root,'node_modules',name)).filter(f=>/^(licen[sc]e|notice)/i.test(f)))copy(name+'/'+file,'licenses/'+name.replaceAll('/','-')+'-'+file);
}
fs.writeFileSync(path.join(out,'versions.json'),JSON.stringify(manifest,null,2)+'\n');
console.log('Assets locais gerados; versões fixadas em package-lock.json.');
