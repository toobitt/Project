<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['name']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="nodeFrame"  need-back>
			<span class="m2o-item-bt">
				{if $v['logo']}<img src="{$v['logo']}" width="40" height="30" >{/if}
				<span>{$v['name']}</span>
			</span>
		</a>
	</div>
	<div class="m2o-item w80 color">{$v['station_count']}</div>
	<!-- <div class="m2o-item w80 color"><img class="biaoti-img" width="40" height="30" src="{$v['station_icon']}"></div> -->
    <div class="common-list-item w120 m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time">
        <span class="common-user">{$v['user_name']}</span>
		<span class="common-time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
