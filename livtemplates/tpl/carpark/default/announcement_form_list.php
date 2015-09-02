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
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}公告</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">公告标题：</span>
								<input type="text"  required="true" value="{$title}" name='title' style="width:322px;">
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'carpark_item_show',
										'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'	=>	1,
									);
									
									if($carpark_id)
									{
										$default = $carpark_id;
									}
									else
									{
										$default = 0;
									}
									$sort[0] = '选择停车场';
									foreach($carpark_data[0] as $k =>$v)
									{
										$sort[$v['id']] = $v['name'];
									}
								{/code}
								<div style="float:right;margin-right:188px;">{template:form/search_source,carpark_id,$default,$sort,$item_source}</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">公告内容：</span>
								<textarea name='content'>{$content}</textarea>
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