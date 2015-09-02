<!-- 选择控件的数据设置 -->
{code}
	/*新增集合的状态控件样式*/
	$item_collect_status = array(
		'class' => 'down_list',
		'show' => 'collect_status_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_videos(\'#search_list_vk\')'
	);
	
	$trans_status_default = -1;
	
	/*新增集合的类型控件样式*/
	$item_collect_leixing = array(
		'class' => 'down_list',
		'show' => 'collect_leixing_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_videos(\'#search_list_vk\')'
	);
	
	$leixing_default = -1;
	$collect_vod_leixing[$leixing_default] = '全部类型';
	foreach($vod_allleixing[0] as $k =>$v)
	{
		$collect_vod_leixing[$v['id']] = $v['name'];
	}
	
	$collect_default = -1;
	$collect_vod_sorts[$collect_default] = '全部分类';
	foreach($vod_sort[0] as $k =>$v)
	{
		$collect_vod_sorts[$v['id']] = $v['name'];
	}

	$_configs['video_upload_status'][-1] = '全部状态';
	
{/code}

{css:vod_style}
{js:jscroll}
{js:vod_select_videos}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
  $(function(){
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
			hg_search_videos('#search_list_vk');
		});
  }
	
</script>
<!-- 新增集合面板 开始-->
  <div id="add_collects"  class="single_upload"  style="left:0;height:480px;min-height:483px;border: 2px solid #77B7F8;"  onmouseover="hg_mouseOverTpl();" onmouseout="hg_mouseOutTpl();" >
  	<div id="add_collect_form" class="add_collect_form">
	 <div id="hg_select_all" style="display:none">
			<!-- 视频选择区域开始 -->
			<div  class="select_vod clear"  id="collect_info_content">
					<div class="jh_vod" style="width:423px">
						<!-- 搜索部分开始 -->
						<div id="search_condition" class="search_condition_all info">
							 <div  class="search_l">{template:form/search_source,sea_add_leixing_id,$leixing_default,$collect_vod_leixing,$item_collect_leixing}</div>
							 <div class="search_l" id="sort_select"></div>
							 <div  class="search_l">{template:form/search_source,collect_trans_status,$trans_status_default,$_configs['video_upload_status'],$item_collect_status}</div>
							 
							 <div class="right_2" style="width:148px">
								  <div class="button_search">
									<input type="button" value="" name="hg_search_videos"   onclick="hg_search_videos('#search_list_vk');"      style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
								  </div>
								  {template:form/search_input,vk,$_INPUT['vk']}                        
							 </div>
						</div>
						<!-- 搜索部分结束-->
						<div class="clear"  id="video_content"></div>
					</div>
					
					<div class="clear jh_vod"  id="selected_videos" style="margin-left:5px;width:152px">
					   <div id="selected_videos_ul" class="ul img"></div>
					</div>
			</div>
			<input type="button" id="close_tpl"   value="关闭"  class="button_2"   onclick="hg_hide_tpl();"  />
	 </div>
   </div>
  </div>
  <!-- 新增集合面板 开始结束-->