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
				<h2>{$optext}评论</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标题&nbsp;&nbsp;</span>
								<input type="text" value="{$seekhelp_title}" name='seekhelp_title' style="width:441px;height:26px;" disabled="disabled">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">评论人&nbsp;&nbsp;</span>
								<input type="text" value="{$member_name}" name='member_name' style="width:441px;height:26px;" disabled="disabled">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">评论内容：</span>
								<textarea name='content'>{$content}</textarea>
								<font class="important" style="color:red">*{if $banword}含有屏蔽字 {$banword}{/if}</font>
								
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
	<!-- <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div> -->
	</div>
{template:foot}