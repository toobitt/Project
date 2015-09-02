{template:head}
{code}
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
	$role_id = $formdata['admin_role_id'];
{/code}
{css:ad_style}
{js:ad}
{css:ad_style}
{css:column_node}
{js:column_node}
{js:auth/select_role}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}用户</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">用户名：</span><input type="text" value='{$formdata["user_name"]}' name='user_name' class="title">
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">密码：</span><input type="text" name='password' class="title"><font class="important">不填默认不修改密码</font>
	</div>
</li>

<li class="i">
	<div class="form_ul_div">	
		<span class="title">描述：</span>
		{template:form/textarea,brief,$formdata['brief']}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">选择角色：</span>
	<div class="select_role"></div>
	</div>
</li>
<li class="i">
    <div class="form_ul_div clear">
    <span class="title">上级组织：</span>
    {code}
        $hg_attr['node_en'] = 'admin_org';
    {/code}
    {template:unit/class,father_org_id,$formdata['father_org_id'], $node_data}
   </div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">登录域名：</span><input type="text" name='domain' value="{$formdata['domain']}" class="title"/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{if $formdata['cardid']}<font color="red">重新绑定</font>{else}绑定密保{/if}：</span><input type="checkbox" name='cardid' value=1 class="title">
		{if $formdata['cardid']}<font class="important">*谨慎操作 重新绑定需要重新发放密保卡至该用户</font>{/if}
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">修改密码：</span><input type="checkbox" name='forced_change_pwd' value=1 class="title" {if $formdata['forced_change_pwd']} checked="checked" {/if}><font class="important">下次登录需要修改密码</font>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">上传头像：</span>
		{code}
			$index_img = '';
			if($formdata['avatar'])
			{	
				$pic = $formdata['avatar'];
				$index_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
			}
		{/code}
		{if $index_img}
			<img src="{$index_img}"/>
		{/if}
		<input type="file" value='' name='Filedata'/>
	</div>
</li>
</ul>
<input type="hidden" value="{$formdata['info'][0]['id']}" id="id" name="id" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}用户" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}">返回前一页</a></h2>
</div>
</div>
<script type="text/javascript">
	$(function(){
		$.globalRender = {code} echo $appendRole ?  json_encode($appendRole) : '{}'; {/code};
		$.globalSelect = {code} echo $role_id ?  json_encode($role_id) : '{}'; {/code};
		$('.select_role').role_select({
			source : $.globalRender,
			select : $.globalSelect,
			name : 'admin_role_id'
		});
	});
</script>
{template:foot}