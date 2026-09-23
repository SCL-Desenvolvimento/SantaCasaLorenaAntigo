const fs = require('fs'), path = require('path');
const walk = d => fs.readdirSync(d,{withFileTypes:true}).flatMap(e=>e.isDirectory()?walk(path.join(d,e.name)):[path.join(d,e.name)]);
for (const dir of ['arquivos','hclorena.org.br','HOSPITALDASCLINICASLORENA.ORG.BR','santacasalorena.org.br','zITK0ffeMM']) {
  if (!fs.existsSync(dir)) continue;
  const types = {}, executable = [];
  let empty = 0, pdfHeaderMismatch = 0, resumeFiles = 0, nestedResumes = 0;
  for (const f of walk(dir)) {
    const e = path.extname(f).toLowerCase() || '(none)'; types[e]=(types[e]||0)+1;
    if (/\.(php[0-9]?|phtml|phar|cgi|pl|py|sh)(\.|$)/i.test(f)) executable.push(f);
    if (fs.statSync(f).size === 0) empty++;
    if (e === '.pdf') {
      const buffer=Buffer.alloc(1024),fd=fs.openSync(f,'r'); const size=fs.readSync(fd,buffer,0,1024,0);fs.closeSync(fd);
      if (!buffer.subarray(0,size).includes(Buffer.from('%PDF-'))) pdfHeaderMismatch++;
    }
    if (f.includes(path.join('arquivos','curriculuns')+path.sep) && e!=='.htaccess' && path.basename(f)!=='.htaccess') {
      resumeFiles++;if(path.relative(path.join('arquivos','curriculuns'),f).includes(path.sep))nestedResumes++;
    }
  }
  console.log(JSON.stringify({directory:dir, extensions:types, executableCount:executable.length,empty,pdfHeaderMismatch,resumeFiles,nestedResumes}));
}
console.log('Potential credential assignments (paths only):');
for (const f of [...walk('_app'),...walk('admin'),...walk('includes')].filter(f=>/\.php$/i.test(f))) {
  if (/(define\s*\(\s*['"](?:PASS|PASSWORD)['"]\s*,\s*['"]|->Password\s*=\s*['"])/i.test(fs.readFileSync(f,'utf8'))) console.log(f);
}
