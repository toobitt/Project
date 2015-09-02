{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
  <div style="width:400px;height:300px;border:1px solid #E0E0E0;overflow:auto;background:#ffffff;">
  	{if $formdata['status']}
  		<ul>
  		{foreach $formdata['status'] AS $k => $v}
  			<li style="margin-top:10px;position:relative;">
  				<div style="width:80px;height:15px;float:left;line-height:15px;">视频id:{$v['id']}</div>
  				<div style="width:32px;height:15px;float:left;line-height:15px;">进度:</div>
  				<div class="trans-jdt">
  					<div style="width:{$v['transcode_percent']}%;" class="trans-progess"></div>
  				</div>
  				<div style="width:32px;height:15px;float:left;line-height:15px;margin-left:5px;">{$v['transcode_percent']}%</div>
  			</li>
  		{/foreach}
  		</ul>
  	{else}
  	<div style="color:red;font-size:16px;font-weight:bold;height:50px;width:200px;line-height:50px;margin:0px auto;margin-top: 108px;">没有检测到视频正在转码！</div>
  	{/if}
  	
  </div>
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
{else}
该转码服务器已经不存在，请刷新页面
{/if}