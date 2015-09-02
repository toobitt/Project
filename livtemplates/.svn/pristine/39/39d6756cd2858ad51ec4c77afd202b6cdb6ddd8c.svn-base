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
					<h2>查看社区详情</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">APP名称</span>
								<input type="text" value="{$app_name}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">创建时间</span>
								<input type="text" value="{$name}" name='name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">头像</span>
								<img  src="{$avatar['host'].$avatar['dir'].$avatar['filepath'].$avatar['filename']}" name='name' style="width:240px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">背景图片</span>
								<img  src="{$background['host'].$background['dir'].$background['filepath'].$background['filename']}" name='name' style="width:240px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">创建时间</span>
								<input type="text" value="{$create_time}" name='create_time' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">创建人</span>
								<input type="text" value="{$user_name}" name='user_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}