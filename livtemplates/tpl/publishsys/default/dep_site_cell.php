<div class="content clear">
	<div class="f">
    	<div class="right v_list_show">
        	{foreach $formdata as $v}
            	<li>
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_type&infrm=1&site_id={$v['id']}" target="page_type_iframe">
                 	{$v['site_name']}
                 	</a>
                 	<a onclick="return parent.hg_ajax_post(this,'检索', 0);" href="./run.php?mid={$_INPUT['mid']}&a=search_cell&infrm=1&site_id={$v['id']}">
                 		<img src="{$RESOURCE_URL}vote_opearte.png">
                 	</a>
            	</li>
        	{/foreach}             
    	</div>
	</div>
</div>
