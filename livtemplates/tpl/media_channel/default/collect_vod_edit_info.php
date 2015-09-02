<script type="text/javascript">
  /*var tp_id = "{$formdata['id']}";*/
  var vs = hg_get_cookie('video_subinfo');
  var vi = hg_get_cookie('video_info');
  var vc = hg_get_cookie('video_collect');
  $(document).ready(function(){
	$('#video_subinfo').css('display',vs?vs:'block');
	$('#video_info').css('display',vi?vi:'none');
	$('#video_collect').css('display',vc?vc:'none');
  });
</script>
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="400" height="330">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['vodid']}&snap=false&aspect={$formdata['aspect']}&autoPlay=false&snapUrl={$formdata['snapUrl']}">
  </object>
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1{if $formdata['frame_type']}&_type={$formdata['frame_type']}{/if}{if $formdata['frame_sort']}&_id={$formdata['frame_sort']}{/if}">编辑</a>
		</li>
		<li>
			<a class="button_4" onclick="hg_removeCollectVideo({$formdata['id']});"  title=""  href="javascript:void(0);">移除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=move&id={$formdata['id']}"  onclick="return hg_ajax_post(this, '移动', 0);">移动</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=1" onclick="return hg_ajax_post(this, '审核', 0,'hg_change_status');">审核</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=0" onclick="return hg_ajax_post(this, '打回', 0,'hg_change_status');">打回</a>
		</li>
		{if !$formdata['is_allow']}
		<li style="clear:left;">
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$formdata['id']}{$_pp}" target="mainwin">{if $formdata['vod_leixing'] == 4}重标注{else}标注{/if}</a>
		</li>
		{/if}
		<li><a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a></li>
		<!-- 
		<li style="margin-left:4px"><a class="button_6" href="javascript:void(0);" onclick="hg_showAddToCollect({$formdata['id']});">添加至集合</a></li>
		 -->
		<li><a class="button_4" href="download.php?a=video&amp;api={$__api}&amp;f=vod_down.php&amp;id={$formdata['id']}&amp;title={$formdata['title']}">下载</a></li>
	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'video_subinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="video_subinfo" class="clear">
		<li class="h"><span>分类：</span>{$formdata['vod_leixing_name']}{if $formdata['sort_name']} » {$formdata['sort_name']}{/if}</li>
		<li class="h"><span>来源：</span>{$formdata['channel_name']}</li>
		<li class="w"><span>点击次数：</span>{$formdata['click_count']}次</li>
		{if $default[$formdata['id']]}
		<li class="w"><span>发布至：</span>
		{if $default[$formdata['id']][1]}
		<div class="earth"></div>
			 {foreach $default[$formdata['id']][1] as $v}
			 <span>{$v}</span>
			 {/foreach}
		{/if}
		{if $default[$formdata['id']][2]}
		<div class="iphone"></div>
			 {foreach $default[$formdata['id']][2] as $v}
			 <span>{$v}</span>
			 {/foreach}
		{/if}
		</li>
		{/if}
		{if $formdata['subtitle']}<li class="w"><span>副标题：</span>{$formdata['subtitle']}</li>{/if}
		{if $formdata['keywords']}<li class="w"><span>关键字：</span>{$formdata['keywords']}</li>{/if}
		{if $formdata['comment']}<li class="w"><span>描述：</span>{$formdata['comment']}</li>{/if}
	</ul>
</div>
<div class="info clear bj">
	<h4 onclick="hg_slide_up(this,'video_info')" ><span title="展开\收缩\ALT+W"  class="b2"></span>文件属性</h4>
	<ul id="video_info"  class="clear" style="display:none;">
		<li><span>时长：</span>{$formdata['format_duration']}</li>
		<li><span>文件大小：</span>{$formdata['totalsize']}</li>
		<li><span>平均码流：</span>{$formdata['bitrate']}</li>
		<li><span>视频编码：</span>{$formdata['video']}</li>
		<li><span>分辨率：</span>{$formdata['resolution']}</li>
		<li><span>宽高比：</span>{$formdata['aspect']}</li>
		<li><span>视频帧率：</span>{$formdata['frame_rate']}</li>
		<li><span>音频编码：</span>{$formdata['audio']}</li>
		<li><span>音频采样率：</span>{$formdata['sampling_rate']}</li>
		<li><span>声道：</span>{$formdata['audio_channels']}</li>
		<li><span>是否是物理文件：</span>{$formdata['isfile']}</li>
		{if $formdata['vod_leixing'] == 4}<li><span>该标注所包含视频片段数：</span>{$formdata['video_count']}</li>{/if}
	</ul>
</div>

<div class="info clear"  style="display:none;">
	<h4><span title="展开\收缩"></span>多码流选择</h4>
</div>
<div class="info clear">
	<h4 onclick="hg_slide_up(this,'video_collect')"><span title="展开\收缩"   class="b2"></span>所属集合</h4>
	<ul id="video_collect" class="clear" style="display:none;">
	  {if $formdata['collects']}
	    {foreach $formdata['collects'] as $k => $v}
	        <li><span><a href="./run.php?mid={$formdata['collect_module_id']}&collect_id={$k}&infrm=1">{$v}</a></span></li>
	    {/foreach}
	  {else}
	        <li><span>该视频不在任何集合中</span></li>
	   {/if}
	</ul>
</div>
{else}
此视频已经不存在,请刷新页面更新
{/if}