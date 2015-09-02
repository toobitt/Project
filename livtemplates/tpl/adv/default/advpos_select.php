{code}
		/*select样式*/
		$pos_style = array(
		'class' => 'down_list i select_margin',
		'show' => 'advpos_ul_' . $id,
		'width' => 200,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_advpos_para(' . $id . ');hg_ad_check(' . $id . ')',
		'more'=>$id,
		);
		$default = 0;
{/code}
{template:form/search_source,advpos,$default,$formdata,$pos_style}