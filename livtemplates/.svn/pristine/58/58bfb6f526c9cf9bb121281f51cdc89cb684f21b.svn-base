{code}$v['departStation'] = urlencode($v['departStation']);{/code}
<li class="common-list-data clear" id="r_{$v['departDate']}_{$v['departStation']}" name="{$v['departDate']}_{$v['departStation']}"
	orderid="{$v['order_id']}" cname="{$v['cid']}"
	corderid="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item group-paixu">
			<div class="common-list-cell">
				<a class="lb" name="alist[]"><input type="checkbox"
					name="infolist[]" value="{$v['departDate']}_{$v['departStation']}"
					title="{$v['departDate']}" />
				</a>
			</div>
		</div>
	</div>
	<div class="common-list-right">
		<div class="group-tz common-list-item open-close" style="width: 195px;padding-right:5px;">
			<div class="common-list-cell">
				<div title="操作" class="btn-box-cz">
					<div class="btn-box-cz-menu" id="rr_2_{$v['id']}">
						<a class="button_4" style="margin-right: 4px;"
							href="./run.php?mid={$_INPUT['mid']}&a=copy&id={$v['departDate']}&station={$v['departStation']}"
							onclick="return hg_ajax_post(this, '复制', 1);">复制</a>
						<a class="button_4" style="margin-right: 4px;"
							href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['departDate']}_{$v['departStation']}"
							onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="common-list-biaoti">
		<div class="common-list-biaoti">
			<div class="common-list-cell" style="width: 95px; font-size: 5px;">
				<span class="common-list-overflow m2o-common-title">{$v['departDate']}</span>
			</div>

			<div class="common-list-cell" style="width: 95px; font-size: 5px;">
				<span class="common-list-overflow m2o-common-title"></span>
			</div>
			<div class="common-list-cell" style="width: 95px; font-size: 5px;">
				<span class="common-list-overflow m2o-common-title"></span>
			</div>
			<div class="common-list-cell" style="width: 95px; font-size: 5px;">
			 <a href="./run.php?a=relate_module_show&app_uniq=convenience&mod_uniq=shipmanage&station={$v['departStation']}&date={$v['departDate']}&infrm=1">
				<span class="common-list-overflow m2o-common-title">{code}echo urldecode($v['departStation']);{/code}</span>
				</a>
			</div>
		</div>
	</div></li>
