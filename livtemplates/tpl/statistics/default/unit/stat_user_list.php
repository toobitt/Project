                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
                    	<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" >
                    	<input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
                    	
                    	<a class="sharesltmix" style="cursor:pointer;" id="stat_username_{$v['id']}">
                    	<span class="m2o-common-title">{if $v['user_name']}{$v['user_name']}{else}</span>
                    	&nbsp;
                    	{/if}
                    	</a>
                    	{foreach $_configs['statistics_type_cn'] as $kk=>$vv}
                    	<a class="statistic_user" style="cursor:pointer;" name="stat_op_type_{$v['user_id']}">
                    	{if $stat_user[0]['record'][$v['user_id']][$kk]}
                    	{$stat_user[0]['record'][$v['user_id']][$kk]}
                    	{else}
                    	<font color=red>0</font>
                    	{/if}
                    	</a>
                    	{/foreach}
						<a class="statistic_user" style="cursor:pointer;" id="stat_total_{$v['user_id']}">
						{if $stat_user[0]['record'][$v['user_id']]['all']}
						{$stat_user[0]['record'][$v['user_id']]['all']}
						{else}
						<font color=red>0</font>
						{/if}
						</a>
						<a class="statistic_user" style="cursor:pointer;" id="share_title_{$v['id']}" 
						onclick="delete_record({$v['user_id']},{$_INPUT['module_uniqueid']},{$_INPUT['date_search']},{code}echo empty($_INPUT['start_time'])?0:$_INPUT['start_time']{/code},{code}echo empty($_INPUT['end_time'])?0:$_INPUT['end_time']{/code})">
						清空
						</a>
                    	</span>
                </li>   
                
                
