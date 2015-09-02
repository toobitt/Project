<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=${ state == globalData.auditValue ? 0 : 1 }&id=${id}" 
				onclick="return hg_ajax_post(this, '{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}', 0, 'hg_change_status');">
				{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}
			</a>
		</div>
		<div class="record-edit-btn-area clear">
			{if $_configs['App_publishcontent']}
			<a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a>
			{/if}
			{if $_configs['App_share']}
			{{if !(expand_id == 0)}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=share_form&id=${_.values(pub_url)[0]}" onclick="return hg_ajax_post(this, '分享', 0);">分享</a>
			{{/if}}
			{/if}
			{if $_configs['App_special']}
			<a href="run.php?mid={$_INPUT['mid']}&a=special&id=${id}&infrm=1">专题</a>
			{/if}
			{if $_configs['App_block']}
			<a>区块</a>
			{/if}
			{if $_configs['video_cloud']['open']}
			
				{{if +is_link}}
				<a>已同步</a>
				{{else}}
				<a class="sync_letv" id="sync_letv${id}" data-size="${video_totalsize}" href="./run.php?mid={$_INPUT['mid']}&a=sync_letv&id=${id}">{$_configs['video_cloud']['title']}</a>
				{{/if}}
			{/if}
		</div>

		<!--add-->
		<div class="record-edit-btn-area clear">
		   <a href="./run.php?mid=2890&a=create&pid=${id}&pushType=vod">推送</a>
		</div>
		<!--add end-->

		{if $_configs['video_cloud']['open']}
		{{if !+is_link}}
		<div class="record-edit-btn-area clear sync_letv_progress_box" style="display:none;background:none;border:0;">
			<div style="background:#fff;height:4px;border-radius:4px;"><span class="sync_letv_progress" style="display:inline-block;vertical-align:top;border-radius:4px;height:100%;width:0;background:#5C99CF;"></span></div>
		</div>
		{{/if}}
		{/if}
		
		{{if catalog}}
		<div class="record-catalog-info">
			<span>编目信息</span>
			<ul>
			{{each catalog}}
				{{if _value }}
				<li><label>${_value.zh_name}：</label>
					{{if typeof( _value.value ) == 'string'}}
						<p>${_value.value}</p>
					{{else}}
						<p class="clear">
						{{each _value.value}}
							{{if _value.host}}
							<span class="record-edit-img-wrap"><img src="${_value.host}${_value.dir}${_value.filepath}${_value.filename}"></span>
							{{else}}
							<span>${_value}</span>
							{{/if}}
						{{/each}}
						</p>
					{{/if}}
				</li>
				{{/if}}
			{{/each}}
			</ul>
		</div>
		{{/if}}
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${img_info.host}${img_info.dir}135x65/${img_info.filepath}${img_info.filename})"></span>
				<span class="maliu-label">${bitrate}</span>
				<span class="record-edit-info-shower">详情</span>
			</div>
			<div>
				{{if is_link == 0}}
				<!-- 下载转码好的视频的功能必须排除:转码失败 转码中 已取消 已暂停 -->
				{{if status != -1}}
					{{if status != 0}}
						{{if status != 5}}
							{{if status != 4}}
							<a class="record-edit-btn" data-notouteriframe="true" href="${download}?id=${id}&access_token={$_user['token']}">下载</a>
							{{/if}}
						{{/if}}
					{{/if}}
				{{/if}}
				
				<!-- 重新转码视频的功能出现在:转码失败 已取消 并且排除标注归档的视频 -->
				{{if status == -1}}
					{{if vod_leixing != 4}}
					<a class="record-edit-btn retranscode" data-notouteriframe="true"  href="${retranscode_url}?id=${id}&access_token={$_user['token']}">重新转码</a>
					{{/if}}
				{{/if}}
				
				{{if status == 5}}
					{{if vod_leixing != 4}}
					<a class="record-edit-btn retranscode" data-notouteriframe="true"  href="${retranscode_url}?id=${id}&access_token={$_user['token']}">重新转码</a>
					{{/if}}
				{{/if}}
				
				<!-- 下载源视频 只要上传上传到mediaserver的视频都可以 所以此处不做限制 -->
				{{if vod_leixing != 4}}<a class="record-edit-btn" data-notouteriframe="true"  href="${download}?id=${id}&need_source=1&access_token={$_user['token']}">下载源</a>{{/if}}
				{{if object_id}}
                	<a class="record-edit-btn" target="_blank"  href="./download.php?a=right&object_id=${object_id}">版权信息</a>
                {{/if}}
				{if $_configs['technical_swdl']}
				<a class="record-edit-btn cancel">技审</a>
				{/if}
				
				
				<!-- 此处的快编与拆条 可以归纳为同为标注归档的操作 必须排除:转码失败 转码中 已取消 已暂停 -->
				{{if status != -1}}
					{{if status != 0}}
						{{if status != 4}}
							{{if status != 5}}
								{{if vod_leixing != 4}}
									{if $_configs['App_video_fast_edit']}
										<a class="record-edit-btn editor-hover"  href="./run.php?a=relate_module_show&app_uniq=video_fast_edit&mod_uniq=video_fast_edit&video_id=${id}{$_pp}" target="mainwin">快编</a>
									{/if}
								{{/if}}
								{{if is_allow == 0 }}
									{if $_configs['App_video_split']}
										<a class="record-edit-btn"  href="./run.php?a=relate_module_show&app_uniq=video_split&mod_uniq=video_split&video_id=${id}{$_pp}" target="mainwin">拆条</a>
									{/if}
								{{/if}}
							{{/if}}
						{{/if}}
					{{/if}}
				{{/if}}
				
				{{/if}}
			</div>
		</div>
		<div class="record-edit-line"></div>
		<div class="record-edit-info">
			{{if click_count != 0}}<span>访问:${click_count}</span>{{/if}}
			{{if downcount != 0}}<span>下载:${downcount}</span>{{/if}}
			{{if share_num != 0}}<span>分享:${share_num}</span>{{/if}}
		</div>
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

	<div class="push-edit-confirm">
    		<p>确定将该内容推送到CRE吗？</p>
    		<div class="record-edit-line"></div>
    		<div class="record-edit-confirm-btn">
    			<a class="push-btn">确定</a>
    			<a>取消</a>
    		</div>
    		<span class="push-edit-confirm-close"></span>
    </div>

	<div class="record-edit-play">
	</div>
	<div class="record-edit-more-info">
	</div>
</div>
  
<script type="tpl" id="vedio-tpl">
<div style="width:360px;height:300px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{{if is_link==1}}${swf}{{else}}{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713{{/if}}" width="360" height="300">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
    <!--<param name="flashvars" value="videoUrl=${video_m3u8}&autoPlay=true&aspect=${aspect}">-->
  <span class="record-edit-back-close"></span>
</script>

<!--<script type="tpl" id="vedio-tpl">
<video src="${hostwork}/${video_path}${video_filename}"  controls="controls" autoplay="autoplay" width="360" height="300">
	您的浏览器不支持 video 标签。
</video>
</script>-->
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
 	<li>是否经过强制转码:<span>${is_forcecode_ok}</span>{{if status}}{{if is_forcecode_ok=="否"}}<a class="force_recodec retranscode" _href="${retranscode_url}?id=${id}&access_token={$_user['token']}&force_recodec=1">强制转码</a>{{/if}}{{/if}}</li>
</ul>
<span class="record-edit-back-close"></span>
</script>
