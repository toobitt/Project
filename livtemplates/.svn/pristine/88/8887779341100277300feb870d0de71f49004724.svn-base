{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>来源配置</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}来源配置</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span class="title">配置名称：</span><input type="text" name="name" value="{$formdata['name']}" size="40">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">配置描述：</span><textarea style="width: 400px;height: 100px;" cols="50" rows="2" name="brief">{$formdata['brief']}</textarea>
		</div>
	</li>
	<h3>{$optext}源数据库</h3>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">服务器：</span><input type="text" name="db_server" value="{$formdata['db_server']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">帐号：</span><input type="text" name="account" value="{$formdata['account']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">密码：</span><input type="text" name="password" value="{$formdata['password']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">数据库名：</span><input type="text" name="db" value="{$formdata['db']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title";>编码: </span><input type="text" name="codefmt" value="{$formdata['codefmt']}" size="40"/>
		</div>
	</li>
	<h3>{$optext}数据接口配置</h3>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">主机：</span><input type="text" name="host" value="{$formdata['host']}" size="40"/><font class="important">例如：localhost</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">路径：</span><input type="text" name="dir" value="{$formdata['dir']}" size="40"/><font class="important">例如：public/api</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">模块标识：</span><input type="text" name="model_name" value="{$formdata['model_name']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">文件名：</span><input type="text" name="file_name" value="{$formdata['file_name']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">接口方法：</span><input type="text" name="function" value="{$formdata['function']}" size="40"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">分类关系：</span><textarea style="width: 400px;height: 100px;" cols="50" rows="2" name="sort">{$formdata['sort']}</textarea><font class="important">例如：1=>3,4=>5</font>
		</div>
	</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
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