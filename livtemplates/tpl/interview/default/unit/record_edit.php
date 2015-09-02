<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}&num=${user_number}">删除</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=interview_authority&id=${id}&infrm=1">权限</a>
		</div>
		<div class="record-edit-btn-area clear">
		{foreach $_relate_module AS $kkk => $vvv}
			<a href="./run.php?mid={$kkk}&interview_id=${id}&infrm=1" >{$vvv}</a>
		{/foreach}
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info"></div>
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