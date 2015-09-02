{template:head}
{code}
$list = $formdata[0];
{/code}
{css:ad_style}
{css:column_node}
<script>
$(function ($) {
	$('form').submit(function (e) {
		var name = $(this).find('input[name="name"]').val(),
			id = $(this).find('input[name="id"]').val();
		parent.updata_id(name, id);
	})
});
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑模板分类信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">模板分类：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">存放路径：</span>
								<input type="text" value="{$list['sort_dir']}" name='sort_dir' style="width:440px;"/>
								<font class="important">不可编辑</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">分类描述：</span>
								<input type="text" value="{$list['brief']}" name='brief' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title"><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	</div>
{template:foot}