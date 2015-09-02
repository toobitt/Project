<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['name']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="nodeFrame"  need-back>
			<span class="m2o-item-bt">
				{if $v['logo']}
					<img src="{$v['logo']['host']}/{$v['logo']['dir']}/{$v['logo']['filepath']}/{$v['logo']['filename']}" class="img-middle"/>
				{/if}
				<span>{$v['name']}</span>
			</span>
		</a>
	</div>
	<div class="m2o-item m2o-style w120 color">{$v['over']}</div>
	<div class="m2o-item m2o-style w120 color">{$v['over_remind']}</div>
	<div class="m2o-item m2o-switch w120" _status="{$v['id']}" >
    	<div class="common-switch {if $v['status']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div> 
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>




























			