{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata[0];
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
/*所有选择控件基础样式*/
$all_select_style = array(
	'class' 	=> 'down_list',
	'state' 	=> 	0,
	'is_sub'	=>	1,
);
{/code}
{css:ad_style}
<style>
	.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 200px;top: 4px;}
	.option_del {
		display: none;
		width: 16px;
		height: 16px;
		cursor: pointer;
		float: right;
		background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;
	}
	.contract-val{display:inline-block;width:225px;}
</style>
<script type="text/javascript">
	function hg_addContactDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>联系方式：</span><input type='text' name='contract_name[]' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;号码：<input type='text' name='contract_value[]' size='40'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if ( $(obj).data("save") ) 
		{
			if(confirm('确定删除该联系方式吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
		hg_resize_nodeFrame();
	}
</script>

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}公司机构</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">机构名称：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">备注描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">地址：</span>
								<input type="text" value="{$list['address']}" name='address' style="width:362px;">
							</div>
						</li>
						
						<li class="i">
							{if($list['contract_way'])}
							{foreach $list['contract_way']['contract_name'] as $k=>$v}
							<div class='form_ul_div clear'><span class='title'>联系方式: </span><input type='text' name='contract_name[]' value='{$v}' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;号码：<input type='text' name='contract_value[]' class="contract-val" value='{$list["contract_way"]["contract_value"][$k]}' size='40'/>&nbsp;&nbsp;
							<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>
							{/foreach}
							{/if}
							<div id="extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addContactDom();">添加联系方式</span>
							</div>
						</li>
		
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}