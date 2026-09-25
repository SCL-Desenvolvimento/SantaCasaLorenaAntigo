/** Build a clean release directory; do not overlay it on an old public directory. */
const fs = require('fs'), path = require('path');
const root = path.resolve(__dirname, '..');
const destination = process.argv[2] && path.resolve(process.argv[2]);
const roots = ['.user.ini','.htaccess','index.php','favicon.ico','touch-icon-ipad-retina.png','touch-icon-ipad.png','touch-icon-iphone-retina.png','touch-icon-iphone.png','_app','admin','includes','resources','arquivos'];
function allowed(relative) {
  const p=relative.replace(/\\/g,'/');
  return !/(^|\/)(\.git|\.env(?:\..*)?|error_log|Thumbs\.db|desktop\.ini)(\/|$)/i.test(p)
    && !/^_app\/(PHPMailer-master\/|Config\.inc - Copia\.php$)/i.test(p)
    && !/^resources\/(plugins|bootstrap|dist)(\/|$)/i.test(p)
    && !/^resources\/vendor\/flatpickr(?:\/|$)/i.test(p)
    && !/^resources\/vendor\/licenses\/flatpickr-/i.test(p)
    && !/^resources\/js\/(app|activeMenu|scripts)\.js$/i.test(p)
    && !/^resources\/css\/style\.css(?:\.22072022)?$/i.test(p)
    && !/^_app\/vendor\/phpmailer\/(src(?:\/|$)|composer\.json$|LICENSE$|README-SCL\.md$)/i.test(p)
    && !/^arquivos\/curriculuns\/(?!\.htaccess$)/i.test(p)
    && (!/\.(bak|old|log|sql|phps|md|yml|yaml|toml)$/i.test(p) || /^resources\/vendor\/licenses\//.test(p))
    && !(p.startsWith('resources/') && /\.(php\d*|phtml|phar)$/i.test(p))
    && !(p.startsWith('arquivos/') && /\.(php\d*|phtml|phar|exe|html?|js|svg|cgi|pl|py|sh)$/i.test(p));
}
const files=[];
function walk(relative){
  if(!allowed(relative))return;
  const full=path.join(root,relative),stat=fs.lstatSync(full);
  if(stat.isSymbolicLink())throw Error('Symbolic link excluded: '+relative);
  if(stat.isDirectory())for(const name of fs.readdirSync(full))walk(path.join(relative,name));
  else files.push(relative);
}
roots.forEach(walk);
for(const file of files.filter(p=>p.endsWith('.php'))){
 const source=fs.readFileSync(path.join(root,file),'utf8');
 if(/define\s*\(\s*['"](?:PASS|PASSWORD)['"]\s*,\s*['"][^'"]+['"]|->Password\s*=\s*['"][^'"]+['"]/i.test(source))throw Error('Embedded credential assignment: '+file);
}
if(destination){
 if(destination.toLowerCase().startsWith(root.toLowerCase()+path.sep)||destination.toLowerCase()===root.toLowerCase()||fs.existsSync(destination))throw Error('Choose a new directory outside the source tree.');
 fs.mkdirSync(destination,{recursive:true});
 for(const file of files){const out=path.join(destination,file);fs.mkdirSync(path.dirname(out),{recursive:true});fs.copyFileSync(path.join(root,file),out);}
}
const privateFiles=files.filter(p=>/^arquivos[\\/]curriculuns[\\/]/.test(p)&&!p.endsWith('.htaccess'));
if(privateFiles.length)throw Error('Private resumes found in release');
console.log(JSON.stringify({files:files.length,privateResumes:privateFiles.length,mode:destination?'built':'checked',destination:destination||null}));
