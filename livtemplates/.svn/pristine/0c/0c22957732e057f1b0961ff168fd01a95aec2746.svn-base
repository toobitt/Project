<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" need-back>
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
	<div class="m2o-item m2o-col w80 color m2o-link">
		<span class="common-list-pub">查看链接</span>
		<div class="link-box">
			<a _href="{$v['url']}" style="display: block;">{if $v['url']}{$v['url']}{else}暂无链接{/if}</a>
			{if $v['url']}
			<span class="clone">复制</span>
            <span id="forLoadSwf{$k}3" class="forLoadSwf" data-index="{$k}3" _text="{if $v['url']}{$v['url']}{else}暂无链接{/if}"></span>
			{/if}
		</div>
	</div>
	<div class="m2o-item m2o-num w80 color">{$v['sort_name']}</div>
	<div class="m2o-item m2o-num w80 color">{$v['type_name']}</div>
	<div class="m2o-item m2o-num w180 color"><span class="m2o-color-state" style="background:{if $v['activ_status'] == 0}#c7d3df{else if $v['activ_status'] == 1}#5ac75a{else if $v['activ_status'] == 2}#ee7b80{/if};"></span>{$v['effective_time']}</div>
	<div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</div>