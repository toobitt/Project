                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
                    	<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" >
                    	<input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
                    	<a class="sharesltmix"><span class="m2o-common-title">{code}if($app[$v['app_uniqueid']]['name']) echo $app[$v['app_uniqueid']]['name']; else echo "&nbsp;";{/code}</span></a>
                    		<a class="sharesltmix" style="cursor:pointer;" id="share_title_{$v['id']}">{$v['content_id']}</a>
                    		<a class="sharesltmix">{code}if($v['user_name'])echo $v['user_name']; else echo "&nbsp;";{/code}</a>
                    		<a class="sharesltmix">{code}if($v['douser_name'])echo $v['douser_name']; else echo "&nbsp;";{/code}</a>
                    		<a class="sharesltmix">{code}echo empty($v['value'])?$_configs['statistics_type_cn'][$v['type']]:$v['value'];{/code}</a>
                    		<a class="shareslt">{code}echo date('Y-m-d H:i:s',$v['create_time']){/code}</a>
                    		<a class="shareslt" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除记录</a>
						
                    	</span>
                </li>   
                
                
