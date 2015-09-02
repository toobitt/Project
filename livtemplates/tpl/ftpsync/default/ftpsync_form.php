{template:head}
{css:ad_style}
{js:ad}
{js:adv_video}
{css:vod_style}
{css:mark_style}
<style type="text/css">
.add_collect_form .jh_vod .ul{overflow-y: auto}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data" id="content_form">
<h2>{$optext}同步配置</h2>
<ul class="form_ul">
<li class="i nobg form_border">
	<div class="form_ul_div clear">
	<span class="title">配置名称：</span><input type="text" value='{$formdata["syncname"]}' name='syncname' class="title">
	<font class="important">必填</font>
	</div>
</li>
<li class="i nobg form_border">
<div class="form_ul_div clear">
<span class="title">描述：</span><textarea style="width:260px;height:55px;min-height:55px;" name="brief">{$formdata["brief"]}</textarea>
<font class="important"></font>
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
{code}
		$adclient_css = array(
		'class' => 'down_list i',
		'show' => 'server_id_show',
		'width' => 140,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['server_id'] = $formdata['server_id'] ? $formdata['server_id'] : 0;
	$servers[0][0]='选择服务器';
{/code}
<span class="title">服务器：</span>{template:form/search_source,server_id,$formdata['server_id'],$servers[0],$adclient_css}
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">目标目录：</span><input type="text" value='{$formdata["server_dir"]}' name='server_dir' class="link">
</div>
</li>
<li class="i">
<div class="form_ul_div clear" id="multi_times">

<span class="title">上传应用：</span><span style="clear:both">
{code}
		$adclient_css = array(
		'class' => 'down_list i',
		'show' => 'app_show',
		'width' => 140,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['app'] = $formdata['app'] ? $formdata['app'] : 0;
	$apps[0][0]='选择应用';
{/code}
{template:form/search_source,app,$formdata['app'],$apps[0],$adclient_css}
<font class="important">选择需要将具体的应用下面的文件上传至ftp服务器</font>
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">源目录：</span><input type="text" value='{$formdata["app_dir"]}' name='app_dir' class="link">
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">文件类型：</span><input type="text" value='{$formdata["allow_ftype"]}' name='allow_ftype' class="link">
<font class="important">多个用","分割，形如html,jpg，留空所有</font>
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">同步周期：</span><input type="text" value='{$formdata["setinterval"]}' name='setinterval' class="link">分钟
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">每周期：</span><input type="text" value='{$formdata["max_number"]}' name='max_number'>个文件
</div>
</li>
</ul>
<input type="hidden" value="{$formdata['id']}" id="id" name="id" />
<input type="hidden" name="a" value="update" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="goon" value="0" id="goon"/>
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="adv_mid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}配置" class="button_6_14"/><!--<input type="button" value="发布广告设置" class="button_6_14" style="margin-left:28px;" onclick="hg_next_publish()"/>
-->
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<div id="clone" style="display:none">
<input type="text" class="date_pick" name='start[]' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off"/>&nbsp;
<input type="text" class="date_pick" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off" name="end[]"/>
</div>
{template:foot}