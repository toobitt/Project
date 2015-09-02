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
					<h2>{$optext}正文模板</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">正文名称：</span>
								<input type="text"  required="true" value="{$name}" name='name' style="width:322px;">
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'body_tpl_item_show',
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
									{template:form/search_source,type,$default,$_configs['body_tpl_type'],$item_source}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">正文标识：</span>
								<input type="text"  required="true" value="{$uniqueid}" name='uniqueid' style="width:322px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">框架标识：</span>
								<input type="text"  required="true" value="{$frame_uniqueid}" name='frame_uniqueid' style="width:322px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">模板图片：</span>
								<input type="file" name='img_info' />
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
								<img src="{$img_url}" width="80" height="60" />
							</div>
							{/if}
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">主体html：</span>
								<textarea name='body_html' style="height:500px;width:600px;">{$body_html}</textarea>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">内容html：</span>
								<textarea name='page_content_html' style="height:200px;width:600px;">{$page_content_html}</textarea>
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