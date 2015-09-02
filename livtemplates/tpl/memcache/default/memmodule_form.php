{template:head}
{css:ad_style}
<script type="text/javascript">
	
</script>
{code}
if(!empty($formdata))
{
	$server = $formdata['server'];
	$app = $formdata['app'];
	$module = $formdata['module'];
	$relation = $formdata['relation'];
	$ram = $relation[$app][$module];
}
//	print_r($info['tem_data']);
//print_r($ram[48]);
{/code}

<div id="channel_form" style="margin-left:60%;position:relative;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l" name="deploy_form" id="deploy_form">
				<h2>
				更新</h2>
				
				<div id="basic_info" name="basic_info" >
					<ul class="form_ul">
					{foreach $server as $k=>$v}
						<li class="i">
							<div class="form_ul_div">
							<input type="checkbox"  name="server[]" value="{$v['id']}" {if $ram[$v['id']]}checked{/if} >勾选添加此服务器
								<span  class="site_title">名称:{$v['name']}<br><br><br><br><br><br>{$v['host']}:{$v['port']}</span>
								<br>
								是否使用持久化连接：<input type="text"  name="{$v['id']}_persistent" value='{if isset($ram[$v['id']]['param']['persistent'])}{$ram[$v['id']]['param']['persistent']}{else}1{/if}' size=5><br>
								此服务器被选中的权重：<input type="text"  name="{$v['id']}_weight" value='{if isset($ram[$v['id']]['param']['weight'])}{$ram[$v['id']]['param']['weight']}{else}0{/if}' size=5><br>
								连接持续（超时）时间（单位秒：<input type="text"  name="{$v['id']}_timeout" value='{if isset($ram[$v['id']]['param']['timeout'])}{$ram[$v['id']]['param']['timeout']}{else}1{/if}' size=5><br>
								服务器连接失败时重试的间隔时间：<input type="text"  name="{$v['id']}_retry_interval" value='{if isset($ram[$v['id']]['param']['retry_interval'])}{$ram[$v['id']]['param']['retry_interval']}{else}15{/if}' size=5><br>
								此服务器是否可以被标记为在线状态：<input type="text"  name="{$v['id']}_status" value='{if isset($ram[$v['id']]['param']['status'])}{$ram[$v['id']]['param']['status']}{else}{/if}' size=5><br>
								指定一个运行时发生错误后的回调函数：<input type="text"  name="{$v['id']}_failure_callback" value='{if isset($ram[$v['id']]['param']['failure_callback'])}{$ram[$v['id']]['param']['failure_callback']}{else}{/if}' size=5>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					{/foreach}	
						
					</ul>
					</div>
				<input type="hidden" name="a" value="{if $app}update{else}create{/if}" />
				<input type="hidden" name="app" value="{$app}" />
				<input type="hidden" name="module" value="{$module}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<br />
				<input type="submit" name="sub" value="{if $app}更新{else}新增{/if}" class="button_6_14"/>
			</form>
		</div>
	</div>
