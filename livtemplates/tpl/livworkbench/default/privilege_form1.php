<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{code}
	$mod = $_INPUT['mod'];
	$is_all = $list[0]['is_all'];
	unset($list[0]['is_all']);
	$app_global = $list[0]['app_global'];
	unset($list[0]['app_global']);
	$id = $list['id'];
	unset($list['gid']);
	$type = $list['type'];
	unset($list['type']);
	$list = $list[0];
	
	$formdata['type'] = $formdata['type'] ? $formdata['type'] : $_INPUT['type'];
	$optext = '更新权限';
	$a = 'update';
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
</style>
<script type="text/javascript">

function hg_checkall()
{	
	var is_all = $("#is_all").attr("checked");
	if(is_all)
	{
		$(".checkbox").attr("checked","checked");
	}
}
function hg_cancelcheck()
{
	var is_all = $("#is_all").attr("checked");
	if(is_all)
	{
		$("#is_all").removeAttr("checked");
	}
}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">

{if is_array($list)}
	{foreach $list as $k=>$v}
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">{$v['op_name']}: </span><input class="checkbox" style="float:right;" type="checkbox" name="{$v['op_en']}" size="4" value="1" onclick="hg_cancelcheck();" {if $v['perm']}checked="checked"{/if} {if $v['perm'] == 2}disabled="disabled"{/if}/>
		</div>
	</li>
	{/foreach}
{/if}

	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">全选: </span><input style="float:right;" id="is_all" onclick="hg_checkall();" type="checkbox" name="is_all" size="4" value="1" {if $is_all}checked="checked"{/if}/>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">应用授权: </span><input style="float:right;" type="checkbox" name="app_global" size="4" value="1" {if $app_global}checked="checked"{/if} {if $app_global == 1}disabled="disabled"{/if}/>
		</div>
	</li>

</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="mod_en" value="{$mod}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="type" value="{$type}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}