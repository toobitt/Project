{template:head}
{template:head/nav}
{css:ad_style}
{js:input_file}
{js:message}
{code}
	
	/*新增集合的状态控件样式*/
	$item_collect_status = array(
		'class' => 'down_list',
		'show' => 'collect_status_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_videos_backup()'
	);
	
	$trans_status_default = -1;
	
	/*新增集合的类型控件样式*/
	$item_collect_leixing = array(
		'class' => 'down_list',
		'show' => 'collect_leixing_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_videos_backup()'
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
{css:vod_style}
{css:mark_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:backup}
{js:jscroll}

<script type="text/javascript">
	function hg_del_keywords()
	{
		var value = $('#search_list').val();
		if(value == '关键字')
		{
			$('#search_list').val('');
		}

		return true;
	}
	
   $(document).ready(function(){
	   
		/*拖放设置*/
		$("#selected_videos").droppable({
			accept: "#video_content_ul span[id^='v_']",
			tolerance:'intersect',
			drop: function(event,ui){
			   var drager = ui.draggable;
			   hg_deleteSelf(drager);
			   hg_scroll_left();
			   hg_scroll_right();
			}
		});

		$("#video_content").droppable({
			accept: "#selected_videos_ul span[id^='select_']",
			tolerance:'intersect',
			drop: function(event,ui){
				var drager = ui.draggable;
				hg_goback_content(drager);
				 hg_scroll_left();
				 hg_scroll_right();
			}
		});

		$('#collect_leixing_show li a').click(function(){
			 var url = './run.php?mid='+gMid+'&a=get_leixing_sort&vod_leixing='+parseInt($(this).attr('attrid'));
			 hg_ajax_post(url);
		});
		
   });   

   function hg_put_sort(html)
   {
		$('#sort_select').html(html);
		$('#sea_collect_addsort_show li a').click(function(){
			hg_search_videos_backup();
		});
   }

	bindFileInput('file_input','f_file','file_text');


function hg_submit_backup_video()
{
    var video_id = hg_get_selectedVidesId();
	
	if(!$('#f_file').val())
	{
		$('#back_up_video_id').val(video_id);
	}
	hg_closeBackUpTpl();
	return hg_ajax_submit('backup','','','hg_backup_callback');
	
}
function hg_backup_callback(obj)
{
	if(obj)
	{
		location.href = './run.php?mid='+gMid+'&infrm=1';
	}
}
function hg_local_upload_backup()
{
	$('#vidoes_show').hide();
	$('#close_file_input').show();
	$('#file_text').show();
	$('#important_2').html('本地上传');
	if($('#back_up_video_id').val())
	{
		$('#back_up_video_id').val('');
	}
}
function hg_toggle_bottom()
{
	$('#add_collect_form').slideUp();
	$('#vidoes_show').show();
	$('#file_input').show();
	$('#img_span').hide();
	$('#file_text').hide();
	$('#important_2').html('视频文件');
	$('#close_file_input').hide();
	$('#f_file').removeAttr('disabled');
}
</script>
{code}
	$action = $a;
{/code}
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div class="ad_middle">
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="backup" name="backup" class="ad_form h_l">
<h2>{$optext}备播文件</h2>
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">备播文件：</span>
				<span class="file_input" id="vidoes_show" onclick="hg_showBackUp(),hg_getManyVideos(0);" style="cursor:pointer;margin-right:10px;float:left;{if $type == 2 && $id}display:none;{/if}">选择视频</span>
				
				<span class="file_input" id="file_input" style="float:left;{if $type == 1 && $id}display:none;{/if}">本地上传</span>
				<span id="file_text" class="overflow file-text">{$logo}</span>
				<input name="backup_file" type="file"  value="" class="file" id="f_file"  onclick="hg_local_upload_backup();" hidefocus>
				
				<span id="img_span" style="display:{if $a == 'create' || !$vodinfo_id}none{else}block{/if};margin-left:10px;color:#333;float:left;line-height:44px;">
					
					<div style="float:right;line-height:22px;margin-left:5px;width:250px;">
						<span id="vod_name" style="width:250px;display:block;" class="overflow">名称:{$filename}</span>
						<span id="vod_toff" style="clear:both;display:block">时长:{$toff}</span>
					</div>
					<img id="vodid_img" src="{$img}" style="height:52px;width:52px;vertical-align: middle;padding:2px;background:white;border:1px solid #CCC;margin:0 2px 0 5px;"/>
				</span>
				
				<span class="important" id="important_2">视频文件</span><span class="" id="close_file_input" title="取消" onclick="hg_toggle_bottom();" style="cursor:pointer;display:none;background:gray;margin-right:50px;padding:0px ;float:right;color:#ffffff;border-radius:50px;width:18px;height:18px;line-height:18px;text-align:center;font-weight: bold;">X</span>
	
			</div>
			<div class="form_ul_div" style="margin:10px 0 0 60px;">
				<div id="add_collect_form" class="add_collect_form"  style="display:none;height:422px;">
					<div id="hg_select_all">
						<!-- 视频选择区域开始 -->
						<div  class="select_vod clear"  id="collect_info_content" style="margin:0;">
							<div class="jh_vod" style="width:632px">
								<!-- 搜索部分开始 -->
								<div id="search_condition" class="search_condition_all info">
									 <div  class="search_l">{template:form/search_source,sea_add_leixing_id,$leixing_default,$collect_vod_leixing,$item_collect_leixing}</div>
									 <div class="search_l" id="sort_select"></div>
									 <div  class="search_l">{template:form/search_source,collect_trans_status,$trans_status_default,$_configs['video_upload_status'],$item_collect_status}</div>
									 
									 <div class="right_2" style="position: relative;">
										  <div class="button_search">
											<input type="button" value="" name="hg_search_videos"   onclick="hg_search_videos_backup();"      style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
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
			<div class="form_ul_div">
				<span class="title">标题：</span>
				<input type="text" name="title" value="{$title}" style="width:192px"  id="required_1" >
				<!-- onblur="input_content_color(1),checkCode();" onfocus="input_content_color(1);"  -->
				<font class="important" id="important_1">必填</font>
			</div>
			{if !empty($server_info)}
			<div class="form_ul_div clear">
				<span class="title">服务器：</span>
				{code}
					$server_source = array(
						'class' => 'down_list i',
						'show' => 'item_shows_',
						'width' => 100,/*列表宽度*/		
						'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'=>1,
					);
					
					$server_id = $server_id ? $server_id : 0;
					$server[$server_id] = '--请选择--';
					
					foreach ($server_info AS $v)
					{
						$server[$v['id']] = $v['name'];
					}
				{/code}
			{if !$id}
				{template:form/search_source,server_id,$server_id,$server,$server_source}
			{else}
				<div class="down_list i" style="width:100px">
					<span class="input_left"></span>
					<span class="input_right"></span>
					<span class="input_middle">
						<a><em></em><label class="overflow">{$server[$server_id]}</label></a>
					</span>
				</div>
				<input type="hidden" name="server_id" value="{$server_id}" id="server_id" />
			{/if}
			</div>
			{/if}
			<div class="form_ul_div">
				<span class="title">描述：</span>
				{template:form/textarea,brief,$brief}
			</div>
		</li>
	</ul>
	</br>
	<input type="submit" value="{$optext}" class="button_6_14"  />
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="back_up_video_id" id="back_up_video_id" value="{$vodinfo_id}" />
	<input type="hidden" id="temp_video_id" value="{$vodinfo_id}" />
	<input type="hidden" name="type" value="{$type}" />
</form>
</div>
<div class="right_version">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}