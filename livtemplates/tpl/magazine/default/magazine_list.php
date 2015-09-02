{template:head}
{css:2013/m2o}
{css:2013/button}
{css:magazine_less}
{js:2013/list}
{js:2013/ajaxload_new}
{js:magazine/magazine-list}
{js:magazine/magazine-add}
<script type="text/javascript">
/*杂志的审核和打回比较特殊，它不针对自己，而是针对属于它的最新期刊，所以改下mid*/
gBatchAction['audit'] = './run.php?mid={$relate_module_id}&a=audit';
gBatchAction['back'] = './run.php?mid={$relate_module_id}&a=back';
</script>
{code}
//print_r($list);
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/magazine_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<!-- <a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin">添加杂志</a> -->
		<!-- <a class="add-button news mr10" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=maga_article&mod_a=show_last_issue&infrm=1" target="mainwin">快捷管理</a> -->
	</div>
</div>

{template:list/ajax_pub}
<div class="common-list-content">
	 <ul class="magazine-list clear">
	 	{foreach $list as $k => $v}
			{template:unit/magazinelist}
		{/foreach}
		<li class="magazine-add">
      		<div class="mag-img pop-add" data-type="magazine">新增杂志</div>
      	</li>
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
	 <div class="pop-add-mag pop-hide" id="add-magazine">
		 <form class="common-list-form" action="./run.php?mid={$_INPUT['mid']}&a=create&ajax=1" method="post" >
			{template:unit/add-magazine}
		</form>
 	</div>
</div>
<script type="text/x-jquery-tmpl" id="magadd-tpl">
	<li class="magazine-each" data-id="${id}" data-issueid="${issue_id}">
		<input type="checkbox" name="infolist[]" value="${id}" class="m2o-check" />
		<div class="mag-img mag-noImg">
			<a class="newest-href" title="进入往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id=${id}&cur_nper=${current_nper}&infrm=1" target="mainwin">暂无封面</a>
			<p><em class="m2o-state" data-method="audit" _id="${id}" _status="0" style="color:#8ea8c8;" >待审核</em>${sort_name}/${release_cycle}</p>
			<!-- <a class="newest-href" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=maga_article&mod_a=show_last_issue&cur_nper=${current_nper}&infrm=1" target="mainwin"></a>
			<a class="period-href" title="往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id=${id}&cur_nper=${current_nper}&infrm=1" target="mainwin"></a> -->
		</div>
		<h4>${tname} {{if current_nper}}第${current_nper}期{{/if}}</h4>
		<p><span>${user_name}</span>${create_time}</p>
		<a class="del" data-method="delete"></a>
	</li>
</script>
{template:foot}