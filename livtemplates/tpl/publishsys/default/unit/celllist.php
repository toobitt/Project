<li class="common-list-data clear" id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}" >
	<div class="common-list-left">
		<div class="common-list-item" style="width:35px;">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>	
    </div>
    <div class="common-list-right">
    	<div class="common-list-item">
    		<div class="common-list-cell">
    			<a href="./run.php?mid={$_INPUT['mid']}&infrm=1&a=form&site_id={$site_id}&page_id={$page_id}&page_data_id={$page_data_id}&content_type={$content_type}&id={$v['id']}">编辑</a>
    			{if $v['original_id']}
    			<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&infrm=1&a=delete&id={$v['id']}">删除</a>
    			{/if}
    		</div>
    	</div>
    	<div class="common-list-item" style="width:120px;">
            <div class="common-list-cell">
                <span class="common-user">{$v['user_name']}</span>
			   <span class="common-time">{$v['create_time']}</span>
            </div>    	
    	</div>  
    </div>
   <div class="common-list-biaoti" style="cursor:pointer;">
	    <div class="common-list-item">
		   <div class="common-list-cell">
		   		<span>{$v['cell_name']}</span>
           </div>
	    </div>
   </div>		           
</li>   