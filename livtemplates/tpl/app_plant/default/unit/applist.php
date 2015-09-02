<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item group-paixu">
			<div class="common-list-cell">
				<a class="lb" name="alist[]" guid="{$v['guid']}"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>    
			</div>  
		</div>                       
	</div>
	<div class="common-list-right">
		<div class="common-list-item wd80" >
			<div class="common-list-cell">
				{if $v['del']}
				<p style="color:#F00;">已废弃</p>
				{else}
				{if $v['client']}
				{if $v['client']['debug']}
				<a class="show-code-pop" _uuid="{$v['uuid']}" _type="debug" _versionname="{$client['version_name']}" _iosdownload="{$v['client']['debug']['ios']['download_url']}" _androiddownload="{$v['client']['debug']['android']['download_url']}">V{$v['client']['debug']['android']['version_name']}</a>
				{/if}
				{else}
				<p>—</p>
				{/if}
				{/if}
			</div>
		</div>
		<div class="common-list-item wd80" >
			<div class="common-list-cell">
				{if $v['del']}
				<p style="color:#F00;">已废弃</p>
				{else}
				{if $v['client']['release']}
				<a class="show-code-pop" _uuid="{$v['uuid']}" _type="release" _versionname="{$client['version_name']}" _iosdownload="{$v['client']['release']['ios']['download_url']}" _androiddownload="{$v['client']['release']['android']['download_url']}">V{$v['client']['release']['android']['version_name']}</a>
				{else}
				<p>—</p>
				{/if}
				{/if}
			</div>
		</div>
		<div class="common-list-item wd50">
			<div class="common-list-cell">
				{if $v['is_shelves']}
				<span class="glyphicon glyphicon-ok"></span>
				{else}
				<span class="glyphicon glyphicon-remove"></span>
				{/if}
			</div>
		</div>
		<div class="common-list-item wd150">
			<div class="common-list-cell">
			{$v['pack_time']}
			</div>
		</div>
		<div class="common-list-item wd150">
			<div class="common-list-cell">
				<span>{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}<br />{$v['user_name']}</span>   
			</div>
		</div>
		<div class="common-list-item wd150">
			<div class="common-list-cell" >
				<button type="button" class="btn btn-default btn-sm to-appstore" _add="{$v['appstore_address']}">
					{if $v['appstore_address']}
					<span class="glyphicon glyphicon-pencil"></span> 编辑上架地址
					{else}
					<span class="glyphicon glyphicon-plus"></span> 填写上架地址
					{/if}
				</button>
				<button type="button" class="btn btn-default btn-sm del-app" title="删除app">
					<span class="glyphicon glyphicon-trash"></span>
				</button>
			</div>
		</div>
	</div>
	<div class="common-list-biaoti group-bt">
		<div class="common-list-item group-title biaoti-transition">
		<div class="common-list-cell">
		<span>
		{if $v['icon']}
		{code}
		if($v['icon']['dir'])
		{
		$_url = hg_bulid_img($v['icon'], 40, 40);
		}
		else
		{
		$_url = $v['icon']['host'] . $v['icon']['filepath'] . $v['icon']['filename'] . '!icon';
		}
		{/code}
		<img style="vertical-align:middle; border-radius:10%;" width="30" height="30" src="{$_url}" alt="{$v['name']}" />
		{/if}
		</span>
		<span id="title_{$v['id']}" class="m2o-common-title">{$v['name']}</span>
		</div>  
		</div>
	</div>
</li>