<div class="common-list-search" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
		{code}	
				$attr_mos = array(
					'class' => 'transcoding down_list',
					'show' => 'mo_type_show',
					'width' => 104,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$attr_group = array(
					'class' => 'transcoding down_list',
					'show' => 'node_type_show',
					'width' => 104,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$attr_source = array(
					'class' => 'transcoding down_list',
					'show' => 'source_type_show',
					'width' => 104,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$time_css = array(
					'class' => 'transcoding down_list',
					'show' => 'time_item',
					'width' => 104,	
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					'para'=> array('fid'=>$_INPUT['fid']),
				);
				$_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;
				$mos[-1] = '全部模块';
				$ops[-1] = '全部操作';
				$sos[-1] = '全部来源';
				$mos_default = $_INPUT['mos'] ? $_INPUT['mos'] : -1;
				$group_default = $_INPUT['ops'] ? $_INPUT['ops'] : -1;
				$sources = $_INPUT['sos'] ? $_INPUT['sos'] : -1;
				if( !$ops[$_INPUT['ops']] ){
					$group_default = -1;
				}
		{/code}	
		<!--{template:form/search_source,mos,$mos_default,$mos,$attr_mos}-->
		{template:form/search_source,ops,$group_default,$ops,$attr_group}
		{template:form/search_source,sos,$sources,$sos,$attr_source}
		{template:form/search_source,create_time,$_INPUT['create_time'],$_configs['date_search'],$time_css}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
		<input type="hidden" name="para" value="{$_INPUT['para']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
		<div class="custom-search">
			{code}
				$attr_ops_per = array(
					'class' => 'custom-item',
					'state' =>3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'操作人'
				);
			{/code}
			{template:form/search_input,ops_per,$_INPUT['ops_per'],1,$attr_ops_per}
		</div>
		<div class="custom-search">
			{code}
				$attr_ip = array(
					'class' => 'custom-item',
					'state' =>3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'ip地址'
				);
			{/code}
			{template:form/search_input,ip,$_INPUT['ip'],1,$attr_ip}
		</div>
	</form>
</div>