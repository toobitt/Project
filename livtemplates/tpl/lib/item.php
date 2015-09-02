<!--<div class="item a"><span class="t">06:15-07:00</span><span class="n overflow">精彩节目精彩节目精彩节目</span></div>
<div class="item_none"></div>

<a class="none" title="" onclick="hg_plan_form(this);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></a>



				{code}
					if($v['start']==$value)		
					{
					{/code}
						<div class="item_start"><span class="t">{$v['start']}-{$v['end']}</span><span class="n overflow">{$v['program_name']}</span></div>
					{code}
					}elseif($v['end']==$value)
					{
					{/code}<div class="item_end"></div>{code}
					}else{
					{/code}<div class="item_none"></div>{code}
					}
				{/code}

{foreach  as $k => $v}
					{if $v['start'] == $value}
						<div class="item_start"><span class="t">{$v['start']}-{$v['end']}</span><span class="n overflow">{$v['program_name']}</span></div>
						{code}
							$mArr[$i] = 1;
							break;
						{/code}
					{/if}

					{if $v['end'] == $value}
						<div class="item_end"></div>
						{code}
							$mArr[$i] = 0;
							break;
						{/code}
					{else}
						{if $mArr[$i]}
							<div class="item_none"></div>
							{code}
								$mArr[$i] = 1;
								break;
							{/code}
						{else}
							<a class="none" title="" onclick="hg_plan_form(this);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></a>
							{code}
								$mArr[$i] = 0;
								break;
							{/code}
						{/if}
					{/if}

				{/foreach}
-->

<?php


echo strtotime("07:00:00")."<br />";
echo date('Y-m-d H:i:s',1324076400);




				<!--
					{if $mArr[$i]}
						{if $v['end'] == $value}
							<div class="item_end"></div>
							{code}
								$mArr[$i] = 0;
								break;
							{/code}
						{else}
						<div class="item_none"></div>
							{code}
								$mArr[$i] = 1;
								break;
							{/code}
						{/if}
					{else}					
						{if $v['start'] == $value}
							<div class="item_start"><span class="t">{code} echo date('H:i',$v['start']);{/code}-{code} echo date('H:i',$v['end']);{/code}</span><span class="n overflow">{$v['program_name']}</span></div>
							{code}
								$mArr[$i] = 1;
								break;
							{/code}
						{else}
							<a class="none" title="" onclick="hg_plan_form(this);" onmouseover="hg_plan_none(this,1);" onmouseout="hg_plan_none(this,0);"></a>
						{/if}
					{/if}
					-->