{template:head}
{code}
$list = $movie_list['list'];
$key_value = $key_value[0];
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 4;
}
if(!isset($_INPUT['area_id']))
{
    $_INPUT['area_id'] = 0;
}
if(!isset($_INPUT['actor']))
{
    $_INPUT['actor'] = 0;
}
if(!isset($_INPUT['movie_sort_id']))
{
    $_INPUT['movie_sort_id'] = 0;
}
if(isset($_INPUT['id']))
{
   $id = $_INPUT['id'];
}
else
{
   $id = '';
}
{/code}

{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:column_node}
{css:movie_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
<script>
gBatchAction['delete'] = "./run.php?mid=246&a=delete";
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	    <span type="button" class="button_6" >
	    	<a href="./run.php?mid={$_INPUT['mid']}&a=detail&infrm=1&nav=1">
	    		<strong>新增视频</strong>
	    	</a>
	    </span>
	</div>
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
	<!--新增视频-->
	<div id="add_videos"  class="single_upload">
		<h2><span class="b" onclick="hg_closeButtonX();"></span>新增视频</h2>
		<h3 id="single_select" class="select_item" onclick="hg_add_single_video(this)">上传单个文件<span class="a"></span></h3>
		<h3 id="more_select" class="select_item" onclick="hg_add_more_videos(this);">上传多个文件<span class="b"></span></h3>
		<h3 id="live_select" class="select_item" onclick="hg_load_timeShift(this)">从直播时移获取<span class="c"></span></h3>		
		<div id="hg_single_select" class="upload_form"></div>
		<div id="hg_more_select" class="upload_form"></div>
		<div id="hg_live_select" class="upload_form"></div>
	</div>
	<!--新增视频结束-->
	<!--添加视频至集合开始-->
	<div id="add_to_collect"  class="single_upload">
		<h2><span class="b" onclick="hg_closeAddToCollectTpl();"></span>添加视频至集合</h2>
		<div id="add_to_collect_form" class="upload_form" style="background:none;"></div>
	 </div>
	 <div class="content clear">
 		<div class="f">
 		
 		  <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							$nodes = $key_value['nodes'];
							$node_default = 0;
							$nodes[$node_default] = '全部分类';
							$nodes_config = array(
								'class' => 'transcoding down_list',
								'show'  => 'nodes_show',
								'width' => 45,
								'state' => 0,
							);
							
							$areas = $key_value['areas'];
							$area_default = 0;
							$areas[$area_default] = '全部地区';
							$areas_config = array(
								'class' => 'transcoding down_list',
								'show'  => 'areas_show',
								'width' => 45,/*列表宽度*/
								'state' => 0,
							);
							
							$persons = $key_value['persons'];
							$person_default = 0;
							$persons[$person_default] = '全部演员';
							$persons_config = array(
								'class' => 'transcoding down_list',
								'show'  => 'persons_show',
								'width' => 45,
								'state' => 0,
							);		
						{/code}
						
						{template:form/search_source,area_id,$_INPUT['area_id'],$areas,$areas_config}
						{template:form/search_source,movie_sort_id,$_INPUT['movie_sort_id'],$nodes,$nodes_config}
						{template:form/search_source,actor,$_INPUT['actor'],$persons,$persons_config}
						
						<input type="hidden" name="a" value="show"/>
						<input type="hidden" name="mid" value="{$_INPUT['mid']}"/>
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}"/>
						<input type="hidden" name="_id" value="{$_INPUT['_id']}"/>
						<input type="hidden" name="_type" value="{$_INPUT['_type']}"/>
						
                    </div>
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,keyword,$_INPUT['keyword']}                        
                    </div>
                    </form>
                </div>
            </div>

			<form method="post" action="" name="listform">		
				<ul class="common-list">
					<li class="common-list-head clear">
						{code}
						$myLeft = array(
							'paixu' => '',
							'thumb' => '缩略图'
						);
						$myRight = array(
							'sort' => '分类',
							'pubtime' => '上映时间',
							'duration' => '时长',
							'area' => '地区',
							'director' => '导演',
							'actor' => '主演'
						);
						{/code}
						<div class="common-list-left">
						{foreach $myLeft as $k => $v}
							<div class="common-list-item {$k}">{$v}</div>
						{/foreach}
						</div>
						<div class="common-list-right">
						{foreach $myRight as $k => $v}
							<div class="common-list-item {$k}">{$v}</div>
						{/foreach}
						</div>
						<div class="common-list-biaoti">
							<div class="common-list-item">名称</div>
						</div>
					</li>
				</ul>
				<ul class="common-list list" id="vodlist">
				{if $list}
				{foreach $list as $k => $v}
					{template:unit/movie_row}  
				{/foreach}
				{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html('#vodlist',1);</script>
				{/if}
				</ul>
				 <div class="bottom clear">
	               <div class="left">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">删除</a>
				   </div>
	               {$pagelink}
	            </div>	
			</form>
			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
			</div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
	<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		</div>
	</div>