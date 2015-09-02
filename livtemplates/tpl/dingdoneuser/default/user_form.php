{template:head}
{css:ad_style}
{css:column_node}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
	//hg_pre($formdata);
{/code}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}帐号</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">帐户&nbsp;&nbsp;</span>
								<input type="text" value="{$account}" name='account' style="width:441px;height:26px;">
								<font class="important" style="color:red">*</font>
							</div>
							<div class="form_ul_div" style="margin-top:20px;">
								<span  class="title">密码&nbsp;&nbsp;</span>
								<input type="password" value="" name='password' style="width:441px;height:26px;">
								<font class="important" style="color:red">不填默认不修改</font>
							</div>
							<div class="form_ul_div" style="margin-top:20px;">
								<span  class="title">邮箱&nbsp;&nbsp;</span>
								<input type="text" value="{$email}" name='email' style="width:441px;height:26px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">头像：</span>
								{if $avatar}
								{code}
									$img = $avatar['host'].$avatar['dir'].'80x70/'.$avatar['filepath'].$avatar['filename'];
								{/code}
								<img src = "{$img}" alt="头像"/>
								{/if}
								
								<input type="file" name='Filedata' />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述：</span>
								<textarea name='brief'>{$brief}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}