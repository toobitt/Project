<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:area}
{if $a}
	{code}
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
<script>
jQuery(function($){
	new PCAS("province", "city", "area");
})
</script>
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>{$optext}行业</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">行业名称：</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="name" id="name" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$name}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			{if $_INPUT['fid']}
			{if $_INPUT['m'] == 'add'}
			<input type="hidden" name="pid" value="{$_INPUT['fid']}" />
			{else}
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">所属行业：</span>
						{code}
						$attr_trade = array(
							'class' => 'down_list',
							'show' => 'trade_show',
							'width' => 100,
							'state' => 0,
							'is_sub'=> 1,
						);
						$default = $_INPUT['fid'] ? $_INPUT['fid'] : 0;
						$trade_info[$default] = '选择行业';
						foreach($tradeInfo as $k =>$v)
						{
							$trade_info[$v['id']] = $v['name'];
						}
						{/code}
						{template:form/search_source,pid,$default,$trade_info,$attr_trade}
						<span class="error" id="siteid_tips" style="display:none;"></span>
					</div>
				</div>
			</li>	
			{/if}
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">所属角色：</span>
						{code}
						$attr_role = array(
							'class' => 'down_list',
							'show' => 'role_show',
							'width' => 100,
							'state' => 0,
							'is_sub'=> 1,
						);
						$default = $_INPUT['roleId'] ? $_INPUT['roleId'] : 0;
						$role_info[$default] = '选择角色';
						foreach ($roleInfo as $k =>$v)
						{
							$role_info[$v['id']] = $v['name'];
							if ($v['id'] == $role_id)
							{
								$default = $role_id;
							}
						}
						{/code}
						{template:form/search_source,roleId,$default,$role_info,$attr_role}
						<span class="error" id="siteid_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			{/if}
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}