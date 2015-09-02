{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="新增";
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
{js:hg_water}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}分类</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">类别名称：</span><input type="text" value="{$name}" name="name" class="title"/>
	</div>
</li>
{if $ac=='create'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">英文名称：</span><input type="text" value="{$tbname}" name="tbname" />
	</div>
</li>
{/if}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">描述：</span><input type="text" value="{$desc}" name="desc"/>
	</div>
</li>
<!--
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">存储引擎：</span><input type="text" value="{$engine}" name="engine" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">字符集：</span><input type="text" value="{$charset}" name="charset" />
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">collate：</span><input type="text" value="{$collate}" name="collate" />
	</div>
</li>
-->

</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}分类" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

<script type="text/javascript">
function hg_remove_rows(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#news_sort_name_"+ids[i]).remove();
	 }
}
</script>
{template:foot}