{template:head}
{css:mark_style}
{css:column_node}
{js:column_node}
{js:vod_mark}
{js:upload_vod}
{js:vod_upload_pic_handler}
{js:jquery-ui-1.8.16.custom.min}
{code}
$markswf_url = RESOURCE_URL.'swf/';
$resource_url = RESOURCE_URL;

if (!$formdata['add_edit'] && $formdata['lastdata'])
{
	$formdata['vod_sort_id'] = $formdata['lastdata']['vod_sort_id'];
}
{/code}
<!-- 来源控件数据 -->
{code}
	$item_mark_source = array(
		'class' => 'down_list',
		'show' => 'mark_source_show',
		'width' => 162,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	if($formdata['add_edit'] && $formdata['source'])/*编辑标注与快编*/
	{
	   $mark_default = $formdata['source'];
	}
	else
	{
	   $mark_default = -1;
	}
	$sources[-1] = '自动';
	foreach($source as $k =>$v)
	{
		$sources[$v['id']] = $v['name'];
	}
	
	$source_id = 'mark_source_id';
{/code} 
<!-- 分类控件数据 -->
{code}
	$item_mark_sort = array(
		'class' => 'down_list',
		'show' => 'mark_sort_show',
		'width' => 90,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	if($formdata['add_edit'] && $formdata['vod_sort_id'])
	{
	   $mark_sort_default = $formdata['vod_sort_id'];
	}
	else
	{
	   $mark_sort_default = -1;
	}
	
	if($formdata['add_edit'])/*编辑标注与快速编辑时的类别*/
	{
		if($formdata['vod_leixing'] == 1)/*编辑上传的类别*/
		{
			$vod_sort_arr = $vod_edit_sort[0];
		}
		else if($formdata['vod_leixing'] == 3)/*直播归档的类别*/
		{
			$vod_sort_arr = $vod_live_sort[0];
		}
		else if($formdata['vod_leixing'] == 4)/*标注归档的类别*/
		{
			$vod_sort_arr = $vod_sort[0];
		}
		
	}
	else
	{
		$vod_sort_arr = $vod_sort[0];/*新增标注的类别*/
	}
	
	$sorts[-1] = '自动';
	foreach($vod_sort_arr as $k =>$v)
	{
		$sorts[$v['id']] = $v['name'];
	}

$vod_sort_id = 'mark_sort_id';
{/code} 
<script type="text/javascript">
function hg_live_s_show()
{
	hg_task_liveclose();
	$("#task").attr('class','');
	$("#program").attr('class','text-click');	
	$(".live").slideDown();		
}
function hg_task_live_show()
{
	hg_livclose();
	$("#task").attr('class','text-click');
	$(".task_live").slideDown();
}
function hg_livclose()
{
	$(".live").slideUp();
	$("#program").attr('class','');
}
function hg_task_liveclose()
{
	$(".task_live").slideUp();
	$("#task").attr('class','');
	$("#program").attr('class','');
}	
function content_m_l_show(obj)
{
	if($('#content_m_l').css('display')=='none')
	{
		/*$(obj).hide();*/
		$('#content_m_l').show();
		$('#content_m_r').removeClass('i');
	}
	else
	{
		/*$(obj).show();*/
		$('#content_m_l').hide();
		$('#content_m_r').addClass('i');
	}
}
function con_m_l_t_show(obj,num)
{
	$('#con_m_l_t').text($('#name_'+num).val());
	var img_h = $(obj).find('img').height();
	var pos_l = $(obj).position().left+76;
	var pos_t = $(obj).position().top+img_h-18;
	$('#con_m_l_t').css({'display':'block','left':pos_l,'top':pos_t});
}

function con_m_l_t_drag(obj)
{
	var img_h = obj.find('img').height();
	var pos_l = obj.position().left+76;
	var pos_t = obj.position().top+img_h-18;
	$('#con_m_l_t').css({'left':pos_l,'top':pos_t});
}

function con_m_l_t_hide(obj)
{
	$('#con_m_l_t').css({'display':'none'});
}
var add_edit = '{$formdata[add_edit]}';

$(function()
{
	$('#hgCounter_0_column_a,#hgCounter_0_coltype').click(function(){
		setTimeout(hg_adjust_position,300);
	});
	
	var mid = '{$_INPUT['mid']}';
	upload_update_preview(mid);

	/*视频片段能拖动*/
	var vcr_order_arr = new Array();
	$('#add_mark_videos_box').sortable({ 
		items: 'li',
		axis: 'y' ,
		scrollSpeed:100,
		revert: true,
		scroll: true,
		containment: $('#add_mark_videos_box') ,
		tolerance: 'pointer',
		start: function(event, ui){
			vcr_order_arr = new Array();
			var old_arr_order = $(this).sortable('toArray');
			for(var i = 0;i < old_arr_order.length;i++)
			{
				var id = old_arr_order[i].substr(10);
				vcr_order_arr.push($('#order_id_'+id).val());
			}
		},
		stop: function(event, ui){
			var new_arr_order = $(this).sortable('toArray');
			for(var i = 0;i < new_arr_order.length;i++)
			{
				var id = new_arr_order[i].substr(10);
				$('#order_id_'+id).val(vcr_order_arr[i]);
			}
		},
		sort: function(event, ui){
			con_m_l_t_drag(ui.item);
		}
	});
	
	var current_video_id = "{$_INPUT['id']}";
	if(parseInt(add_edit) == 1)/*编辑标注的时候 */
	{
		var url = "./run.php?mid="+gMid+"&a=get_vcr_data&vcr_id="+current_video_id;
		hg_ajax_post(url);
	}
	else/*快速编辑与添加标注的时候 */
	{
		hg_putSourceVideo(current_video_id);
		add_mark_one_video(current_video_id);
	}
});

var mark_count = "{$formdata['mark_count']}";


</script>
<!--//[if IE]>
<style type="text/css">
#video{behavior: url(images/ie-css3.htc);}
</style>
<![endif]-->
<div class="top clear">
    	<div class="menu">        
        	<!-- <ul class="right">
            	<li><a id="task"  href="javascript:void(0);" onclick="hg_task_live_show();">我的任务</a></li>
                <li><a id="program" href="javascript:void(0);" onclick="hg_live_s_show();">直播节目</a></li>
                <!-- <li><a href="javascript:void(0);" onclick="hg_showAddCollect();">选择视频</a></li> 
                <li><a>视频库</a></li>
            </ul> -->        
        </div>
</div>
        
<div id="content_big" class="content clear" style="border-top-left-radius: 5px;-webkit-border-top-left-radius: 5px;-moz-border-top-left-radius: 5px;border-top-right-radius: 5px;-webkit-border-top-right-radius: 5px;-moz-border-top-right-radius: 5px;">
	<span class="con_l_mor" onclick="content_m_l_show(this);" id="con_l_mor" unselectable="on" onselectstart="return false;"></span>
    <form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vod_mark_form"  id="vod_mark_form"   onsubmit="return  hg_submit_mark_video('vod_mark_form')" class="clear"> 
	<span class="con_m_l_t" style="top:100px;display:none" id="con_m_l_t"></span>
	<div class="content_middle_left" id="content_m_l" style="display:none">
		<div id="unselect"  onclick="hg_unselect_videos();" style="height:20px;background:#AAD3FF;cursor:pointer;height:20px;margin:0 auto 10px;width:75px;color:white;line-height:16px;">反选</div>
		<ul class="clear" id="add_mark_videos_box"></ul>
		<span class="con_m_l_add"  onmouseover="hg_mouseOverShow();"   onclick="hg_clickAdd();" onmouseout="hg_mouseOut();"></span>
	</div>
	
    <div class="content_middle i" id="content_m_r">
    	<div class="content_middle_1">
    		<div id="list_object">
	        	<object id="list" type="application/x-shockwave-flash" data="{$markswf_url}list.swf?{$formdata['time']}" width="100%" height="80">
					<param name="movie" value="{$markswf_url}list.swf?{$formdata['time']}"/>
					<param name="allowscriptaccess" value="true">
					<param name="wmode" value="transparent">
					<param name="allowFullScreen" value="true">
					<param name="flashvars" value="connectName={$formdata['connect_name']}">
				</object>
        	</div>
        </div>
		{code}
		/*$formdata['video_pic_api'] = str_replace('snap.php', 'snap/',$formdata['video_pic_api']);*/
		{/code}    
    	<div class="content_middle_2">
        	<div class="left clear" id="append_img">
				<img class="move_img_a" src="" style="width:330;height:240px;" id="img_move"/>
				<div id="view_object">
					<object id="view" type="application/x-shockwave-flash" data="{$markswf_url}view.swf?{$formdata['time']}" width="415" height="500" style="background:#222;">
						<param name="movie" value="{$markswf_url}view.swf?{$formdata['time']}"/>
						<param name="allowscriptaccess" value="true">
						<param name="wmode" value="transparent">
						<param name="allowFullScreen" value="true">
						<param name="flashvars"  value="connectName={$formdata['connect_name']}&id={$formdata['id']}&video={$formdata['video_mark']}&snapUrl={$formdata['video_pic_api']}snap/&startTime={$formdata['start']}&duration={$formdata['duration']}&aspect={$formdata['aspect']}&jsNameSpace=selector" >
					</object>
				</div>
				<div class="title info clear" style="margin-top:10px;color:#7D7D7D;line-height:22px;height:200px;width:415px;overflow:auto;">
					<div style="width:50%;float:left" class="overflow">
						{if $formdata['add_edit'] == -1}
							<span class="nr">视频快编：</span>
						{else}
							<span class="nr">{if $formdata['add_edit'] == 1}编辑{else}正在{/if}标注：</span>
						{/if}
						<span>{$formdata['title']}</span>
					</div>
					<div style="width:50%;float:left" class="overflow">
						{if $formdata['add_edit'] != -1}
							{if $formdata['add_edit'] == 1}
							<span class="nr" >该标注来源于：</span>
							<a href="{if $formdata['original_id']}./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$formdata['original_id']}{else}javascript:void(0){/if}"><span style="color:#7D7D7D;">{$formdata['original_title']}</span></a>
							{else if $formdata['mark_count'] == 0}
								<span id="mark_text">该视频还未被标注</span>
							{else}
								<span id="mark_text">该视频已被标注：</span>
								<span id="count_id">{$formdata['mark_count']}</span>
								<span id="count_type">条</span>
							{/if}
						{/if}
					</div>
					<div style="width:50%;">
					{if $formdata['sub_mark_info']}
						{foreach $formdata['sub_mark_info'] as $v}
								<div style="cursor:pointer;">
									<a href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$v['id']}">
										<span class="overflow"  style="color:#7D7D7D;">{$v['title']}</span>
										<span style="margin-left:10px;color:#7D7D7D;">{$v['duration']}</span>
									</a>
								</div>
						{/foreach}
					{/if}
					</div>
				</div>
			</div>
        <div class="right clear">
        	<div class="bg_top"></div>
            <div class="bg_middle" style="min-height:730px;">
	         	{if $formdata['add_edit'] == -1}
	         		<h2>视频快编</h2>
	         	{else}
	         		<h2>{if $formdata['add_edit'] == 1}编辑{else}新增{/if}标注</h2>
	         	{/if}
                <div class="info">
                <div class="clear" style="height:140px">
                  <a class="info-bigimg" onclick="hg_info_bigimg_show();"><img  src="{$formdata['source_img']}"  style="width:160px;"   id="pic_face"   title="点击图片更换截图"   onclick="hg_get_mark_images();"  /></a>
               	  <div class="info-left"><input type="text" name="title"  id="title" value="{if $formdata['add_edit']}{$formdata['title']}{else}请输入标题{/if}"    style="width:318px;float:left;"     class="info-title info-input-left  {if $formdata['add_edit']}{else}t_c_b{/if}" onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');"/> 	  
               	  <div style="float:left;margin:14px 5px 6px;">{template:form/search_source,$vod_sort_id,$mark_sort_default,$sorts,$item_mark_sort}</div>
               	  <!--<textarea name="comment" id="comment" rows="2" class="info-description info-input-left" placeholder="这里输入描述">{if $formdata['comment']}{$formdata['comment']}{/if}</textarea>  -->
				  <textarea rows="2" class="info-description info-input-left {if $formdata['add_edit']}{else}t_c_b{/if}" name="comment"  id="comment"  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['add_edit']}{$formdata['comment']}{else}这里输入描述{/if}</textarea></div>
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
                        <td width="74%">副题</td>
                        <td width="26%">来源</td>
                      </tr>
                      <tr>
                        <td><input type="text"  name="subtitle"  id="subtitle"  value="{if $formdata['add_edit']}{$formdata['subtitle']}{else}{/if}"  class="subtitle info-input-left"/></td>
                        <td>{template:form/search_source,$source_id,$mark_default,$sources,$item_mark_source}</td>  
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
                        <td valign="middle"><input type="text" name="keywords"   id="keywords"  value="{if $formdata['add_edit']}{$formdata['keywords']}{else}{/if}"   class="subtitle info-input-left"/></td>
                        <td><input type="text" name="author"   id="author"    value="{$formdata['author']}"  class="subtitle info-input-right" style="float:right;"/></td>
                      </tr>
                    </table>
              </div>
			  {code}
                  $hg_attr['multiple'] = 1;
				  $hg_attr['multiple_site'] = 1;
				  $default = $formdata['haspub'];
             	{/code}
             {template:unit/publish, 1, $formdata['column_id']}
             <script>
             jQuery(function($){
                $('#publish-1').css('margin', '10px auto').commonPublish({
                    column : 2,
                    maxcolumn : 2,
                    height : 224,
                    absolute : false
                });
             });
             </script>
              <div class="submit clear">	
              	<input type="hidden"  name="add_edit"   id="add_edit"    value="{$formdata['add_edit']}" />
              	<input type="hidden"  name="mark_start" id="mark_start"  value="" />
              	<input type="hidden"  name="mark_end"   id="mark_end"    value="" />
              	{if $formdata['add_edit'] == -1}
              	  <input type="submit" class="fix" value="完成编辑视频"     id="finish_mark">
              	{else if $formdata['add_edit'] == 1}
              	  <input type="submit" class="fix" value="完成编辑标注"     id="finish_mark">
              	{else}
              	  <input type="submit" class="fix" value="完成并继续标注"   id="finish_mark">
              	{/if}
              	<input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="{$formdata['source_img']}" />
              	<input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
              	<input type="hidden" name="img_src"  id="img_src"  value=""   />
              	<input type="hidden"   id="source"  name="source"  value="" />
              	<input type="hidden"  name="module_id"  value="{$_INPUT['mid']}" />
	  			<input type="hidden"   id="vod_sort_id"  name="vod_sort_id"  value="" />
              	<input type="hidden" name="reffer_a" value="video_mark"  />
              	<input type="hidden" value="add_video_mark" name="a"  />
              	<input type="hidden" value="{$_INPUT[$primary_key]}" name="{$primary_key}"  id="mark_id"  />
	 			<input type="hidden" name="referto" value="{$_INPUT['referto']}"  id="go_to_refer" />
              </div>
            </div>
        </div>
        </div>
    </div>
  </form>
  <div id="mark_select_videos"  style="position:absolute;top:0;left: 8%;">
    {template:unit/vod_select_videoslist}
  </div>
</div>
<script type="text/javascript">
    
$(document).ready(function(){
	setTimeout("$('#list')[0].focus();hg_getDefaultImage();", 2000);
});

</script>
<script language="JavaScript" type="text/javascript">var client_id = 1;</script>
<div id="dragHelper" style="position: absolute; display: none; cursor: move; list-style-type: none; list-style-position: initial; list-style-image: initial; overflow-x: hidden; overflow-y: hidden; -webkit-user-select: none; "></div>
{template:foot}