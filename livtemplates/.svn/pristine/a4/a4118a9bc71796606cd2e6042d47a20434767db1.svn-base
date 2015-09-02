{code}
	$period = $val;
	//print_r($period);
{/code}
<div class="m2o-each m2o-flex m2o-flex-center"  _id="{$val['id']}"  id="{$val['id']}"  name="" data-id="{$val['id']}">
	<div class="m2o-item m2o-paixu">
		<a class="lb" name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$period[$primary_key]}" title="{$period[$primary_key]}" class="m2o-check"/>
		</a>
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<div class="m2o-title-transition m2o-title-overflow max-wd">
			<a class="" href="run.php?mid={$_INPUT['mid']}&a=form&id={$period['id']}&epaper_name={$_INPUT['epaper_name']}&infrim=1" need-back target="mainwin">
				{if $period['index_pic']}
				<img src="{$period['index_pic']}">
				{/if}
				<span class="m2o-common-title">{$period['period_num']}期</span>
			</a>
		</div>
	</div>
	<div class="m2o-item m2o-item-right date">{$period['period_date']}</div>
	<div class="m2o-item m2o-item-right num">{$period['stack_num']}/{$period['page_num']}</div>
	<div class="m2o-item m2o-item-right m2o-audit reaudit" _status="{$period['status']}" _id="{$period['id']}" data-method="audit" style="color:{$_configs['status_color'][$period['status']]}">{$_configs['status_show'][$period['status']]}</div>
	<div class="m2o-item m2o-time">
		<span class="name" id="title_{$period['id']}">{$period['user_name']}</span>
		<span class="time">{$period['create_time']}</span>
	</div>
	<div class="m2o-item m2o-ibtn"></div>
</div>