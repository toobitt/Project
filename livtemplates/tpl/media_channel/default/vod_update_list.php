{template:head}
{css:common/common_form}
{css:upload_vod}
{css:vod_update_list}
{js:upload_vod}
{js:vod}
{js:column_node}
{js:jscroll}
{css:column_node}
{js:vod_upload_pic_handler}
{css:hg_sort_box}
{js:hg_sort_box}
{js:common/auto_textarea}
{js:common/common_form}
{js:vod/vod_form}
{js:common/ajax_upload}
{code}
  $markswf_url = RESOURCE_URL.'swf/';
  $image_resource = RESOURCE_URL;
{/code}
<!-- 来源控件的数据 -->
{code}
	$item_up_source = array(
		'class' => 'down_list',
		'show' => 'source_show',
		'width' => 130,/*列表宽度*/		
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
	
	
	foreach($formdata['sort_name'] as $k =>$v)
	{
		$sorts[$v['id']] = $v['name'];
	}
	
	$vod_sort_id = 'update_sort_id';
	$sort_default = $formdata['vod_sort_id'];
	$sort_default_name = ($formdata['vod_sort_name'] != '无' ? $formdata['vod_sort_name'] : '选择分类');
	
{/code} 

<script type="text/javascript">
<!--
	$(function ($) {
		$(window).on( 'resize', function (e) {
			var width = $(this).width() - $('.form-left').width();
			$('.form-middle').css({
				width: width < 935 ? 935 : width
			});
		}).trigger('resize');
	});
//-->
</script>

<style>
.form-dioption-keyword .color,.form-dioption-fabu .color{color: #A5A5A5;}
.form-dioption-item, .form-cioption-item{margin:0;}
.form-dioption-keyword label input{vertical-align:middle;margin-right:5px;}
.form-dioption-keyword label{width:100px;line-height:22px;display:inline-block;}
#keywords-box .a{position:relative;}
.b{width:70px;height:18px;position:absolute;top:-3px;left:0;}
#hoge_edit_play{-moz-transition:all 0.3s ease-in;}
.form-middle-left{overflow:visible;}
#vod-title{margin-right:10px;padding:0;font-size:12px}
.form-title-option span{margin-top:2px;}
</style>
{if $formdata['id']}
<form  action="./run.php?mid={$_INPUT['mid']}&a=frame&menuid=189" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return  hg_toSubmit();">
	{template:unit/publish, 1, $formdata['column_id']}
	<div class="form-left">
        <div class="option-iframe-back-box"><a class="option-iframe-back">返回视频库</a></div>
        <div class="form-dioption">
		<div style="position:relative;"><h2>编辑视频</h2><p onclick="video_show();" title="显示、关闭视频/ALT+W" style="position:absolute;bottom:10px;right:10px;cursor:pointer;">视频预览/截屏<img style="margin: 0 0 2px 5px;" src="{$RESOURCE_URL}tuji/drop.png" /></p></div>
		<div class="form-edit-img">
			<div class="form-dioption-source-img">
				<a class="source-img"><img _src="{$formdata['source_img']}" id="pic_face" title="点击图片更换截图" /></a>
				<div class="source-img-box">
					<span></span>
					<ul id="add-img">
					{foreach $formdata['snap_img'] as $k => $v}
						<li class="snap-img"><div class="middle-img-wrap"><img src="{$v}" /></div></li>
					{/foreach}
						<li class="add-img-button">从电脑添加</li>	
						<div class="clear"></div>
					</ul>
				</div>
			</div>
			<div class="form-dioption-sort form-dioption-item"  id="sort-box">
				<label style="color:#9f9f9f;{if $sort_default_name == '选择分类'}display:none;{/if}">分类： </label><p style="display:inline-block;" class="sort-label" _multi="vod_media_node">{$sort_default_name}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
						<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
				<input type="hidden" value="{$sort_default}" name="update_sort_id" id="sort_id" />
			</div>
			    
		
			<div class="form-dioption-keyword form-dioption-item clearfix">
				<span class="keywords-del"></span>
				<span class="form-item" _value="添加关键字" id="keywords-box">
					<span class="keywords-start color">添加关键字</span>
					<span class="keywords-add">+</span>
				</span>
				<input name="keywords" value="{$formdata['keywords']}" id="keywords" style="display:none;"/>
			    </div>
			    <div class="form-dioption-fabu form-dioption-item">
				<a class="common-publish-button color overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
			    </div>
		</div>
	</div>
	</div>
	<div class="form-middle">
        <div class="form-middle-left">
        		<div class="vod-info-box clear">
				<div class="vod-info-item">
					 <input type="text" id="vod-title" value="{if $formdata['title']}{$formdata['title']}{else}请输入标题{/if}" name="title"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');" >
				</div>
				<div class="form-title-option clearfix">
					<span class="form-title-color"></span>
				        <span class="form-title-weight"></span>
				        <span class="form-title-italic"></span>
				</div>
				
				<input name="tcolor" type="hidden" value="{$formdata['tcolor']}" id="tcolor" />
                <input name="isbold" type="hidden" value="{if $formdata['isbold']}1{else}0{/if}" id="isbold" />
                <input name="isitalic" type="hidden" value="{if $formdata['isitalic']}1{else}0{/if}" id="isitalic" />
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom">
				<div class="vod-info-item">
					<textarea rows="5" name="comment"  id="comment" {if $formdata['comment']}{$formdata['comment']}{else}class="t_c_b"{/if}  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['comment']}{$formdata['comment']}{else}这里输入描述{/if}</textarea>
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom">
				<div class="vod-info-item">
					<label class="input-label" for="subtitle">副题</label>
					<input type="text" name="subtitle" id="subtitle"  value="{$formdata['subtitle']}" />
				</div>
				<div class="vod-info-item laiyuan" style="margin-right:10px;">
					<label class="input-label" >来源</label>
					{template:form/search_source,$source_id,$default,$sources,$item_up_source}
				</div>
				<div class="vod-info-item zz" style="float:left;">
					<label class="input-label">作者</label>
					<input type="text" name="author" id="author" value="{$formdata['author']}" />
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom" style="display: none">
				<div class="vod-info-item vod-info-subtitle">
					<label class="input-label" for="subtitle">选项</label>
					<!--<input type="text" name="keywords" id="keywords" value="{$formdata['keywords']}" />-->
					<div class="form-dioption-keyword clearfix">
						<label><input type="checkbox" value="name" />开发评论</label>
						<label><input type="checkbox" value="name" />自动台标</label>
						<label><input type="checkbox" value="name" />附加广告</label>
						<label><input type="checkbox" value="name" />允许打分</label>
						<label><input type="checkbox" value="name" />观看心情</label>
					</div>
				</div>
				
			</div>
			
			<input type="hidden" name="submit_type" id="submit_type"/>
     
        	<input type="submit" value="确定" class="button_6_14" style="margin-left:15px;margin-top:20px;" />
        </div>
        
        <div class="form-middle-right">
        	<div class="vod-details">
        			{if $formdata['is_fast_edit']}
						<a class="content_vodinfo_text"  href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$formdata['id']}&fast_edit=1{$_pp}" target="mainwin">源视频快编</a>
					{else}
						<a class="content_vodinfo_text"  href="javascript:void(0);"  onclick="alert('此视频已被标注，不可快编');" >源视频快编</a>
					{/if}
        		<ul>
        			<li>时长:<span>{$formdata['video_duration']}</span></li>
        			<li>文件大小:<span>{$formdata['video_totalsize']}</span></li>
        			<li>视频编码:<span>{$formdata['video']}</span></li>
        			<li>平均码流:<span>{$formdata['bitrate']}</span></li>
        			<li>视频帧率:<span>{$formdata['frame_rate']}</span></li>
        			<li>分辨率:<span>{$formdata['video_resolution']}</span></li>
        			<li>宽高比:<span>{$formdata['aspect']}</span></li>
        			<li>音频编码:<span>{$formdata['audio']}</span></li>
        			<li>音频采样率:<span>{$formdata['sampling_rate']}</span></li>
        			<li>声道:<span>{$formdata['video_audio_channels']}</span></li>
        			
        		</ul>
        	</div>
        	<div class="vod-option" data-tip="暂时隐藏" style="display:none;">
        		<p>选项:</p>
        		<ul>
                	<li><label><input type="checkbox" value="1" name="comment2out">开放评论</label></li>
                	<li><label><input type="checkbox" value="2" name="taibiao">自动台标</label></li>
                	<li><label><input type="checkbox" value="3" name="guanggao">附加广告</label></li>
                	<li><label><input type="checkbox" value="4" name="dafen">允许打分</label></li>
                	<li><label><input type="checkbox" value="5" name="xinqing">观看心情</label></li>
                </ul>
        	</div>
        </div>
	</div>
	<input type="file" id="file" style="display: none;" />
	<input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="{$formdata['source_img']}" />
	<input type="hidden"   id="source"  name="source"  value="" />
	<input type="hidden"   id="vod_sort_id"  name="vod_sort_id"  value="" />
	<input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
	<input type="hidden" name="img_src"  id="img_src"  value=""   />
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$_INPUT['mid']}" name="module_id" />
	<input type="hidden" value="{$formdata['total_num']}"  id="total_num" />
	<input type="hidden" value="{$formdata['page_num']}"  id="page_num" />
	<input type="hidden" value="{$formdata['id']}" name="id" id="edit_id" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	
	<div class="right_version"  style="overflow:hidden;display:none;">
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
</form>
<div id="hoge_edit_play" style="top:-378px;left:273px;">
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