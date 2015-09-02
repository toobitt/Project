{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:hg_switchable}
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
{css:station_style}
{js:constellation/station}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}星座信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">星座名称：</span>
								<input type="text" value="{$list['astrocn']}" name='name' style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title" >LOGO：</span>
								<input type="file" name="logo"  value="submit"> 
							</div>
							{if $list['logo']}
							<div class="form_ul_div clear">
								<span style="float:right;margin-right:71%;border:0px solid #DADADA;">
									<img width="60" height="60" src="{$list['logo']}">
								</span>
							</div>
							{/if}
							<div class="form_ul_div clear">
								<span  class="title">星座介绍：</span>
								<textarea rows="3" cols="80" name='astrointroduction'>{$list['astrointroduction']}</textarea>
							</div>
						</li>
		           <li class="i">
							<div class="form_ul_div">
								<span  class="title">星座时间：</span>
								<input type="text" value="{$list['astrostart']}" name='astrostart' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'MM-dd'})" style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">星座时间：</span>
								<input type="text" value="{$list['astroend']}" name='astroend'  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'MM-dd'})" style="width:257px;">
								<font class="important" style="color:red">*</font>
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