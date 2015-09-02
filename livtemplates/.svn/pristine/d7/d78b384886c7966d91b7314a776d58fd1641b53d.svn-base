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
					<h2>{$optext}域名</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">名称</span>
								<input type="text" value="{$name}" name='name' style="width:240px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">域名</span>
								<input type="text" value="{$domain}" name='domain' style="width:240px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">正则域名</span>
								<input type="text" value="{$domain_reg}" name='domain_reg' style="width:240px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="width: 100px;">推荐页面是否展示</span>
								<input type="radio" name="is_display" class="is_display" {if $is_display == 1 } checked="checked"{/if} value="1" /><span>显示</span>
								<input type="radio" name="is_display" class="is_display" {if $is_display == 0 } checked="checked"{/if} value="0" /><span>不显示</span>
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