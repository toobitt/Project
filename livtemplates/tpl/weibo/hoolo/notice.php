{code}
	$typeArr = array(0,1,2,3);  
	$tips = array();
{/code}
{if !empty($notice)}
	{foreach $notice as $key => $value}
		{if in_array($value['type'],$typeArr)} 
			{if $value['type'] == 0}
				{code}
					$title = '系统通知';
					$link =  'javascript:void(0);';
					$id = $value['id'];
				{/code}
			{else}
				{code}
					$title =  $value['content']['title'];
					$link =  $value['content']['page_link'];
					$id = $value['id'];
				{/code}
			{/if}
			<li style="font-weight:bold;">1条<a  href="{$link}" title="点击查看" onclick="insertReadSMS('{$id}',{$value['type']});" >{$title}</a></li>
		{else}
		{code}
			$create_at = date("Y-m-d H:i:s",$value['notify_time']);
			$title =  $value['content']['title'];
			$link =  $value['content']['page_link'];
			$id = $value['id'];
		{/code}
			<li>{$create_at}<a href="{$link}" title="点击查看" onclick="insertReadSMS('{$id}',{$value['type']});" >{$title}</a></li>
		{/if}
	{/foreach}
	<li style="text-align:right;padding: 0;"><a href="javascript:void(0);" onclick="markAllSMS()" title="标记全部">全部标记为已读</a></li>  
{/if}