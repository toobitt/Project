<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                   
                 {foreach $formdata as $v}
                 	<li >
                 	{if !$v['has_content']}
                 	{$v['title']}
                 	{else}
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_data&infrm=1&page_id={$v['id']}"  target="page_data_iframe">
                 	{$v['title']}
                 	</a>
                 	{/if}
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=deploy_form&infrm=1&page_id={$v['id']}" target="set_iframe">
                 	<img src="{$RESOURCE_URL}vote_opearte.png">
                 	</a>
                 	</li>
                 {/foreach}
                    
    			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
           </div>
        </div>
</div>
