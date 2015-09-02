{code}//hg_pre($list);{/code}
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
			{{if state == 2}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id=${id}">打回</a> 
			{{else}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id=${id}">审核</a>
			{{/if}}
		</div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${video_preview.host}${video_preview.dir}135x65/${video_preview.filepath}${video_preview.filename})"></span>
			</div>
			<div>
				<!-- 下载转码好的视频的功能必须排除:转码失败 转码中 已取消 已暂停 -->
				<a class="record-edit-btn" data-notouteriframe="true" href="${material.host}${material.dir}${material.filepath}${material.filename}">下载</a>
				
			</div>
		</div>
		<div class="record-edit-line mt20"></div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
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
	<param name="flashvars" value="videoUrl=${m3u8}&autoPlay=true&aspect">
  </object>
</div>
    <!--<param name="flashvars" value="videoUrl=${m3u8}&autoPlay=true&aspect=${aspect}">-->
  <span class="record-edit-back-close"></span>
</script>