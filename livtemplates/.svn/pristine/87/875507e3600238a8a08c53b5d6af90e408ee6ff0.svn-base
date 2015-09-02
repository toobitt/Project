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
					<h2>推送条件详情</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">注册时间</span>
								<input type="text" name='register_time' value="{$register_time}" style="width:257px;" />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">最近天数</span>
								<input type="text" name="recent_time" value="{$recent_time}" style="width:257px;" />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">日增内容</span>
								<input type="text" name="content_count" value="{$content_count}" style="width:257px;" />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">激活数量</span>
								<input type="text" name="activate_count" value="{$activate_count}" style="width:257px;"  />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">iOS测试版</span>
								<input type="text" name="ios_debug_version" value="{$ios_debug_version}" style="width:257px;"  />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">iOS正式版</span>
								<input type="text" name="ios_release_version" value="{$ios_release_version}" style="width:257px;"  />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">android测试</span>
								<input type="text" name="android_debug_version" value="{$android_debug_version}" style="width:257px;"  />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">android正式</span>
								<input type="text" name="android_release_version" value="{$android_release_version}" style="width:257px;"  />
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="update" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" value="确认" class="button_6_14" style="margin-left:28px;"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}