{css:common/common_publish}
{js:jqueryfn/jquery.tmpl.min}
{js:common/move_publish}
<style>
.common-hg-publish .publish-result ul, .common-hg-publish .publish-result-empty{height:190px;}
</style>

<div class="publish-box common-hg-publish" id="publish-box-{$hg_name}">
	<div class="publish-result" >
		<p class="publish-result-title" _title="移动">移动至：</p>
		<ul>

		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>

	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
		</div>
	</div>
	<input type="hidden" class="publish-hidden" name="node_id" value="" />
	<input type="hidden" class="publish-name-hidden" name="content_id" value="" />
</div>