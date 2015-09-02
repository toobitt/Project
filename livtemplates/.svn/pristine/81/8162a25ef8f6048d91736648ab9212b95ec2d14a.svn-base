
{template:head}
{css:ad_style}
{css:ucusers_style}

<script type="text/javascript">

</script>

{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div class="ad_middle">
	<form name="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}用户</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">用户名：</span>
					<input type="text" name="addname" value="{$uname}" />
				</div>
				{if $action == 'update'}
				<div class="form_ul_div">	
					<span class="title">旧密码：</span>
					<input type="text" name="old_addpassword" value="" />
				</div>
				{/if}
				<div class="form_ul_div">	
					<span class="title">密码：</span>
					<input type="text" name="addpassword" value="" />
				</div>
				<div class="form_ul_div">	
					<span class="title">邮件：</span>
					<input type="text" name="addemail" value="{$email}" />
				</div>
			</li>
			
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="olduname" value="{$uname}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}