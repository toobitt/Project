{template:head}
{code}
$list = $vod_collect_list[0];

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['collect_sort_id']))
{
    $_INPUT['collect_sort_id'] = -1;
}
$list = $list['collect_info'];
$ul_class_name = 'vod-collect-list';
{/code}
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
	$collect_vod_sorts[$collect_default] = '全部分类';
	foreach($vod_sort[0] as $k =>$v)
	{
		$collect_vod_sorts[$v['id']] = $v['name'];
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
{js:vod_collect}
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
	   
		/*拖动排序部分开始*/
		tablesort('collect_list','vod_collect','collect_order_id');
		$("#collect_list").sortable('disable');
		/*拖动排序部分结束*/
		
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

		$('#collect_sort_show li a').click(function(){
			$('#searchform').submit();
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
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<span type="button" class="button_6"  onclick="hg_showAddCollect();" ><strong>新增集合</strong></span>
</div>
<div class="content clear">
	<div class="f">
<!--视频发布模板占位符-->
			<span class="vod_fb" id="vod_fb"></span>
			<div id="vodpub" class="vodpub lightbox">
				<div class="lightbox_top">
					<span class="lightbox_top_left"></span>
					<span class="lightbox_top_right"></span>
					<span class="lightbox_top_middle"></span>
				</div>
				<div class="lightbox_middle">
					<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
					<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
					
					</div>
				</div>
				<div class="lightbox_bottom">
					<span class="lightbox_bottom_left"></span>
					<span class="lightbox_bottom_right"></span>
					<span class="lightbox_bottom_middle"></span>
				</div>				
			</div>
			<!--//视频发布>
 		 <!-- 新增集合面板 开始-->
 		 <div id="add_collects"  class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeCollectTpl();"></span><span id="collect_title">新增集合</span></h2>
 		 	<div id="add_collect_form" class="add_collect_form">
 		 	  <div class="collect_form_top info clear">
				<input type="button"  style="cursor:pointer;margin:0;"  class="select_btn"   value="选择视频" onclick="hg_getManyVideos(0);" />
			 </div>
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
							
						   
							<div class="clear jh_vod"  id="selected_videos" style="margin-left:5px;width:152px" >
							   <div id="selected_videos_ul" class="ul img"></div>
							   <div class="page" style="text-align:left"><input type="button"  class="button_2"   value="清除"  onclick="hg_goBackAllVideos();" /></div>
							</div>
					</div>
					<!-- 视频选择区域结束 -->
					
			</div>
			<div class="collect_form_top clear info">
				<div class="title" style="clear:both;display: block;width: 100%;margin-bottom: 10px;color: #777;">集合名称：</div>
				<div class="input" style="width:490px;float:left;margin-right:5px;">
					<span class="input_left"></span>
					<span class="input_right"></span>
					<span class="input_middle">
						<input style="float:left;margin:0 0 0 5px;width:475px;"  type="text"  name="add_collect_name"  id="add_collect_name" />
					</span>
				</div>
				{template:form/search_source,add_collect_id,$collect_default,$collect_vod_sorts,$item_collect_addsort}
			</div>
			 <!-- 操作部分开始  -->
             <div  style="height:30px;">
			 	 <input type="button"  style="cursor:pointer;font-weight:bold;display:none;"  class="button_6_14"   value="创建"   id="create_button"     onclick="hg_createThecollect();"  />
			 	 <input type="button"  style="float:right;cursor:pointer;display:none;"  class="button_6_14"   value="更新"   id="edit_button"       onclick="hg_editThecollect();"  />
			 </div>
             <!-- 操作部分结束  -->

		   </div>
 		 </div>
 		 <!-- 新增集合面板 开始结束-->
 		 
 		 
 		 
 		 
		<div class="right v_list_show">
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{template:form/search_source,collect_sort_id,$_INPUT['collect_sort_id'],$collect_vod_sorts,$item_collect_sort}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
                    </div>
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
				</form>
			</div>
		
		{if !$list}			
			<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
			<script>hg_error_html('p',1);</script>	
		{else}
			{css:common/common_list}
			{js:domcached/jquery.json-2.2.min}
			{js:domcached/domcached-0.1-jquery}
			{js:common/common_list}
			<script>
			jQuery(function($) {
				$.commonListCache('{$ul_class_name}');	
			});
			</script>
			<style>
			.common-list-item{width:80px;}
			.common-list-biaoti .common-list-item{width:auto;}
			.paixu{width:20px;}
			.thumb{width:50px;}
			.mark{width:100px;}
			.movie-time{font-size:10px;color: #888888;}
			.edit em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -60px -24px;}
			.delete em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -64px -118px;}
			.name{display:block;}
			.time{color:#888;font-size:10px;-webkit-text-size-adjust:none;}
			</style>
			<form method="post" action="" name="listform">
				{code}
				$columData = array(
					'fabu' => '发布',
					'edit' => '编辑',
					'delete' => '删除',
					'sort' => '分类',
					'ren' => '添加人/时间'
				);
				$headData = array(
					'left' => array(
						'paixu' => '',
					),
					'right' => $columData,
					'biaoti' => array(
						'biaoti' => '标题'
					)
				);
				{/code}
				<div style="position: relative;">
					<div id="open-close-box">
						<span></span>
						<div class="open-close-title">显示/关闭</div>
						<ul>
						{foreach $columData as $kk => $vv}
							<li which="{$kk}"><label class="overflow"><input type="checkbox" checked />{$vv}</label></li>
						{/foreach}
						</ul>
					</div>
				</div>
				<ul class="common-list">
					<li class="common-list-head clear">
					{foreach array('left', 'right', 'biaoti') as $v}
						<div class="common-list-{$v}">
						{foreach $headData[$v] as $k => $v}
							<div class="common-list-item {$k}">{$v}</div>
						{/foreach}
						</div>
					{/foreach}
					</li>
				</ul>
				<ul class="common-list {$$ul_class_name}" id="vodlist">
				{foreach $list as $k => $v}
					{template:unit/vod_collectlist}
				{/foreach}
				</ul>
				<div class="bottom clear">
					<div class="left">
						<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
						<a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
					</div>
					{$pagelink}
				</div>
			</form>
		{/if}
		</div>
	</div>
</div>

<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}