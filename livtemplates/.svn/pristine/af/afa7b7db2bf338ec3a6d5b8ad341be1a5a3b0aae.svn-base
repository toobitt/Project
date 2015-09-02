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
					<h2>查看融云服务信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">APP名称</span>
								<input type="text" value="{$app_name}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">状态</span>
								<input type="text" value="{$rongcloud_return['code']}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">融云应用名称</span>
								<input type="text" value="{$rongcloud_return['data']['name']}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">app_key</span>
								<input type="text" value="{$rongcloud_return['data']['production_app_key']}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">app_secret</span>
								<input type="text" value="{$rongcloud_return['data']['production_app_secret']}" name='app_name' style="width:240px;" readOnly="true"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">创建时间</span>
								<input type="text" value="{$rongcloud_return['data']['create_time']}" name='app_name' style="width:240px;" readOnly="true"/>
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