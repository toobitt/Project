<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
                    {if $_configs['is_need_audit']}
            {{if state == 1}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=2&id=${id}">打回</a> 
			{{else}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id=${id}">审核</a>
			{{/if}}
                        {/if}
			<a href="run.php?mid={$_INPUT['mid']}&a=delete&rid=${id}">删除</a>
                        {if $_configs['App_share']}
			<a href="./run.php?mid={$_INPUT['mid']}&a=share_form&id=${id}">分享</a>		
			{/if}
                        {if $_configs['App_cdn']}
                        {if $_configs['is_need_audit']}
                        {{if state == 1}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=cdn_publish&id=${id}&ajax=1" class="send">cdn推送</a>		
			{{/if}}
                        {else}
                        <a href="./run.php?mid={$_INPUT['mid']}&a=cdn_publish&id=${id}&ajax=1" class="send">cdn推送</a>
                        {/if}
                        
			{/if}
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info">
			{{if click_num != 0}}<span>访问:${click_num}</span>{{/if}}
			{{if comm_num}}<span>评论:${comm_num}</span>{{/if}}
			{{if share_num != 0}}<span>分享:${share_num}</span>{{/if}}
		</div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>
</div>