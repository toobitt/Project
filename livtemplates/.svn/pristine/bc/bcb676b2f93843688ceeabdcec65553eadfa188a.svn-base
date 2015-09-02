{code}
	$image_resource = RESOURCE_URL;
{/code}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['data']['id']}">
	<div id="contribute_pics_show" class="tuji_pics_show">
	  	{code}
			$url = '';
			if (!empty($formdata['avatar']))
			{
				$url = $formdata['avatar']['host'].$formdata['avatar']['dir'].'400x300/'.$formdata['avatar']['filepath'].$formdata['avatar']['filename'];
			}
		{/code}
		{if $url}
			<img alt="头像" src="{$url}">		
		{/if}	  	  	
		<span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span> 
	</div>
</div>
<div class="info clear cz"  >
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
		</li>
		<li>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"  href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&status=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0);">审核</a>		
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&status=2&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0);">打回</a>
		</li>
	</ul>
</div>

<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'staff_baseinfo')"><span title="展开\收缩"></span>基本信息</h4>
	<ul id="staff_baseinfo" class="clear">	    
		<li class="w"><span>帐户：{$formdata['account']}</span></li>
		<li class="h"><span>姓名：{$formdata['name']}</span></li>
		<li class="h"><span>英文名：{$formdata['english_name']}</span></li>
		<li class="h"><span>电话：{$formdata['tel']}</span></li>
		<li class="h"><span>分机号：{if $formdata['ext_num']}{$formdata['ext_num']}{/if}</span></li>
		<li class="h"><span>手机：{$formdata['mobile']}</span></li>
		<li class="w"><span>邮件：{$formdata['email']}</span></li>
	</ul>
</div>

{else}
	资料不存在,请刷新页面更新
{/if}

