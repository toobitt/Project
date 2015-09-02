<div class="m2o-each m2o-flex m2o-flex-center" _id="{$v['id']}" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&fid={$_INPUT['fid']}&infrm=1" target="formwin" need-back>
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
	{if $_configs['App_im']}
	<div class="m2o-item m2o-replay w140 color">
	  <span class="m2o-item" _reply ="{$v['reply']}" style="color:{$_configs['status_color'][$v['reply']]};cursor:pointer;">{if $v['reply']}有新回复{else if($v['user_id'])}回复{else}不可回复{/if}</span>
    </div>
    {/if}
	<div class="m2o-item m2o-process w140 color">
	  <span class="m2o-item" _process ="{$v['process']}" style="color:{$_configs['status_color'][$v['process']]};cursor:pointer;" title="{$_configs['process'][$v['process']]}">{$_configs['process'][$v['process']]}</span>
    </div>
	<div class="m2o-item m2o-col w140 color">
    <span class="common-list-pre-pub">{$v['column']}</span>
     </div>
    <div class="m2o-item m2o-time">
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
