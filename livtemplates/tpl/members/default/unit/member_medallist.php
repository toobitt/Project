<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
	<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-mark">{$v['member_name']}</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['brief']}">
				<span>{$v['medal_name']}</span>
	</div>
	<div class="m2o-item m2o-time">{$v['dateline']}</div>
	<div class="m2o-item m2o-time">{$v['expiration']}</div>
	 <!--<div class="m2o-item m2o-mark">{if $v['issystem']}<img style="margin-right:10px" src="{$RESOURCE_URL}select@2x.png" width="16" height="16">{else}<img style="margin-right:10px" src="{$RESOURCE_URL}close_plan.png" width="16" height="16">{/if}</div> -->
	<!--<div class="m2o-item m2o-mark">{if $v['icon']}{code}$v['icon']=hg_fetchimgurl($v['icon'],'25','30');{/code}<img src="{$v['icon']}" style="width: auto; height: auto;"/>{/if}</div>-->
   <!-- <div class="m2o-item m2o-sort">{$v['variable']}</div>
      
    <div class="m2o-item m2o-switch" _status="{$v['switch']}" >
    	<div class="common-switch {if $v['switch']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div> 
    -->
   <!--  <div class="m2o-item m2o-sort">{$v['type']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['update_time']}</span>
    </div> -->
</div>
