<div class="divs">
	{if is_array($topic_list)&&$topic_list}
	<table cellspacing=0 cellpadding=0 style="width:100%">
		<tr class="tab_tr">
			<td>名称</td>
			<td>地盘/作者</td>
			<td>回复/阅读</td>
			<td>最后发表</td>
		</tr>
	
		{foreach $topic_list as $key => $value}
		
		<tr class="con_tr">
			<td>
		        {$value['flag']}
				<span title="由 {$value['user_name']} 于 {$value['pub_time']} 发起, {$value['click_count']}次阅读, {$value['post_count']}篇回复">
				 <a href="<?php echo SNS_TOPIC.$value['topic_link'];?>"  {$value['style']}>
					<?php echo hg_cutchars($value['title'],15,'…',true);?></a>{$value['cons']}</span>
			</td>
			<td>
				<span style="font-size:14px;float: left;"><a href="<?php echo SNS_TOPIC.$value['group_link'];?>">{$value['group_name']}</a></span>
				<div class="author">
					{$value['avatar']}
					{$value['user_link']}
					<span class="times">{$value['pub_time']}</span>
				</div>
			</td>
			<td>{code} echo ($value['post_count']){/code}/{code} echo $value['click_count'];{/code}</td>
			<td>
				<div class="last_pub">
					<span class="names" title="{$value['last_poster']}">{$value['last_poster']}</span>
					<span class="times clear">{$value['last_post_time']}</span>
				</div>
			</td>
		</tr>
		{/foreach}
	</table>
	
	    {$showpages}
	{else}
	{code}
				$null_title = "";
				$null_text = "暂未发帖";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
	{/if}
	<div class="clear"></div>	
</div>