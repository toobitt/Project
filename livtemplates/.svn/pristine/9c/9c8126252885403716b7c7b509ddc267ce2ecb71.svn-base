{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>来源配置</a></li>
			<li class=" dq"><em></em><a>导入设置</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>导入设置</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
		<span class="title">追加导入：</span>
			<input type="radio" name="addition" value="1" {if $formdata['addition'] == 1}checked="checked"{/if}> 是
			<input type="radio" name="addition" value="0" {if $formdata['addition'] == 0}checked="checked"{/if}> 否
		</div>
	</li>	
	<li class="i">
		<div class="form_ul_div">
			<span class="title">周期条数：</span><input type="text" name="num" value="{$formdata['num']}" size="40">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">lastID：</span><input type="text" name="lastid" value="{$formdata['lastid']}" size="40"><font class="important">不需要填写</font>
		</div>
	</li>
</ul>
<input type="hidden" name="a" value="upimport" />
<input type="hidden" name="html" value="true"/>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" class="button_6_14"/>
<br>
<input type="submit" name="sub" value="确定" class="button_6_14"/>
<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version"><h2><a href="./source_config.php">返回前一页</a></h2></div>
</div>
{template:foot}