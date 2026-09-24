/* Maintained adapters between existing form controllers and modern components. */
(function ($) {
  'use strict';
  $.fn.sclModal=function(action){return this.each(function(){bootstrap.Modal.getOrCreateInstance(this)[action||'show']();});};
  $.fn.sclTab=function(){return this.each(function(){bootstrap.Tab.getOrCreateInstance(this).show();});};
  $.fn.sclSelect=function(options){
    options=options||{};
    return this.each(function(){
      if(this.tagName!=='SELECT')return;
      if(this.tomselect){this.tomselect.sync();return;}
      const element=this,settings={create:!!options.tags,plugins:element.multiple?['remove_button']:[],placeholder:options.placeholder||'Selecione',maxOptions:100};
      if(options.ajax){
        settings.valueField='id';settings.labelField='text';settings.searchField='text';
        settings.load=function(query,callback){
          const data=options.ajax.data?options.ajax.data({term:query}):{q:query};
          $.ajax({url:options.ajax.url,data,dataType:'json'}).done(function(result){
            const mapped=options.ajax.processResults?options.ajax.processResults(result):{results:result};callback(mapped.results||[]);
          }).fail(function(){callback();});
        };
      }
      const select=new TomSelect(element,settings);
      $(element).on('change.scl',function(){if(!select.isSyncing){select.isSyncing=true;select.sync();select.isSyncing=false;}});
    });
  };
  window.sclConfirm=function(options,callback){
    if(typeof options==='string')options={message:options,callback};
    const dialog=document.createElement('dialog');dialog.className='scl-dialog';
    const text=document.createElement('p');text.textContent=options.message||'Confirmar operação?';dialog.append(text);
    const actions=document.createElement('div');actions.className='d-flex gap-2 justify-content-end';dialog.append(actions);
    let finished=false;
    function finish(value){if(finished)return;finished=true;dialog.close();dialog.remove();if(options.callback)options.callback(value);}
    for(const [value,label,kind] of [[false,options.buttons?.cancel?.label||'Cancelar','secondary'],[true,options.buttons?.confirm?.label||'Confirmar','primary']]){
      const button=document.createElement('button');button.type='button';button.className='btn btn-'+kind;button.textContent=label;button.onclick=()=>finish(value);actions.append(button);
    }
    dialog.addEventListener('cancel',event=>{event.preventDefault();finish(false);});document.body.append(dialog);dialog.showModal();actions.firstChild.focus();
  };
  $(function(){
    function accessibleActions(root){
      root.querySelectorAll('a[onclick]:not([href])').forEach(link=>{link.setAttribute('role','button');link.tabIndex=0;});
    }
    accessibleActions(document);
    const content=document.querySelector('.content');
    if(content)new MutationObserver(()=>accessibleActions(content)).observe(content,{childList:true,subtree:true});
    document.addEventListener('keydown',event=>{
      if((event.key==='Enter'||event.key===' ')&&event.target.matches('a[onclick]:not([href])')){event.preventDefault();event.target.click();}
    });
    // TinyMCE dialogs live outside Bootstrap's modal element.
    document.addEventListener('focusin',event=>{if(event.target.closest('.tox-tinymce-aux'))event.stopImmediatePropagation();});
    const icons={'fa-list':['☷','Listar'],'fa-plus':['+','Adicionar'],'fa-refresh':['↻','Atualizar'],'fa-file-excel-o':['↓','Exportar'],'fa-share':['↗','Abrir'],'fa-image':['▧','Imagem'],'fa-file-text':['▤','Documento']};
    document.querySelectorAll('.content .fa').forEach(icon=>{for(const [name,[symbol,label]] of Object.entries(icons)){if(icon.classList.contains(name)){icon.textContent=symbol;icon.setAttribute('aria-hidden','true');const button=icon.closest('a,button');if(button&&!button.textContent.replace(symbol,'').trim())button.setAttribute('aria-label',label);break;}}});
    document.querySelectorAll('.nav-tabs a').forEach(link=>link.classList.add('nav-link'));
    document.querySelectorAll('.nav-tabs li.active').forEach(item=>{item.classList.remove('active');item.querySelector('a')?.classList.add('active');});
    document.querySelectorAll('.tab-pane.active').forEach(pane=>pane.classList.add('show'));
    document.querySelectorAll('.nav-tabs').forEach(nav=>{if(!nav.querySelector('a.active')){const first=nav.querySelector('a[href^="#"]');if(first)bootstrap.Tab.getOrCreateInstance(first).show();}});
    document.querySelectorAll('input.minimal').forEach(input=>input.classList.add('form-check-input'));
    const route=new URL(location.href).searchParams.get('exe');
    document.querySelectorAll('.treeview-menu a[href]').forEach(link=>{
      if(link.getAttribute('href').startsWith('#'))return;
      const target=new URL(link.href).searchParams.get('exe');
      if(!target||!route||target.split('/')[0]!==route.split('/')[0]||(route.startsWith('paginas/')&&target!==route))return;
      let parent=link.parentElement.closest('.treeview');
      while(parent){parent.classList.add('is-open');parent=parent.parentElement.closest('.treeview');}
    });
    document.querySelectorAll('.treeview > a').forEach(link=>{
      link.setAttribute('role','button');link.setAttribute('aria-expanded',String(link.parentElement.classList.contains('is-open')));
      link.addEventListener('click',event=>{event.preventDefault();const open=link.parentElement.classList.toggle('is-open');link.setAttribute('aria-expanded',String(open));});
    });
    document.querySelector('[data-sidebar-toggle]')?.addEventListener('click',function(){const open=document.body.classList.toggle('sidebar-open');this.setAttribute('aria-expanded',String(open));});
    document.querySelectorAll('.admin-sidebar a[href]').forEach(link=>link.addEventListener('click',()=>{
      if(link.getAttribute('href')==='#')return;
      document.body.classList.remove('sidebar-open');document.querySelector('[data-sidebar-toggle]')?.setAttribute('aria-expanded','false');
    }));
    $.extend(true,$.fn.dataTable.defaults,{language:{infoFiltered:'(filtrado de _MAX_ registros)',loadingRecords:'Carregando…',processing:'Processando…',entries:{_: 'registros',1:'registro'},aria:{orderable:': ativar para ordenar',orderableReverse:': inverter ordenação',orderableRemove:': remover ordenação',paginate:{first:'Primeira',last:'Última',next:'Próxima',previous:'Anterior',number:'Página '}},search:'Buscar:',lengthMenu:'Mostrar _MENU_',info:'_START_ a _END_ de _TOTAL_',infoEmpty:'Nenhum registro',zeroRecords:'Nenhum resultado',emptyTable:'Nenhum registro',paginate:{first:'Primeira',last:'Última',next:'Próxima',previous:'Anterior'}}});
  });
})(jQuery);
