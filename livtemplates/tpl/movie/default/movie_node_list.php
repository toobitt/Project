{template:head}
{code}
$list = $movie_node_list;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

{/code}
<!-- 选择控件的数据设置 -->
{code}
	
	/*集合页面的分类控件样式*/
	$item_sort_leixing = array(
		'class' => 'transcoding down_list',
		'show' => 'sort_leixing_show',
		'width' => 100,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	/*新增分类面板里的类型选择空间的样式*/
	$item_sort_addleixing = array(
		'class' => 'transcoding down_list',
		'show' => 'addleixing_show',
		'width' => 100,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	$leixing_default = intval($_INPUT['fid']);
	
	//$vod_leixing = $_configs['video_upload_type'];
	//print_r($vod_sort_node);
	
	if($vod_sort_node)
	{
		foreach($vod_sort_node as $v)
		{
			$vod_leixing[$v['id']] = $v['name'];
		}
	}
	$vod_leixing[0] = '全部类型';
	$attr_date = array(
		'class' => 'colonm down_list data_time',
		'show' => 'colonm_show',
		'width' => 104,
		'state' => 1,
	);
	
{/code}

{css:vod_style}
{css:mark_style}
{css:common/common_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:vod_sort}
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
		tablesort('vod_sort_form_list','movie_node','order_id');
		$("#vod_sort_form_list").sortable('disable');

		$('#sort_leixing_show li a').click(function(){
			$('#searchform').submit();
		});
   });   

</script>
<style>
.common-list-item{width:60px;}
.paixu{width:20px;}
.ren{width:120px;}
.sort-edit, .sort-delete{width:40px;}
.sort-edit em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -60px -24px;}
.sort-delete em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -64px -118px;}
.common-list-biaoti span{font-size:14px;}

.ren .time{color:#888;font-size:10px;-webkit-text-size-adjust:none;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6"  onclick="hg_showAddSort();" ><strong>新增类别</strong></span>
	</div>
	<div class="content clear">
		<div class="f">
			<div class="right v_list_show">
				<div class="search_a" id="info_list_search">
					<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
	                    	 <!-- 
							{template:form/search_source,fid,$leixing_default,$vod_leixing,$item_sort_leixing}
							 -->
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
				 <form method="post" action="" name="vod_sort_listform">
					<ul class="common-list">
						<li class="common-list-head clear">
							<div class="common-list-left">
								<div class="common-list-item paixu"><a class="common-list-paixu" style="cursor:pointer;" onclick="hg_switch_order('vod_sort_form_list');" title="排序模式切换/ALT+R"></a></div>
								<div class="common-list-item sort-name">类型名</div>
							</div>
							<div class="common-list-right">
							    <div class="common-list-item sort-edit">编辑</div>
								<div class="common-list-item sort-delete">删除</div>
								<div class="common-list-item vod-num">视频个数</div>
								<div class="common-list-item set-num">集合个数</div>
								<div class="common-list-item ren">添加人/创建时间</div>
							</div>
						</li>
					</ul>
					<ul class="common-list" id="vod_sort_form_list">
					{foreach $list as $k => $v}
						{template:unit/nodelist}
					{/foreach}
					</ul>
					<div class="bottom clear">
		            	<div class="left">
	                   		<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
					        <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'sort_id', '', 'ajax');"    name="batdelete">删除</a>
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
	<!-- 新增分类面板 开始-->
	<div id="add_sorts"  class="single_upload" style="z-index:900">
		<h2><span class="b" onclick="hg_closeSortTpl();"></span><span id="sort_title">新增分类</span></h2>
		<div id="add_sort_tpl" class="add_collect_form">
		</div>
	</div>
	<!-- 新增分类面板结束-->
</body>
{template:foot}