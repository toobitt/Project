<?php 
/* $Id: template_list.php*/
?>
{template:head}
{css:2013/iframe}
{css:2013/list}
{css:program_day}
{css:common/common_category}
{js:program/program_template}
{code}
//print_r($list);
{/code}
<div style="display:none">
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a type="button" class="button_6" href="run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=form" target="mainwin">新增节目模板</a>
		<!-- <a class="gray mr10" href="run.php?mid={$_INPUT['main_mid']}&a=frame" target="mainwin">返回节目单</a> -->
	</div>
</div>
<div class="wrap common-list-content">
	 <ul class="template-list clear">
	 	{foreach $list as $k=> $temp}
	 	<li class="template-each" data-id="{$temp['id']}">
	 		<div class="template-img">
	 			{code}
	 				$index_img = '';
	 				if($temp['indexpic']){
	 			 		$index_img = $temp['indexpic']['host'].$temp['indexpic']['dir'].'190x190/'.$temp['indexpic']['filepath'].$temp['indexpic']['filename'];
	 				}
	 			{/code}
	 			{if $index_img}
					<img src="{$index_img}"/>
				{/if}
	 		</div>
	 		<div class="template-info">
	 			<span>{$temp['title']}</span>
	 			<a class="edit-temp" title="编辑模板" href="run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=form&id={$temp['id']}" target="mainwin"></a>
	 			<a class="del-temp" title="删除模板">删除</a>
	 		</div>
	 	</li>
	 	{/foreach}
	 </ul>
	  <div class="record-bottom m2o-flex m2o-flex-center">
	  	 <div class="record-operate">
	  	 	<input type="checkbox" name="checkall" class="checkAll" title="全选" />
	  	    <a name="delete" data-method="delete" class="batch-delete">删除</a>
	  	 </div>
	  	 <div class="m2o-flex-one">
	  	 {$pagelink}
	  	 </div>
	 </div>
</div>