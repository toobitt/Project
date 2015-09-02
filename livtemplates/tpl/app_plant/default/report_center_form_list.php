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
					<h2>举报详情</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">举报会员</span>
								<input type="text" value="{$member_name}" style="width:257px;" readonly />
							</div>
							<div class="form_ul_div">
								<span  class="title">举报人号码</span>
								<input type="text" value="{$tele_phone}" style="width:257px;" readonly />
							</div>
							<div class="form_ul_div">
								<span  class="title">应用名称</span>
								<input type="text" value="{$app_name}" style="width:257px;" readonly />
							</div>
							<div class="form_ul_div">
								<span  class="title">版本</span>
								<input type="text" value="{$is_debug}" style="width:257px;" readonly />
							</div>
							<div class="form_ul_div">
								<span  class="title">设备</span>
								<input type="text" value="{$model} / {$system} / v{$app_version}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">举报内容</span>
								<textarea style="width:400px;height:200px;" readonly>{$content}</textarea>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="audit" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}