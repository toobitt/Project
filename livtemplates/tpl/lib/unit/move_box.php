<!-- 专题内容移动框 -->
<div id="move_publish">
	<div class="common-list-pub-title">
		<p>正在移动</p>
		<div>
			<p style="max-width:250px;" class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<form action="run.php?mid={$_INPUT['mid']}&a=ch_column" method="post">
	{template:unit/move_push}
	<input type="hidden" name="id" value="" />
	<div><span class="publish-box-save">保存</span></div>
	<span class="common-list-pub-close"></span>
	</form>
	<span class="common-list-pub-close"></span>
</div>