{template:head}
{code}
	$type = array('0'=>'图片','1'=>'flash','2'=>'视频','3'=>'文字');
	if(!$formdata['link'])
	{
		$formdata['link'] = 'http://';
	}
	$ad_token = md5(time()+rand(1,10000));
	/*支持的上传类型*/
	$types = implode(';', array_merge($_configs['allow_upload_types']['img'],$_configs['allow_upload_types']['video']));
	if($id)
	{
		$optext="更新";
		$a="update_content";
	}
	else
	{
		$optext="添加";
		$a="create_content";
	}
	
	
		/*新增集合的状态控件样式*/
	$item_collect_status = array(
		'class' => 'down_list',
		'show' => 'collect_status_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_k()'
	);
	
	$trans_status_default = -1;
	
	/*新增集合的类型控件样式*/
	$item_collect_leixing = array(
		'class' => 'down_list',
		'show' => 'collect_leixing_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_k()'
	);
	
	$leixing_default = -1;
	$collect_vod_leixing[$leixing_default] = '全部类型';
	foreach($_configs['video_upload_type'] as $k =>$v)
	{
		$collect_vod_leixing[$k] = $v;
	}
	
	/*集合页面的分类控件样式*/
	$item_collect_sort = array(
		'class' => 'transcoding down_list',
		'show' => 'collect_sort_show',
		'width' => 100,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	/*新增集合面板里分类控件样式*/
	$item_collect_addsort = array(
		'class' => 'down_list',
		'show' => 'collect_addsort_show',
		'width' => 85,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	$collect_default = -1;
	$collect_vod_sorts[$collect_default] = '选择分类';
	foreach($vod_sort[0] as $k =>$v)
	{
		$collect_vod_sorts[$v['id']] = $v['sort_name'];
	}
	
	/*集合面板日期控件的数据设定*/
	$attr_date_collect = array(
		'class' => 'colonm down_list data_time',
		'show' => 'collect_colonm_show',
		'width' => 104,
		'state' => 1,
	);
	
	$date_default = 1;
	
	$attr_date = array(
		'class' => 'colonm down_list data_time',
		'show' => 'colonm_show',
		'width' => 104,
		'state' => 1,
	);
	$_configs['video_upload_status'][-1] = '全部状态';
{/code}
{css:calendar}
{css:ad_style}
{js:adv_upload}
{js:ad}
{js:adv_video}
{css:vod_style}
{css:mark_style}
<style type="text/css">
.add_collect_form .jh_vod .ul{overflow-y: auto}
</style>
<script type="text/javascript">
var swfu;
window.onload = function () {
	var url = "run.php?a=upload_ad_material&mid={$_INPUT['mid']}";
	swfu = new SWFUpload({
		upload_url: url,
		post_params: {"ad_token": "{$ad_token}", "access_token":"{$_user['token']}"},	
		file_size_limit : "20 MB",
		file_types : '{$types}',
		file_types_description : "视频和图片",
		file_upload_limit : "0",

		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,

		
		button_image_url : RESOURCE_URL+"upload_browser.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 100,
		button_height: 18,
		button_text : '<span class="button">单击选择素材</span>',
		button_text_style : '.button {font-family: Helvetica, Arial, sans-serif; font-size: 12pt;}',
		button_text_top_padding: 0,
		button_text_left_padding: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILE,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		debug: false
	});
};


function hg_add_times()
{
	$('#multi_times').append('<timedel class="date_list"><span class="title"></span><a class="plus" style="display:inline-block;float:none;margin-left:0;text-indent:-9999em;" href="###" onclick="hg_add_times()" id="chang_time_rule">添加多段时间</a>'+$.trim($('#clone').html())+'<span onclick="hg_del_times(this)"  style="float:none;display:inline-block;text-indent:-99999em;" class="minus">删除</span><br/></timedel>');
	frame_inner_Height($(".date_list:last").height() + 3);
}
function hg_del_times(obj)
{
	$(obj).parents('timedel').remove();
}
function hg_add_weight(obj)
{
	if($(obj).attr('checked'))
	{
		$('#hg_weight').show();
	}
	else
	{
		$('#hg_weight').hide();
	}
}
function hg_show_customer_form(html)
{
	$('#ad_customer').html(html).fadeIn('fast');
}
function hg_create_customer_callback(data)
{
	var array_data = $.parseJSON(data);
	if(array_data.id)
	{
		$('#ad_customer').fadeOut('fast');
	}
	html = '<li><a class="overflow" attrid="'+array_data.id+'" onclick="if(hg_select_value(this,0,\'adclient_show\',\'source\',0)){};" href="###">'+array_data.customer_name+'</a></li>';
	$('#adclient_show').prepend(html);
	$('#adclient_show').children('li:first').children('a').click();
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data" onsubmit="return submit_form_verify()" id="content_form">
<h2>广告内容</h2>
<ul class="form_ul">
<li class="i nobg form_border">
	<div class="form_ul_div clear">
	<span class="title">广告名称：</span><input type="text" value='{$formdata["title"]}' name='title' class="title">
	<font class="important">必填</font>
	</div>
</li>
<li class="i nobg form_border">
<div class="form_ul_div clear">
<span class="title">描述：</span><textarea style="width:260px;height:55px;min-height:55px;" name="brief">{$formdata["brief"]}</textarea>
<br><div style="margin-left:73px;margin-top:10px;"><input style="width:20px;vertical-align:middle" type="checkbox" {if $formdata['mtype']=='text'}checked="checked"{/if} name="ad_text" id="select_matrial_five"/>作为文字广告内容</div>
<font class="important"></font>
</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
{code}
		$adclient_css = array(
		'class' => 'down_list i',
		'show' => 'adclient_show',
		'width' => 140,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['source'] = $formdata['source'] ? $formdata['source'] : 0;
	$customer[0][0]='选择客户';
{/code}
<span class="title">广告客户：</span>{template:form/search_source,source,$formdata['source'],$customer[0],$adclient_css}
<a style="line-height:22px;margin-left:10px;background:orange;color:white" href="./run.php?mid={$_INPUT['mid']}&a=detail_customer" onclick="return hg_ajax_post(this, '新建广告客户',0)">新建广告客户</a></div>
</li>
{code}
$hg_attr['onchange'] = 'onchange=change_material(this)';
{/code}

<li class="i nobg">
<div class="form_ul_div clear">
	<span class="title">素材：</span><div id="material">
	<span class="select_matrial" id="select_matrial_one">本地素材</span>
	<span class="select_matrial" id="select_matrial_two">联网素材(URL)</span>
	<span class="select_matrial" id="select_matrial_three">JS代码块</span>
	<span class="select_matrial" id="select_matrial_four">选择视频</span>
<div class="matrial_input">
<div id="local_material_contianer" class="local_material" style="padding-top:0;">
<!--上传控件flash位置-->
<span id="spanButtonPlaceholder"></span>
</div>
<div class="remote clear" style="margin-left:41px;" id="remote_two"><input type="text" style="float:left;height:20px;" value="http://将图片、flash链接粘贴到这里" name="remote_matrial" id="remote_matrial" class="remote_matrial"  onfocus="javascript:if(this.value=='http://将图片、flash链接粘贴到这里') this.value='';void(0)" onblur="javascript:if(this.value=='') this.value='http://将图片、flash链接粘贴到这里';add_remote_material()"><input type="button" onclick="add_remote_material()" value="预览" class="button_2" style="margin:0 0 0 4px;height:26px;line-height: 26px;float:left;border:none;"></div>
<div id="ad_js_code" style="display:none"><textarea name="js_code" style="margin-bottom:5px;" onchange="javascript:
	$('input[name=mtype]').val('javascript');">{if $formdata['mtype']=='javascript'}{$formdata['material']}{/if}</textarea><input type="hidden" value="true" name="html"></div>
</div>
<div id="divFileProgressContainer" class="adv_tips"></div>
</div>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<div style="float:left">
	<span class="title">预览：</span>
	<div class="preview" id="thumbnails" style="overflow-x:auto;max-height:400px;text-align:center;min-height:100px;">
        {template:unit/adv_mtype, adv, adv, $formdata}
    </div>
</div>
<input type="hidden" value='' name="material" id="material_url" />
<input type="hidden" name="mtype" value="{$formdata['mtype']}"/>
</div>

		<div class="form_ul_div" style="margin:10px 0 0 60px;">
			<div id="add_collect_form" class="add_collect_form"  style="display:none;height:422px;">
				<div id="hg_select_all">
					<!-- 视频选择区域开始 -->
					<div  class="select_vod clear"  id="collect_info_content" style="margin:0;position:absolute;left:102px;top:285px;background:#f2f2f2;z-index:99999">
						<div class="jh_vod" style="width:632px">
							<!-- 搜索部分开始 -->
							<div id="search_condition" class="search_condition_all info">
								 <div  class="search_l">{template:form/search_source,sea_add_leixing_id,$leixing_default,$collect_vod_leixing,$item_collect_leixing}</div>
								 <div class="search_l" id="sort_select"></div>
								 <div  class="search_l">{template:form/search_source,collect_trans_status,$trans_status_default,$_configs['video_upload_status'],$item_collect_status}</div>
								 
								 <div class="right_2" style="position: relative;">
									  <div class="button_search">
										<input type="button" value="" name="hg_search_videos"   onclick="hg_search_k();"      style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
									  </div>
									  {template:form/search_input,vk,$_INPUT['vk']}             
								 </div>
							</div>
							<!-- 搜索部分结束-->
							<div class="clear"  id="video_content"></div>
						</div>
						<div class="clear jh_vod"  id="selected_videos" style="margin-left:5px;width:152px;display:none;" >
						   <div id="selected_videos_ul" class="ul img"></div>
						</div><!--暂时隐藏这块区域-->
					</div>
					<!-- 视频选择区域结束 -->
				</div>
			</div>
		</div>
</li>
<li class="i form_border">
<div class="form_ul_div clear">
<span class="title">链接：</span><input type="text" value='{$formdata["link"]}' name='link' class="link">
</div>
</li>
<li class="i">
<div class="form_ul_div clear" id="multi_times">

<span class="title">投放时间：</span><span style="clear:both">
{if $formdata['pub_time']}
	{foreach $formdata['pub_time']['start_time'] as $k=>$v}<timedel class="date_list">{if $k>=1}<span class="title"></span>{/if}<a class="plus" style="display:inline-block;float:none;margin-left:0;text-indent:-9999em;" href="###" onclick="hg_add_times()" id="chang_time_rule">添加多段时间</a><input type="text" class="date_pick" name='start[]' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off" value="{$v}"/>&nbsp;
	<input type="text" class="date_pick" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off" name="end[]" value="{$formdata['pub_time']['end_time'][$k]}"/></span>{if $k>0}<span style="float:none;display:inline-block;text-indent:-99999em;" class="minus" onclick="hg_del_times(this)">&nbsp;</span>{/if}</timedel>
	{/foreach}
{else}<a class="plus" style="display:inline-block;float:none;margin-left:0;text-indent:-9999em;" href="###" onclick="hg_add_times()" id="chang_time_rule">添加多段时间</a><input type="text" class="date_pick" name='start[]' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off"/>&nbsp;
	<input type="text" class="date_pick" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off" name="end[]"/>
{/if}
<font class="important">不填写代表无期限，多时间段需要连续</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
{code}
		/*select样式*/
		$weight_css = array(
		'class' => 'down_list i',
		'show' => 'weight_show',
		'width' => 40,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['weight'] = $formdata['weight'] ? $formdata['weight'] : 1;
	$weight = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);

/*select样式*/
		$priority_css = array(
		'class' => 'down_list i',
		'show' => 'priority_show',
		'width' => 40,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['priority'] = $formdata['priority'] ? $formdata['priority'] : 2;
{/code}
<span class="title">优先级：</span>{template:form/search_source,priority,$formdata['priority'],$_configs['priority'],$priority_css}<input style="float:left;margin:3px 7px 0 20px;" type="checkbox" value="true" name="isvalidweight" id="isvalidweight" onclick="hg_add_weight(this)" {if $formdata['weight']}checked="checked"{/if}/><label for="isvalidweight" style="float:left;margin-top:4px;">设置权重</label><div id="hg_weight" {if !$formdata['weight']}style="display:none;"{/if}>{template:form/search_source,weight,$formdata['weight'],$weight,$weight_css}</div>
</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">其他设置：</span>
	<div style="float:left;padding:5px 0;">
		<div class="clear" style="padding:0 0 7px 0;"><input name="other_num_price" id="other_num_price" style="float:left;margin:-2px 7px 0" type="checkbox" /><label for="other_num_price">投放数量和价格[待开发]</label></div>
		<div class="clear" style="padding:7px 0;"><input name="other_count" id="other_count" style="float:left;margin:-2px 7px 0" type="checkbox" /><label for="other_count">限制每日投放数量[待开发]</label></div>
		<div class="clear" style="padding:7px 0;"><input name="other_ip_count" id="other_ip_count" style="float:left;margin:-2px 7px 0" type="checkbox" /><label for="other_ip_count">限制对独立访客的展现次数[待开发]</label></div>
	</div>
	</div>
</li>
</ul>
<input type="hidden" value="{$formdata['id']}" id="ad_id" name="ad_id" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="goon" value="0" id="goon"/>
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="adv_mid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}广告" class="button_6_14"/><!--<input type="button" value="发布广告设置" class="button_6_14" style="margin-left:28px;" onclick="hg_next_publish()"/>
-->
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<div id="clone" style="display:none">
<input type="text" class="date_pick" name='start[]' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off"/>&nbsp;
<input type="text" class="date_pick" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:00'})" autocomplete="off" name="end[]"/>
</div>
<script type="text/javascript">frame_inner_Height()</script>
<div id="ad_customer" style="box-shadow:0 0 3px #555;padding:0 12px 12px 12px;background:#f0f0f0;display:none;position:absolute;top:100px;left:320px;border:1px solid #f5f5f5;border-radius:5px;"></div>
{template:foot}