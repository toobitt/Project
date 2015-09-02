<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" order_id="{$v['order_id']}">
	<div class="common-list-left">	
		<div class="common-list-item paixu">
			<a class="lb" name="alist[]">
				<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" />
			</a>
		</div>	
	</div>
	<div class="common-list-right">
		<div class="common-list-item">
			<a class="makefile handler-icon" href="./run.php?a=relate_module_show&app_uniq=ticket&mod_uniq=ticket_perform&show_id={$v['id']}&show_name={$v['title']}&infrm=1">场次管理</a>
		</div>
		<div class="common-list-item wd60" id="sale_{$v['id']}">{$v['sale_state_name']}</div>
		<div class="common-list-item wd60">{$v['name']}</div>
		<div class="common-list-item ticket-fabu">
			<!-- {$v['column_name']} -->
			<div class="common-list-pub-overflow">
			<span id="column_{$v[id]}">{foreach $v['column_id'] as $key=>$val}<span>{$val}</span>{/foreach}</span>
			</div>
		</div>
		{template:list/list_weight,tuji-quanzhong,$v['weight']}
		<div class="common-list-item wd60 tuji-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['status_name']]};">{$v['status_name']}</span>
			</div>
		</div>
		<div class="common-list-item wd150 tjr">
			<span class="common-user">{$v['user_name']}</span>
			<span class="common-time">{$v['update_time']}</span>
		</div>
	</div>
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
	<div class="common-list-biaoti" >
		<div class="common-list-item biaoti-transition">
			{code}
			$log_img = '';
			$img = '';
			if($v['index_url'])
			{
				$img = $v['index_url']['host'] . $v['index_url']['dir'] . '40x30/' . $v['index_url']['filepath'] . $v['index_url']['filename'];
			}
			{/code}	
			{if $img}<a class="fl" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" style="width:40px">
				<img class="common-img" alt="" src="{$img}" />
			</a>{/if}
			<div class="common-title-box common-list-overflow max-wd">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><span class="m2o-common-title">{$v['title']}</span></a>
				<div class="common-title-detail">
					<span>场馆：{$v['venue']}</span>
					<span>地点：{$v['address']}</span>
				</div>
			</div>
		</div>
	</div>
</li>