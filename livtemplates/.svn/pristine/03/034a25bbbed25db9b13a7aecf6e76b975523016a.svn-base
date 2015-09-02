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
			<form action="" method="post" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑返回参数信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">参数描述：</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:200px;"/>
								<font class="important"></font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">参数类型：</span>
								<select name="variable_type">
								<option value='1' {if $formdata['type']==1}selected{/if}>
								直接打印
								</option>
								<option value='2' {if $formdata['type']==2}selected{/if}>
								循环数组
								</option>
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">参数默认值：</span>
								<input type="text" value="{$formdata['value']}" name='var_value' style="width:200px;"/>
								<font class="important">默认值为直接输出的值</font>
							</div>
						</li>
					</ul>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="update_out_variable" />
				<input type="hidden" name="id" value="{$_INPUT['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<br />
				<input type="submit" name="sub" value="更新" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	</div>
