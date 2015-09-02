<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">描述：</label>
	<input type="text" name="title"   id="title"  style="width:450px;"  value="{$formdata['title']}"/>
</div>

<div style="width:100%;margin-top:10px;">
	<label  style="float:left;margin-left:25px;">封面：</label>
	<div id="log_box" style="float:left;">
	{code}
		$log = '';
		$serialize_logo = '';
		$log = $formdata['host'] . $formdata['dir'] .'100x75/'. $formdata['filepath'] . $formdata['filename'];
		$serialize_logo = serialize($formdata); 
	{/code}
	{if $formdata['host']}
		<img style="float:left;" src="{$log}"  width="100" height="75" />
		<input type="hidden" name="name" value="{$formdata['name']}" />
		<input type="hidden" name="host" value="{$formdata['host']}" />
		<input type="hidden" name="dir" value="{$formdata['dir']}" />
		<input type="hidden" name="filepath" value="{$formdata['filepath']}" />
		<input type="hidden" name="filename" value="{$formdata['filename']}" />
	{/if}
	</div>
	<div id="circle_upload" style="float: left;"></div>图片大小建议尺寸2100*1400
</div>
<div class="clear"></div>

<div style="width:100%;margin-top:10px;">
	<input type="submit"  value="提交" class="button_6" style="margin-left:441px;" />
</div>
<input type="hidden" value="{code} echo $_INPUT['id'] ? 'update' : 'create';{/code}" name="a" />
<input type="hidden" value="{$formdata['id']}"  name="id" />
<!--<input type="hidden" value="{$formdata['type']}"  name="type" /> -->
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
