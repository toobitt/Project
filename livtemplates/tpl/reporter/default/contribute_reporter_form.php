{template:head}
{css:ad_style}
{css:column_node}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 270px;top: 4px;}
.option_del {
    background: url("../../.././../livtemplates/tpl/lib/images/close_plan.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    display: none;
    float: right;
    height: 16px;
    width: 16px;
}
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
.staff-img-a{float:right;position:relative;margin-right:10px;}
.staff-img-a span{position:absolute;top:-18px;right:-18px;font-size:18px;}
</style>
<script type="text/javascript">
	function hg_addConnectDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>时间: </span><input type='text' name='connect_start_time[]' style='width:90px;' class='title'  onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">--<input type='text' name='connect_end_time[]' style='width:90px;' class='title'  onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">电话：&nbsp;<input type='text' name='connect_tel[]' size='17'/>&nbsp;&nbsp;<span class='option_del_box' style='float:right'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该联系方式吗？'))
		{
			$(obj).parent().parent().remove();
		}
		hg_resize_nodeFrame();
	}
	function preview_avatar(id)
	{
		$('#avatar_' +id+ ' a').lightBox();
	}
	function delete_avatar(id, btn)
	{
		$(btn).parent().remove();
		$('#delete_avatar_'+id).val(1);
	}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}记者信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">帐号：</span>
								<input type="text" value="{$formdata['account']}" name='account' style="width:440px;">
								<font class="important"><font style="color:red">*</font></font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">密码：</span>
								<input type="text" value="" name='password' style="width:440px;" />
								<font class="important">{if $a=='create'}<font style="color:red">*</font>{else}不填默认不修改密码{/if}</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">描述：</span>
								<textarea rows="10" cols="8" name="brief">{$formdata['brief']}</textarea>
							</div>
						</li>
						{if $AuthRole}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">角色选择：</span>
								{code}
								$AuthRole = $AuthRole[0];
								$admin_role_id = explode(',', $formdata['role_id']);
								{/code}
								{if $AuthRole}
									{foreach $AuthRole as $_role_id=>$_role_name}
									<label for="admin_role_id"><input name="role_id[]" value="{$_role_id}" type="checkbox" {if in_array($_role_id, $admin_role_id)}checked="checked"{/if} />{$_role_name}</label>
									{/foreach}
								{/if}
							</div>
						</li>
						{/if}
						<!--  
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">登录域名：</span>
								<input type="text" name='domain' value="{$formdata['domain']}" class="title"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">绑定密保：</span>
								<input type="checkbox" {if $formdata['card_id'] ==1} checked="checked" {/if} name='card_id' value=1 class="title">
							</div>
						</li>
						-->
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">头像：</span>
								{code}
									$avatar = '';
									if(!empty($formdata['avatar']))
									{	
										$org = $formdata['avatar']['host'] . $formdata['avatar']['dir'] . $formdata['avatar']['filepath'] . $formdata['avatar']['filename'];
										$avatar = $formdata['avatar']['host'] . $formdata['avatar']['dir'] .'40x30/'. $formdata['avatar']['filepath'] . $formdata['avatar']['filename'];
									}
								{/code}
								
								<input type="file" value='' name='Filedata'/>
								{if $avatar}
								<div class="staff-img-a" id = "avatar_{$formdata['id']}" >
									<a  href="{$org}" >
										<img src="{$avatar}" alt="索引图" style="float: right" onclick="preview_avatar({$formdata['id']})" />
									</a>
									<!--  
									<span onclick="delete_avatar({$formdata['id']},this)" style="cursor: pointer">x</span>
									-->
								</div>
								{/if}
								<input type="hidden" name="delete_avatar" id = "delete_avatar_{$formdata['id']}" value="0" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">姓名：</span>
								<input type="text" value="{$formdata['name']}" name='name'/>
								<font style="color:red">*</font>
								<span  style="margin-left: 70px;color:#7D7D7D">英文名：</span>
								<input type="text" value="{$formdata['english_name']}" name='english_name'/>
							</div>
						</li>
						{if $_configs['reporter_sex']}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">性别：</span>
								{foreach $_configs['reporter_sex'] as $key=>$val}
								<input type="radio" name="sex" value="{$key}"  {if $key==$formdata['sex']}checked="checked"{/if}/>{$val}
								{/foreach}
							</div>
						</li>
						{/if}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">电话：</span>
								<input type="text" value="{$formdata['tel']}" name='tel' />
								<span  style="margin-left: 80px;color:#7D7D7D">分机号：</span>
								<input type="text" value="{$formdata['ext_num']}" name='ext_num' />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">手机：</span>
								<input type="text" value="{$formdata['mobile']}" name='mobile' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">邮件：</span>
								<input type="text" value="{$formdata['email']}" name='email' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}