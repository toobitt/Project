
{if $formdata['appid'] && $formdata['device_token']}
{code}
$device_token = str_replace(" ","_",$formdata['device_token']);
{/code}
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q" style="background: url('../../.././../livtemplates/tpl/lib/images/bg-all.png') -67px -70px no-repeat;width:26px;height:26px;top:2px;right:3px;display:inline-block;font-size:0;cursor:pointer;position:absolute;"></span> 
	<div class="info clear cz"  id="vodplayer_{$formdata['appid']}_{$device_token}">
		<ul id="video_opration" class="clear" style="border:0;">
			
			<li>
				 <span>{$formdata['device_token']}</span>
			</li>
		</ul>
	</div>
	<div class="info clear cz"  >
		<ul id="video_opration" class="clear" style="border:0;">
			
			<li>
				<a class="button_4" onclick="return hg_ajax_post(this, '发送消息',0)" href="./run.php?mid={$_INPUT['mid']}&amp;a=instantMessaging&amp;device_token={$formdata['device_token']}_{$formdata['appid']}">发送消息</a>
			</li>
		
			<li>
				<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&device_token={$formdata['device_token']}_{$formdata['appid']}">删除</a>
			</li>
		</ul>
	</div>
	<div class="info clear vo">
		<h4 onclick="hg_slide_up(this,'con_textinfo')"><span title="展开\收缩"></span>内容属性</h4>
		<ul id="con_textinfo" class="clear">	    
			<li class="h"><span>终端类型：</span>{$formdata['types']}</li>
			<li class="h"><span>手机号码：</span>{$formdata['phone_num']}</li>
			<li class="h"><span>系统：</span>{$formdata['system']}</li>
			<li class="h"><span>状态：</span>{$formdata['state']}</li>
			<li class="h"><span>版本：</span>{$formdata['debug']}</li>
			
			<li class="h"><span>iccid：</span>{$formdata['iccid']}</li>
			{if $formdata['imei']}
			<li class="h"><span>imei：</span>{$formdata['imei']}</li>
			{/if}
			<li class="h insInfo" _flag='{$formdata["device_token"]}' style="cursor: pointer;"><span>安装次数：</span>{$formdata['insta_num']}</li>
		</ul>
	</div>
	<div class="info clear ins-info" style="display: none;">
		<h4 onclick="hg_slide_up(this,'show-info')"><span title="展开\收缩"></span>安装信息</h4>
		<div id="show-info">
			<ul id='info-list'></ul>
			<div data-offset='5' class="more" style="display: none;">加载更多</div>
		</div>
	</div>
{else}
	此客户端信息异常,请检查appid是否存在
{/if}

