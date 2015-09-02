{template:head}
{css:ad_style}
{code}
$columninfo = serialize($formdata['column']);
$add = $formdata['addcondition'];
{/code}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>来源配置</a></li>
			<li class=" dq"><em></em><a>内容条件</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:70%;">
<h2>内容条件</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<div style="float:left;width:185px;">where条件:</div><input type="text" name="where" value="{$formdata['content_condition']}" size="40"><font class="important">不需要填写 where，若填写此项，下列项则无需填写</font>
		</div>
	</li>
	<li class="i">
		{foreach $formdata['column'] as $k=>$v}
			{code}
				$value = $add[$v];			
			{/code}	
			<div style='width:100%;height:30px;'>
				<div style="float:left;width:185px;">{$v}:</div>
				<input type="text" name='{$k}' value="{$value}" size="40"/><font class="important">需加操作符</font>
			</div>
		{/foreach}
	</li>
</ul>
<input type="hidden" name="a" value="add_condition" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="columninfo" value='{$columninfo}' />
<input type="hidden" name="html" value="ture"/>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" class="button_6_14"/>
<br>
<input type="submit" name="sub" value="确定" class="button_6_14"/>
<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version"><h2><a href="./source_config.php">返回前一页</a></h2></div>
</div>
{template:foot}