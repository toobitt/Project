<div class="search_a" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="right_1">        
			{code}
			$attr_source = array(
				'class' => 'transcoding down_list',
				'show' => 'transcoding_show',
				'width' => 104,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			$attr_date = array(
				'class' => 'colonm down_list data_time',
				'show' => 'colonm_show',
				'width' => 104,/*列表宽度*/
				'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			$_configs['video_upload_status'][-1] = '全部状态';
			
			$attr_is_finish = array(
				'class' => 'transcoding down_list',
				'show'  => 'is_finish_show',
				'width' => 45,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			$is_finish_default = 0;
			$is_finish_arr = array(
		 		-1 => '所有状态',
				 0 => '未完成',
				 1 => '完成'
			);
			{/code}
			{template:form/search_source,is_finish,$_INPUT['is_finish'],$is_finish_arr,$attr_is_finish}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
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