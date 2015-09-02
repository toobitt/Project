{template:head}
<style type="text/css">
  .version_diff{width:98%;margin:0 auto;margin-top:10px;height:400px;position:relative;}
  .version_diff .l{width:46%;height:100%;float:left;}
  .version_diff .r{width:46%;height:100%;float:right;}
</style>
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" >
<div class="version_diff">
  <div class="l">
  	<span>{$formdata[0]['version_name']}</span>
  	<textarea style="width:100%;height:380px;">{$formdata[0]['content']}</textarea>
  </div>
  <div class="r">
  	<span>{$formdata[1]['version_name']}</span>
  	<textarea style="width:100%;height:380px;">{$formdata[1]['content']}</textarea>
  </div>
  <input type="button" value="=>" style="position:absolute;left:48%;top:200px;" class="button_2" />
</div>
<div class="version_diff" style="height:100px;">
 	<textarea style="width:100%;height:100%;" name="diff_content">{$formdata[2]['diff_content']}</textarea>
</div>
<input type="hidden" name="a" value="save_diff" />
<input type="hidden" name="id" value="{$formdata[0]['id']},{$formdata[1]['id']}" />
<input type="hidden" name="infrm" value="1" />
<input type="submit" class="button_6" value="保存差异" style="margin-left:10px;margin-top:16px;" />
</form>
{template:foot}