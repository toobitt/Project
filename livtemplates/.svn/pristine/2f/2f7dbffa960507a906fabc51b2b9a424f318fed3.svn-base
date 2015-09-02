<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-data">
        <div class="common-list-item circle-tjr">
             <div class="common-list-cell" style="width:48px;">
           			<!--<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
           			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>-->
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&tbname=scoretop"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span><a href="./run.php?mid={$_INPUT['mid']}&game_id={$v['game_id']}&a="><span class="m2o-common-title">{$v['game_name']}</span></span>
            </div>
        </div> 
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['user_name']}</span>
            </div>
        </div>    

        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['score']}</span>
            </div>
        </div>
    </div>
    <!--
   <div class="common-list-biaoti min-wd" style="cursor:pointer;">
	    <div class="common-list-item biaoti-transition">
		   <div class="common-list-cell">
			<span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}">{$v['title']}</span>	
           </div>
	    </div>
   </div>  
   --> 
</li>