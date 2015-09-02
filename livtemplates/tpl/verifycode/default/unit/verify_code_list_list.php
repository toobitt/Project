<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&state=1" target="formwin">
			<span class="m2o-item-bt">
				<img src="run.php?a=get_verify_code&amp;type={$v['id']}">
				<span>{$v['name']}</span>
			</span>
		</a>
	</div>
    <div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
    <div class="common-list-item m2o-switch" _status="{$v['is_default']}" style="position:relative;">
    		<div class="common-switch {if $v['is_default']}common-switch-on{/if}" style="bottom:0px;">
           		<div class="switch-item switch-left" data-number="0"></div>
           		<div class="switch-slide"></div>
           		<div class="switch-item switch-right" data-number="100"></div>
        	</div>
    </div>
    <div class="m2o-item m2o-state">{$v['type']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</div>
