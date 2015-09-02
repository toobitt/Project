{if is_array($formdata)}
{code}
$date = array(
	"00:00~09:00" => array("is_first" => 1,"show"=>"00:00"),
	"09:00~13:00" => array("is_first" => 0,"show"=>"09:00"),
	"13:00~19:00" => array("is_first" => 0,"show"=>"13:00"),
	"19:00~24:00" => array("is_first" => 0,"show"=>"19:00"),
	);
file_put_contents('111222.txt',var_export($formdata,1));
{/code}
{foreach $formdata as $key => $value}
<table border="0" cellpadding="0" cellspacing="0" class="bor">
	<tr>
		{foreach $value as $k => $v}
			{code}
				$class = $show = "";
				if(!$date[$k]['is_first'])
				{
					$class = ' class="normal" ';
					$show = $date[$k]['show'];
				}
				else
				{
					$show = $key;
				}
			{/code}
				<td valign="top" class="time">
				  <h4 {$class}>{$show}</h4>
					{if is_array($v)}
					<ul>
						{foreach $v as $ks => $vs}
							{if $vs['display']&& !$vs['now_display']}
							<li class="default"><a onclick="hg_copy_record({$vs['id']});"><span class="text overflow">{$vs['theme']}</span><span class="time">{$vs['start']}</span></a>
							<input id="theme_{$vs['id']}" value="{$vs['theme']}" type="hidden"/>
							<input id="subtopic_{$vs['id']}" value="{$vs['subtopic']}" type="hidden"/>
							<input id="starts_{$vs['id']}" value="{$vs['starttime']}" type="hidden"/>
							<input id="ends_{$vs['id']}" value="{$vs['endtime']}" type="hidden"/>{/if}
							{if !$vs['display'] && !$vs['now_display']}
							<li class="none"><a><span class="text overflow">{$vs['theme']}</span><span class="time">{$vs['start']}</span></a>
							{/if}
							{if $vs['now_display']}
							<li class="none"><a><span class="text overflow">{$vs['theme']}</span><span class="now_play"></span><span class="time">{$vs['start']}</span></a>
							{/if}
							</li>
						{/foreach}
					</ul>
					{/if}
				</td>
		{/foreach}
	</tr>
</table>{/foreach}{/if}