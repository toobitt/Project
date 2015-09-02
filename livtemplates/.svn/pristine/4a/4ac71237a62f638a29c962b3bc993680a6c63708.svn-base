<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{css:ad_style}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:350px">
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="{if $formdata}run.php?mid={$_INPUT['mid']}&a=update&id={$formdata['id']}{/if}" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
</div>
</li>

<a href="javascript:void(0)"  onclick="hg_close_opration_info();" title="关闭/ALT+Q">关闭</a>

<li class="i">
<div class="form_ul_div clear">
<span class="title">分类: </span><span class="title">{$_configs['share_plat'][$formdata['type']]['name_ch']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">状态: </span> <span class="title">{$formdata['status']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">账号名称: </span> <span class="title">{$formdata['name']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">官方账号: </span> <span class="title" >{$formdata['offiaccount']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">apikey: </span><span class="title" style="width:250px;text-align:left;">{$formdata['akey']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">密钥: </span><span class="title" style="width:250px;text-align:left;">{$formdata['skey']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">返回地址: </span><span class="title" style="width:250px;text-align:left;">{$formdata['callback']}</span>
</div>
</li>
{if $formdata['type']==127}
{foreach $_configs['share_plat'][127]['para'] as $v}
<li class="i" name="platdata">
<div class="form_ul_div clear">
<span  class="title">{$v['name']}: </span><span class="title" style="width:250px;text-align:left;">{if $formdata['platdata'][$v['param']]}{$formdata['platdata'][$v['param']]}{/if}</span>
</div>
</li>
{/foreach}
{/if}

</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
<a class="button_4"  onclick="hg_showAddShare({$formdata['id']});" href="javascript:void(0);">编辑</a>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
