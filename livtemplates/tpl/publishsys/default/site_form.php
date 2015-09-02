{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">
function mod_hidden_show(id)
{
	if(id == 'basic_info')
	{
		$('#basic_info').css('display','');
		$('#pub_set').css('display','none');
		$('#recommend_block').css('display','none');
	}
	else if(id == 'pub_set')
	{
		$('#basic_info').css('display','none');
		$('#pub_set').css('display','');
		$('#recommend_block').css('display','none');
	}
	else if(id == 'recommend_block')
	{
		$('#basic_info').css('display','none');
		$('#pub_set').css('display','none');
		$('#recommend_block').css('display','');
	}
}
function delete_site(id)
{
	window.location.href = "?mid={$_INPUT['mid']}&a=delete&infrm={$_INPUT['infrm']}&site_id="+id;
}
</script>
{code}
if($formdata['id'])
{
	$site_form[0] = $formdata;
}
{/code}
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form&infrm={$_INPUT['infrm']}" class="button_6" style="font-weight:bold;">添加站点</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>新增站点</h2>
				<a href="javascript:void(0)" onclick="mod_hidden_show('basic_info')">基本信息 </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('pub_set')">发布设置   </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('recommend_block')">推荐区块 </a>
				<div id="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">网站名称：</span>
								<input type="text" value="{$site_form[0]['site_name']}" name='site_name' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								将在网站所有页面的浏览器窗口标题中显示。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">网站关键字：</span>
								<input type="text" value="{$site_form[0]['site_keywords']}" name='site_keywords' style="width:450px;">
								<span class="site_fill_tip">
								为网站添加关键字，以便能够在搜索引擎中快速搜索到您的网站。(多个关键字请用,隔开)
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="site_title">网站描述：</span>
								<textarea rows="3" cols="80" name="content">{$site_form[0]['content']}</textarea>
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								为所有页面添加描述，以便能够在搜索引擎中正确搜索到您的网站。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">网站域名:</span>
								<input type="text" value="{$site_form[0]['weburl']}" name='weburl' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								您网站的网址。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">网站发布主目录:</span>
								<input type="text" value="{$site_form[0]['site_dir']}" name='site_dir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
									支持相对路径和绝对路径，如果是linux该目录需要有权限
									如果是相对路径是从ws目录算起,可以直接输入目录名，用"../"返回上级目录
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">首页的文件名:</span>
								<input type="text" value="{$site_form[0]['indexname']}" name='indexname' style="width:100px;">.html
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								不用添加扩展名.
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">生成方式:</span>
								<select name='produce_format' value="{$site_form[0]['produce_format']}">
									{foreach $_configs['site_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['produce_format']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<select name='suffix' value="{$site_form[0]['suffix']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['suffix']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">素材目录格式:</span>
								<select value="{$site_form[0]['material_fmt']}" name='material_fmt'>
									{foreach $_configs['site_material_fmt'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['material_fmt']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								不用添加扩展名.
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">素材访问域名:</span>
								<input type="text" value="{$site_form[0]['material_url']}" name='material_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								用 , 隔开多个 不用填写http://头部 ，如：img1,img2 
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">模板素材访问域名:</span>
								<input type="text" value="{$site_form[0]['tem_material_url']}" name='tem_material_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								需要填写 http:// 如：http:tmp1.xxx.com
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">模板素材目录:</span>
								<input type="text" value="{$site_form[0]['tem_material_dir']}" name='tem_material_dir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								不用添加扩展名.
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">动态程序目录:</span>
								<input type="text" value="{$site_form[0]['program_dir']}" name='program_dir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">动态程序访问域名:</span>
								<input type="text" value="{$site_form[0]['program_url']}" name='program_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								需要填写 http:// 如：http:site.xxx.com
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">js调用文件存放目录:</span>
								<input type="text" value="{$site_form[0]['jsphpdir']}" name='jsphpdir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
					</ul>
					</div>
					<div id="pub_set" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">文章缩略图的宽度:</span>
								<input type="text" value="{$site_form[0]['imagewidth']}" name='imagewidth' style="width:50px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								将在网站所有页面的浏览器窗口标题中显示。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">文章缩略图的高度:</span>
								<input type="text" value="{$site_form[0]['imageheight']}" name='imageheight' style="width:50px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								将在网站所有页面的浏览器窗口标题中显示。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">每次生成页面个数:</span>
								<input type="text" value="{$site_form[0]['pro_page_num']}" name='pro_page_num' style="width:50px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								（生成到本地目录）.
								</span>
							</div>
						</li>
						</ul>
					</div>
					<div id="recommend_block" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">网站名称：</span>
								
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								将在网站所有页面的浏览器窗口标题中显示。
								</span>
							</div>
						</li>
						</ul>
					</div>
				<input type="hidden" name="a" value="create_update" />
				<input type="hidden" name="site_id" value="{$site_form[0]['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $site_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
				{if $site_form[0]['id']}<input type="button" onclick="delete_site({$site_form[0]['id']})" name="sub" value="删除" class="button_6_14"/>{/if}
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}