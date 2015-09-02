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
			<div id="blower_box" style="background:#ffffff;width:335px;height:300px;border:1px solid #A8A8A8;position:absolute;top:-312px;left:520px;">
			</div>
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
								<span  class="site_title">索引IP：</span>
								<input type="text" name="index"  value='{$info['index']}' />
								端口：
								<input type="text" name="index_port"  value='{$info['index_port']}' />
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">搜索IP：</span>
								<input type="text" name="search" value='{$info['search']}'  />
								端口：
								<input type="text" name="search_port"  value='{$info['search_port']}' />
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
						<!--
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">编码格式：</span>
								<input type="text"  name="charset" value={$info['charset']}>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">内容：</span>
								<textarea name="content">{code}echo $info['content'];{/code}</textarea>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						-->
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">选择所支持模块：</span>
								{foreach $app as $k=>$v}
								{$v['name']}&nbsp;&nbsp;&nbsp;
								{foreach $module as $kk=>$vv}
								{if $vv['app_uniqueid']==$v['bundle']}
								<input type="checkbox" name="module_id[]"  value='{$v['bundle']}/{$vv['mod_uniqueid']}' {if $db_relation[$v['bundle']][$vv['mod_uniqueid']]}checked{/if} />
								{$vv['name']}&nbsp;
								{/if}
								{/foreach}
								<br>
								{/foreach}
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
