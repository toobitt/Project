<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
			<span class="m2o-item-bt">
			{if $v['img']}
				<img src="{$v['img']}">
			{/if}
				<span class="boke-title">{$v['title']}</span>
			</span>
		</a>
	</div>
    <div class="m2o-item m2o-state color" style="height:30px;line-height:30px;"><span>{$v['admin_cate_name']}</span>
    <ul class="item-list">
    	<em></em>
    	{foreach $v['cate_info'] as $key => $val}
    		<li id="{$val['id']}" _id="{$val['id']}">{$val['name']}</li>
    	{/foreach}
    	</ul>
    </div>
    <div class="m2o-item m2o-style w80 color">{$v['cate_name']}</div>
    <div class="m2o-item m2o-audit" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]};" >{if $v['state']==0}待审核{else if $v['state']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time color">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>















