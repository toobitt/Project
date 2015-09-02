{template:head}
{css:2013/m2o}
{css:2013/button}
{css:magazine_less}
{js:2013/list}
{js:2013/ajaxload_new}
{js:cinema/movie_list}
<script type="text/javascript">
gBatchAction['audit'] = './run.php?mid={$relate_module_id}&a=audit';
gBatchAction['back'] = './run.php?mid={$relate_module_id}&a=back';
</script>
{code}
//print_r($list);
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/movie_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin" class="button_6">新增影片</a>
	</div>
</div>
<div class="common-list-content">
	 <ul class="magazine-list clear">
	 	{foreach $list as $k => $v}
			{template:unit/movie_list_list}
		{/foreach}
	 </ul>
	 <div class="record-bottom m2o-flex m2o-flex-center">
	  	 <div class="record-operate">
	  	 	<input type="checkbox" name="checkall" class="checkAll" title="全选" />
	  	 	<a name="audit" data-method="audit" class="batch-audit">审核</a>
	  	 	<a name="back" data-method="audit" class="batch-back">打回</a>
	  	    <a name="delete" data-method="delete" class="batch-delete">删除</a>
	  	 </div>
	  	 <div class="m2o-flex-one">
	  	 {$pagelink}
	  	 </div>
	 </div>
</div>
{template:foot}