{template:head}
{css:vod_style}

<style>
/*隐藏创建二级分类*/
.column-inner-box .column-each{display:none;}
.column-inner-box .column-each:first-child{display:block;}
</style>

<div id="hg_page_menu" class="head_op_program controll-area"{if $_INPUT['infrm']} style="display:none"{/if}>
	
</div>
{template:unit/sort, template_sort, $sort_list}

{template:foot}