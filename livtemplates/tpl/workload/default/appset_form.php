{template:head}
{css:common/common_category}
{js:hg_sort_box}
{css:column_node}
{js:column_node}
{css:ad_style}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
{css:colorpicker}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{code}
	/*所有选择控件基础样式*/
$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
	);
{/code}
{foreach $formdata as $key => $value}
	{code}
		$$key = $value;			
	{/code}
{/foreach}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}应用</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">应用名：</span>
								<input type="text" value="{$name}" name='name' required="true">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">应用标识：</span>
								<input type="text" value="{$app_uniqueid}" {if $app_uniqueid}readonly{/if} name='app_uniqueid' required="true"><span class="color">(不可修改)</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">接口文件：</span>								
								<input type="text" value="{$filename}" name='filename' style="width:325px;">
							</div>
						</li> 
						<li class="i">
							<div class="form_ul_div">
								<span class="title">接口方法：</span>
								<input type="text" value="{$functions}" name='functions'>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">颜色:</span>
								<div style="display: inline-block;">
		                		<input required class="select-input color-picker" data-color="{$color}" type="text" name="color" value="{$color}"/>   		
                				</div>
                			</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$action}" />
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
<script>
    $('.color-picker').hg_colorpicker();
</script>
{template:foot}