<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
		</div>
		
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${preview})">
					<img src="${preview}" width="135" height="65" />
				</span>
				<span class="maliu-label"></span>
			</div>
			<div>
				
			</div>
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
	<div class="record-edit-play">
	</div>
</div>
<textarea style="display:none;" type="tpl" id="vedio-tpl">
  <div id="flashBox" style="width:360px;height:300px;">
  </div>
  <script>
  setSwfPlay('flashBox', "${channel_stream[0].output_url}", '360', '300', 100, 'flashBox');
  </script>
  <span class="record-edit-back-close"></span>
</textarea>