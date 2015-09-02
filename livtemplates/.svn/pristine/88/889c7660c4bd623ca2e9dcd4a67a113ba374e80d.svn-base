{template:head}
{css:upload_vod}
{js:upload_vod}
{js:vod}
{js:column_node}
{js:jscroll}
{css:column_node}
{js:vod_upload_pic_handler}
{code}
  $markswf_url = RESOURCE_URL.'swf/';
  $image_resource = RESOURCE_URL;
{/code}
<!-- 来源控件的数据 -->
{code}
	$item_up_source = array(
		'class' => 'down_list',
		'show' => 'source_show',
		'width' => 188,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	if($formdata['source'])
	{
	   $default = $formdata['source'];
	}
	else
	{
	   $default = -1;
	}
	$sources[-1] = '自动';
	foreach($source as $k =>$v)
	{
		$sources[$v['id']] = $v['name'];
	}
	
	$source_id = 'update_source_id';
{/code} 
<!-- 分类控件的数据 -->	               	  
{code}
	$item_up_sort = array(
		'class' => 'down_list',
		'show' => 'up_sort_show',
		'width' => 90,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	if($formdata['vod_sort_id'])
	{
	   $sort_default = $formdata['vod_sort_id'];
	}
	else
	{
	   $sort_default = -1;
	}
	$sorts[-1] = '自动';
	foreach($formdata['sort_name'] as $k =>$v)
	{
		$sorts[$v['id']] = $v['name'];
	}
	
	$vod_sort_id = 'update_sort_id';
{/code} 

<script type="text/javascript">

	$(function(){
		var mid = '{$_INPUT['mid']}';
		upload_update_preview(mid);
	});

    var adminDemandPlayer = {};

    adminDemandPlayer.startHandler = function()
    {
       
    };

    adminDemandPlayer.endHandler   = function()
    {
        
    };

	adminDemandPlayer.snap = function(vodid,current_time)
	{
		var id = $('#edit_id').val();
		var img_count = 1;
		var url = "./run.php?mid="+gMid+"&a=get_current_img&img_count="+img_count+"&stime="+current_time+"&id="+id;
		hg_ajax_post(url,'','','hg_get_one_vimg');
	};
	var ii=tt=tt_m=tt_s=hg_img_url=0;
	var img_move="#img_move";
	var move_time = 400;
    function hg_get_one_vimg(obj)
    {
    	$(img_move).clearQueue();
		clearTimeout(tt_s,tt_m);
		$(img_move).attr('src',obj[0].new_img);
		$('#source_img_pic').val(obj[0].new_img);
		move_img();
		tt_s = setTimeout(he_get_mov_img_show,move_time+10);
	}
	function he_get_mov_img_show()
	{
		hg_img_url = $('#source_img_pic').val();
		$('#pic_face').attr('src',hg_img_url);
	}
	function move_img()
	{
		$(img_move).addClass('move_img_b');
		$(img_move).animate({top:'0px',left:'-201px',width:'188px'},move_time);
		tt_m = setTimeout(remove_move_img,move_time+20);
	}
	function remove_move_img()
	{
		$(img_move).remove();
		$("#video").before('<img class="move_img_a" src="" id="img_move" style="width:320px;" />');
	}
	function video_close(){
		$("#hoge_edit_play").css("display","none");
	}
  
	function video_show()
	{
		$("#hoge_edit_play").clearQueue();
		if(ii==0)
		{
			ii=1;
			clearTimeout(tt);
			$("#hoge_edit_play").css("display","block");
			$("#hoge_edit_play").animate({top:"63px"});
			
		}
		else{
			ii=0;
			$("#hoge_edit_play").animate({top:"-378px"});
			tt = setTimeout(video_close,600);
		}
	}

	function hg_toSubmit()
	{
		$('#vod_sort_id').val($('#update_sort_id').val());
		$('#source').val($('#update_source_id').val());
		if($('#title').val() == '请输入标题')
	    {
	    	alert('您未填写标题');
	    	return false;
	    }
	    
	    if($('#comment').val() == '这里输入描述')
	    {
	    	$('#comment').val('');
	    }
		return hg_ajax_submit('vodform','');
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

	function hg_change_color(obj,flag)
	{
		if(flag)
		{
			$(obj).css('background','#5F9BD1');
		}
		else
		{
			$(obj).css('background','');
		}
	}
	function hg_content_vod_show()
	{
		if($('#content_vodinfo_more').text()=='更多')
		{
			$('#content_vodinfo_ul_one').attr('class','');
			$('#content_vodinfo_ul').show(0,function(){hg_resize_nodeFrame();});
			$('#hg_vod_text_more').hide();
			$('#content_vodinfo_more').text('收起');
		}
		else
		{
			$('#content_vodinfo_ul_one').attr('class','overflow i');
			$('#content_vodinfo_ul').hide(0,function(){hg_resize_nodeFrame();});
			$('#hg_vod_text_more').show();
			$('#content_vodinfo_more').text('更多');

		}
		
	}

</script>
{if $formdata['id']}
<div  id="updatelist"  name="updatelist" class="clear">
 <form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return  hg_toSubmit();">
	<div class="right clear" style="overflow:hidden;">
            <div class="bg_middle">
                <h2>编辑视频<span onclick="video_show();" title="显示、关闭视频/ALT+W" class="edit_video_show">视频预览/截图</span></h2>
				<div class="info content_vodinfo clear">
				
					<ul class="clear">
						<li class="overflow i" id="content_vodinfo_ul_one">文件属性：<span>时长：</span>{$formdata['video_duration']},<span>文件大小：</span>{$formdata['video_totalsize']},<span>视频编码：</span>{$formdata['video']},<span>平均码流：</span>{$formdata['bitrate']},<span>视频帧率：</span>{$formdata['frame_rate']}<span id="hg_vod_text_more">...</span></li>
						<!--<li class="overflow" title="时长:{$formdata['video_duration']}"></li>
						<li class="overflow" title="文件大小：{$formdata['video_totalsize']}"></li>
						<li class="overflow" title="视频编码：{$formdata['video']}"></li>
						<li class="overflow" title="平均码流:{$formdata['bitrate']}"></li>
						<li class="overflow"></li>-->
						
						
					</ul>
					<ul class="clear" id="content_vodinfo_ul" style="display:none;">
						<li class="overflow"><span>分辨率：</span>{$formdata['video_resolution']},<span>宽高比：</span>{$formdata['aspect']},<span>音频编码：</span>{$formdata['audio']},<span>音频采样率：</span>{$formdata['sampling_rate']},<span>声道：</span>{$formdata['video_audio_channels']}</li>
						<!--<li class="overflow" title="宽高比：{$formdata['aspect']}"></li>
						<li class="overflow" title="音频编码：{$formdata['audio']}"></li>
						<li class="overflow" title="音频采样率：{$formdata['sampling_rate']}"></li>
						<li class="overflow" title="声道：{$formdata['video_audio_channels']}"></li>-->
					</ul>
					<span id="content_vodinfo_more" onclick="hg_content_vod_show();" class="content_vodinfo_more" >更多</span>
					{if $formdata['is_fast_edit']}
					<a class="content_vodinfo_text"  href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$formdata['id']}&fast_edit=1{$_pp}" target="mainwin">视频快编</a>
					{else}
					<a class="content_vodinfo_text"  href="javascript:void(0);"  onclick="alert('此视频已被标注，不可快编');" >视频快编</a>
					{/if}
				</div>
                <div class="info">
                <div class="clear" style="height:156px;">
                  <a class="info-bigimg"  onclick="uploadimg_show({$formdata['id']},{$_INPUT['mid']});" ><span><img   src="{$formdata['source_img']}"   id="pic_face"    title="点击图片更换截图" /></span></a>
               	  <div class="info-left">
	               	  <input type="text" name="title"  id="title"  value="{if $formdata['title']}{$formdata['title']}{else}请输入标题{/if}"  class="info-title info-input-left   {if $formdata['title']}{$formdata['title']}{else}t_c_b{/if}"  style="width:368px;float:left;"   onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');"/>
					  <div style="float:left;margin:14px 5px 6px;">{template:form/search_source,$vod_sort_id,$sort_default,$sorts,$item_up_sort}</div>
	               	  <textarea rows="2" class="info-description info-input-left  {if $formdata['comment']}{$formdata['comment']}{else}t_c_b{/if}" name="comment"  id="comment"  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['comment']}{$formdata['comment']}{else}这里输入描述{/if}</textarea>
               	  </div>
                </div>
				  <div id="info-img" class="clear" style="display:none">
					<span class="info-img-top"></span>
				
					  <dl id="add-img">
							<dt>选择本标注的视频示意图</dt>
							<dd class="loading-img"></dd>                       
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
                            <dd class="loading-img"></dd>
					  </dl>
                      <div id="add_from_compueter" class="addinfo-img"></div>
				  </div>
                </div>
                <div class="info">
                	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td width="72%">副题</td>
                        <td width="28%">来源</td>
                      </tr>
                      <tr>
                        <td><input type="text" name="subtitle" id="subtitle"  value="{$formdata['subtitle']}" class="subtitle info-input-left"/></td>
                        <td>
                         {template:form/search_source,$source_id,$default,$sources,$item_up_source}
						 </td>
                      </tr>
                    </table>
              </div>
              <div class="info">
                	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td width="72%" valign="middle">关键字</td>
                        <td width="28%">作者</td>
                      </tr>
                      <tr>
                        <td valign="middle"><input type="text" name="keywords" id="keywords" value="{$formdata['keywords']}" class="subtitle info-input-left"/></td>
                        <td><input type="text" name="author" id="author" value="{$formdata['author']}"   class="subtitle info-input-right"/></td>
                      </tr>
                    </table>
              </div>
              {code}
                  $hg_attr['multiple'] = 1;
				  $hg_attr['multiple_site'] = 1;
				  $default = $formdata['haspub'];
              {/code}
             {template:unit/publish, 1, $formdata['colunm_id']}
             <script>
             jQuery(function($){
                $('#publish-1').css('margin', '10px auto').commonPublish({
                    column : 3,
                    maxcolumn : 3,
                    height : 224,
                    absolute : false
                });
             });
             </script>
              <div class="info">
              	    <table width="100%" border="0" class="info-table">
                      <tr>
                        <td colspan="3" valign="middle">选项</td>
                      </tr>
                      <tr>
                        <td colspan="3" valign="middle">
                        	<div class="options">
                            	<lable><span>开放评论</span><input name="comment2out" value="1" type="checkbox"  checked="checked"/></lable>
                            	<lable><span>自动台标</span><input type="checkbox" name="taibiao" value="2" checked="checked"/></lable>
                            	<lable><span>附加广告</span><input type="checkbox" name="guanggao" value="3" checked="checked"/></lable>
                            	<lable><span>允许打分</span><input type="checkbox" name="dafen" value="4" checked="checked"/></lable>
                            	<lable><span>观看心情</span><input type="checkbox" name="xinqing" value="5" checked="checked"/></lable>
                            </div>
                        </td>
                      </tr>
                    </table>
              </div>
              <div class="submit clear">
              	<span>
                <a>编辑：{$_user['user_name']}</a>
                </span>
              	<input class="fix"  type="submit" name="submit" id="submit_bianji" value="编辑完成">
              </div>
            </div>
        </div>
		<div class="right_version"  style="overflow:hidden;">
			<h2><a href="{$_INPUT['referto']}">返回上页</a></h2>
			<h2 class="b">历史版本</h2>
			<ul class="u" id="copyright_list">
			 {if $formdata['update_copyright']}
     			{foreach $formdata['update_copyright'] as $k => $v}
				<li  onclick="hg_get_copyright(this,{$v['id']},{$_INPUT['mid']});"  style="cursor:pointer;"  name="copyright[]" >
					<span class="time">{$v['update_time']}</span>
					<span>{$v['update_man']}编辑</span>
				</li>
			   {/foreach}
  			 {/if}	
			</ul>
			{if $formdata['total_num'] > $formdata['page_num']}
			<div class="more"  id="haveMore" onclick="hg_getMoreCopyright({$$primary_key},{$_INPUT['mid']});">更多<span></span></div>
			{/if}
		</div>
	  <input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="{$formdata['source_img']}" />
	  <input type="hidden"   id="source"  name="source"  value="" />
	  <input type="hidden"   id="vod_sort_id"  name="vod_sort_id"  value="" />
	  <input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
	  <input type="hidden" name="img_src"  id="img_src"  value=""   />
	  <input type="hidden" value="{$a}" name="a" />
	  <input type="hidden" value="{$_INPUT['mid']}" name="module_id" />
	  <input type="hidden" value="{$formdata['total_num']}"  id="total_num" />
	  <input type="hidden" value="{$formdata['page_num']}"  id="page_num" />
	  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" id="edit_id" />
	  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
</div>
<div id="hoge_edit_play" style="top:-378px;">
<img class="move_img_a" src="" id="img_move" style="width:320px;" />
<object id="video" type="application/x-shockwave-flash" data="{$markswf_url}vodPlayer.swf?{$formdata['time']}" width="320" height="270">
	<param name="movie" value="{$markswf_url}vodPlayer.swf?{$formdata['time']}">
	<param name="allowscriptaccess" value="always">
	<param name="wmode" value="transparent">
	<param name="allowFullScreen" value="true">
	<param name="flashvars" value="jsNameSpace=adminDemandPlayer&startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['vodid']}&snap=true&autoPlay=true&snapUrl={$formdata['snapUrl']}">
</object>

<span></span>
</div>
{else}
此视频不存在,<a href="./run.php?mid={$_INPUT['mid']}&infrm=1">请返回</a>
{/if}
{template:foot}