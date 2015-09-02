{template:head}
{code}
    $formdata = $vod_collect_video_list[0];
	$image_resource = RESOURCE_URL;
{/code}

{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:vod_upload_pic_handler}
{js:vod_video_edit}
{js:vod_add_to_collect}

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

	function hg_overEditVideoInfo()
	{
		var frame_type = "{$_INPUT['_type']}";
		if(frame_type)
		{
			frame_type = '&_type='+frame_type;
		}
		else
		{
			frame_type = '';
		}
		
		var frame_sort = "{$_INPUT['_id']}";
		if(frame_sort)
		{
			frame_sort = '&_id='+frame_sort;
		}
		else
		{
			frame_sort = '';
		}
		window.location.href="./run.php?mid="+gMid+"&infrm=1"+frame_type+frame_sort;
	}

    var id = '{$id}';
    
   $(document).ready(function(){

	if(id)
	{
	   hg_show_opration_info(id);
	}
	   
	tablesort('vodlist','vod_collect_video','order_id',false,true);
	$("#vodlist").sortable('disable');

   });

   function hg_removeCollectVideo(id)
   {
	  var collect_id = parseInt($('#collect_id').val());
	  var url = './run.php?mid='+gMid+'&a=remove&id='+id+'&collect_id='+collect_id;
	  hg_ajax_post(url,'移除',1);
   }

   function hg_overRemoveVideos(obj)
   {
	   var obj = eval('('+obj+')');
	   hg_remove_row(obj.id);
   }

</script>

<body class="biaoz">
<div class="head_style" id="info_list_search">
  <div class="head_content overflow" ><label><font color="#7B7D7C">视频集合：</font></label>{$formdata['collect']['collect_name']}{if $formdata['collect']['is_auto']}(标注时创建){/if}</div>
  <div class="head_content overflow" ><label><font color="#7B7D7C">分类：</font></label>{$formdata['collect']['sort_name']}</div>
  <div class="head_content overflow" ><label><font color="#7B7D7C">视频数量：</font></label>{$formdata['collect']['count']}</div>
  <div class="head_content overflow" ><label><font color="#7B7D7C">来源：</font></label>{$formdata['collect']['channel_name']}</div>
  <div class="head_content overflow" ><label><font color="#7B7D7C">最后更新：</font></label>{$formdata['collect']['update_time']}</div>
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
			<!--添加视频至集合开始-->
		  <div id="add_to_collect"  class="single_upload">
				<h2><span class="b" onclick="hg_closeAddToCollectTpl();"></span>添加视频至集合</h2>
				<div id="add_to_collect_form" class="upload_form" style="background:none;"></div>
		  </div>
			<!--添加视频至集合结束-->
   <div class="right v_list_show">
        <div class="list_first clear"  id="list_head">
               <span class="left"><a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"><em></em></a><a class="slt">缩略图</a></span>
               <span class="right"><a class="fb">编辑</a><a class="ml">码流</a><a class="fl">分类</a><a class="zt">状态</a><a class="tjr">添加人/时间</a></span><a class="title">标题</a>
        </div>
		<form method="post" action="" name="listform">
			<ul class="list" id="vodlist">
				{if $formdata['collect_video']}
					{foreach $formdata['collect_video'] as $k => $v} 
						{template:unit/vod_list}
					{/foreach}
				{else}
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
				{/if}
				<li style="height:0px;padding:0;" class="clear"></li>
			</ul>
		<div class="bottom clear">
		<div class="left">
		<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
			<a  onclick="return  hg_batchremove(this, 'remove', '移除集合', 1, 'id', '', 'ajax',{$formdata['collect']['id']});"   name="batremove"  style="cursor:pointer;">移除集合</a> 
	        <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
	        <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"    name="batgoback" >打回</a>
		</div>
		{$pagelink}
		</div>	
		<input type="hidden" id="collect_id"  value="{$formdata['collect']['id']}"  />
		</form>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>
   </div>
  </div>
</div>
<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}