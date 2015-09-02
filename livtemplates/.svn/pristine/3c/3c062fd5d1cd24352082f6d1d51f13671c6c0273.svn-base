{code}
    $imgInfo = $v['img_info'];
    $img = $imgInfo ? $imgInfo['host'] . $imgInfo['dir'] . '40x30/' . $imgInfo['filepath'] . $imgInfo['filename'] : '';
{/code}
<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="标题">
		 <div class="m2o-title-transition m2o-title-overflow">
			 <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
				{if $img}<img style="margin-right:10px" src="{$img}" width="40" height="30">{/if}
				<span>{$v['title']}</span>
			</a>
		 </div>
	</div>
    <div class="m2o-item m2o-sort">{$v['sort_name']}</div>
    <div class="m2o-item m2o-area">{$v['region']}</div>
    <div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{$v['status_name']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['format_create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>