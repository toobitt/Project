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
		<li class="h"><span>编号：{$formdata['number']}</span></li>
		<li class="h"><span>姓名：{$formdata['surname']}{$formdata['name']}{if $formdata['english_name']}({$formdata['english_name']}){/if}</span></li>
		<li class="h"><span>部门：{$formdata['department']}</span></li>
		<li class="h"><span>职位：{$formdata['position']}{if $formdata['en_position']}({$formdata['en_position']}){/if}</span></li>
		{code}
			$degree = $_configs['staff_degree'][$formdata['degree']];
		{/code}
		<li class="h"><span>学历：{$degree}</span></li>
		<li class="h"><span>英语：{$formdata['english_level']}</span></li>
		{code}
			$sex = $_configs['staff_sex'][$formdata['sex']];
		{/code}
		<li class="h"><span>性别：{$sex}</span></li>
		{code}
			$married = $_configs['staff_married'][$formdata['is_married']];
		{/code}
		<li class="h"><span>婚姻：{$married}</span></li>
		<li class="h"><span>籍贯：{$formdata['native_place']}</span></li>
		<li class="h"><span>民族：{$formdata['nation']}</span></li>
	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'staff_contact')"><span title="展开\收缩"></span>联系方式</h4>
	<ul id="staff_contact" class="clear">	    
		<li class="h"><span>电话：{$formdata['tel']}{if $formdata['ext_num']}({$formdata['ext_num']}){/if}</span></li>
		<li class="h"><span>手机：{$formdata['mobile']}</span></li>
		<li class="h"><span>传真：{$formdata['fax']}</span></li>
		<li class="h"><span>QQ：{$formdata['qq']}</span></li>
		<li class="w"><span>邮件：{$formdata['email']}</span></li>
		<li class="w"><span>公司：{$formdata['company']}</span></li>
		<li class="w"><span>地址：{$formdata['company_addr']}</span></li>
		{if $formdata['en_company_addr']}
		<li class="w"><span>英文地址：{$formdata['en_company_addr']}</span></li>	
		{/if}		
	</ul>
</div>
{else}
	资料不存在,请刷新页面更新
{/if}

