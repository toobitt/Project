<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>添加</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">账号名称: </span><input type="text" name="name" value="" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">apikey: </span><input type="text" name="apikey" size="50" value="" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">secretkey: </span><input type="text" name="secretkey" size="50" value="" />
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">callback: </span><input type="text" name="callback" size="50" value="" />
</div>
</li>



</ul>
<input type="hidden" name="a" value="create" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
<input type="submit" name="sub" value="添加" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}