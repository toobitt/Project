<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="javascript:void(0);" _href="?mid={$_INPUT['mid']}&a=get_result&id={$v['id']}{$_ext_link}" onclick="hg_getQestionOption({$v['id']});">
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}" _onclick="hg_getQestionOption({$v['id']});">{$v['title']}</span>
		</a>
	</div>
	<div class="m2o-item m2o-num w80 color">{$v['counts']}</div>
    <div class="m2o-item m2o-sort color">{$v['column_name']}</div>
    <div class="m2o-item m2o-state">{$v['app_uniqueid']}</div>
    <div class="m2o-item m2o-style w80 color">{$v['mood_style_name']}</div>
    <div class="m2o-item m2o-style w80 m2o-img">{if $v['mood_style_picture']}<img width="30" src="{$v['mood_style_picture']}"/>{/if}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['create_user']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>