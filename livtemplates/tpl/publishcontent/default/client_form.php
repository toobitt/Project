{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">
</script>
{code}
if($formdata['id'])
{
	$client_form[0] = $formdata;
}
{/code}
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form&infrm={$_INPUT['infrm']}" class="button_6" style="font-weight:bold;">添加站点</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>终端</h2>
				<div id="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">终端名称：</span>
								<input type="text" value="{$client_form[0]['name']}" name='name' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">终端标识：</span>
								<input type="text" value="{$client_form[0]['sign']}" name='sign' style="width:300px;">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否打开：</span>
								<input type="radio" name="is_open" value="1"  {if  $formdata['is_open']==1}checked="checked"{/if}/> 是
								<input type="radio" name="is_open" value="0"  {if  $formdata['is_open']==0}checked="checked"{/if}/> 否
							
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否需要部署：</span>
								<input type="radio" name="need_deploy" value="1"  {if  $formdata['need_deploy']==1}checked="checked"{/if}/> 是
								<input type="radio" name="need_deploy" value="0"  {if  $formdata['need_deploy']==0}checked="checked"{/if}/> 否
							
							</div>
						</li>
						
					</ul>
					</div>
				<input type="hidden" name="a" value="create_update" />
				<input type="hidden" name="client_id" value="{$client_form[0]['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $client_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}