<h3>{$formdata['jsname']}</h3>
<form action="run.php?mid={$_INPUT['mid']}&a=savejs" method="post" enctype="multipart/form-data" onsubmit="return hg_ajax_submit('savejs')" id="savejs" name="savejs">
	<textarea style="width:500px;height:550px;border:1px solid #88a7f7;border-radius:5px;box-shadow:0px 0px 5px  #88a7f7;" name="jstext">{$formdata['jstext']}</textarea>
	<input type="hidden" name="a" value="savejs">
	<input type="hidden" name="mid" value="{$_INPUT['mid']}">
	<input type="hidden" name="jsname" value="{$formdata['jsname']}">
	<input type="hidden" name="html" value="true"/>
	<br />
	<input type="hidden" value="name" value="{$formdata['jsname']}">
	<br />
	<input type="submit" value="编辑完成" class="button_4"/>&nbsp;&nbsp;<input class="button_4" type="button" value="取消编辑" onclick="hg_hidden_js()"/>
</form>