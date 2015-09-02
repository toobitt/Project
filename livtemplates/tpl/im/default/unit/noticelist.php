<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"  need-back>
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
	<div class="m2o-item m2o-name w120 color">
        <span>{$v['owner_uname']}</span>
     </div>
	<div class="m2o-item m2o-state w120 color">
        <span>{$v['notice_state']}</span>
    </div>
    <div class="m2o-item m2o-style w120 color">
        <span>{$_configs['type'][$v['type']]}</span>
    </div>
    <div class="m2o-item m2o-audit w120" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >
        <span>{$_configs['status'][$v['status']]}</span>
    </div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['send_uname']}</span>
        <span class="time">{$v['send_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>