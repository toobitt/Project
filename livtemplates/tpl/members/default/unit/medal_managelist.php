<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
	<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
		<div class="m2o-item m2o-image">{if $v['image_url']}<img src="{$v['image_url']}" style="width: auto; height: auto;vertical-align: middle;"/>{/if}</div>
	
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['brief']}">
		 <div class="m2o-title-transition m2o-title-overflow">
			 <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
				<span>{$v['name']}</span>
			</a>
		 </div>
	</div>
	<div class="m2o-item m2o-time">{$v['award_date']}</div>
	<div class="m2o-item m2o-used">{$v['used_num']}</div>
	<div class="m2o-item m2o-limit">{$v['limit_num']}</div>
	<div class="m2o-item m2o-expiration">{$v['expiration']}</div>	
	<div class="m2o-item m2o-mark">{$v['type_name']}</div>
	<div class="m2o-item m2o-switch" _status="{$v['available']}" >
    	<div class="common-switch {if $v['available']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div> 
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
    <div class="m2o-item m2o-ibtn">
    </div>
</div>