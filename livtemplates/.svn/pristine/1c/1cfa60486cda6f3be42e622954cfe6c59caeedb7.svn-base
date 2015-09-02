{template:head}
{code}
$list = $formdata;
{/code}
{css:ad_style}
{css:column_node}
{js:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑专题分类信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">专题分类名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">专题分类描述：</span>
								<input type="text" value="{$list['brief']}" name='brief' style="width:440px;">
							</div>
						</li>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="html" value="true" />
				<input type="hidden" name="fid" value="{$list['fid']}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	 <!--<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>-->
	</div>
{template:foot}