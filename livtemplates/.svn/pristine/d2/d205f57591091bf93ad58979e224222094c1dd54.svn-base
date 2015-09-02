{template:head}
{code}
$list = $vodinfo_list;
$image_resource = RESOURCE_URL;
$vodPlayerSwf = RESOURCE_URL.'swf/';

if(!isset($_INPUT['trans_status']))
{
    $_INPUT['trans_status'] = -1;
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
$levelLabel = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);
{/code}

{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:vod_upload_pic_handler}
{js:vod_video_edit}
{js:vod_add_to_collect}
{js:technical_review}
{js:column_node}
{css:column_node}

{js:tree/animate}

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

	function hg_change_status(obj)
	{
	   var obj = obj[0];
	   var status_text = "";
       if(obj.status == 2)
       {
    	   status_text = '已审核';
       }
       else if(obj.status == 3)
       {
    	   status_text = '被打回';    
       }

       for(var i = 0;i<obj.id.length;i++)
       {
    	   $('#text_'+obj.id[i]).text(status_text);
    	   if(obj.status == 2)
    	   {
        	   if($('#img_sj_'+obj.id[i]).length)
        	   {
        		   $('#img_sj_'+obj.id[i]).removeClass('b');
               }

        	   if($('#img_lm_'+obj.id[i]).length)
        	   {
        		   $('#img_lm_'+obj.id[i]).removeClass('b');
               }
    	   }
    	   else
    	   {
        	   if($('#img_sj_'+obj.id[i]).length)
        	   {
        		   $('#img_sj_'+obj.id[i]).addClass('b');
               }

        	   if($('#img_lm_'+obj.id[i]).length)
        	   {
        		   $('#img_lm_'+obj.id[i]).addClass('b');
               }
           }
       }

	   	if($('#edit_show'))
		{
			hg_close_opration_info();
		}
	}

    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";
    
   $(document).ready(function(){

	if(id)
	{
	   hg_show_opration_info(id,frame_type,frame_sort);
	}
	   
	tablesort('vodlist','vodinfo','video_order_id');
	$("#vodlist").sortable('option', 'cancel', '.common-list-head');
	$("#vodlist").sortable('disable');

   });
function header()
{
	var node_type = $('#node_type').val();
	var url="run.php?a=change_node&node_type="+node_type+"&mid={$_INPUT['mid']}";
	hg_request_to(url);
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

/*防止排序模式打开的时候，标题打开iframe编辑页还能用*/
$(function($) {
	var hasOpIfr = $('.option-iframe');
	if ( hasOpIfr.length ) {
		var func = window.hg_switch_order;
		window.hg_switch_order = function() {
			hasOpIfr.toggleClass('option-iframe');
			func.apply(null, arguments);
		};
	}
});
</script>

<style type="text/css">
.vod-quanzhong{ width:60px; }
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" onclick="hg_show_upload({$_INPUT['mid']});" ><strong>新增视频</strong></span>
	    <!-- <input type="button"  id="vod_upload"  value="批量新增"  class=" button_4" /> -->
	    <!-- <input type="button"  value="{$mode_show_text}"  class=" button_4"  onclick="hg_change_list('vodlist');" /> -->
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
				<h3 id="live_select" class="select_item" onclick="hg_load_timeShift(this)">从直播时移获取<span class="c"></span></h3>
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
			<div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
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
								'class' => 'transcoding down_list',
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
							$_configs['video_upload_status'][-1] = '全部状态';
							/*$type_search = array(0=>'媒资分类', 1=>'网站栏目', 2=>'手机栏目');*/
							$_type_search = array(0=>"媒资分类");
							foreach($type_search as $k=>$v)
							{
								$_type_search[$k] = $v .'栏目';
							}
							$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
						{/code}
						<!--{template:form/search_source,node_type,$default_node_type,$_type_search,$attr_type}-->
						{template:form/search_source,trans_status,$_INPUT['trans_status'],$_configs['video_upload_status'],$attr_source}
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
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('p',1);</script>
			{else}
                {css:common/common_list}
                {css:vod_list}
                {code}
                $columnData = array(
					array(
						'class' => 'vod-fabu',
						'innerHtml' => '发布至'
					),
					array(
						'class' => 'vod-fenlei',
						'innerHtml' => '分类'
					),
					array(
						'class' => 'vod-maliu',
						'innerHtml' => '码流'
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
                {template:list/list_column}
                <form method="post" action="" name="listform" style="display:block;position:relative;">
                    <ul class="vod-list common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="vod-paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a></div>
                                <div class="vod-fengmian common-list-item">缩略图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="vod-fabu common-list-item open-close" which="vod-fabu">发布至</div>
                                <div class="vod-maliu common-list-item open-close" which="vod-maliu">码流</div>
                                <div class="vod-fenlei common-list-item open-close" which="vod-fenlei">分类</div>
                                <div class="vod-quanzhong common-list-item open-close" which="vod-quanzhong">权重</div>
                                <div class="vod-zhuangtai common-list-item open-close" which="vod-zhuangtai">状态</div>
                                <div class="vod-ren common-list-item open-close" which="vod-ren">添加人/时间</div>
                            </div>
                            <div class="vod-biaoti">标题</div>
                        </li>
                    </ul>
                    <ul class="vod-list common-list" id="vodlist">
					{foreach $list as $k => $v}
						{template:unit/vod_list}
					{/foreach}             
                    </ul>
                    <ul class="common-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 0, 'id', '', 'ajax');"    name="batmove" >移动</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
								<a style="cursor:pointer;"  onclick="return hg_moreVideosToCollect(this);"   name="batadd_to_collect">添加到集合</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置标注', 1, 'id', '&is_allow=0', 'ajax');"   name="allow_mark">允许标注</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置标注', 1, 'id', '&is_allow=1', 'ajax');"   name="no_mark">不允许标注</a>
                       		</div>
                       		{$pagelink}
                    	</li>
                    </ul>
    			</form>
				<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
				</div>
			{/if}
            </div>
        </div>
	</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
   <!--<div id="img_test"></div>-->
</body>

<script type="text/javascript">

 var gcheckTime = 5000;
 var _type = "{$_INPUT['_type']}";/*获取当前页面的类型*/
 var _id = "{$_INPUT['_id']}";/*获取当前页面的某个类型下面的类别*/
 if(!_type){_type = -1;}
 if(!_id){_id = -1;}
 
 function  hg_panduan(obj)
 {
	     var obj = obj[0];
	     var pmaxid = hg_get_maxid();/*获取当前页面最大id ,并且存起来*/
	     var transIds = '';/*存放当前页面正在转码中的视频 */

	     
	     /*对需要动态添加一行的列表的进行操作 */
		 if(obj.add_data)
		 {
			 for(var i = 0;i<obj.add_data.length;i++)
			 {
				if(obj.add_data[i].id)
				{
					if(!$('#r_'+obj.add_data[i].id).length)
					{
						hg_single_video({'vodid':obj.add_data[i].vodid,'id':obj.add_data[i].id});
						correctPosition();
				    }
				    
					pmaxid = obj.add_data[i].id;/*每次将新添加之后列表id存入最大值变量中*/
				}
		     }
	     }

	     /*对正在转码中的列表进行操作*/
		 if(obj.status_data)
		 {
			 for(var i = 0;i<obj.status_data.length;i++)
			 {
				 var  id     = obj.status_data[i].id;
				 var percent = obj.status_data[i].transcode_percent;
				 var  status = obj.status_data[i].status;
				 $("#request_videoinfo").remove();
				 
	             if(status != 1 && status != 2 && status != 3)
	             {
	                 if(id)
	                 {
		                 /*
	                      if(!$('#img_'+id).attr('src'))
	                      {
	                          $('#img_'+id).attr('src',obj.status_data[i].img);
	                      }
	                      */
	                      
	                	  $("#text_"+id).text("转码中");
	                	  $("#tool_"+id).css("display","block");
		   	           	  var tool = $("#tool_"+id).css("width");
		   	           	  //var tool_width = Math.round((transize/totalsize)*parseInt(tool));
		   	           	  var tool_width = Math.ceil(parseInt(tool)*percent/100);
		   	           	  $("#status_"+id).css("width",tool_width);

		   	           	  /*
		   	           	  var needTime = hg_TimeFormat(Math.round((totalsize - transize)/(Math.round(speed) * 1024)));
		   	           	  var transTip = ''; 
		   	           	  if(speed)
		   	           	  {
		   	           		  transTip = '大约还需要' + '<font style="color:#e93f3f;">' + needTime + '</font>';
			   	          }
		   	           	  else
		   	           	  {
		   	           	 	  transTip = '<font style="color:#e93f3f;">正在等待转码...</font>';
			   	          }
						  
						  
		   	           	  $('#hg_t_'+id).html((speed * 8) + 'kb/s <br>' + transTip);
		   	           	  */
				
		   	           	  var html = "<input type='button' value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;' onclick='hg_controlTranscodeTask("+id+",1);' />";
		   	           	  $('#hg_t_'+id).html(html);
	                 }
	             }
	             else
	             {
		             /*
					  if(!$('#img_'+id).attr('src'))
					  {
						  $('#img_'+id).attr('src',obj.status_data[i].img);
					  }

                      $('#bitrate_'+id).text(obj.status_data[i].bitrate);
                      */
                      var html = "<a class='button_6'  href='run.php?mid="+gMid+"&id="+id+"&a=multi_bitrate' onclick='return hg_ajax_post(this);'  style='margin-left:24px;margin-top:7px;' >新增多码流</a>";
                      $('#hg_t_'+id).html('');
                      if(status == 1)
                      {
                    	  $("#text_"+id).text("待审核");
                      }
                      else if(status == 2)
                      {
                    	  $("#text_"+id).text("已审核");
                      }
                      else if(status == 3)
                      {
                    	  $("#text_"+id).text("被打回");
                      }
	             	  $("#tool_"+id).css("display","none");
	             }
		     }
	     }

		/*经过上面一轮的数据请求以及前台页面的dom操作之后，预备好数据，准备下一轮的数据请求*/
		 transIds = hg_get_trans_id();
		 if(!hg_checkFirstPage())
		 {
			 pmaxid = -1;
		 }

		 gcheckTime = changeCheckTime();/*改变检测的时间*/
		 setTimeout("hg_getvideoinfo(" + pmaxid + ",'"+transIds+"'," + _type + "," + _id + ");", gcheckTime);/*每隔一段时间请求一次*/

 }

 var maxid = hg_get_maxid();
 var trans_ids =  hg_get_trans_id();

 if(!hg_checkFirstPage())
 {
	 maxid = -1;
 }
 
 hg_getvideoinfo(maxid,trans_ids,_type,_id);

 /*判断当前页面是不是第一页*/
 function hg_checkFirstPage()
 {
	 if($('#pagelink_1').length)
	 {
		 if($('#pagelink_1').hasClass('page_cur'))/*根据有没有page_cur这个样式来判断是不是首页 */
		 {
			return  true;
		 }
		 else
		 {
			return  false; 
		 }
	 }
	 else
	 {
		 return true;
	 }
	 
 }
 

 /*获取当前页面最大id*/
 function  hg_get_maxid()
 {
	 var maxid = 0;
     $('#vodlist li[id^="r_"]').each(function(){
        
    	 var cid = parseInt($(this).attr('name'));
    	 if(cid > maxid)
    	 {
        	 maxid = cid;
         }
     });

     return maxid;
}


 /*获取当前页面转码中的id*/
 function  hg_get_trans_id()
 {
	 var trans_ids = new Array();
	 $('span[id^="text_"]').each(function(){
		if($(this).text() == '转码中')
		{
			var id_str = $(this).attr('id');
			var tid = id_str.substr(5);
			trans_ids.push(tid);
		}
	 });

	 if(trans_ids.length)
	 {
		 return trans_ids.join(',');
	 }
	 else
	 {
		 return 0;
	 }
 }


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

 /*判断当前页面有没有正在转码中,有的话,检测时间变为隔3秒检测一次,没有则变为5秒检测一次*/
 function changeCheckTime()
 {
	 var checkTime;
	 var arr = hg_get_trans_id();
	 if(arr.length)
	 {
		 checkTime = 3000;
	 }
	 else
	 {
		 checkTime = 5000;
	 }
	 
	 return checkTime;
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
	 var obj = eval('('+obj+')');
	 if(obj.op == 'pause' && obj.return == 'success')
	 {
		 $("#text_"+obj.id).text("已暂停");
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

$(function(){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    /*缓存页面的打开的标题个数*/
    $.commonListCache('vod-list');

     $.initOptionIframe();
});
</script>
{template:foot}