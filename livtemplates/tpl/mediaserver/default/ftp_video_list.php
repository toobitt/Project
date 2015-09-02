{template:head}
{code}
$list = $ftp_video_list[0];
//hg_pre($vod_config);
{/code}
{css:2013/button}
{css:ftp}
<script type="text/javascript">
     function getsize(){
    	 setInterval(function(){
        /*	 var file_name = $('.file').map(function(){
        		 return $(this).attr('_name');
    		 }).get();
        	 var filesize =  $('.file').map(function(){
                 return $(this).find('.size_change').attr('_size');
        	 }).get();
        	 var fileName = file_name.join(',');*/
        	 var file=$('.file').map(function(){
        		 return{
            		 name :$(this).attr('_name'),
                     size : $(this).find('.size_change').attr('_size')
            	 }
    		 }).get();
     		 var file_name = [],
     		     filesize=[];
     		 $.each(file,function(key ,value){
         		 file_name.push(value['name']);
     			 filesize.push(value['size']);
     		 });
             var fileName = file_name.join(',');
        	 var info={
                	 dir : $.globalpath,
               		 filename : fileName
        	 };
        	 var url ='./run.php?mid=' + gMid + '&a=getFileSize';
			 $.post(url,info,function(data){
				 var data = data[0];
             //  var name = [];
                 var size = [];
              	 $.each(data,function(key , value){
             //      name.push(key);
                	 size.push(value);
             	 });
               /*for(i=0;i<size.length;i++){
                   	 var obj = $('div[_name="'+file_name[i]+'"]');
               		 var txt = size[i]['filesize'];
               		 obj.find('.size').html(txt);
               		 obj.find('span').attr({'_size':size[i]['byte_size']});
                	 if(size[i]['byte_size']>filesize[i]){
                		 obj.find('.file_png').addClass('pic');
                		 obj.find('.file_size').css('background','#e2e2e2');
                		 obj.find('em').css({'border':'6px solid #e2e2e2','border-color':'transparent transparent #e2e2e2'});
                		 obj.attr({'onclick':''});
 	 			 	 }else{
 	 			 		 obj.find('.file_png').removeClass('pic');
 	 			 		 obj.find('.file_size').css('background','#aed2f2');
 	 			 		 obj.find('em').css({'border':'6px solid #aed2f2','border-color':'transparent transparent #aed2f2'});
 	 			 		 obj.attr({'onclick':'check(this)'});
 	 	 			 }
         		 }*/
               	 $.each(size,function(index){
                   	 var obj = $('div[_name="'+file_name[index]+'"]'),
                    	 txt = size[index]['filesize'];
                     obj.find('.size').html(txt);
                     obj.find('span').attr({'_size':size[index]['byte_size']});
                     if(size[index]['byte_size']>filesize[index]){
                         obj.find('.file_png').addClass('pic');
                     	 obj.find('.file_size').css('background','#e2e2e2');
                     	 obj.find('em').css({'border':'6px solid #e2e2e2','border-color':'transparent transparent #e2e2e2'});
                     	 obj.attr({'onclick':''});
      	 			 }else{
      	 			 	 obj.find('.file_png').removeClass('pic');
      	 			 	 obj.find('.file_size').css('background','#aed2f2');
      	 			 	 obj.find('em').css({'border':'6px solid #aed2f2','border-color':'transparent transparent #aed2f2'});
      	 			 	 obj.attr({'onclick':'check(this)'});
      	 	 		 }
      	 	 	 })  	
     		 },'json');
		},1000);
	 }
 	
	 function goToDeep(obj){
		 var orignalDir = $('#videopath').val();
		 if(orignalDir){
			 orignalDir = orignalDir+'/';
		 }
		 var dir = orignalDir + $(obj).text();
		 var url = "run.php?mid="+gMid+"&dir="+dir+"&infrm=1&nav=1";  
		 window.location.href = url;
	 }

	 function submitVideo(){
		 var arr = new Array();
		 $('[name="dir[]"]').each(function(){
			 if($(this).attr('checked')){
				 arr.push($(this).val());
			 }
		 });
		 if(arr.length){
			 var obj=$('.result-tip');
			 var tip="视频正在提交,请稍等...";
			 ajaxTip(obj, tip);
			 var strFile = arr.join();
			 var dir = $('#videopath').val();
			 var water_id = $('input[name="water_id"]').val();
			 var server_id = $('input[name="server_id"]').val();
			 var mosaic_id = $('input[name="mosaic_id"]').val();
			 var water_pos = $('input[name="water_pos"]').val();
			 var no_water = $('input[name="no_water"]').val();
			 var vod_config_id = $('input[name="vod_config_id"]').val();
			 
			 var url = 'run.php?mid='+gMid+'&a=submit&videofiles='+strFile+'&dir='+dir 
			 +'&water_id=' + water_id
			 +'&server_id=' + server_id
			 +'&mosaic_id=' + mosaic_id
			 +'&water_pos=' + water_pos
			 +'&no_water=' + no_water
			 +'&vod_config_id=' + vod_config_id;
			 
			 hg_ajax_post(url,'','','ftp_callback');
		 }else{
			 var obj=$('.result-tip');
			 var tip="您未选择视频文件，不能提交";
		 	 ajaxTip(obj, tip);
		 }
	 }

	 function ftp_callback(obj){
		 var arr = new Array();
		 $('[name="dir[]"]').each(function(){
			 if($(this).attr('checked')){
				 $('#file_list_'+$(this).attr('_id')).remove();
			 }
		 });
		 var obj=$('.result-tip');
		 var tip="视频提交成功";
		 $('#livUpload_div').hide();	
		 ajaxTip(obj, tip);
	 }
	
	 function check(obj){
		 var objc = $(obj).find('.select');
		 objc.toggleClass('current').toggle();
		 if(objc.hasClass('current')){
			 $(obj).find('input').attr('checked',true);
			 $(obj).addClass('current');
			 $('#livUpload_div').show();	
			 
         }else{
        	 $(obj).find('input').attr('checked',false);
        	 $(obj).removeClass('current');
        	 if( !$('.file').hasClass('current') ){
        		 $('#livUpload_div').hide();
	       	 }
         }
	 }
	
	 function ajaxTip(obj,tip){
		 obj.html(tip).css({'opacity':1,'z-index':100001});
		 setTimeout(function(){
				 obj.css({'opacity':0,'z-index':-1});
		 },2000);
	 }

</script>
<!-- <style type="text/css">
	.path_show{width:91%;height:30px;border:1px solid white;margin-left:48px;margin-top:14px;clear:both;border-bottom: 1px dashed #cccccc;}
	.dircss{line-height:30px;cursor:pointer;width:84px;height:112px;float:left;margin-left:44px;margin-top:15px;position:relative;text-align: center;}
	.dircss span{width:84px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
	.folder{background:url({$RESOURCE_URL}folder.png);width:100%;height:60%;border:1px solid white;}
	.folder:hover{background:url({$RESOURCE_URL}folder_active.png);width:100%;height:60%;border:1px solid white;}
	.file_png{background:url({$RESOURCE_URL}file.png);width:100%;height:60%;}
	.file_png:hover{background:url({$RESOURCE_URL}file_active.png);width:100%;height:60%;}
	.navcss{border-left: 1px solid white;color: #232323;font-size: 14px;margin-left: 3px;}
	.navcss span{color:#cccccc}
	.select{background:url({$RESOURCE_URL}select.png);width:21px;height:21px;position: absolute;top: -10px;left: 6px;display:none;}
	.is_submit{background:url({$RESOURCE_URL}is_submit_1.png);width: 16px;height: 45px;position: absolute;top: 0px;left: 50px;}
	.navcss:hover{color:#cfe4f7}
	.file input{float:left;margin-top:11px;display:none}
	.current{opacity:1}
	.pic{background:url({$RESOURCE_URL}file-none.png);}
	.pic:hover{background:url({$RESOURCE_URL}file-none.png);}
	.result-tip{overflow:hidden;z-index:-1;position:absolute;top:215px;left:50%;font-size:24px;background:#fff;text-align:center;margin-left:-200px;border-radius:4px;width:350px;height:60px;border:4px solid #6ba4eb;box-shadow:0 0 4px #ccc;line-height:60px;transition:all 3s;-webkit-transition:all 3s;-moz-transition:all 3s;opacity:0;}
    .file_size{width: 82px;height: 26px;position: absolute;top: 74px;background:#aed2f2;border-radius: 2px;display:none;}
    .file_size span{width:82px;height:26px;}
    .file_size em{border:6px solid #aed2f2; border-color: transparent transparent #aed2f2;position:absolute;top:-12px;left:33px;}
</style>-->
<body onload="getsize()">
	<div class="path_show" >
		<a href="run.php?mid={$_INPUT['mid']}&infrm=1&nav=1" class="navcss" >Home</a>
		{if $list['dir_path']}
			{foreach $list['dir_path'] AS $_key => $_val}
			<a href="run.php?mid={$_INPUT['mid']}&dir={$_val}&infrm=1&nav=1" class="navcss" title="{$_key}" ><span>></span> {$_key}</a>
			{/foreach}
		{/if}
	</div>
	<div style="width:100%;margin-top:3px;float:left;margin-bottom:20px;margin-left:0px;">
		{foreach $list['dir'] AS $k => $v}
		<div class="dircss" onclick="goToDeep(this);" >
			<p class="folder" ></p>
			<span title="{$v}">{$v}</span>
		</div>
		{/foreach}
		{foreach $list['file'] AS $k => $v}
		<div class="dircss file" id="file_list_{$k}" _name="{$v['filename']}" onclick="check(this)">
			<input type="checkbox" value="{$v['filename']}" name="dir[]"  _id="{$k}" />
			<p class="file_png"  title="{$v['filename']}"></p>
			<p class="select" ></p>
			{if $v['is_submit']}
			<p class="is_submit"></p>
			{/if}
			<span class="size_change" title="{$v['filename']}" _size="{$v['byte_size']}">{$v['filename']}</span>
			<div class="file_size">
			    <span class="size"></span>
			    <em></em>
			</div>
		</div>
		{/foreach}
	</div>
	
	<!-- 提交转码的一些配置 -->
	{template:unit/fast_set}
	<!-- 提交转码的一些配置 -->
	
	
	<div style="margin-left:48px;margin-bottom:30px;">
		<div class="save-button"  onclick="submitVideo();">提交视频</div>
		<input id="videopath" type="hidden" value="{$_INPUT['dir']}" />
	</div>
	     <span class="result-tip"></span>
</body>
{template:foot}
<script type="text/javascript">
$(function($){
    $.globalpath = {code} echo json_encode($_val);{/code};
});
$(function($){
	$('.file').on("mouseover",function(event){
		var self = $(event.currentTarget);
  		var obj = self.find('.file_size');
   		obj.show();
	});
	$('.file').on("mouseout",function(event){
		var self = $(event.currentTarget);
		var obj = self.find('.file_size');
		obj.hide();
	});
});
</script>