<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                   
                 {foreach $formdata as $v}
                 	<li >
                 	{if !$v['has_child']}
                 	{$v['title']}
                 	{else}
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_data&infrm=1&site_id={$v['site_id']}&page_id={$v['id']}"  target="page_data_iframe">
                 	{$v['title']}
                 	</a>
                 	{/if}
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=search_cell&infrm=1&site_id={$v['site_id']}&page_id={$v['id']}" onclick="return parent.hg_ajax_post(this,'检索',0);">
                 	<img src="{$RESOURCE_URL}vote_opearte.png">
                 	</a>
                 	</li>
                 {/foreach}
           </div>
        </div>
</div>
