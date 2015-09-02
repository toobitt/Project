{code}
	$notice = array();
	$notice = $notice_arr;
	$typeArr = array(0,1,2,3);  
	$stmp = $gtmp = array();
{/code}
{if !empty($notice)}
	{code}
		$i=1;
	{/code}
	{foreach $notice as $type => $notices}
 
		{if $i <= 5}
	
			{if in_array($type,$typeArr)} 
			
				{code}
				$idstr = array_shift($notices);
				$str = explode(',',$idstr);
				$j = 0;
				{/code}
				{foreach $str as $ids}
			
					{if $ids}
				
						{code}
						$j++;
						{/code}
					{/if}
				{/foreach}
				{if $type == 0}
			
					{code}
					$title = '系统通知'; 
					$link = 'javascript:void(0)';
					{/code}
				
				{else}
			
					{code}
					$title = $notices['content']['title'];
					$link = $notices['content']['page_link'];
					{/code}
				{/if}
				
				<li>{$j}条<a  href="{$link}" title="点击查看" onclick="insertReadSMS('{$idstr}',{$type});" >{$title}</a></li>
		
			
			{else}
		
				{foreach $notices as $id => $cons}
			
					{code}
					$title = $cons['content']['title'];
					$link = $cons['content']['page_link'];
					{/code}
					<li ><a  href="{$link}" title="点击查看" onclick="insertReadSMS('{$id}',{$type});" >{$title}</a></li>
			 	 
				{/foreach} 
			{/if}
		{/if}
		{code}
			$i++; 
		{/code}
	{/foreach}
	
	<li style="text-align:right;padding: 0;"><a href="javascript:void(0);" onclick="markAllSMS()" title="标记全部">全部标记为已读</a></li>  
{/if}