<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" {if $v['errcode']} href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="nodeFrame" need-back title="重新发送"{/if}>
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title {if $v['errcode']}resend{/if}" title="{$v['title']}">{$v['content']}</span>
		</a>
	</div>
	<div class="m2o-item w80 color">{$v['platform_type']}</div>
    <div class="m2o-item w200 overflow color" title="{$v['errmsg']}">{$v['errmsg']}</div>
    <div class="m2o-item w160 overflow color" title="{$v['send_time']}">{$v['send_time']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
