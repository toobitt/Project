<style>
.u_notice {}
.u_notice ul li{border-bottom: 1px dotted #C7C7C7;
    height: 22px;
    line-height: 22px;
    padding-left: 15px;}
.history_no{background: none repeat scroll 0 0 #F7F7F7;font-size: 13px;height: 25px;line-height: 25px;padding-left: 5px;display: block;}
</style>
<div class="u_notice">
<ul>
{code}
$typeArr = array(0,1,2,3);  
{/code}

{if is_array($un_notice)}
  
	{foreach  $un_notice as $key => $value}
	  
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
	<li style="text-align:right;padding: 0;"><a href="javascript:void(0);" onclick="markAllSMS();" title="标记全部">全部标记为已读</a></li>  
{else}

<li>暂无通知信息......</li>
{/if}
</ul>
</div>
{$showpages}
