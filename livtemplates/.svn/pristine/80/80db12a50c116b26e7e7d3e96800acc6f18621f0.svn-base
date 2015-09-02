<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<div class="m2o-title-transition m2o-title-overflow">
		<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
			{if $v['color']}<span class="m2o-color" style="background-color:{$v['color']}"></span>{/if}
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
		</div>
	</div>
    <div class="m2o-item m2o-direction">{$v['direction']}</div>
	<div class="m2o-item m2o-sort">{$v['sort_name']}</div>
	 <div class="m2o-item m2o-count">{$v['site_count']}</div>
	  {code}
    	$v['switch'] = ($v['is_operate'] == '在建') ? 0 : 1
    {/code}
	 <div class="m2o-item m2o-switch" _status="{$v['switch']}" >
    	<div class="common-switch {if $v['switch']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div>
    <div class="m2o-item m2o-audit" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]};" >{$v['status']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['cre_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
