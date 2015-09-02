<div class="common-list-search" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
		{code}	
		if(!class_exists('column'))
		{
		    include_once(ROOT_DIR . 'lib/class/column.class.php');
		    $publish = new column();
		}
        if(!class_exists('publishsys'))
        {
            include_once(ROOT_DIR . 'lib/class/publishsys.class.php');
            $publishsys = new publishsys();
        }
		//获取所有站点
		//$hg_sites = $publish->getallsites();
        $hg_sites = $publishsys->getallsites();
		$_INPUT['site_id'] = $_INPUT['site_id'] ? $_INPUT['site_id'] : 1;
		
		$attr_site = array(
			'class'  => 'colonm down_list date_time',
			'show'   => 'app_show',
			'width'  => 104,
			'state'  => 0,
		);
		{/code}	
		{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
	</form>
</div>