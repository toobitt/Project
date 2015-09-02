{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}UI</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">UI名称：</span>
								<input type="text"  required="true" value="{$name}" name='name' style="width:322px;">
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'ui_type_item_show',
										'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'	=>	1,
									);
									
									if($type)
									{
										$default = $type;
									}
									else
									{
										$default = 0;
									}
								{/code}
								<div style="float:right;margin-right:188px;">
									{template:form/search_source,type,$default,$_configs['ui_type'],$item_source}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标识：</span>
								<input type="text"  required="true" value="{$uniqueid}" name='uniqueid' />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">UI图片：</span>
								<input type="file" name='ui_pic' />
							</div>
							{code}
								$img_url = '';
								if($img_info && is_array($img_info))
								{
									$img_url = $img_info['host'] . $img_info['dir'] . $img_info['filepath'] . $img_info['filename'];
								}
							{/code}
							{if $img_url}
							<div class="form_ul_div" style="margin-left:84px;">
								<img src="{$img_url}" width="320" height="480" />
							</div>
							{/if}
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">简介：</span>
								<textarea name='brief'>{$brief}</textarea>
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