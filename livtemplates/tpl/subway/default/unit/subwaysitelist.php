<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
    <div class="m2o-item m2o-sort color">{$v['direction']}</div>
	<div class="m2o-item m2o-num w80 color">{$v['sort_name']}</div>
	 <div class="m2o-item m2o-style w80 color">{$v['site_count']}</div>
	 <div class="m2o-item m2o-style w80 color">{$v['is_operate']}</div>
    <div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['state']==0}待审核{else if $v['state']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['cre_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
