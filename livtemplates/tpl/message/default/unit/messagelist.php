<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}"  name="{$v['id']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item common-paixu">
			<a class="lb" name="alist[]" >
				<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
			</a>
		</div>
	</div>
	
	<div class="common-list-right">
		<div class="common-list-item overflow reviews-object">
			{if $v['cmid']}
			<a class="overflow" title="{$v['content_title']}" href="./run.php?mid={$_INPUT['mid']}&a=show&cmid={$v['cmid']}&infrm=1">
			{else}
			<a class="overflow" title="{$v['content_title']}" href="./run.php?mid={$_INPUT['mid']}&a=show&content_id={$v['contentid']}&app_uniqueid={$v['app_uniqueid']}&mod_uniqueid={$v['mod_uniqueid']}&infrm=1">
			{/if}
			{$v['content_title']}
			</a>
		</div>
		<div class="common-list-item overflow wd130" title="{$v['content_url']}" >
		     {$v['content_url']}
		</div>
		<div class="common-list-item" title="{$v['ip_info']}">
		     {$v['ip']}
		</div>
		<div class="common-list-item" >
			<!--  <span id="comment_audit_{$v['id']}">{$v['state']}</span>-->
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['state']]};">{$v['state']}</span>
			</div>
		</div>
		<div class="common-list-item wd130">
			<span class="common-user">{$v['username']}</span>
			<span class="common-time">{$v['pub_time']}</span>
		</div>
	</div>
	<div class="common-list-biaoti" >
		<div class="common-list-item biaoti-transition max-wd overflow">
			{if $v['last_reply']}<a title="{$v['content']}" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&tablename={$v['tablename']}&infrm=1" class="common-title has-reply">{$v['content']}</a>{else}<span class="common-title m2o-common-title" title="{$v['content']}">{$v['content']}</span>{/if}
			{if $v['last_reply']}<a href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&tablename={$v['tablename']}&infrm=1" class="reply-btn">查看回复</a>{/if}
		</div>
	</div>
	
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']},'tablename={$v['tablename']}');"></div>
	
</li>