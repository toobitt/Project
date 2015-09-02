{code}//hg_pre($list);{/code}
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="run.php?mid={$_INPUT['mid']}&a=form&id=${id}" target="formwin">编辑</a>
			<a href="run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
			<!-- 
			{{if status == 1}}
			<a  class="audit" data-id="${id}" _status="1" >打回</a> 
			{{else}}
			<a  class="audit" data-id="${id}" _status="2">审核</a>
			{{/if}}
			 -->
			{{if is_push == 1}}
			<a  class="push" data-id="${id}" _status="1">撤销推送</a>
			{{else}}
			<a  class="push" data-id="${id}" _status="0">推送</a>
			{{/if}}
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