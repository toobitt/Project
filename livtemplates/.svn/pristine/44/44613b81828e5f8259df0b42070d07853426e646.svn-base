

{code}
  $image_resource = RESOURCE_URL;
{/code}

{js:vod_upload_pic_handler}
<script type="text/javascript">

   
   var old_obj = "";
   function hg_select_pic(obj)
   {
	  if(old_obj)
	  {
		  old_obj.css("border","");
	  }
	  old_obj = $(obj);
	  $(obj).css("border","1px solid blue");
	  var link = $(obj).attr("src");
	  $("#pic_face").attr("src",link);
	  $("#img_src").val(link);
   }

   function hg_cancel(source_img)
   {
	  $("img[id^='list_pic_']").css("border","");
	  $("#pic_face").attr("src",source_img);
	  $("#img_src").val("");
	  $("#img_src_cpu").val("");
   }

   var mid = '{$_INPUT['mid']}';
   upload_preview(mid);

  
</script>

<style type="text/css">
  .pic_list{width:auto;height:auto;}
  .pic_list_l{width:125px;height:180px;float:left;margin-top:10px;border-right:1px solid #E5E5E5;}
  .pic_list_r{width:500px;height:auto;float:left;margin-left:8px;position:relative;}
  .button_save{position:absolute;border-top:1px solid #E5E5E5;left:0px;top:151px;width:500px;}
  #add_img_content{width:80px;height:60px;border:1px solid gray;float:left;margin-top:10px;margin-left:10px;}
  .every_pic{cursor:pointer;}
  .img_box{border:1px solid gray;background:url({$image_resource}loading.gif) no-repeat 22px 15px;width:80px;height:60px;float:left;margin-left:10px;margin-top:10px;}
</style>

<div id="show_pic_list" class="pic_list">
   <div id="show_list_left" class="pic_list_l">
     <img src="{$formdata['source_img']}" width="118px" height="88px"  id="pic_face" />
   </div>
   <div id="show_list_right" class="pic_list_r">
    {foreach $formdata['new_img'] as $k => $v}
	<div class="img_box"><img src="{$v}" class="every_pic" id="list_pic_{$k}" width="80px" height="60px" onclick="hg_select_pic(this);"  /></div>
	{/foreach}
	 <form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data"  id="vod_img_form"  name="vod_img_form"  onsubmit="return hg_ajax_submit('vod_img_form', '');">	
	  <div id="add_img_content">
		<div id="add_from_compueter"></div>
	  </div>
	  <div class="button_save">
		    <input type="submit" class="button_2" name="save" value="保存" id="save"   style="float:left;margin-top:8px;margin-left:10px;" />
		    <input type="reset"  class="button_2" name="cancel" value="取消" id="cancel"  style="float:left;margin-left:10px;margin-top:8px;"  onclick="hg_cancel('{$formdata[source_img]}');" />
			<input type="hidden" name="img_src" value=""  id="img_src"  />
			<input type="hidden" name="img_src_cpu" value="" id="img_src_cpu" /> 
			<input type="hidden" value="update_img" name="a" />
			<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	  </div>
	</form>  
   </div>
</div>















