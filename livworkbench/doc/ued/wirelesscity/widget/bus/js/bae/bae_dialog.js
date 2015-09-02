$(function(){$.bae_dialog=function(options){var defaults={id:"bae_dialog_box",title:"\u786e\u8ba4\u63d0\u793a",message:"",position:"center",width:-1,height:-1,type:"confirm",modal:true,useAnimation:false,autoClose:undefined,okEvent:undefined,cancelEvent:undefined};var plugin=this;plugin.timer=undefined;plugin.settings=$.extend({},defaults,options);var getSize=function(ele){var width=ele.offsetWidth,height=ele.offsetHeight;if(!width&&!height){var style=ele.style;var cssShow="position:absolute;visibility:hidden;display:block;left:-9999px;top:-9999px;";var cssBack="position:"+style.position+";visibility:"+style.visibility+";display:"+style.display+";left:"+style.left+";top:"+style.top;ele.style.cssText=cssShow;width=ele.offsetWidth;height=ele.offsetHeight;ele.style.cssText=cssBack}return{width:parseInt(width),height:parseInt(height)}};var each=function(a,b){for(var i=0,len=a.length;i<len;i++){b(a[i],i)}};var bind=function(obj,type,fn){if(!obj){return}if(obj.attachEvent){obj["e"+type+fn]=fn;obj[type+fn]=function(){obj["e"+type+fn](window.event)};obj.attachEvent("on"+type,obj[type+fn])}else{obj.addEventListener(type,fn,false)}};var unbind=function(obj,type,fn){if(!obj){return}if(obj.detachEvent){try{obj.detachEvent("on"+type,obj[type+fn]);obj[type+fn]=null}catch(e){}}else{obj.removeEventListener(type,fn,false)}};var stopDefault=function(e){e.preventDefault?e.preventDefault():e.returnValue=false};var getScrollPos=function(){var dd=document.documentElement,db=document.body;return{left:Math.max(dd.scrollLeft,db.scrollLeft),top:Math.max(dd.scrollTop,db.scrollTop)}};var computePosition=function(ele){var zoom=$("body").css("zoom");if(!zoom){zoom=1}var c_height=document.compatMode!="BackCompat"?document.documentElement.clientHeight:document.body.clientHeight;var pos=getSize(ele);var pos_w=pos.width*zoom,pos_h=pos.height*zoom;var l=0,t=0;switch(plugin.settings.position){case"left-top":l=2;t=2;break;case"left-bottom":l=2;t=c_height-pos_h-2;break;case"right-top":l=document.body.clientWidth-pos_w-2;t=2;break;case"right-bottom":l=document.body.clientWidth-pos_w-2;t=c_height-pos_h-2;break;case"center-bottom":l=(document.body.clientWidth-pos_w)/2;t=c_height-pos_h-2;break;default:l=(document.body.clientWidth-pos_w)/2;t=(c_height-pos_h)/2}ele.style.left=l/zoom+"px";ele.style.top=t/zoom+"px"};plugin.init=function(){plugin.dialog=document.getElementById(plugin.settings.id);plugin.cover=document.getElementById("bae_dialog_mask");if(plugin.settings.modal&&!plugin.cover){plugin.cover=document.createElement("div");plugin.cover.className="ui-bae-screen-mask";plugin.cover.id="bae_dialog_mask";plugin.cover.style.display="none";document.body.appendChild(plugin.cover);plugin.cover.addEventListener("touchmove",function(e){e.preventDefault()},false)}if(!plugin.dialog){plugin.dialog=document.createElement("div");plugin.dialog.className="ui-bae-dialog-box ui-bae-btn-corner-all ui-bae-shadow";plugin.dialog.id=plugin.settings.id;document.body.appendChild(plugin.dialog)}if(plugin.settings.width>0){plugin.dialog.style.width=plugin.settings.width}if(plugin.settings.height>0){plugin.dialog.style.height=plugin.settings.height}if(!plugin.dialogBody){plugin.dialogBody=document.createElement("div");plugin.dialog.appendChild(plugin.dialogBody)}else{while(plugin.dialogBody.lastChild){plugin.dialogBody.removeChild(plugin.dialogBody.lastChild)}}plugin.dialogBody.innerHTML="<div class='title ui-forbid-user-select'>"+plugin.settings.title+"</div><div class='message'>"+plugin.settings.message+"</div>";var buttons=document.createElement("div");buttons.className="buttons";if(plugin.settings.type=="confirm"){buttons.innerHTML="<a data-inline='true'>\u786e\u5b9a</a>&nbsp;<a data-inline='true'>\u53d6\u6d88</a>"}else{buttons.innerHTML="<a data-inline='true'>\u786e\u5b9a</a>"}plugin.dialogBody.appendChild(buttons);var btns=buttons.getElementsByTagName("a");if(plugin.settings.type=="confirm"){$(btns).bae_button({width:150,height:27})}else{btns[0].style.width="90%";$(btns[0]).bae_button()}bind(btns[0],"click",function(){plugin.close();if(plugin.settings.okEvent){setTimeout(function(){plugin.settings.okEvent()},10)}});bind(btns[1],"click",function(){plugin.close();if(plugin.settings.cancelEvent){setTimeout(function(){plugin.settings.cancelEvent()},10)}});computePosition(plugin.dialog);if(plugin.settings.autoClose){var delay=5000;try{delay=parseInt(plugin.settings.autoClose)}catch(e){}plugin.timer=setTimeout(function(){plugin.close()},delay)}};plugin.mouse=function(evt){stopDefault(evt||window.event);var p=getScrollPos(),left=p.left,top=p.top;scroll(left,top)};plugin.open=function(){each(["DOMMouseScroll","mousewheel","scroll","contextmenu"],function(o,i){bind(document,o,plugin.mouse)});if(plugin.settings.useAnimation){$(plugin.dialog).fadeIn();if(plugin.settings.modal){$(plugin.cover).fadeIn()}else{plugin.cover.style.display="none"}}else{plugin.dialog.style.display="block";if(plugin.settings.modal){plugin.cover.style.display="block"}else{plugin.cover.style.display="none"}}};plugin.close=function(){plugin.settings.autoClose=undefined;if(undefined!=plugin.timer){clearTimeout(plugin.timer);plugin.timer=undefined}each(["DOMMouseScroll","mousewheel","scroll","contextmenu"],function(o,i){unbind(document,o,plugin.mouse)});if(plugin.settings.useAnimation){$(plugin.dialog).fadeOut();$(plugin.cover).fadeOut()}else{plugin.dialog.style.display="none";plugin.cover.style.display="none"}};$(window).resize(function(){computePosition(plugin.dialog)});plugin.init();plugin.open();return{close:function(){plugin.close()},destory:function(){document.body.removeChild(plugin.dialog);if(plugin.cover){document.body.removeChild(plugin.cover)}delete plugin}}}});
