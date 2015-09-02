{code}
foreach (array(
	'article_status', 'start_time', 'end_time', 'date_search',
	'start_weight', 'end_weight', 'key'
) as $each) { $search_conditions[$each] = $_INPUT[$each]; }
{/code}
<div class="common-list-search" id="info_list_search">
	<span class="serach-btn"></span>
	<form target="nodeFrame" name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
			{code}
				$attr_status=array(
					'class' => 'colonm down_list data_time',
					'show' => 'status_show',
					'width' =>90,
					'state' =>0,
				);
				
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 90,/*列表宽度*/
					'state' => 1 /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*/
				);
				$type_search = array(0=>'新闻分类', 1=>'网站栏目', 2=>'手机栏目');
				$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
			{/code}
			{code}
				$column_default = $_INPUT['pub_column_id'] ? $_INPUT['pub_column_id'] : 0;
				if( $column_default ==0 ) {
					$column_list = 	array(
						0 => '栏目'
					);
				}else{
					$column_list = split(',', $_INPUT['pub_column_name'] );
				}
				$attr_column = array(
					'class' => 'pub_column_search down_list',
					'show' => 'pub_column_show',
					'select_column' => $_INPUT['pub_column_name'],
					'width' => 90,/*列表宽度*/
					'state' => 4 /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*,4--栏目搜索*/
				);
			{/code}
			{template:form/search_source,article_status,$_INPUT['article_status'],$_configs['article_status'],$attr_status}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:form/search_source,pub_column_id,$column_default,$column_list,$attr_column}
			
			{template:form/search_weight}
			
			<input type="hidden" name="a" value="show" /> 
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> 
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="node_en" value="{$_INPUT['node_en']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" /> 
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
			</div>
			{template:form/search_input,key,$_INPUT['key']}
		</div>
		<div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*/
					'width' => 90,/*列表宽度*/
					'place' =>'添加人'
				);
				$attr_author = array(
					'class' => 'custom-item',
					'state' => 3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*/
					'place' =>'作者',
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
			{template:form/search_input,author,$_INPUT['author'],1,$attr_author}
		</div>
<style>

</style>
	</form>
</div>
