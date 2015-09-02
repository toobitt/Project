{code}
       if ($v['state'] == 1) {
           $status = '已审核';
       }elseif ($v['state'] == 0) {
           $status = '待审核';
       }
{/code}
<div class="m2o-each m2o-flex m2o-flex-center" id="{$v['id']}" name="{$v['id']}" data-id="{$v['id']}">
	<div class="m2o-item m2o-paixu">
		<a class="lb" name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="m2o-check"/>
		</a>
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<div class="m2o-title-transition max-wd">
			<!--<a class="m2o-title-overflow">-->
				<span style="padding-right:20px;">{$v['content']}</span>
			<!--</a>-->
		</div>
	</div>
	<div class="m2o-item dx">{code}echo hg_cutchars($v['photo_name'], 10);{/code}</div>
	<div class="audit m2o-audit" _status="{$v['state']}" data-method="audit" style="color:{$_configs['status_color'][$v['state']]}">{$status}</div>
	<div class="m2o-item m2o-time">
		<span class="name" id="title_{$v['id']}">{$v['user_name']}</span>
		<span class="time">{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}</span>
	</div>
</div>
