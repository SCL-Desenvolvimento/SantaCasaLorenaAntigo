(function(){
  'use strict';
  const instances={},pickers=new Map();
  function mediaPicker(callback){
    const id=crypto.randomUUID(),popup=window.open('media.php?picker='+encodeURIComponent(id),'scl-media','width=1000,height=750');
    if(popup)pickers.set(id,{callback,popup});
  }
  window.addEventListener('message',event=>{
    if(event.origin!==location.origin||event.data?.type!=='scl-media')return;
    const picker=pickers.get(event.data.id);if(!picker||event.source!==picker.popup)return;
    const url=new URL(event.data.url,location.href);if(url.origin!==location.origin)return;
    picker.callback(url.href,{text:'Documento'});pickers.delete(event.data.id);
  });
  window.SCLEditor={instances,replace:function(id){
    const field=document.getElementById(id)||document.getElementsByName(id)[0];if(!field)return;
    if(instances[id])return instances[id];
    let changed=false,editor=null,destroyed=false;
    const facade={getData:()=>changed&&editor?editor.getContent():field.value,setData:value=>{field.value=value;if(editor)editor.setContent(value);},destroy:()=>{destroyed=true;if(editor)editor.remove();delete instances[id];}};
    instances[id]=facade;
    tinymce.init({target:field,license_key:'gpl',base_url:'../resources/vendor/tinymce',suffix:'.min',language:'pt-BR',height:360,
      plugins:'code link image table lists autolink nonbreaking searchreplace fullscreen',
      toolbar:'undo redo | blocks | bold italic | bullist numlist | link image table sclgallery | code fullscreen',
      promotion:false,branding:false,convert_urls:false,relative_urls:false,
      content_style:'.ck-galleria,.rpc-galleria{padding:16px;background:#edf5f5;border:1px dashed #388} .ck-galleria:before,.rpc-galleria:before{content:"Galeria de imagens do site";color:#266}',
      extended_valid_elements:'input[value|type|class],div[class|data-gallery-id|contenteditable]',
      file_picker_types:'file image',file_picker_callback:mediaPicker,
      setup:function(instance){editor=instance;instance.on('init',()=>{if(destroyed)instance.remove();else instance.setContent(field.value);});
        instance.on('change input undo redo',()=>{changed=true;document.sclDirty=true;field.value=instance.getContent();});
        instance.ui.registry.addButton('sclgallery',{text:'Galeria',tooltip:'Inserir galeria do site',onAction:async()=>{
          try{
            const response=await fetch('galerias.php',{credentials:'same-origin'});if(!response.ok)throw Error();const galleries=await response.json();
            instance.windowManager.open({title:'Galeria do site',body:{type:'panel',items:[{type:'selectbox',name:'gallery',label:'Galeria',items:galleries.map(item=>({text:item.nome,value:String(item.id_galeria)}))}]},buttons:[{type:'cancel',text:'Cancelar'},{type:'submit',text:'Inserir',primary:true}],onSubmit:dialog=>{
              const id=Number(dialog.getData().gallery);if(Number.isSafeInteger(id)&&id>0)instance.insertContent('<div class="ck-galleria"><input type="hidden" value="'+id+'"></div>');dialog.close();
            }});
          }catch{instance.notificationManager.open({text:'Não foi possível carregar as galerias. Tente novamente.',type:'error'});}
        }});
      }
    }).catch(()=>{editor=null;field.hidden=false;field.style.display='';});
    return facade;
  }};
  document.addEventListener('submit',()=>{for(const [id,instance] of Object.entries(instances)){const field=document.getElementById(id)||document.getElementsByName(id)[0];if(field)field.value=instance.getData();}},true);
})();
