<div class="common-list-search" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
		{code}
                if(!class_exists('publishsys'))
                {
                    include_once(ROOT_DIR . 'lib/class/publishsys.class.php');
                    $publishsys = new publishsys();
                }
                //获取所有站点
                //$hg_sites = $publishsys->getallsites();
				$time_css = array(
					'class' => 'transcoding down_list',
					'show' => 'time_item',
					'width' => 120,	
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					'para'=> array('fid'=>$_INPUT['fid']),
				);
				$attr_site = array(
					'class'  => 'colonm down_list date_time',
					'show'   => 'app_show',
					'width'  => 104,
					'state'  => 0,
				);
				
				$attr_sort = array(
					'class'  => 'colonm down_list date_time',
					'show'   => 'sort_show',
					'width'  => 104,
					'state'  => 0,
				);
				
				$attr_moudle = array(
					'class'  => 'colonm down_list date_time',
					'show'   => 'moudle_show',
					'width'  => 104,
					'state'  => 0,
				);
				
				
				$_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;
				//获取所有站点
				$hg_sites = $publishsys->getallsites();
				if(!$_INPUT['site_id'])
				{
					$_INPUT['site_id'] = 1;
				}
				
				$hg_sites[0] = '所有站点';
				$template_sorts[0][-1] = '全部分类';
				$apps[0][-1] = '全部应用';

				$_INPUT['sort_id'] = $_INPUT['sort_id'] ? $_INPUT['sort_id'] : -1;
				$_INPUT['app_uniqueid']= $_INPUT['app_uniqueid'] ? $_INPUT['app_uniqueid'] : -1;
		{/code}	
		{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}
		{template:form/search_source,create_time,$_INPUT['create_time'],$_configs['date_search'],$time_css}
		{template:form/search_source,sort_id,$_INPUT['sort_id'],$template_sorts[0],$attr_sort}
		{template:form/search_source,app_uniqueid,$_INPUT['app_uniqueid'],$apps[0],$attr_moudle}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
	</form>
</div>