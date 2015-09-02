{js:special/column_move}
<!-- 专题内容移动框 -->
<div id="move-column-pop" data-speid="{$speid}">
	<div class="common-list-pub-title">
		<p>正在移动</p>
		<div>
			<p style="max-width:100px;" class="overflow title-show">标题</p><span>共<span class="title-number">1</span>条</span>
			<div class="title-list" style="max-width:190px;overflow:hidden;">
				<p>标题</p>
			</div>
		</div>
	</div>
	<form action="run.php?mid={$_INPUT['mid']}&a=ch_column" method="post">
		<div class="move-column-body">
			<ul></ul>
		</div>
	<input type="hidden" class="hidden-id" name="id" value="" />
	<input type="hidden" class="old-columnid" name="old_columnid" value="" />
	<input type="hidden" name="speid" value="{$speid}" />
	<input type="hidden" class="bundle-id"  name="bundle_id" value="" />
	<input type="hidden" class="module-id"  name="module_id" value="" />
	<input type="hidden" class="content-fromid"  name="content_fromid" value="" />
	<input type="hidden" class="hidden-name" name="column_name" value="" />
	<input type="hidden" class="c-id" name="cid" value="" />
	<div><span class="publish-box-save">保存</span></div>
	<span class="common-list-pub-close"></span>
	</form>
	<span class="common-list-pub-close"></span>
</div>
<script type="text/x-jquery-tmpl" id="column-tmpl-list">
	<li class="column-item-each">
		<input type="radio" name="column_id" value="${id}" />
		<span class="name">${column_name}</span>
	</li>
</script>