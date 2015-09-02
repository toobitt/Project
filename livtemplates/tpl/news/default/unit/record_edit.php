{code}//hg_pre($list);{/code}
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="run.php?mid={$_INPUT['mid']}&a={{if outlink}}form_outerlink{{else}}detail{{/if}}&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=move_form&id=${id}&nodevar=news_node" data-node ='news_node'>移动</a>
			{{if state == 1}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id=${id}">打回</a> 
			{{else}}
			<a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id=${id}">审核</a>
			{{/if}}
		</div>
		<div class="record-edit-btn-area clear">
			{if $_configs['App_publishcontent']}
			<a href="run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}">签发</a>
			{/if}
			{if $_configs['App_share']}
			{{if !(expand_id == 0)}}
			<a href="run.php?mid={$_INPUT['mid']}&a=share_form&id=${_.values(pub_url)[0]}">分享</a>
			{{/if}}
			{/if}
			{if $_configs['App_special']}
			<a href="run.php?mid={$_INPUT['mid']}&a=special&id=${id}&infrm=1">专题</a>
			{/if}
			{if $_configs['App_block']}
			<a>区块</a>
			{/if}
		</div>
		<div class="record-edit-btn-area clear">
		   <a  href="./run.php?mid=2890&a=create&id=${id}&pushType=news">推送</a>
		</div>

		{{if catalog}}
		<div class="record-catalog-info">
			<span>编目信息</span>
			<ul>
			{{each catalog}}
				{{if _value }}
				<li><label>${_value.zh_name}：</label>
					{{if typeof( _value.value ) == 'string'}}
						<p>${_value.value}</p>
					{{else}}
						<p class="clear">
						{{each _value.value}}
							{{if _value.host}}
							<span class="record-edit-img-wrap"><img src="${_value.host}${_value.dir}${_value.filepath}${_value.filename}"></span>
							{{else}}
							<span>${_value}</span>
							{{/if}}
						{{/each}}
						</p>
					{{/if}}
				</li>
				{{/if}}
			{{/each}}
			</ul>
		</div>
		{{/if}}
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info">
			{{if click_num != 0}}<span>访问:${click_num}</span>{{/if}}
			{{if comm_num != 0}}<span>评论:${comm_num}</span>{{/if}}
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

	<div class="push-edit-confirm">
		<p>确定将该内容推送到CRE吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a class="push-btn">确定</a>
			<a>取消</a>
		</div>
		<span class="push-edit-confirm-close"></span>
    </div>
</div>