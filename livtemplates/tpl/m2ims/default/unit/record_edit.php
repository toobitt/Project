<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=registerMmobjct&id=${id}" class="regsend" onclick="return hg_ajax_post(this, '注册至IMS', 0);">注册至IMS</a>
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${img_info.host}${img_info.dir}135x65/${img_info.filepath}${img_info.filename})"></span>
			</div>
		</div>
		<span class="record-edit-close"></span>
	</div>
	
	<div class="record-edit-play">
		
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
 	</object>
</div>
<span class="record-edit-back-close"></span>
</script>
