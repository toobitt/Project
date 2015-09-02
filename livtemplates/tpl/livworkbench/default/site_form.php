{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>站点</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}站点</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
	<div class="form_ul_div">
	<span class="title">站点名称：</span><input type="text" value="{$formdata['site_name']}" name="site_name" size="40">
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">关键字：</span><input type="text" name="site_keywords" value="{$formdata['site_keywords']}" size="60"/>
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">站点描述：</span><textarea style="width: 400px;height: 100px;" cols="50" rows="2" name="site_brief">{$formdata['site_brief']}</textarea>
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">网站域名：</span><input type="text" name="weburl" value="{$formdata['weburl']}" size="60"/>
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">站点目录：</span><input type="text" name="sitedir" value="{$formdata['sitedir']}" size="60"/>
	</div>
	</li>
</ul>
<input type="hidden" value="{$formdata['cms_siteid']}" name="cms_siteid">
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" class="button_6_14"/>
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14" />
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}