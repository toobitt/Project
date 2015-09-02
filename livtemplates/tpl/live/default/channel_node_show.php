<div id="channel_node_box">
	{code}
	$channel_id	  = $formdata['channel_id'];
	$node_id 	  = $formdata['node_id'];
	$channel_node = $formdata['channel_node'];
	
		$attr_channel_node = array(
			'class' => 'down_list i',
			'show' => 'node_show_',
			'width' => 100,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			'is_sub'=> 1,
			'onclick' => 'hg_channel_node(this);',
		);
		
		$defualt_node_id = $node_id ? $node_id : 0;
		$node[0] = '未分类';
		if (!empty($channel_node))
		{
			foreach($channel_node AS $kk =>$vv)
			{
				$node[$vv['id']] = $vv['name'];
			}
		}
		
	{/code}
	{template:form/search_source,node_id,$defualt_node_id,$node,$attr_channel_node}
</div>