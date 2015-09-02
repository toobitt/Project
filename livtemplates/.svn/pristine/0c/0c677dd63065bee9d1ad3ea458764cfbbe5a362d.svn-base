
{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{css:column_node}
{js:column_node}

{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">

</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">App名称</span>
                                <input type="hidden" value="{$app_id}" name="app_id"/>
								<input type="text" value="{$app_name}" name="app_name" style="width:240px;" readonly/>
							</div>
						</li>
                        <li class="i">
                            <div class="form_ul_div">
                                <span  class="title">朋友圈分享图</span>
                                <img src="{$picture_1['host'].$picture_1['dir'].$picture_1['filepath'].$picture_1['filename']}" width="600px;" height="400px;">
                            </div>
                        </li>
                        <li class="i">
                            <div class="form_ul_div">
                                <span  class="title">百度口碑好评截图</span>
                                <img src="{$picture_2['host'].$picture_2['dir'].$picture_2['filepath'].$picture_2['filename']}" width="600px;" height="400px;">
                            </div>
                        </li>
                        <li class="i">
                            <div class="form_ul_div">
                                <span  class="title">36氪NEXT评论截图</span>
                                <img src="{$picture_3['host'].$picture_3['dir'].$picture_3['filepath'].$picture_3['filename']}" width="600px;" height="400px;">
                            </div>
                        </li>
						<li class="i">
							<div class="form_ul_div" >
								<span  class="title">审核状态</span>
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'general_audit_status',
										'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'	=>	1,
									);

									if($status)
									{
										$default = $status;
									}
									else
									{
										$default = 1;
									}
								{/code}
								<div>
									{template:form/search_source,status,$default,$_configs['general_audit_status'],$item_source}
								</div>
								<div class="form_ul_div" style="clear:both;margin-top:50px;">
								</div>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
