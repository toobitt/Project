<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<a {if $v['is_group']==0} href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}" target="mianwin"{else}href="run.php?mid={$_INPUT['mid']}&a=show_xml&id={$v['id']}" target="mianwin"{/if} need-back>
			<span class="m2o-item-bt">
				<span>{$v['title']}</span>
			</span>
		</a>
	</div>
    <div class="common-list-item m2o-switch" _status="{$v['is_open']}" style="position:relative;">
    		<div class="common-switch {if $v['is_open']}common-switch-on{/if}" style="bottom:0px;">
           		<div class="switch-item switch-left" data-number="0"></div>
           		<div class="switch-slide"></div>
           		<div class="switch-item switch-right" data-number="100"></div>
        	</div>
    </div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</div>
