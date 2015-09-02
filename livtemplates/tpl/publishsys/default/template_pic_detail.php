{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}"  style="width:100%;min-height:50px;background:white;overflow:hidden;">
	<div class="ajax_view_html" style="width:100%;height:100%;background:white;text-align:center;padding:10px;overflow-x:auto;">
	<img src="{$v['material']}" align="center" style="max-height:300px;"/>
	</div>
	  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
	</div>
<div class="info clear cz">
	<h4 onclick="hg_slide_up(this,'adv_opration')"><span title="展开\收缩"></span>操作选项</h4>
	<ul id="adv_opration" class="clear" style="height:23px;">
		<li><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=content_form&id={$formdata['id']}&infrm=1">编辑</a></li>
	</ul>
</div>
{else}
此示意图不存在,请刷新页面更新
{/if}