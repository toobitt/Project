{foreach $formdata['column_info'] as $kk => $vv}
	{code}
	$modules[$vv['bundle']] = $vv['name'];
	{/code}
{/foreach}
{code}
$modules[0]="全部";
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['modules'])
{
	$_INPUT['modules'] = 0;
}
if(!$_INPUT['data_source'])
{
	$_INPUT['data_source'] = 2;
}

{/code}
<div class="choice-area">
	<form  id="searchform" action="" method="get">
		<div class="select-search">
			{code}
				
				$attr_modules = array(
					'class' => 'down_list',
					'show' => 'modules_show',
					'width' => 70,
					'state' => 0,
				);

				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'date_show',
					'width' => 70,
					'state' => 1,
				);
				
				$source = array(
					'class' => 'down_list data_source',
					'show' => 'data_source',
					'width' => 70,
					'state' => 0,
					'is_sub' => 1,
				);
				$column = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*/
					'width' => 90,/*列表宽度*/
					'place' =>'栏目搜索'
				);
			{/code}
			{template:form/search_source,data_source,$_INPUT['data_source'],$_configs['data_source'],$source}
			{template:form/search_source,modules,$_INPUT['modules'],$modules,$attr_modules}
			{template:form/search_weight}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
		</div>
		{template:form/search_input,column_name,$_INPUT['column_name'],1,$column}
		<div class="search-input-area">
	      <input type="text" name="k" class="search-k" value="{$_INPUT['k']}" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">
		  <input class="serach-btn" id="modules-search" type="submit" value="">
		 </div>
		 <input type="hidden" name="column_name" value="{$_INPUT['column_name']}" />
		  <input type="hidden" name="column_id" value="{$_INPUT['column_id']}" />
		  <input type="hidden" name="a" value="newslist" />
		  <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<script>
$(function(){
	(function($){
		var box = $('.weightPicker').find('.weight-box');
		$('.weightPicker').hover(function(){
			box.show();
			needHide = false;
		},function(){
			box.hide();
			needHide = true;
		});
	})($);
});
</script>