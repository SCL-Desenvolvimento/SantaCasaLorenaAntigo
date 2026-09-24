(()=>{
'use strict';let sequence=0,pageLoaded=false;
window.sclSyncEditors=()=>{for(const [id,editor]of Object.entries(window.SCLEditor?.instances||{})){const field=document.getElementById(id)||document.getElementsByName(id)[0];if(field)field.value=editor.getData();}};
window.sclRefreshCounters=()=>document.querySelectorAll('textarea[maxlength]').forEach(field=>{const counter=document.getElementById(field.id+'-counter');if(counter)counter.textContent=field.value.length+' / '+field.maxLength;});
function enhance(root=document){
 root.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"]),textarea,select').forEach(field=>{
 if(field.closest('.tox,.ts-wrapper,.dt-container')||field.dataset.workspaceReady)return;field.dataset.workspaceReady='1';if(!field.id)field.id='admin-field-'+(++sequence);field.classList.add(field.tagName==='SELECT'?'form-select':'form-control');
 const group=field.closest('.form-group');const label=group?.querySelector('label');if(label&&!label.contains(field))label.htmlFor=field.id;
 if(field.tagName==='TEXTAREA'&&field.name.endsWith('_seo'))field.maxLength=155;
 if(field.maxLength>0&&field.tagName==='TEXTAREA'){const counter=document.createElement('small');counter.className='field-counter';counter.id=field.id+'-counter';field.setAttribute('aria-describedby',counter.id);field.after(counter);const update=()=>counter.textContent=field.value.length+' / '+field.maxLength;field.addEventListener('input',update);update();}
 if(field.type==='file'&&field.closest('.modal[id^="edite-"]'))field.required=false;
 if(field.type==='file'&&!field.accept){field.accept=/pdf|^balanco_image$|^download_manual_paciente_image$/.test(field.name)?'.pdf':'.jpg,.jpeg,.png';}
 });
 root.querySelectorAll('.modal').forEach(modal=>{const title=modal.querySelector('.modal-title');if(title){const names={galeria_sobre:'imagem da página Sobre',galeria_acao:'imagem de ações sociais',galeria_humanizacao:'imagem de humanização'};const name=names[modal.id.replace(/^(create|edite)-/,'')];if(name)title.textContent=(modal.id.startsWith('create')?'Adicionar ':'Editar ')+name;if(!title.id)title.id='admin-modal-title-'+(++sequence);modal.setAttribute('aria-labelledby',title.id);}});
 root.querySelectorAll('img').forEach(img=>{if(!img.getAttribute('src')){img.hidden=true;img.dataset.emptyPreview='1';}else if(img.dataset.emptyPreview){img.hidden=false;delete img.dataset.emptyPreview;}});
}
function rich(){if(window.jQuery?.active||(document.getElementById('paginasForm')&&!pageLoaded))return;document.querySelectorAll('textarea[name*="-texto"]').forEach(field=>{if(field.offsetParent&&field.id&&!SCLEditor.instances[field.id])SCLEditor.replace(field.id);});}
window.addEventListener('beforeunload',event=>{if(document.sclDirty){event.preventDefault();event.returnValue='';}});
for(const type of ['input','change'])document.addEventListener(type,event=>{if(event.target.closest('.record-form,#paginasForm,[data-gallery-form],.modal form'))document.sclDirty=true;});
new MutationObserver(records=>{for(const record of records){const img=record.target;if(img.tagName==='IMG'&&img.dataset.emptyPreview&&img.getAttribute('src')){img.hidden=false;delete img.dataset.emptyPreview;}}}).observe(document.documentElement,{subtree:true,attributes:true,attributeFilter:['src']});
document.addEventListener('click',event=>{if(event.target.closest('[onclick],button[type="submit"]'))window.sclSyncEditors();},true);
document.addEventListener('shown.bs.tab',rich);
document.addEventListener('shown.bs.modal',event=>{enhance(event.target);event.target.querySelectorAll('textarea[name="descricao"]').forEach(field=>{const editor=SCLEditor.instances[field.id]||SCLEditor.replace(field.id);editor?.setData(field.value);});});
document.addEventListener('DOMContentLoaded',()=>{enhance();rich();document.querySelectorAll('.box-footer button').forEach(button=>button.classList.remove('col-md-4'));});
if(window.jQuery){jQuery(document).ajaxStop(()=>{pageLoaded=true;enhance();window.sclRefreshCounters();rich();});jQuery(document).ajaxError((event,xhr)=>{if(xhr.status===401||xhr.status===403)return;let box=document.getElementById('mensagem_evento');if(box){box.className='alert alert-danger';box.setAttribute('role','alert');box.textContent='Não foi possível concluir a solicitação. Confira sua conexão e tente novamente.';}});}
})();
