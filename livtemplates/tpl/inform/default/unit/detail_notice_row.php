<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item circle-tjr">
             <div class="common-list-cell" style="width:48px;">
           			<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['notice_id']}&infrm=1">
           			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&via=via_notice"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div> 
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['user_name']}</span>
            </div>
        </div>
        
         <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>
                    {code}
					if($v['sendto_org_id']==0)
						echo "全部";                    
                    else
                    	echo $v['sendto_org_name']
					{/code}                    
                    </span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['create_time']}</span>
            </div>
        </div>
        
        
       
        
    </div>
   <!--<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>-->
   <div class="common-list-biaoti min-wd" style="cursor:pointer;">
	    <div class="common-list-item biaoti-transition">
	    	<!--title-->
		   <div class="common-list-cell">
		   <!--
		   <span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}"><a href="{$v['content_url']}" target="_blank">{$v['title']}</a></span>
		   -->
			<span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}">{$v['title']}</span>	
           </div>
	    </div>
   </div>   
</li>