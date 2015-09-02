
{template:head}
{css:ad_style}
{css:ucusers_style}
{css:column_node}
{js:column_node}

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
					<input type="text" name="member_name" value="{$member_name}" />
				</div>
				{if $action == 'update' && $_configs['ucenter']['open']}
				<div class="form_ul_div">	
					<span class="title">旧密码：</span>
					<input type="text" name="old_password" value="" />
				</div>
				{/if}
				<div class="form_ul_div">	
					<span class="title">密码：</span>
					<input type="text" name="password" value="" />
				</div>
				<div class="form_ul_div">	
					<span class="title">邮件：</span>
					<input type="text" name="email" value="{$email}" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">所属分组：</span>
				{code}
					$hg_attr['node_en'] = 'member_node';
				{/code}
				{template:unit/class,node_id,$node_id, $node_data}
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发布至：</span>
            		{template:unit/publish, 1, $formdata['column_id']}
		             <script>
		             jQuery(function($){
		                $('#publish-1').css('margin', '10px auto').commonPublish({
		                    column : 2,
		                    maxcolumn : 2,
		                    height : 224,
		                    absolute : false
		                });
		             });
             		 </script>
			    </div>
			</li>
			
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="id" value="{$formdata['id']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="old_member_name" value="{$member_name}" />
		<input type="hidden" name="old_email" value="{$email}" />
		<input type="hidden" name="salt" value="{$salt}" />
		<input type="hidden" name="uc_id" value="{$uc_id}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}