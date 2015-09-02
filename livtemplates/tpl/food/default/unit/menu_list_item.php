<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item auth-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
       <div class="common-list-item auth-pic-path">
            <div class="common-list-cell">
                <span><img src="{$v['pic_path']}" width="40" height="30" /></span>
            </div>
       </div>
       
       <div class="common-list-item auth-cook-style">
            <div class="common-list-cell">
                <span>{$v['cname']}</span>
            </div>
       </div>
       
       <div class="common-list-item auth-price">
            <div class="common-list-cell">
                <span>{$v['price']}</span>
            </div>
       </div>
       
       <div class="common-list-item auth-status">
            <div class="common-list-cell">
                <span>{if $v['status']}可以{else}不可以{/if}</span>
            </div>
       </div>
       
       <div class="common-list-item auth-max-num">
            <div class="common-list-cell">
                <span>{$v['max_num']}</span>
            </div>
       </div>
       
       <div class="common-list-item auth-create-time">
            <div class="common-list-cell">
                <span>{$v['create_time']}</span>
            </div>
       </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item auth-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <span class="common-list-overflow auth-biaoti-overflow">{$v['name']}</span>
            </div>  
	    </div>
   </div>
</li> 
