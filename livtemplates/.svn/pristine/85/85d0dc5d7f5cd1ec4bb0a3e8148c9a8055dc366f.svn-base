<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{css:ad_style}
{css:edit_video_list}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:350px">
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="{if $formdata}run.php?mid={$_INPUT['mid']}&a=update&id={$formdata['id']}{/if}" method="post" class="ad_form h_l">
<ul class="form_ul">
<span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;"  class="share-close" title="关闭/ALT+Q"></span>
<li class="i">
<div class="form_ul_div clear">
<span class="title">平台: </span><span class="title">{$formdata['platname']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">用户id: </span><span class="title">{$formdata['uid']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">用户名: </span> <span class="title" style="width:250px;text-align:left;">{$formdata['name']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">url: </span> <span class="title" style="width:250px;text-align:left;">{$formdata['url']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">内容: </span> {$formdata['content']}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">图片: </span> <img src="{$formdata['picpath']}" width="200px" height="100px">
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">经度: </span><span class="title" >{$formdata['jing']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">纬度: </span><span class="title" >{$formdata['wei']}</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">分享时间: </span><span class="title" style="width:250px;text-align:left;">{code}echo date('Y-m-d H:i:s',$formdata['addtime']);{/code}</span>
</div>
</li>


</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
