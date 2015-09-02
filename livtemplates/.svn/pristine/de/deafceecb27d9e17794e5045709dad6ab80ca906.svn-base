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
					<h2>{$optext}JSSDK接口</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">名称：</span>
								<input type="text" name='name'  value="{$name}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">英文名称：</span>
								<input type="text" name='ename'  value="{$ename}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">层级：</span>
								<input type="text" name='level'  value="{$level}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述：</span>
								<textarea name="brief">{$brief}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">开启：</span>
								<input type="checkbox" name="is_open" value="1" {if $is_open}checked{/if}/>
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