{code}
if(isset($plan))
{
	$formdata = $plan;
}
{/code}
{if !empty($formdata['time_arr'])}
{code}
	$mArr = $mKey = $lastEnd = array();
	$mNextStart = 0;
	$num = count($formdata['time_arr']);
{/code}
	{foreach $formdata['time_arr'] as $key => $value}
	{code} $mNextStart = ($key+1) < $num ? date('H:i:s',$formdata['time_arr'][$key+1]) : '23:59:59';{/code}
		  <tr class="default" align="left" valign="top">
			{for $i=1;$i<=7;$i++}
			{if !empty($formdata['info'][$i])}
				{code}	
					$mKey[$i] = $mKey[$i] ? $mKey[$i] : 0;
					$mArr[$i] = $mArr[$i] ? $mArr[$i] : 0;

					$single = $next = $next_next = $prev = array();
					if($mKey[$i])
					{
						$prev = $formdata['info'][$i][$mKey[$i]-1];
					}
					if($mKey[$i] < count($formdata['info'][$i]))
					{
						$single = $formdata['info'][$i][$mKey[$i]];
						if($mKey[$i] < (count($formdata['info'][$i])-1))
						{
							$next = $formdata['info'][$i][$mKey[$i]+1];
							if($mKey[$i] < (count($formdata['info'][$i])-2))
							{
								$next_next = $formdata['info'][$i][$mKey[$i]+2];
							}
						}
					}
				{/code}
				{if empty($single)}
					<td class="none" onclick="hg_plan_form(this,0,'{code} echo date('H:i:s',$value);{/code}','{$mNextStart}',{$i});"  onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
				{else}
					{if $mArr[$i]}
						{if $single['end'] == $value}
							{if !empty($next) && $next['start'] == $value}
								<td onclick="hg_plan_form(this,{$next['id']},'','',{$i});"><div class="item_start" style="{if empty($prev) || $prev['end'] != $single['start']}
									{if !empty($next_next) && $next_next['start'] == $next['end']}
										border-top:1px solid #EAF7FD;border-bottom:1px solid #EAF7FD;
									{else}
										{if !empty($single) && $single['end'] == $next['start']}
											border-top:1px solid #EAF7FD;
										{/if}
									{/if}
								{else}
									{if !empty($next_next) && $next_next['start'] == $next['end']}
											border-bottom:1px solid #EAF7FD;margin-top:-1px;height:41px
									{/if}
								{/if}"><span class="t">{code} echo date('H:i',$next['start']);{/code}-{code} echo date('H:i',$next['end']);{/code}</span><span class="n overflow">{$next['channel2_name']}<!-- {if $next['program_start_time']}{code} echo date('H:i:s', $next['program_start_time']) ;{/code} - {code} echo date('H:i:s', $next['program_start_time']+$next['toff']) ;{/code}{/if} --></span></div></td>
								{code}
									$mArr[$i] = 1;
									$mKey[$i]++;
									continue;
								{/code}
							{else}<td class="none" onclick="hg_plan_form(this,0,'{code} echo date('H:i:s',$value);{/code}','{$mNextStart}',{$i});" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>{code}
								$mArr[$i] = 0;
								$mKey[$i]++;
								continue;
							{/code}{/if}
						{else}
								<td onclick="hg_plan_form(this,{$single['id']},'','',{$i});"><div class="item_none"></div></td>
								{code}
									$mArr[$i] = 1;
									continue;
								{/code}	
						{/if}
					{else}					
						{if $single['start'] == $value}
							<td onclick="hg_plan_form(this,{$single['id']},'','',{$i});"><div class="item_start"><span class="t">{code} echo date('H:i',$single['start']);{/code}-{code} echo date('H:i',$single['end']);{/code}</span><span class="n overflow">{$single['channel2_name']}<!-- {if $single['program_start_time']}{code} echo date('H:i:s', $single['program_start_time']) ;{/code} - {code} echo date('H:i:s', $single['program_start_time']+$single['toff']) ;{/code}{/if} --></span></div></td>
							{code}
								$mArr[$i] = 1;
								continue;
							{/code}
						{else}
							<td class="none" onclick="hg_plan_form(this,0,'{code} echo date('H:i:s',$value);{/code}','{$mNextStart}',{$i});"  onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>{code}
									$mArr[$i] = 0;
									continue;
								{/code}
						{/if}
					{/if}
				{/if}
			{else}<td class="none" onclick="hg_plan_form(this,0,'{code} echo date('H:i:s',$value);{/code}','{$mNextStart}',{$i});" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>{/if}			
			{/for}
		  </tr>
	{/foreach}
{else}
	<tr class="default" align="left" valign="top">
		<td class="none" onclick="hg_plan_form(this,'','','',1);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',2);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',3);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',4);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',5);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',6);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
		<td class="none" onclick="hg_plan_form(this,'','','',7);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></td>
	</tr>
{/if}

