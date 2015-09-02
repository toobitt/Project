<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}" class="regsend" onclick="return hg_ajax_post(this, '编辑', 0);">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" class="regsend" onclick="return hg_ajax_post(this, '删除', 0);">删除</a>
		</div>
		<div class="record-edit-line mt20"></div>
		<span class="record-edit-close"></span>
	</div>
	
	<div class="record-edit-play">
		
	</div>
	
</div>