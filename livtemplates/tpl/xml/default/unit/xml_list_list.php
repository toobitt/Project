<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<a {if $v['is_group']==0}href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&state=1" target="mianwin"{else}href="run.php?a=relate_module_show&app_uniq=xml&mod_uniq=xml" target="mianwin"{/if}>
			<span class="m2o-item-bt">
				<span>{$v['title']}</span>
			</span>
		</a>
	</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</div>
