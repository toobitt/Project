<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="run.php?mid={$_INPUT['mid']}&a={{if outlink}}form_outerlink{{else}}form{{/if}}&id=${id}&infrm=1" target="nodeFrame">编辑</a>
			<a href="run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
		</div>
		<div class="record-edit-btn-area clear">
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info">
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
</div>