<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${img_info.host}${img_info.dir}135x65/${img_info.filepath}${img_info.filename})"></span>
				<span class="maliu-label">${bitrate}</span>
				<span class="record-edit-info-shower">详情</span>
			</div>
		</div>
		<div class="record-edit-line"></div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-play">
	</div>
	<div class="record-edit-more-info">
	</div>
</div>


<script type="tpl" id="vedio-tpl">
<div style="width:360px;height:300px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="360" height="300">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	
	    <param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
    
<!--
    <param name="flashvars" value="videoUrl=${video_m3u8}&autoPlay=true&aspect=${aspect}">
-->
  </object>
</div>
  <span class="record-edit-back-close"></span>
</script>
<script type="tpl" id="record-info-tpl">
	<ul>
        <li>时长:<span>${video_duration}</span></li>
        <li>文件大小:<span>${video_totalsize}</span></li>
        <li>视频编码:<span>${video}</span></li>
        <li>平均码流:<span>${bitrate}</span></li>
        <li>视频帧率:<span>${frame_rate}</span></li>
        <li>分辨率:<span>${video_resolution}</span></li>
        <li>宽高比:<span>${aspect}</span></li>
        <li>音频编码:<span>${audio}</span></li>
        <li>音频采样率:<span>${sampling_rate}</span></li>
        <li>声道:<span>${video_audio_channels}</span></li>	
		<li>视频来自与应用:<span>${app_uniqueid}</span></li>		
        <li>是否是物理文件:<span>${isfile_name}</span></li>			
        <li>是否经过多码流处理:<span>${is_do_morebit}</span></li>			
        <li>多码流处理是否成功:<span>${is_morebitrate_ok}</span></li>			
 		<li>是否经过强制转码:<span>${is_forcecode_ok}</span></li>
		<li>导出是否需要文件:<span>{if need_file}是{else}否{/if}</span></li>
		<li title="${export_dir}" style="display: -webkit-box;">导出位置:<span style="display: block;width: 260px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">{if export_dir}${export_dir}{else}未导出{/if}</span></li>	
	</ul>
	<span class="record-edit-back-close"></span>
</script>