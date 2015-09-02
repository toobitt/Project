<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<a class="lb" name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
			</a>
		</div>
	</div>
	<div class="common-list-right">
		<div class="common-list-item wd60">
		{if !$v['is_send']}
			<a style="color:{$list_setting['status_color'][$v['audit']]};" title="{$v['audit']}" id="audit_{$v['id']}" onclick="hg_stateAudit({$v['id']},{if $v['state'] == 1}2{else}1{/if});" href="javascript:">{$v['audit']}</a>
		{else}
			<span style="color:{$list_setting['status_color'][$v['audit']]};">{$v['audit']}</span>
		{/if}
		</div>
		<!-- <div class="common-list-item">
		<span >{if $v['state']}已审核{else}未审核{/if}</span>
		</div>-->
		<div class="common-list-item">
			<span id="send_{$v['id']}">{if $v['is_send']}<span style="color:#17b202;">已发送</span>{else}未发送{/if}</span>
		</div>
		<div class="common-list-item wd120">
			<span class="common-time">{$v['send_time']}</span>
		</div>
		<div class="common-list-item wd130">
			<span class="common-user">{$v['username']}</span>
			<span class="common-time">{$v['create_time']}</span>
		</div>
	</div>
	
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
	<div class="common-list-biaoti ">
		<div class="common-list-item biaoti-transition">
			{if $v['is_send']}
			<span class="m2o-common-title" style="cursor:default;">{$v['message']}</span>
			{else}
			<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" class="common-title">
			     <span class="m2o-common-title">{$v['message']}</span>
			</a>
			{/if}
		</div>
	</div>
</li>