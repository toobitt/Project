
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="nodeFrame">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			{{if status == 1}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id=${id}">打回</a> 
			{{else}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id=${id}">审核</a>
			{{/if}}
		</div>
		<div class="record-edit-btn-area clear">
			<a class="options-publish" data-id="${id}">发布</a>
			<!-- <a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a> -->
			<a class="ticket_sale" data-state="1" data-id="${id}">预售</a>
			<a class="ticket_sale" data-state="2" data-id="${id}">出售</a>
			<a class="ticket_sale" data-state="3" data-id="${id}">结束</a>
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
</div>
