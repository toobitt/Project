<li class="common-list-data clear" id="r_{$v['id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
				<a class="lb" name="alist[]">
					<input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
				</a>
		</div>
		<!--
<div class="common-list-item stream-name">
			<div class="common-list-cell">
				<span id="s_name_{$v['id']}">{$v['s_name']}</span>
			</div>
		</div>
-->
	</div>
	<div class="common-list-right">
		<!-- <div class="common-list-item option wd100">
				<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" style="margin-right:10px;">编辑</a>
				<a onclick="hg_check_channel({$v['id']});" title="删除" href="javascript:void(0);">删除</a>
		</div>-->
		<div class="common-list-item token wd120">
				<span class="stream-typename">{$v['server_name']}</span>
		</div>
		<div class="common-list-item token wd70">
				<span class="stream-typename">{if $v['wait_relay']}推送{else}拉取{/if}</span>
		</div>
		<div class="common-list-item status wd70">
				<div class="need-switch" title="{if $v['s_status']}已启动{else}未启动{/if}" state="{if $v['s_status']}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}" server_id="{$v['server_id']}"></div>
		</div>
		<div class="common-list-item channel-stream wd150">
			{foreach $v['out_uri'] AS $kk => $vv}
				<span title="{$vv}" id="out_uri_{$v['id']}_{$kk}">{$kk}</span>
			{/foreach}
		</div>
	</div>
	<div class="common-list-biaoti biaoti-transition">
		 <div class="common-list-item token wd200">
			  <a  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" style="font-size:14px;">
			  	<span class="stream-xinhao">{$v['ch_name']}</span>
			  </a>
				{if $v['audio_only']}<span class="red-zifu">*</span>{/if}
				{if $v['type']}<span style="color: red;">*</span>{/if}
		</div>
		<div class="common-list-item channel-stream bb-orign">
		{if !$v['type']}
			<div class="common-list-cell" id="isPlay_{$v['id']}">
				
			</div>
		{/if}
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
</li>