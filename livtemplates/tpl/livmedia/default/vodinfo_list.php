{template:head}
{code}
$list = $vodinfo_list;
//hg_pre($list);
//hg_pre($vod_config);
$image_resource = RESOURCE_URL;
$vodPlayerSwf = RESOURCE_URL.'swf/';

if(!isset($_INPUT['trans_status']))
{
    $_INPUT['trans_status'] = -2;
}

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
if(isset($_INPUT['id']))
{
   $id = $_INPUT['id'];
}
else
{
   $id = '';
}
$hg_vod_list_mode = hg_get_cookie('hg_vod_list_mode');
if ($hg_vod_list_mode)
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list_img';
}
else
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list';
}
{/code}

{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:2013/button}
{js:vod_opration}
{js:vod_upload_pic_handler}
{js:vod_video_edit}
{js:vod_add_to_collect}
{js:technical_review}
{js:column_node}
{css:column_node}
{js:flat_pop/base_pop}
{js:flat_pop/link_vodupload}
{js:tree/animate}
{js:2013/tip}
{js:2013/ajaxload_new}


{code}
$status_key = 'status_display';
$audit_value = 2;
$back_value = 3;
$back_label = '被打回';
$attrs_for_edit = array(
	'frame_rate', 
	'bitrate',
	'download',
	'retranscode_url',
	'vod_leixing', 
	'aspect',
	'format_duration',
	'video_duration',
	'video_totalsize',
	'video_resolution',
	'aspect',
	'audio',
	'sampling_rate',
	'video_audio_channels',
	'video',
	'isfile_name',
	'is_allow',
	'pub_url',
	'is_do_morebit',
	'is_morebitrate_ok',
	'is_forcecode_ok',
	'is_forcecode',
	'app_uniqueid',
	'object_id',
	'video_m3u8',
	'catalog',
	'is_link',
	'swf',
	'status',
);
//print_r($list);
{/code}
<script>
{code}
$arr['file_title'] = '视频批量上传';
$arr['upload_url'] = $_configs['App_mediaserver']['protocol'] . $_configs['App_mediaserver']['host'] . $port . '/' . $_configs['App_mediaserver']['dir'] . 'admin/create.php';
$arr['file_types'] = $_configs['flash_video_type'];
$arr['description'] = 'Videos Upload';
$arr['flagId'] = 'moreFlag';
$arr['button_left'] = '900px';
$arr['button_top'] = '125px';
$arr['padding_left'] = '5';
$arr['padding_top'] = '2';
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['token'] = $_user['token'];
$arr['mid'] = $_INPUT['mid'];
$arr['upload_type'] = 0;
$arr['file_size_limit'] = $transcode_server[0]['max_size']*1024*1024;/*视频限制大小*/
$params = json_encode($arr);
{/code}
var livUploder_params = {$params};
</script>
{if $_configs['cloud_video']['open'] == 2 || $_configs['cloud_video']['open'] ==4}
<script src="{$SCRIPT_URL}vod/init_uploader.js"></script>
{/if}
{template:list/common_list}

<script type="text/javascript">
	function hg_t_show(obj)
	{
		if($('#text_'+obj).text()=='转码中')
		{
			$('#hg_t_'+obj).css({'display':'block',});
		}
		
	}
	function hg_t_none(obj)
	{
		$('#hg_t_'+obj).css({'display':'none',})
	}

	function hg_del_keywords()
	{
		var value = $('#search_list').val();
		if(value == '关键字')
		{
			$('#search_list').val('');
		}

		return true;
	}
	function changeStatusLabel(status, model)
	{
		 var color;
		 var status_text = "";
		 var obj = model;
		if(status == 2)
	       {
	    	   status_text = '已审核';
	    	   color = '{$list_setting['status_color'][1]}';
	       }
	       else if(status == 3)
	       {
	    	   status_text = '被打回';
	    	   color = '{$list_setting['status_color'][2]}';    
	       }
		 $('#text_'+model.id).text(status_text).css('color', color).attr('_state', status);
		 if(status == 2)
  	   {
      	   if($('#img_sj_'+obj.id).length)
      	   {
      		   $('#img_sj_'+obj.id).removeClass('b');
             }

      	   if($('#img_lm_'+obj.id).length)
      	   {
      		   $('#img_lm_'+obj.id).removeClass('b');
             }
  	   }
  	   else
  	   {
      	   if($('#img_sj_'+obj.id).length)
      	   {
      		   $('#img_sj_'+obj.id).addClass('b');
             }

      	   if($('#img_lm_'+obj.id).length)
      	   {
      		   $('#img_lm_'+obj.id).addClass('b');
             }
         }
	}
	function hg_change_status(obj)
	{
	   var color;
	   var obj = obj[0];
	   
       

       for(var i = 0;i<obj.id.length;i++)
       {
    	   recordCollection.get(obj.id[i]).set('state', obj.status);
       }
	}

    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";
   
function header()
{
	var node_type = $('#node_type').val();
	var url="run.php?a=change_node&node_type="+node_type+"&mid={$_INPUT['mid']}";
	hg_request_to(url);
}

function hg_pickup_callback(obj)
{
	var data = eval('('+obj+')');
	$('a[name="pickupvideo"]').myTip( {
		string : '视频已成功提取在 : ' + data.path + ' 目录下',
		delay : 5000,
		width : 450
	} );
}

/*function hg_showmainwin(html, selfurl, nodetype)
{
	if(parent)
	{
		if(parseInt(nodetype))
		{
			parent.$('#append_menu').hide();
		}
		else
		{
			parent.$('#append_menu').show();
		}
		parent.$('#hg_node_node').html(html);
		parent.$('#nodeFrame').attr('src',selfurl);
	}
	else
	{
		if(parseInt(nodetype))
		{
			$('#append_menu').hide();
		}
		else
		{
			$('#append_menu').show();
		}
		$('#append_menu').hide();
		$('#hg_node_node').html(html);
		$('#nodeFrame').attr('src',selfurl);
		$('#hg_node_node').html(html);
	}
}*/
</script>

<style type="text/css">
.vod-quanzhong{ width:60px; }
#vedio-player{position:absolute;right:380px;top:-120%;z-index:1;transition:top .3s;-webkit-transition:top .3s;width:346px;height:264px;background:#000;}
.show-transcode-box{cursor:pointer;}
.transcode-box{display:none;position:absolute;z-index:10;width:200px;padding:10px 20px;background:#4c4c4c;top:0;}
.transcode-box .transcode-info{color:#eee;padding:10px 0;}
.transcode-box p{line-height:2;}
.transcode-box .title{color:#eee;width:60px;}
.transcode-box .handler-btns{border-top:1px solid #555;padding:10px 0;}
.transcode-box .handler-btn{display:inline-block;background: #414141;height: 28px;line-height: 28px;color: #fff;padding:0 15px;}
.transcode-box .handler-btn:hover{background-color: #393738;}
.transcode-box .close-btn{position:absolute;width: 22px;height: 28px;top: 0;right: -23px;border-left: 1px solid #3e3e3e;box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.6);cursor: pointer;background: url("{$RESOURCE_URL}common/icon_close.png") no-repeat center center #4c4c4c;}
.force_recodec{height: 20px;line-height: 20px;padding: 0px 5px;background: #5C99CF;display: block;color: #fff;border-radius: 2px;position: absolute;left: 160px;bottom: 25px;}
</style>

<script>
var client_id = '{$_configs['cloud_video']['client_id']}';
</script>
{css:video_yun}
{js:jqueryfn/jqueryfn_custom/uploadify/jquery.uploadify}
{if $_configs['cloud_video']['open'] == 1 || $_configs['cloud_video']['open'] ==4}
<script src="{$SCRIPT_URL}livmedia/upYun.js"></script>
{/if}
<div class="" {if $_INPUT['infrm']}style="display:none"{/if}>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	{code}
		$is_open = $_configs['cloud_video']['open'];
		switch($is_open)
		{
			case 1:
			{/code}
			<div class="add-button mr10 add-yunvideo-btn">新增云视频</div>
			{code}
			if($_configs['is_cloud'])
			{
				{/code}
				<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="{$_INPUT['mid']}">{$_configs['is_cloud']}</a>
				{code}
			}
			break;
			case 2:
			{/code}
			<a class="add-button mr10 flash-position">新增视频</a>
			{code}
			if($_configs['is_cloud'])
			{
				{/code}
				<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="{$_INPUT['mid']}">{$_configs['is_cloud']}</a>
				{code}
			}
			break;
			case 3:
			{/code}
			<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="{$_INPUT['mid']}">{$_configs['is_cloud']}</a>
			{code}
			break;
			case 4:
			{/code}
			<div class="add-button mr10 add-yunvideo-btn">新增云视频</div>
			<a class="add-button mr10 flash-position">新增视频</a>
			<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="{$_INPUT['mid']}">{$_configs['is_cloud']}</a>
			<a class="add-button mr10 pop-linkupload">{$_configs['is_link']}</a>
			{code}
			break;
			default:
			break;
		}
	{/code}
</div>

<div class="search_a" id="info_list_search">
				  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1 select-search">
						{code}
							$attr_type = array(
								'class' => 'transcoding down_list',
								'show' => 'node_type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'header()',
							);

							$attr_source = array(
								'class' => 'transcoding colonm down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_weight = array(
								'class'  => 'colonm down_list data_time',
								'show'   => 'weight_show',
								'width'  => 104 /*列表宽度*/,
							);							
							$video_upload_status[-2] = '全部状态';
							foreach ($_configs['video_upload_status'] as $index => $item) {
								$video_upload_status[$index] =  $item;
							}
							/*$type_search = array(0=>'媒资分类', 1=>'网站栏目', 2=>'手机栏目');*/
							$_type_search = array(0=>"媒资分类");
							foreach($type_search as $k=>$v)
							{
								$_type_search[$k] = $v .'栏目';
							}
							$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
						{/code}
						{code}
							$column_default = $_INPUT['pub_column_id'] ? $_INPUT['pub_column_id'] : 0;
							if( $column_default ==0 ) {
								$column_list = 	array(
									0 => '栏目'
								);
							}else{
								$column_list = split(',', $_INPUT['pub_column_name'] );
							}
							$attr_column = array(
								'class' => 'pub_column_search down_list',
								'show' => 'pub_column_show',
								'select_column' => $_INPUT['pub_column_name'],
								'width' => 90,/*列表宽度*/
								'state' => 4 /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*,4--栏目搜索*/
							);
						{/code}
						<!--{template:form/search_source,node_type,$default_node_type,$_type_search,$attr_type}-->
						{template:form/search_source,trans_status,$_INPUT['trans_status'],$video_upload_status,$attr_source}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						{template:form/search_source,pub_column_id,$column_default,$column_list,$attr_column}
						{template:form/search_weight,weight_search,$_INPUT['weight_search'],$_configs['weight_search'],$attr_weight}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
                    </div>
                    <div class="right_2 text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    <div class="custom-search">
						{code}
							$attr_creater = array(
								'class' => 'custom-item',
								'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
								'place' =>'添加人'
							);
						{/code}
						{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
					</div>
                    </form>
</div>
</div>

 {template:list/ajax_pub}

<div class="common-list-content" style="min-height:auto;min-width:auto;">
		
		  
		   <div id="technical_review"  class="single_upload" style='height:1300px;'>
				<h2><span class="b" onclick="hg_closeTechnicalReviewTpl();"></span>技审信息</h2>
				<div id="technical_info" class="upload_form"></div>
		   </div>
			<!--新增视频-->
		  <div id="add_videos"  class="single_upload">
				<h2><span class="b" onclick="hg_closeButtonX();"></span>新增视频</h2>
				<h3 id="single_select" class="select_item" onclick="hg_add_single_video(this)">上传单个文件<span class="a"></span></h3>
				<h3 id="more_select" class="select_item" onclick="hg_add_more_videos(this);">上传多个文件<span class="b"></span></h3>
				<!-- 
				<h3 id="live_select" class="select_item" onclick="hg_load_timeShift(this)" {if $_configs['App_live']}style="display:block;"{else}style="display:none;"{/if}>从直播时移获取<span class="c"></span></h3>
				 -->
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
			<!--添加视频至集合结束-->
		
                
			{if !$list}
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('p',1);</script>
			{else}
                {css:vod_list}
                {code}
                $columnData = array(
					array(
						'class' => 'vod-fabu',
						'innerHtml' => '发布至'
					),
					
					array(
						'class' => 'vod-maliu',
						'innerHtml' => '码流'
					),
					array(
						'class' => 'vod-fenlei',
						'innerHtml' => '分类'
					),
					array(
						'class' => 'vod-quanzhong',
						'innerHtml' => '权重'
					),
					array(
						'class' => 'vod-zhuangtai',
						'innerHtml' => '状态'
					),
					array(
						'class' => 'vod-ren',
						'innerHtml' => '添加人/时间'
					)
				);
                {/code}
               <!-- {template:list/list_column} --> 
                <form method="post" action="" name="listform" style="display:block;position:relative;">
                    <ul class="vod-list common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
                                   <a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a>
                                </div>
                                <div class="common-list-item wd60">缩略图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="vod-fabu common-list-item open-close common-list-pub-overflow" which="vod-fabu">发布至</div>
                                <div class="vod-maliu common-list-item open-close wd70" which="vod-maliu">码流</div>
                                <div class="vod-fenlei common-list-item open-close wd80" which="vod-fenlei">分类</div>
                                <div class="vod-quanzhong common-list-item open-close wd60" which="vod-quanzhong">权重</div>
                                <div class="vod-zhuangtai common-list-item open-close wd60" which="vod-zhuangtai">状态</div>
                                <div class="vod-zhuangtai common-list-item open-close wd60" which="vod-tuisong">推送</div>
                                <div class="vod-ren common-list-item open-close wd100" which="vod-ren">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">标题</div>
					        </div>
                        </li>
                    </ul>
                    <script>

                        function hg_get_ids()
                        {
                            var inputs = document.getElementsByTagName("input");
                            var checkboxArray = [];
                            for(var i=0;i<inputs.length;i++){
                                var obj = inputs[i];
                                if(obj.type=='checkbox' && obj.checked == true){
                                    checkboxArray.push(obj.value);
                                }
                            }
                            return checkboxArray;
                        }

                        function hg_outpush_vod()
                        {
                            var ids = hg_get_ids();
                            $(function() {
                                $.post(
                                    "./run.php?mid=2890&a=create",
                                    {
                                        ids: ids,
                                        pushType:'vod'
                                    },
                                    function (data) {
                                        console.log(data);
                                    }
                                )
                            })
                        }
                    </script>
                    <ul class="vod-list common-list public-list hg_sortable_list" data-order_name="video_order_id" data-table_name="vodinfo" id="vodlist">
					{foreach $list as $k => $v}
						{template:unit/vod_list}
					{/foreach}             
                    </ul>
                    <ul class="common-list public-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
								<!--<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 0, 'id', '', 'ajax');"    name="batmove" >移动</a>-->
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
								<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
								<!-- <a style="cursor:pointer;"  onclick="return hg_moreVideosToCollect(this);"   name="batadd_to_collect">添加到集合</a> -->
								{if $_configs['App_video_split']}
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置拆条', 1, 'id', '&is_allow=0', 'ajax');"   name="allow_mark">允许拆条</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置拆条', 1, 'id', '&is_allow=1', 'ajax');"   name="no_mark">不允许拆条</a>
								{/if}
								{if $_configs['App_special']}
								<a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
								{/if}
								<a style="cursor:pointer;"  onclick="return hg_extractVod(this);"   name="pickupvideo">批量提取视频</a>
                                {if $v['outpush'] == 1}
                                <a style="cursor:pointer;"   onclick="return hg_ajax_batchpost(this,'batchpush'	,'推送',1,'id','','ajax');" name="outpush">推送</a>
                                {/if}
                       		</div>
                       		{$pagelink}
                    	</li>
                    </ul>
                    <div class="edit_show">
						<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
						<div id="edit_show"></div>
					</div>
    			</form>
				
			{/if}
           
        
	</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
   <div id="add_share" style="box-shadow:0 0 3px #555;padding:0 12px 12px 12px;background:#f0f0f0;display:none;position:fixed;top:50px;left:150px;z-index:100000;border:1px solid #f5f5f5;border-radius:5px;width:500px;min-height:300px;overflow:auto;"></div> 

{template:unit/fast_set}
{template:unit/record_edit}

{code}
foreach (array('start_time', 'end_time', 'date_search', 'start_weight', 'end_weight', 'k', 'trans_status','user_name','pub_column_id') as $v) 
{
	$conditions[$v] = $_INPUT[$v];
}
$conditions['vod_leixing'] = $_INPUT['_type'];
$conditions['vod_sort_id'] = $_INPUT['_id'];
$conditions['k'] = $conditions['k'] ? $conditions['k'] : $_INPUT['key'];
{/code}
<script type="text/x-jquery-tmpl" id="transcode-box-tpl">
	<div class="transcode-info">
		{{if ajaxReturn == 'success'}}
			{{if status == 'running'}}
			<p><span class="title">状态：</span>正在转码中</p>
			<p><span class="title">已完成：</span>{{= transcode_percent}}%</p>
			<p><span class="title">剩余时间：</span>约{{= Math.floor(parseInt(transcode_lefttime)/60)}}分钟</p>
			{{/if}}
			{{if status == 'waiting'}}
			<p><span class="title">状态：</span>等待转码中</p>
			<p><span class="title">任务权重：</span>{{= waiting_task_weight}}</p>
			{{/if}}
			{{if status == 'callback_failed'}}
			<p><span class="title">状态：</span>转码失败</p>
			{{/if}}
		{{else}}
			任务id不存在，当前没有转码任务
		{{/if}}
	</div>
	{{if status == 'callback_failed'}}
	<div class="handler-btns">
		<a class="handler-btn re-transcode" _id="{{= id}}">重新转码</a>
	</div>
	{{/if}}
	<a class="close-btn"></a>
</script>


<!-- 重新转码 -->
<script type="text/javascript">
$(function(){
	$('#record-edit').on('click' , '.retranscode' , function(event){
		var self = $(event.currentTarget);
		if( self.data('ajax') ){
			return false;
		}
		self.text('转码中...').data('ajax',true);
		$.ajax({
			url : self.attr('href'),
			dataType : 'jsonp',
			success : function(json){
				if(json['ErrorText']){
					var tip = json.ErrorText;
					_tip(self , tip);
					self.data('ajax',false);
					self.text('重新转码');
					return false;
				}
			}
		})
		return false;
	})

	function _tip(self , tip){
		self.myTip({
			string : tip,
			delay: 1000,
			width : 150,
			dtop : 0,
			dleft : 80,
		});
	}
})
</script>

<script type="text/javascript">
$(function(){
	(function(){
		function Transcode(el){
			this.init();
			var _this = this;
			this.el = $('.transcode-box');
			this.tpl = $('#transcode-box-tpl');
			this.el.on('click','.close-btn',function(){
				_this.hide();
			});
			this.el.on('click','.re-transcode',function(){
				// console.log($(this).attr('_id'));
			});
		};
		$.extend(Transcode.prototype,{
			init : function(){
				$('<div class="transcode-box"></div>').appendTo('body');
			},
			show : function(self){
				var state = self.attr('_state');
				var pp = self.offset();
				var sHeight = self.outerHeight(),
					sWidth = self.outerWidth();
	            var	eHeight = this.el.outerHeight(),
	            	eWidth = this.el.outerWidth();
				var left = pp.left - eWidth - 30,
	            	top = pp.top;
            	var dHeight = $(document).outerHeight();
            	this.el.find('.close-btn').css({top:0,bottom:'auto'});
				if(top + eHeight > dHeight){
					this.el.find('.close-btn').css({bottom:0,top:'auto'});
	                top = pp.top + sHeight - eHeight;
				}
	            this.el.css({
	                left : left + 'px',
	                top : top + 'px'
	            });
				this.el.slideDown(300);
			},
			hide : function(){
				this.el.slideUp(300);
			},
			refresh : function( self,json ){
				this.tpl.tmpl(json).appendTo(this.el.empty());
				this.show(self);
			}
		});
		
		$.hg_transcode = new Transcode();
		$('.common-list').on('click','.show-transcode-box',function(){
			var self = $(this);
			var type = '';
			if( self.attr('_state')== 0 ){
				type = 1;
			}else if( self.attr('_state')== -1 ){
				type = 0;
			}
			var url = './run.php?mid='+ gMid +'&a=get_video_status',
				data = {
					ajax : 1,
					id : self.closest('li').attr('_id'),
					type : type
				};
			$.globalAjax(self,function(){
				return $.getJSON(url,data,function(json){
					/*
					json = [{
						"return":"fail",
						"reason":"task not found",
						"id":"111"
					}];
					*/
					$.hg_transcode.el.hide();
					if( json['callback'] ){
						eval( json['callback'] );
						return;
					}
					if( json[0]['return'] ){
						json[0]['ajaxReturn'] = json[0]['return'];
						$.hg_transcode.refresh(self,json[0]);
					}else{
						//console.log('返回值为空');
					}
				});
			});
		});
	})($);
});
</script>
<script type="text/javascript">
var _type = {code}echo $_INPUT['_type'] ? $_INPUT['_type'] : -1;{/code};
var statusSettings = {
	1: ['待审核', "#8ea8c8"],
	2: ['已审核', "#17b202"],
	3: ['被打回', "#f8a6a6"]	
};
/*判断当前页面是不是第一页*/
function hg_checkFirstPage()
{
	if(!$('span[id^="pagelink_"]').length)
	{
		return true;
	}
	return ($('#pagelink_1').length && $('#pagelink_1').hasClass('page_cur'))?true:false;
}
 
/*获取当前页面最大id*/
function hg_get_maxid()
{
	var maxid = 0;
	$('#vodlist').children().each(function() {
		maxid = Math.max(maxid, $(this).attr('_id'));
	});
	return hg_checkFirstPage() ? maxid : -1;
}

/*获取当前页面转码中的id*/
function hg_get_trans_id()
{
	var trans_ids = new Array();
	 
	$('span[id^="text_"]', $('#vodlist')).each(function(){
		if($(this).text() == '转码中') {
			trans_ids.push( $(this).attr('_id') );
		}
	});
	return trans_ids.join() || 0;
}
/*判断当前页面有没有正在转码中,有的话,检测时间变为隔3秒检测一次,没有则变为5秒检测一次*/
function changeCheckTime()
{
	return gtransIds ? 3000 : 5000; 
}
/*请求视频转码的信息*/
function hg_getvideoinfo(maxid, trans_ids, conditions)
{
	var mpara = '', 
		transpara = '', 
		html = '';
	
	if (maxid && maxid != -1)
	{
		mpara = '&since_id=' + maxid;
	}
	transpara = '&transids=' + trans_ids;
	html = $.getScript('run.php?mid={$_INPUT['mid']}&a=getinfo&ajax=1' + mpara + '&' + $.param(conditions) + transpara);
}
/*获取视频转码的转码信息去，更新页面*/
function hg_panduan(obj)
{
	var obj = obj[0];
	

	/*增长列表*/
	$.each(obj.add_data || [], function (i, item) {
		gmaxId = Math.max(gmaxId, item.id);
		gtransIds += (',' + item.id); 
		if (item.id && !$("r_" + item.id).length) {
			hg_single_video({ "vodid": item.vodid, "id": item.id });
		}
	});
	/*对正在转码中的列表进行操作*/
	$.each(obj.status_data || [], function (i, item) {
		var id = item.id, percent = item.transcode_percent, status = item.status,
			text = $("#text_"+id),
			tool = $("#tool_"+id);
		
		if (!id) return;
		if ( status >= 1 && status <= 3 ) 
		{
			gtransIds = gtransIds.replace(',' + id, '').replace(id + ',', '');/*两次replace次序不要颠倒*/
			$('#hg_t_'+id).html('');
			text.html( statusSettings[status][0] ).css('color', statusSettings[status][1]).attr("_state", status); 
			tool
				.prev().addClass("common-switch-status")
				.parent().removeAttr("onmouseover").removeAttr("onmouseout")
        	  	.end().end().remove();
		} 
		else if (status == -1) 
		{
			gtransIds = gtransIds.replace(',' + id, '').replace(id + ',', '');
			text.html("转码失败").attr("_state", status);
			$('#hg_t_'+id).html('<a class="button_6" href="run.php?mid={$_INPUT['mid']}&id=' + id + '&a=retranscode" onclick="return hg_ajax_post(this, \'重新转码\', 0);" style="margin-left:24px;margin-top:7px;" >重新转码</a>');
		}
		else 
		{  
			$("#status_"+id).css("width", Math.ceil( tool.width() * percent / 100 ));
		} 
	});
	
	/*开始下一次请求*/
	setTimeout(function () {
		hg_getvideoinfo(gmaxId, gtransIds, cond);
	}, gcheckTime);
}

function changeSynletv_state(is_link, record) {
	var id = record.id,
		is_link = +is_link;
	is_link && $("#sync_letv" + id).text('已同步');
}

/*乐视TV同步后回调，改变这条记录的is_link*/
function hg_synletv_state(obj) {
	var obj = obj[0];
    hg_close_opration_info();
	if (obj.errmsg) {
		top.jAlert(obj.errmsg, '失败提醒');
		return;
	}
	$.each(obj, function (i, id) {
		recordCollection.get(id).set('is_link', 1);
	});
	$.sync_letv_progress.css(width,'100%');
}

/*乐视TV2M/s模拟显示同步进度*/
function hg_synletv_progress( target ){
	var SPEED = 2;	//模拟速度为2M/s
	var size = target.data('size'),
		reg_number_size = size.match(/^\d+(\.)?\d*/g);
		units = ['GB','MB','KB','Bytes'];
	$.sync_letv_progress_box = target.closest('#record-edit').find('.sync_letv_progress_box');
	$.sync_letv_progress = $.sync_letv_progress_box.find('.sync_letv_progress');
	if( $.isArray( reg_number_size ) && reg_number_size.length ){
		var number_size = reg_number_size[0];
		$.sync_letv_progress_box.show();
		/*以MB为单位计算视频数值大小*/
		$.each( units, function( key, value ){
			if( size.indexOf( value ) > -1 ){
				switch( value ){
					case 'GB' :
						number_size*=1024;
						break;
					case 'MB' : 
						number_size = number_size;
						break;
					case 'KB' : 
						number_size = number_size/1024;
						break;
					case 'Bytes' : 
						number_size = number_size/1024/1024;
						break;
					default : 
						number_size = number_size;
				}
				return false;
			}
		} );
		var need_time = ( number_size/SPEED)*1000;   //毫秒
		$.sync_letv_progress.animate( { width : '98%', },need_time );
	}
}

function hg_close_synletv_progress(){
	setTimeout( function(){
		$.sync_letv_progress_box.remove();
	}, 1000 );
}

var cond = {code}echo json_encode($conditions);{/code};
var gtransIds = hg_get_trans_id(); 
var gcheckTime = 5000;
var gmaxId = hg_get_maxid();

$(function ($) {
	hg_getvideoinfo(gmaxId, gtransIds, cond);
});

 /*求两个索引数组的差集*/
 function  array_diff(arr1,arr2)
 {
	 var arr3 = new Array();
	 for(var i=0; i < arr1.length; i++)
     {
		 var flag = true;
		 for(var j=0; j < arr2.length; j++)
	     {
			 if(arr1[i] == arr2[j])
			 {
				 flag = false;
		     }
		 }

		 if(flag)
		 {
			 arr3.push(arr1[i]);
		 }

	  }

	  return arr3;
 }

 /*时间格式化函数*/
 function  hg_TimeFormat(time)
 {
	 if(time < 60)
	 {
		 return time + '秒钟';
	 }
	 else if(time >= 60 && time < 3600)
	 {
		 return  Math.round(time/60) + '分钟';
	 }
	 else if(time >= 3600)
	 {
		 return Math.round(time/3600) + '小时';
	 }
		
 }



 function hg_control_status(obj,e)
 {
	 if(e)
	 {
		 $(obj).show();
	 }
	 else
	 {
		 $(obj).hide();
	 }
 }

 /*控制转码的状态*/
 function hg_controlTranscodeTask(id,type)
 {
	 var op = type?'pause':'resume';
	 var url = "run.php?mid="+gMid+"&a=control_transcode&type="+op+"&id="+id;
	 hg_ajax_post(url);
 }

 function hg_overControlTranscode(obj)
 {
	 // console.log(obj);
	 var obj = eval('('+obj+')');
	 if(obj.op == 'pause' && obj.return == 'success')
	 {
		 $("#text_"+obj.id).text("已暂停").addClass('show-transcode-box');
		 var html = "<input type='button'  value='恢复' class='button_6' style='margin-left:24px;margin-top:7px;' onclick='hg_controlTranscodeTask("+obj.id+",0);' />";
         $('#hg_t_'+obj.id).html(html);
	 }

	 if(obj.op == 'resume' && obj.return == 'success')
	 {
		 $("#text_"+obj.id).text("转码中");
   	  	 $("#tool_"+obj.id).show();
		 var html = "<input type='button'  value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;' onclick='hg_controlTranscodeTask("+obj.id+",1);' />";
         $('#hg_t_'+obj.id).html(html);
	 }
 }

function hg_extractVod( dom ){
	var isLink = true;
	var checked = $(dom).closest('form').find('input:checked:not([name="checkall"])');
	if( checked && checked.length ){
		checked.closest('li').each(function(){
			var	$this = $(this);
			if( $this.find('.common-list-biaoti').find('.link-upload').length ){
				isLink = false;
				return false;
			}
		});
	}
	if( isLink ){
		return hg_ajax_batchpost(dom, 'pickup_video', '批量提取视频', 1, 'id', '', 'ajax');
	}else{
		var msg = '批量提取视频中不能包含链接上传的视频';
		jAlert ? jAlert(msg, '批量提取视频提醒').position(dom) : alert(msg);
	}
}

 $( function(){
	 /*填充视频设置到top层*/
	 ( function($){
		 var box = top.$('#livUpload_div').find('.set-area'),
		 	content = $('#fast-set-tpl').html();
		 box.empty().append( content );
		 box.trigger( 'initlocalStorage' );
		 
		 var popupload = $('.pop-linkupload', parent.document),
		 	idname = 'link-vodupload';
		 popupload.click(function(){
		 	var pop = $('body').find('#' + idname);
		 	if( pop.length ){
		 		pop.link_vodupload('show');
		 	}else{
			 	var configInfo = {
			 		id : idname,
					width : 600,
					height : 260,
					ptop : '130',
					savebtn : false,
					modalHead : false,
					popTitle : '提取视频'
			 	}
			 	var voduploadPop = $.modalPop( idname );
			 	voduploadPop.link_vodupload( configInfo );
		 	}
		 });
	 } )($);
} );

</script>
{template:foot}
