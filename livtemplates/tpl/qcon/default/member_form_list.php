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
					<h2>嘉宾详情</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">姓名</span>
								<input type="text" value="{$name}" name='name' style="width:257px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">头像</span>
								{code}
									if($avatar && is_array($avatar))
									{
										$_avatar = $avatar['host'] .  $avatar['dir'] .  $avatar['filepath'] .  $avatar['filename'];
									}
									else
									{
										$_avatar = $RESOURCE_URL . 'avatar.jpg';
									}
								{/code}
								<img src="{$_avatar}"  />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">公司</span>
								<input type="text" value="{$company}" name='company' style="width:257px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">职务</span>
								<input type="text" value="{$job}" name='job' style="width:257px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">电话</span>
								<input type="text" value="{$telephone}" name='telephone' style="width:257px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">邮箱</span>
								<input type="text" value="{$email}" name='email' style="width:257px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">二维码</span>
								<img src="{$vcard_url}"  />
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