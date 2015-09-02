<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
{code}
{/code}
{if is_array($formdata['ret']) && count($formdata['ret'])}
	{foreach $formdata['ret'] as $k => $v}		
		<div style="width:100%;margin-top:10px;">
			<label><input type="checkbox" name="column_id[]" value="{$v['id']}" class="n-h" /> <span>{$v['name']}</span>：</label>
		</div>	
	{/foreach}
{else}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
	<script>hg_error_html(vodlist,1);</script>
{/if}
<div style="width:100%;margin-top:10px;">
	<input type="submit"  value="推送" class="button_6" style="margin-left:441px;" />
</div>
<input type="hidden" value="do_recommond" name="a" />
<input type="hidden" value="{$formdata['source']}" name="source" />
<input type="hidden" value="{$formdata['title']}" name="title" />
<input type="hidden" value="{$formdata['aid']}" name="{$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
