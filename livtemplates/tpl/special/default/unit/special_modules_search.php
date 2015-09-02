<div class="choice-area">
	<form target="nodeFrame"  id="modules_searchform" action="" method="post">
	    <ul class="modules-searlist">
	       <li class="menu">
	           <span class="current">{$_configs['data_source'][2]}</span>
	           {if $_configs['data_source']}
	            <div class="item-conlist" id="publish-area">
	            {foreach $_configs['data_source'] as $k => $v}
	                <span _id="{$k}" class="item">{$v}</span>
	            {/foreach}
	            </div>
	           {/if}
	       </li>
	    </ul>
		<div class="select-search">
			{code}
				if(!class_exists('column'))
				{
				    include_once(ROOT_DIR . 'lib/class/column.class.php');
				    $publish = new column();
				}
				//获取所有站点
				$hg_sites = $publish->getallsites();
				$hg_attr = array(
					func => 'special_hg_select_value',
					state => 0,
    				is_sub => 1,
				    show => 'site'
				);
				
			    $_INPUT['site_id']=$_INPUT['site_id'] ? $_INPUT['site_id'] : '1';
			    $modules[0][0]="全部";
			    $_INPUT['modules']=$_INPUT['modules'] ? $_INPUT['modules'] : '0';
			    $_INPUT['date_search'] = isset($_INPUT['date_search']) ? $_INPUT['date_search'] : '1';
			    $attr_site=array(
					'class' => 'colonm down_list',
					'show' => 'site',
					'width' =>104,
					'state' =>0,
				);
				$attr_modules=array(
					'class' => 'colonm down_list',
					'show' => 'modules',
					'width' =>104,
					'state' =>0,
				);
				
				$attr_date = array(
					'class' => 'colonm down_list',
					'show' => 'special_data_time',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
				);
				$type_search = array(0=>'新闻分类', 1=>'网站栏目', 2=>'手机栏目');
			{/code}
			{template:site/new_site_search, site_id, $_INPUT['site_id'], $hg_sites, $hg_attr}
	        {template:unit/search_source,special_modules,$_INPUT['modules'],$modules[0],$attr_modules}
			{template:unit/search_source,special_date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:unit/search_weight}
		</div>
		<div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'width' => 104,/*列表宽度*/
					'place' =>'添加人'
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
		</div>
	      <input type="text" name="k" class="search-k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">
		<span class="serach-btn" id="modules-search"></span>
	</form>
</div>
<script>
$(function(){
	$('.site_list').site_list();
})
</script>