<!-- 移动框 -->
<style>
.result-tip {overflow: hidden; z-index: -1; position: absolute;top: 170px;left: 50%;font-size: 25px;background: #ffffff;text-align: center;margin-left: -200px;
border-radius: 4px;min-width: 240px;width:auto;height: 60px;border: 4px solid #6ba4eb;-webkit-box-shadow: 0 0 4px #cccccc #000000; -moz-box-shadow: 0 0 4px #cccccc #000000;
box-shadow: 0 0 4px #cccccc #000000;line-height: 60px;-webkit-transition: all 3s linear;-moz-transition: all 3s linear;-o-transition: all 3s linear;
-ms-transition: all 3s linear;transition: all 3s linear;opacity: 0;}
</style>
<div id="move_box_publish" class="common-list-ajax-pub">
	<div class="common-list-pub-title">
		<p>正在移动</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<div id="move_body">
		<form  action="run.php?mid={$_INPUT['mid']}&a=move" method="post" >
			{template:unit/list_move}
			<div><span class="publish-box-save">保存</span></div>
		</form>
		<input type="hidden" name="id" value="">
	</div>
	<span class="common-list-pub-close"></span>
</div>
