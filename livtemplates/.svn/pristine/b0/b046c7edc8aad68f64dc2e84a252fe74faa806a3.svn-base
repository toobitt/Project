<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="标题"> <a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" > <span id="title_{$v['id']}" class="m2o-common-title">{$v['name']}</span></a></div>
	<div class="m2o-item m2o-sort"><span>    
                              {if $v['index_picture']}  
                              <img width="30" src="{$v['index_picture']}"/>
                              {/if}
                        </span></div>
    <div class="m2o-item m2o-num w80">{$v['count']}</div>
    <div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['create_user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>

