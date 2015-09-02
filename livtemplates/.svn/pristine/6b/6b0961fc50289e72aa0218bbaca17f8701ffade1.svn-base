function bindFileInput(customerBtnId,fileInputId,textInputId){  
  
    //添加事件监听函数  
  
    var addListener=function(element,eventName,funName){  
  
       if(window.addEventListener){  
  
           element.addEventListener(eventName,funName,false);  
  
       }else if(window.attachEvent){  
  
           element.attachEvent('on'+eventName,funName);  
  
       }else{  
  
           element['on'+eventName]=funName;  
  
       }  
  
    }  
  
    var render=function(){  
  
       var fileInput=document.getElementById(fileInputId),  
  
       customerBtn=document.getElementById(customerBtnId);  
  
       fileInput.style.cssText="filter:alpha(opacity=0);opacity:0;"+  
  
       "position:absolute;display:none;cursor:pointer;z-index:10;";  
  
       fileInput.size=1;  
  
       //当用户选择文件后，把文件框的value值显示到指定的文本框中  
  
       addListener(fileInput,'change',function(){  
           document.getElementById(textInputId).innerHTML=fileInput.value;  
  
       });  
  
       //当鼠标移上自定义按钮时，显示文件框  
  
       addListener(customerBtn,'mouseover',function(){  
  
           fileInput.style.display='block';  
  
       });  
  
       //当鼠标在按钮上移动时，让文件框跟随鼠标移动  
  
       addListener(customerBtn,'mousemove',function(event){  
  
           event=event||window.event;  
  
           fileInput.style.left=event.clientX-50+'px';  
  
           fileInput.style.top=event.clientY-10+'px';  
  
       });  
  
       //当鼠标离开文件框时，让文件框隐藏  
  
       addListener(fileInput,'mouseout',function(){  
  
           fileInput.style.display='none';  
  
       });  
  
    }  
  
    addListener(window,'load',render);  
  
}  