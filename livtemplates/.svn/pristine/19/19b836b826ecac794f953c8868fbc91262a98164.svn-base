{template:head}
{css:ad_style}
<script type="text/javascript">
	
</script>
{code}
if(!empty($formdata))
{
	$info = $formdata['info'];
	$app = $formdata['app'];
	$module = $formdata['module'];
	$db_relation = $formdata['db_relation'];
}
//	print_r($info['tem_data']);
//print_r($formdata);
{/code}

<div id="channel_form" style="margin-left:60%;position:relative;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l" name="deploy_form" id="deploy_form">
				<h2>
				{if $info['id']}更新{else}新增{/if}配置</h2>
				
				<div id="basic_info" name="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">服务器名称：</span>
								<input type="text"  name="name" value={$info['name']}>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">服务器IP：</span>
								<input type="text" name="host"  value='{$info['host']}' />
								端口：
								<input type="text" name="port"  value='{$info['port']}' />
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">是否开启：</span>
								<input type="checkbox" name="is_open" {if $info['is_open']}checked{/if} value='1'  />
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					</ul>
					</div>
				<input type="hidden" name="a" value="{if $info['id']}update{else}create{/if}" />
				<input type="hidden" name="id" value="{$info['id']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<br />
				<input type="submit" name="sub" value="{if $info['id']}更新{else}新增{/if}" class="button_6_14"/>
			</form>
		</div>
	</div>
