{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="添加";
		$ac="create";
	}
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;		
		{/code}
	{/foreach}
{/if}
{css:ad_style}
{js:ad}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post"   id="content_form">
<h2>{$optext}配置</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">配置名称：</span><input type="text" value='{$aname}' name='aname' class="title" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">扩展名：</span><input type="text" value='{$expand}' name='expand' class="title">
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">解析代码：</span><textarea name="code">{$code}</textarea>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">启用：</span>
						<input type="radio" name="is_open" value="1" {if $is_open}checked="checked"{/if}/>是
						<input type="radio" name="is_open" value="0" {if !$is_open}checked="checked"{/if}/>否
    </div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">附件类型：</span>
		{code}
			$material_style=array(
				'class' => 'colonm down_list data_time',
				'show' => 'status_show',
				'width' =>104,
				'state' =>0,
			);
			$mark = $mark ? $mark : -1;
		{/code}
		{template:form/search_source,material_style,$mark,$_configs['material_style'],$material_style}
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}附件" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}