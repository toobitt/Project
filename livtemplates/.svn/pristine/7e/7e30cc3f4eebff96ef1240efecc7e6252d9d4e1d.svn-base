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
{if !ISIOS && !ISANDROID}
<div style="position:relative;z-index:300;"> 
   <object id="CUTV_PLAYER_0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="400" height="330">
	<param name="movie" value="http://www.cutv.com/static/player/v.swf">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="opaque">
	<param name="flashvars" value="id={$formdata['maid']}&tvie=media-api.cutv.com&hd=false&keyword=&autoplay=false&norecomm=true">
  	<embed type="application/x-shockwave-flash"  flashvars="id={$formdata['maid']}&tvie=media-api.cutv.com&hd=false&keyword=&autoplay=false&norecomm=true&allowFullScreen=true" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash"  allowscriptaccess="always"  allowfullscreen="true" wmode="transparent"  src="http://www.cutv.com/static/player/v.swf"  name="CUTV_PLAYER_0"  style= "width:400px;height:340px;outline:0">
  </object>
</div>
 {else}
	<video id="phonehtmt5player" width="400" height="330" controls="controls" autoplay="autoplay"></video>
  {/if}
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		{if $formdata['indexpic']}
		<li><a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}&indexpic={$formdata['indexpic']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a></li>
		{else}
		<li><a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a></li>
		{/if}
	</ul>
</div>
<div class="info clear bj">
	<h4 onclick="hg_slide_up(this,'video_info')" ><span title="展开\收缩\ALT+W"  class="b2"></span>文件属性</h4>
	<ul id="video_info"  class="clear" style="display:none;">
		<li><span>时长：</span>{$formdata['format_duration']}</li>
		<li><span>平均码流：</span>{$formdata['bitrate']}</li>
	</ul>
</div>
{else}
此视频已经不存在,请刷新页面更新
{/if}

<script>
jQuery(function($){
    $('.chaitiao-option-iframe').click(function(){
        top.$('#livwinarea').trigger('iopen', [{
            src : $(this).attr('href'),
            gMid: gMid
        }]);
        return false;
    });
});
</script>