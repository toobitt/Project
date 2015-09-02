<div class="common-list-search" id="info_list_search" style="display:none;">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
					{code}
					//获取所有站点
					$hg_sites = $publish->getallsites();
					//foreach($hg_sites as $k=>$v)
					//{
                    //    if(!$_INPUT['site_id'])
                    //    {
                    //        $_INPUT['site_id'] = $k;
                    //    }
					//	$hg_sites_[$k] = $v;
					//}
                    //$site_id = $_INPUT['site_id'];
					$attr_site = array(
					'class'  => 'colonm down_list',
					'show'   => 'app_show',
					'width'  => 90,
					'state'  => 0,
					//'href' => './run.php?a=frame&mid='.$_INPUT['mid'].'&infrm=1&fid=',
					);
						$attr_client = array(
							'class' => 'transcoding down_list',
							'show' => 'client_type_show',
							'width' => 90,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						foreach($content[0]['client'] AS $k => $v)
						{
							$client_arr[$k] = $v['name'];
						}
						$client_arr[-1] = '终端';
						
						$attr_app = array(
							'class' => 'transcoding down_list',
							'show' => 'sort_type_show',
							'width' => 90,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						
						$app_arr = array();
						$app_arr['all'] = '应用';
						foreach($content[0]['app_data'] AS $k => $v)
						{
							$app_arr[$v['bundle']] = $v['name'];
						}
						
						$attr_appchild = array(
							'class' => 'transcoding down_list',
							'show' => 'appchild_type_show',
							'width' => 90,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						
						$appchild_arr = array();
						$appchild_arr['all'] = '模块';
						foreach($content[0]['appchild_data'] AS $k => $v)
						{
							$appchild_arr[$v['bundle']] = $v['name'];
						}
						
						$order_fieldarr = array(
							'class' => 'transcoding down_list',
							'show' => 'order_field_show',
							'width' => 90,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						$_configs['order_field'][-1] = '排序方式';
						$attr_create_date = array(
							'class' => 'colonm down_list data_time',
							'show' => 'create_show',
							'time_name' =>'create',
							'width' => 90,/*列表宽度*/
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
						$attr_publish_date = array(
							'class' => 'colonm down_list data_time',
							'show' => 'publish_show',
							'time_name' =>'publish',
							'width' => 90,/*列表宽度*/
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
					{/code}
					{template:site/new_site_search, site_id, $site_id , $hg_sites}
					{template:form/search_source,client_type,$_INPUT['client_type'],$client_arr,$attr_client}
					{template:form/search_source,con_app,$_INPUT['con_app'],$app_arr,$attr_app}
					{template:form/search_source,con_appchild,$_INPUT['con_appchild'],$appchild_arr,$attr_appchild}
					{template:form/search_source,order_field,$_INPUT['order_field'],$_configs['order_field'],$order_fieldarr}
					{template:form/search_source,create_date_search,$_INPUT['create_date_search'],$_configs['create_date_search'],$attr_create_date}
					{template:form/search_source,publish_date_search,$_INPUT['publish_date_search'],$_configs['publish_date_search'],$attr_publish_date}
					{template:form/search_weight}
					<!-- <input type="hidden" name="a" value="show" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> -->
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
					<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
            <div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'创建人'
				);
				$attr_cusotm = array(
					'class' => 'custom-item',
					'search_btn' => 1, /*添加搜索按钮*/
					'place' =>'发布人',
				);
			{/code}
			{template:form/search_input,create_user,$_INPUT['create_user'],1,$attr_creater}
			{template:form/search_input,publish_user,$_INPUT['publish_user'],1,$attr_cusotm}
		</div>
	</form>        
</div>
