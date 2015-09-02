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
		$ac="alter";
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
<h2>{$optext}字段</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">字段名称(备注)：</span><input type="text" value="{$Comment}" name="Comment" class="title"/>
	</div>
</li>
{if $ac=='alter'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">英文名称：</span><input type="text" value="{$Field}" name="Field" />
	</div>
</li>
{/if}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">数据类型：</span><input type="text" value="{$Type}" name="Type" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">长度：</span><input type="text" value="{$length}" name="length" />
	</div>
</li>

</ul>
<input type="hidden" name="tbname" value="{code}echo $_INPUT['tbname']{/code}" />
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}字段" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
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