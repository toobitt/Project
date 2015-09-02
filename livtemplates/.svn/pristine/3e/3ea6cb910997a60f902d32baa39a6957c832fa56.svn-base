{foreach $formdata['column_info'] as $kk => $vv}
	{code}
	$modules[$vv['bundle']] = $vv['name'];
	{/code}
{/foreach}
{code}
$modules[0]="全部";
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['modules'])
{
	$_INPUT['modules'] = 0;
}

{/code}
<div class="choice-area">
	<form  id="searchform" action="" method="get">
	    <div class="modules-box">
	    	<span class="current">
	    		<a>
	    			<em></em>
	    			<label class="overflow">发布库</label>
	    		</a>
	    	</span>
	    	<ul class="modules-list">
	    		<li class="module-type">发布库</li>
	    		<li class="module-type">自定义</li>
	    	</ul>
	    </div>
		<div class="select-search">
			{code}
				
				$attr_modules = array(
					'class' => 'down_list',
					'show' => 'modules_show',
					'width' => 104,
					'state' => 0,
				);

				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,
					'state' => 1,
				);
			{/code}
			{template:form/search_source,modules,$_INPUT['modules'],$modules,$attr_modules}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:form/search_weight}
		</div>
	      <input type="text" name="k" class="search-k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">
		  <input type="hidden" name="a" value="newslist" />
		  <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		  <input class="serach-btn" id="modules-search" type="submit" value="">
	</form>
</div>