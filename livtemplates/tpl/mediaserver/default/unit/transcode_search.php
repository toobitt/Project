<span class="serach-btn"></span>
	<form target="mainwin" name="searchform" id="searchform" action="" method="get">
	    <div class="key-search">
	      {template:form/search_input,k,$_INPUT['k']}
	      <input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;">
	    </div>
		<div class="select-search">
			{code}
				if(!isset($_INPUT['date_search']))
				{
				    $_INPUT['date_search'] = 1;
				}
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					'is_sub'=> 0,
				);
			{/code}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
	</form>