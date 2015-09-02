<li class="common-list-data clear" id="r_{$v['id']}"    name="{$v['id']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item weather-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    
	    {code}
			$dayk = array(
			0 => 'one',
			1 => 'two',
			2 => 'three',
			3 => 'four',
			4 => 'five',
			5 => 'six',
			);
		{/code}
		{for $i=0;$i<6;$i++}
		<div class="common-list-item weather-day">
            <div class="common-list-cell">
	            {code}
					$num  = count($v[$dayk[$i]]['img']);
					$url1 = $v[$dayk[$i]]['img'][0]['host'].$v[$dayk[$i]]['img'][0]['dir'].'70x65/'.$v[$dayk[$i]]['img'][0]['filepath'].$v[$dayk[$i]]['img'][0]['filename'];
					$url2 = $v[$dayk[$i]]['img'][1]['host'].$v[$dayk[$i]]['img'][1]['dir'].'70x65/'.$v[$dayk[$i]]['img'][1]['filepath'].$v[$dayk[$i]]['img'][1]['filename'];
				{/code}
				{if $num==1}
				<div>
					<img alt="" src="{$url1}">
				</div>
				{else}	
				<div>
					<div >
						<img alt="" src="{$url1}">
					</div>
					<div>
						<img alt="" src="{$url2}">
					</div>
				</div>
				{/if}
				<div>{$v[$dayk[$i]]['report']}</div>
				<div>{$v[$dayk[$i]]['temp']}</div>
				{if $i==0}
				<div onclick ="showRealtime({$v['id']})" style="position: relative">
					<p>实时天气</p>
					<div style="position: absolute;z-index:1;display:none;background:pink;width:200px"  id = "realtime_{$v['id']}">
						
					</div>		
				</div>
				{/if}
            </div>
        </div>
        {/for} 
        <div class="common-list-item weather-ck">
            <div class="common-list-cell">
                 <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" style="margin:4px 2px 0 0;" class="button_2">天气</a>
                 <a href="./run.php?mid={$_INPUT['mid']}&a=formpm25&id={$v['id']}&infrm=1" style="margin:4px 2px 0 0;" class="button_2">PM2.5</a>
                 <a  onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" style="margin:4px 2px 0 0;" class="button_2">删除</a>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item biaoti-transition">
			   <div class="common-list-cell">
			      <span class="common-list-overflow m2o-common-title" id="interview_title_{$v['id']}">{$v['city_name']}</span>
            </div>  
	    </div>
   </div>
</li> 
               