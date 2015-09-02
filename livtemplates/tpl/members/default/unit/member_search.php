<!-- 搜索 -->
<div class="search_a" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="right_1">
		{code}		
			if(!isset($_INPUT['status']))
			{
				$_INPUT['status'] = -1;
			}
			
			$attr_state = array(
				'class' => 'transcoding down_list',
				'show' => 'state_show',
				'width' => 90,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				'is_sub'=> 0,
			);
			
			$attr_date = array(
				'class' => 'colonm down_list data_time',
				'show' => 'colonm_show',
				'width' => 104,/*列表宽度*/
				'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			
			$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;

			$member_type_style = array(
				'class' => 'colonm down_list data_time',
				'show' => 'member_type_show',
				'width' => 104,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				'is_sub'=> 0,
			);
			if(!isset($_INPUT['member_type']))
			{
			    $_INPUT['member_type'] = -1;
			}

			$member_type[-1] = '所有类型';
			if(is_array($get_member_type)&&$get_member_type)
			{
				foreach($get_member_type as $k=>$v)
				{
					$member_type[$v['type']] = $v['type_name'];
				}
			}
				$memberapp_style = array(
				'class' => 'colonm down_list data_time',
				'show' => 'memberapp_show',
				'width' => 104,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				'is_sub'=> 0,
			);
			if(!isset($_INPUT['member_appid']))
			{
			    $_INPUT['member_appid'] = -1;
			}
			$memberapp_info[-1] = '所有来源';
			if(is_array($get_member_app)&&$get_member_app)
			{
				foreach($get_member_app as $k=>$v)
				{
					$memberapp_info[$v['appid']] = $v['appname'];
				}
			}
			
				$membermedal_style = array(
				'class' => 'colonm down_list data_time',
				'show' => 'membermedal_show',
				'width' => 104,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				'is_sub'=> 0,
			);
			if(!isset($_INPUT['medalid']))
			{
			    $_INPUT['medalid'] = -1;
			}
			$membermedal[-1] = '所有勋章';
			if(is_array($medal_info)&&$medal_info)
			{
				foreach($medal_info as $k=>$v)
				{
					$membermedal[$v['id']] = $v['name'];
				}
			}
		{/code}
			
			{template:form/search_source,status,$_INPUT['status'],$_configs['member_state'],$attr_state}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:form/search_source,member_type,$_INPUT['member_type'],$member_type,$member_type_style}
			{template:form/search_source,member_appid,$_INPUT['member_appid'],$memberapp_info,$memberapp_style}
			{template:form/search_source,medalid,$_INPUT['medalid'],$membermedal,$membermedal_style}
					
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="right_2">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
	</form>
</div>