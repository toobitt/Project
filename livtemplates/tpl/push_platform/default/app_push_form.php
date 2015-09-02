{template:head}
{code}
	foreach($formdata as $k=>$v)
	{
		$$k = $v;
	}
	if($id)
	{
		$optext="更新";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="create";
	}
{/code}
{css:calendar}
{css:ad_style}
{js:push_platform/app_push_form}
<style>
.list-info{display:none;}
.red{color:red;}
</style>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}应用</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title title-name">平台类型:</span>				
			{code}
				$attr_type = array(
					'class' => 'down_list i',
					'show' => 'item_shows_',
					'width' => 150,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
				);
				
				$default = $platform_type ? $platform_type : -1;
				
				$platfrom_type = $_configs['platfrom_type'];
				$platfrom_type[-1] = '请选择';
			{/code}
			{template:form/search_source,platfrom_type,$default,$platfrom_type,$attr_type}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">应用名称: </span><input type="text" name="name" size="48" required value="{$name}"/>
		</div>
	</li>
	{if $platform_type !=2}
	<li class="i list-info appid">
		<div class="form_ul_div clear">
			<span  class="title">AppID: </span><input type="text" name="access_id" size="48" value="{$access_id}" />
		</div>
	</li>
	{/if}
	<li class="i list-info appkey">
		<div class="form_ul_div clear">
			<span  class="title">AppKey: </span><input type="text" name="access_key" size="48" value="{$access_key}" />
		</div>
	</li>
	<li class="i list-info secretkey">
		<div class="form_ul_div clear">
			<span  class="title">SecretKey: </span><input type="text" name="secret_key" size="48" value="{$secret_key}" />
		</div>
	</li>
	
	
	<li class="i list-info channel">
		<div class="form_ul_div clear">
			<span  class="title">订阅频道: </span><input type="text" name="channel" size="48" value="{$channel}" />
			<font class="important red">多个频道用逗号间隔</font>
		</div>
	</li>
	
	<li class="i list-info channel">
		<div class="form_ul_div clear">
			<span  class="title">action: </span><input type="text" name="action" size="48" value="{$action}" />
			<font class="important red">avos安卓绑定</font>
		</div>
	</li>
	
	<li class="i list-info channel">
		<div class="form_ul_div clear">
			<span  class="title">packagename: </span><input type="text" name="packagename" size="48" value="{$packagename}" />
			<font class="important red">avos安卓绑定</font>
		</div>
	</li>
	
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}应用" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}