<li class="common-list-data" id="r_{$v['id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				<a class="lb" name="alist[]">
					<input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
				</a>
			</div>
		</div>
	</div>
	<div class="common-list-right">
		<div class="common-list-item bianji">
			<div class="common-list-cell">
				<a href="./run.php?mid={$_INPUT['mid']}&a=site_form&infrm=1&id={$v['id']}"><em></em> </a>
			</div>
		</div>
		<div class="common-list-item shanchu">
			<div class="common-list-cell">
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&site_id={$v['id']}"><em></em></a>
			</div>
		</div>
		<div class="common-list-item shijian">
			<div class="common-list-cell">
				<span class="time">{code}echo date('H:i:s',$v['create_time']);{/code}</span>
				<span class="time">{code}echo date('Y-m-d',$v['create_time']);{/code}</span>
			</div>
		</div>
	</div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-content biaoti-transition">
			<div class="common-list-cell">
				<a class="common-list-overflow mwd300" id="title_{$v['id']}" href="./run.php?mid={$_INPUT['mid']}&a=site_form&id={$v['id']}&infrm=1"  onclick="javascript:void(0);">{$v['site_name']}</a>
			</div>
		</div>
	</div>
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
</li>