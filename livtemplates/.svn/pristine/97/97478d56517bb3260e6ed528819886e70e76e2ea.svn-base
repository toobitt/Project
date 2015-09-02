{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">
function mod_hidden_show(id)
{
	$('#basic_info,#video_set').css('display','none');
	$('#'+id).css('display','');
}
function delete_site(id)
{
	window.location.href = "?mid={$_INPUT['mid']}&a=delete&infrm={$_INPUT['infrm']}&site_id="+id;
}
function video_record()
{
	if($('input[name=is_video_record]:checked').val()==1)
	{
		$('#video_record_count,#video_update_peri,#video_record_url,#video_record_filename').attr('disabled',false);
	}
	else
	{
		$('#video_record_count,#video_update_peri,#video_record_url,#video_record_filename').attr({disabled:true});
	}
}
function check_domain()
{
	var site_id = $('#site_id').val();
	var weburl = $('#weburl').val();
	var sub_weburl = $('#sub_weburl').val();
	var site_dir = $('#site_dir').val();
	if(weburl && sub_weburl)
	{
		var url= "./run.php?mid="+gMid+"&a=check_domain&site_id="+site_id+"&weburl="+weburl+"&sub_weburl="+sub_weburl+"&site_dir="+site_dir;
    	$.ajax({
		type:'get',
		url:url,
		data:'',
		dataType:'Json',
		success:function(msg){
			if(msg!=1)
			{
				alert('该域名,子域名已存在');
			}
		},
		error:function(){
		
		}
		})
	}
}
</script>
{code}
	$site_form[0] = $formdata;
{/code}

<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=site_form&infrm={$_INPUT['infrm']}" class="button_6" style="font-weight:bold;">新增站点</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>新增站点</h2>
				<div class="ext-tab">
				<a href="javascript:void(0)" onclick="mod_hidden_show('basic_info')">基本信息 </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('video_set')">百度视频收录 </a>
				</div>
				<div id="basic_info" >
					<ul class="form_ul">
					<!--
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title" style="width:130px;">支持的模块：</span>
								{if $formdata['module']}
								{foreach $formdata['module'] as $k=>$v}
								<input type=checkbox name="support_module[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$site_form[0]['site']['support_module']))) echo "checked";{/code} />{$v['name']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title" style="width:130px;">支持的内容类型：</span>
								{if $formdata['content_type']}
								{foreach $formdata['content_type'] as $k=>$v}
								<input type=checkbox name="support_content_type[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$site_form[0]['site']['support_content_type']))) echo "checked";{/code} />{$v['content_type']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						-->
					<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">支持的客户端：</span>
								{foreach $site_form[0]['client'] as $k=>$v}
								<input type=checkbox name="client[]" value="{$v['id']}" {code}if(!empty($site_form[0]['site']['support_client'])){ if(in_array($v['id'],explode(',',$site_form[0]['site']['support_client']))) echo "checked";}{/code}>{$v['name']}&nbsp;&nbsp;&nbsp;
								{/foreach}
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">网站名称：</span>
								<input type="text" value="{$site_form[0]['site']['site_name']}" name='site_name' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								将在网站所有页面的浏览器窗口标题中显示。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">网站关键字：</span>
								<input type="text" value="{$site_form[0]['site']['site_keywords']}" name='site_keywords' style="width:450px;">
								<span class="site_fill_tip">
								为网站添加关键字，以便能够在搜索引擎中快速搜索到您的网站。(多个关键字请用,隔开)
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="site_title">网站描述：</span>
								<textarea rows="3" cols="80" name="content">{$site_form[0]['site']['content']}</textarea>
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								为所有页面添加描述，以便能够在搜索引擎中正确搜索到您的网站。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">网站发布主目录:</span>
								<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['site_dir']}" name='site_dir' id="site_dir" style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
									支持相对路径和绝对路径，如果是linux该目录需要有权限
									如果是相对路径是从ws目录算起,可以直接输入目录名，用"../"返回上级目录
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">域名:</span>
								<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['weburl']}" name='weburl' id="weburl" style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								您网站的网址,不包含www,如：www.hogesoft.com只需要填写：hogesoft.com
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">子域名:</span>
								<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['sub_weburl']}" name='sub_weburl' id="sub_weburl" style="width:150px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								您网站的子域名。如：www.hogesoft.com只需要填写：www.
								</span>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">首页的文件名:</span>
								<input type="text" value="{$site_form[0]['site']['indexname']}" name='indexname' style="width:100px;">.html
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								不用添加扩展名.
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">生成方式:</span>
								<select name='produce_format' value="{$site_form[0]['site']['produce_format']}">
									{foreach $_configs['site_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['site']['produce_format']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<select name='suffix' value="{$site_form[0]['site']['suffix']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['site']['suffix']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<!--
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">素材目录格式:</span>
								<select value="{$site_form[0]['site']['material_fmt']}" name='material_fmt'>
									{foreach $_configs['site_material_fmt'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['site']['material_fmt']==$k) echo "selected";{/code}>
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
								<input type="text" value="{$site_form[0]['site']['material_url']}" name='material_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								用 , 隔开多个 不用填写http://头部 ，如：img1,img2 
								</span>
							</div>
						</li>
						-->
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">模板素材访问域名:</span>
								<input type="text" value="{$site_form[0]['site']['tem_material_url']}" name='tem_material_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								需要填写 http:// 如：http:tmp1.xxx.com
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">模板素材目录:</span>
								<input type="text" value="{$site_form[0]['site']['tem_material_dir']}" name='tem_material_dir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								不用添加扩展名.
								</span>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">动态程序目录:</span>
								<input type="text" value="{$site_form[0]['site']['program_dir']}" name='program_dir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">动态程序访问域名:</span>
								<input type="text" value="{$site_form[0]['site']['program_url']}" name='program_url' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								需要填写 http:// 如：http:site.xxx.com
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">js调用文件存放目录:</span>
								<input type="text" value="{$site_form[0]['site']['jsphpdir']}" name='jsphpdir' style="width:300px;">
								<font class="important" style="color:red">*</font>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
					</ul>
					</div>
					
					<div id="video_set" style="display:none">
						<ul class="form_ul">
							<li class="i">
							<div class="form_ul_div"><span class="site_title">用户email：</span>
								<input type=text name="user_email" id="user_email" value="{$site_form[0]['site']['user_email']}">
								<span class="site_fill_tip">
								</span>
							</div>
							</li>
							<li class="i">
							<div class="form_ul_div"><span class="site_title">百度视频收录：</span>
								<input type=radio name="is_video_record" onclick="video_record()" value=1 {if $site_form[0]['site']['is_video_record']}checked{/if}>启用
								<input type=radio name="is_video_record" onclick="video_record()" value=0 {if !$site_form[0]['site']['is_video_record']}checked{/if}>关闭
								<span class="site_fill_tip">
								</span>
							</div>
							</li>
							<li class="i">
							<div class="form_ul_div"><span class="site_title">收录周期(分钟)：</span>
								<input type=text name="video_update_peri" id="video_update_peri" value="{if $site_form[0]['site']['is_video_record']}{$site_form[0]['site']['video_update_peri']}{/if}" {if !$site_form[0]['site']['is_video_record']}disabled{/if}>
								<span class="site_fill_tip">
								搜索引擎将遵照此周期访问该页面
								</span>
							</div>
							</li>
							<li class="i">
							<div class="form_ul_div"><span class="site_title">每次取收录条数：</span>
								<input type=text name="video_record_count" id="video_record_count" value="{if $site_form[0]['site']['is_video_record']}{$site_form[0]['site']['video_record_count']}{/if}" {if !$site_form[0]['site']['is_video_record']}disabled{/if}>
								<span class="site_fill_tip">
								</span>
							</div>
							</li>
							<li class="i">
							<div class="form_ul_div"><span class="site_title">视频收录XML目录：</span>
								<input type=text name="video_record_url" id="video_record_url" value="{$site_form[0]['site']['video_record_url']}" style="width:200px;">
								<span class="site_fill_tip">
								</span>
							</div>
							</li>
							<li class="i">
							<div class="form_ul_div"><span class="site_title">视频收录XML文件名：</span>
								<input type=text name="video_record_filename" id="video_record_filename" value="{$site_form[0]['site']['video_record_filename']}" style="width:100px;">
								<span class="site_fill_tip">
								</span>
							</div>
							</li>	
						</ul>
					</div>
					
				<input type="hidden" name="a" value="create_update" />
				<input type="hidden" id="site_id" name="site_id" value="{$site_form[0]['site']['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $site_form[0]['site']['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}