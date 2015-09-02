<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]"></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd60">
			<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" style="margin-right:10px;">
				<em class="b2" style="display:inline-block;width:16px;height:16px;background-position:-60px -24px;vertical-align:middle;"></em>
			</a>
		</div>
		<div class="common-list-item wd60">									
			<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">
				<em class="b3" style="display:inline-block;width:16px;height:16px;background-position:-64px -118px;vertical-align:middle;"></em>
			</a>	
		</div>	
        <div class="common-list-item wd300">
            <div class="common-list-cell">
			    <span>{$v['venue_address']}</span>
            </div>
        </div>
        
        <div class="common-list-item vote-tjr wd120">
            <div class="common-list-cell">
			    <span class="common-user">{$v['user_name']}</span>
			    <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
			   <div class="common-list-cell">
			   <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
                <span class="common-list-overflow m2o-common-title" title="{$v['venue_name']}">{$v['venue_name']}</span>
               </a>
            </div>  
	    </div>
   </div>
</li>
