{css:ad_style}
<style type="text/css">
	.water_type{width:100%;height:40px;border-bottom:1px dotted gray;}
	.left_title{width:56px;float:left;}
	.wleft_box{width:40%;height:100%;}
	.wright_box{width:39%;height:100%;margin-left:40px;}
	.wfl{float:left;}
	.marg{margin-top:2px;cursor:pointer;}
	.lab{margin-left:5px;margin-top:2px;}
	.inerbox{width:67px;height:32px;margin-left:16px;margin-top:12px;}
	.clor{background:#eeeeee;text-align:center;visibility:hidden;}
	.clor div{margin-top:8px;}
	.img_preview{width:270px;height:100%;float:left;margin-left:32%;}
</style>

<div class="ad_middle">
<form class="ad_form h_l" action="run.php?mid={$_INPUT['mid']}" method="post"   id="content_form" name="content_form" onsubmit="return hg_ajax_submit('content_form');">
<h2>配置</h2>
<ul class="form_ul">
{if  $formdata}
{foreach $formdata AS $k => $v}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['var_name']}：</span>
		<input type="hidden" value="{$v['var_name']}" name="st_key[]" />
		<input type="hidden" value="{$v['bundle_id']}" name="st_bundle_id[]" />
		<input type="hidden" value="{$v['module_id']}" name="st_module_id[]" />
		<input type="hidden" value="{$v['type']}" name="st_type[]" />
		<input type="text" value="{$v['value']}" name='st_val[]' class="title" />
	</div>
</li>
{/foreach}
{/if}
</ul>
<input type="hidden" name="a" value="update"  />
<br/>
<input type="submit" id="submit_ok" name="sub" value="更改" class="button_6_14" />
</form>
</div>


