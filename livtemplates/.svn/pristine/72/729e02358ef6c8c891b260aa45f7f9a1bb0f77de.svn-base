{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata;
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

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}明星</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">明星名称：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title" >LOGO：</span>
								<input type="file" name="Filedata"  value="submit"> 
							</div>
							{if $list['logo']}
							<div class="form_ul_div clear">
								<span style="float:right;margin-right:79%;border:0px solid #DADADA;">
									<img width="60" height="60" src="{$list['logo']}">
								</span>
							</div>
							{/if}
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">备注描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
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