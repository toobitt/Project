{template:head}
{code}
	$optext="设置权限";
	$ac="update";
{/code}
{css:ad_style}
{js:ad}
<script type="text/javascript">
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post">
<h2>{$optext}</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">用户昵称：</span>
		<input type="text" value='{$formdata['name']}' name='nick_name' class="title" disabled="disabled">
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">所属角色：</span>
		<div style="overflow:hidden;">
		{if is_array( $roles ) && count( $roles ) > 0}
			{foreach $roles as $k => $v }
				<label>
				<input type="checkbox" value="{$v['id']}" name="roles[]" class="n-h" {if in_array($v['id'], $formdata['roles'])}checked{/if}><span>{$v['name']}</span>
				</label>
			{/foreach}
		{else}
			<span>暂无角色</span>
		{/if}
		</div>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="提交" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}