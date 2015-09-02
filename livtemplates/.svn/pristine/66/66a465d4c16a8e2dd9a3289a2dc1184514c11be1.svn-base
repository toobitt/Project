<?php 
/* $Id$ */
?>
{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_set first"><em></em><a>设置维护</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}设置</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div"><span  class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" /></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">所属分组 </span>{template:form/select,group_id,$formdata['group_id'],$setting_groups}</div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">上级设置 </span>{template:form/select,father_id,$formdata['father_id'],$father_settings}</div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">变量名: </span><input type="text" name="varname" value="{$formdata['varname']}" /></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">变量值: </span><input type="text" name="varvalue" size="60" value="{$formdata['varvalue']}" /></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">是否常量: </span>{template:form/radio, isconst, $formdata['isconst']}</div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" /></div></li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14" />
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}