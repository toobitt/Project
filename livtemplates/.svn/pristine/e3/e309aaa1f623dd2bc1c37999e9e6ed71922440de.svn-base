{js:qingao/jquery}
{css:manage}
<body>
<div id="wrap">
	{template:./top}
	<ul class="nav">
		<li><a href="manage.php?group_id={$group_id}">设置</a></li>
		<li><a href="manage.php?a=member&group_id={$group_id}">居民</a></li>
		<li class="currentSetting">回收站</li>
		<li id="goto_index"><a href="group.php?group_id={$group_id}">返回讨论区首页</a></li>
	</ul>
	<div class="nav_con">
		<div id="recycle">
			<table cellpadding="0" cellspacing="1" border="0" width="100%" id="thread_list">
				<thead>
					<tr>
						<th>ID</th>
						<th width="50%">帖子标题</th>
						<th>作者</th>
						<th>回复/查看</th>
						<th>最后发表</th>
					</tr>
				</thead>
				<tbody>
				{if $threads}
					{foreach $threads as $thread}
					<tr>
						<td><input type="checkbox" name="thread_id" value="{$thread['thread_id']}" /></td>
						<td class="thread_title"><strong><a href="#">{$thread['title']} </a></strong></td>
						<td><em>{$thread['user_name']}</em><br /><span>{$thread['pub_time']}</span></td>
						<td>{$thread['post_count']}/{$thread['click_count']}</td>
						<td><em>{$thread['last_poster']}</em><br /><span>{$thread['last_post_time']}</span></td>
					</tr>
					{/foreach}
				{else}
					<tr>
						<td colspan="5">回收站为空</td>
					</tr>
				{/if}
				</tbody>
			</table>
			<div id="util"><a href="javascript:;" m="selectAll">全选</a>&nbsp;<a href="javascript:;" m="cancelAll">取消</a>&nbsp;|&nbsp;<a href="javascript:;" m="restore">还原</a>&nbsp;|&nbsp;<a href="javascript:;" m="thoroughDel">彻底删除</a></div>
			{$pagelink}
		</div>
	</div>
</div>
</body>