<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
<div class="m2o-item m2o-paixu">
	<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
</div>
<div class="m2o-item m2o-flex-one m2o-bt">
		<span class="m2o-item-bt font{$v['id']}">
		<img src="{$v['dir']}">
			<span>{$v['name']}</span>
		</span>
</div>
 <div class="m2o-item m2o-state">{$v['type']}</div>
<div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
<div class="m2o-item m2o-time">
    <span class="name">{$v['user_name']}</span>
    <span class="time">{$v['create_time']}</span>
</div>
<div class="m2o-item m2o-ibtn"></div>
</div>