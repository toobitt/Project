{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{code}
$user_id = $formdata['user_id'];
{/code}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_columns first"><em></em><a>动态口令</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}口令</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	{if $a== 'create'}
	<li class="i">
	<div class="form_ul_div clear">
	<span class="title">用户名：</span><input type="text" value="{$formdata['user_name']}" name="name">
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">密码：</span><input type="password" value="{$formdata['password']}" name="password">
	</div>
	</li>
	
	<li class="i">
	<div class="form_ul_div">
	<span class="title">客户端：</span><input type="text" value="{$formdata['client']}" name="client">
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">是否启用：</span>
	<input style="vertical-align:middle" type="checkbox" name="state" value="1">
	<font class="important">留空默认不启用</font>
	</div>
	</li>
	{/if}
	{if $a== 'update'}
	<li class="i">
	<div class="form_ul_div">
	<span class="title">串：</span><input type="text" size="50" value="{$formdata['salt']}" name="salt">
	</div>
	</li>
	{/if}
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br/>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}