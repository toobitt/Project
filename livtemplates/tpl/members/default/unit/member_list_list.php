<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data" data-id="{$v['member_id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt overflow" title="会员名：{$v['member_name']}；{if $v['nick_name']}昵称：{$v['nick_name']}{/if}">
		<!--<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['member_id']}&infrm=1" target="formwin"  need-back>-->
		<a class="common-title">
			<span class="m2o-item-bt">
				{if $v['avatar']}<img  src="{$v['avatar']['host']}/{$v['avatar']['dir']}/{$v['avatar']['filepath']}/{$v['avatar']['filename']}">{/if}
				<span style="{if $v['usernamecolor']}color:{$v['usernamecolor']}{/if}">{$v['member_name']}{if $v['nick_name']}({$v['nick_name']}){/if}</span>
				{if $v['groupicon']}
           			{code}$v['groupicon']=hg_fetchimgurl($v['groupicon']);{/code}
		        	<img src="{$v['groupicon']}" style="width:16px;height:16px;margin-top: -12px;margin-left:-5px;" />
		        {/if}
			</span>
		</a>
	</div>
	<div class="m2o-item m2o-style w60 color">{$v['credits']}</div>
	<div class="m2o-item m2o-style w120 color">{$v['groupname']}{if $v['graname']}/{$v['graname']}{/if}</div>
	<div class="m2o-item w60 verify" style="color:{if $v['isVerify']== 0}#8ea8c8;{else if $v['isVerify']== 1}#17b202;{/if}" _isVerify="{$v['isVerify']}" data-id="{$v['member_id']}">{if $v['isVerify']== 1}已认证{else if $v['isVerify']== 0}待认证{/if}</div>
    <div class="m2o-item m2o-style w120 color">{$v['type_name']}</div>
    <div class="m2o-item m2o-style w120 color overflow" title="{$v['iusname']}">{$v['iusname']}</div>
    <div class="m2o-item w60 m2o-audit {if $v['blacklist']['isblack']}isblack{/if}" style="color:{if $v['status']== 0 || $v['blacklist']['isblack']}#8ea8c8;{else if $v['status']== 1}#17b202;{else if $v['status']== 2}#f8a6a6{/if}" _status="{$v['status']}" data-id="{$v['member_id']}" _black="{$v['blacklist']['isblack']}">{if $v['blacklist']['isblack']}已加入黑名单{else if $v['status']== 0 }待审核{else if $v['status']== 1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-time w120">
        <span class="name">{$v['appname']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>