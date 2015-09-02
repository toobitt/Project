<li class="m2o-each common-list-data clear" _id="{$v['app_id']}" data-id="{$v['app_id']}" id="r_{$v['app_id']}" order_id="{$v['order_id']}"  name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
        		{code}
        			$_url = '';
        			if($list['app'][$v['app_id']]['icon'])
        			{
        				$_icon = $list['app'][$v['app_id']]['icon'];
        				if($_icon['dir'])
        				{
        					$_url  = $_icon['host'] . $_icon['dir'] . '40x30/' . $_icon['filepath'] . $_icon['filename'];
        				}
        				else
        				{
        					$_url  = $_icon['host'] . $_icon['filepath'] . $_icon['filename'] . '!small';
        				}
        			}
        		{/code}
                <img _src="{if $_url}{$_url}{/if}" width="40" height="30" />
        </div>
    </div>
    <div class="common-list-right">
        
        <div class="common-list-item wd180 overflow">
        		{if !$list['version'][$v['app_id']]['release']}
                <span>暂未打包</span>
                {else}
                	{if $list['version'][$v['app_id']]['release']['android']}
                		<span>安卓：{$list['version'][$v['app_id']]['release']['android']['version_name']} 状态：{$_configs['unpack'][$list['version'][$v['app_id']]['release']['android']['status']]}</span>
                		<br/>
                	{/if}
                	
                	{if $list['version'][$v['app_id']]['release']['ios']}
                		<span>ios：{$list['version'][$v['app_id']]['release']['ios']['version_name']} 状态：{$_configs['unpack'][$list['version'][$v['app_id']]['release']['ios']['status']]}</span>
                		<br/>
                	{/if}
                {/if}
        </div>
        
        <div class="common-list-item wd180 overflow">
        		{if !$list['version'][$v['app_id']]['debug']}
                <span>暂未打包</span>
                {else}
                	{if $list['version'][$v['app_id']]['debug']['android']}
                		<span>安卓：{$list['version'][$v['app_id']]['debug']['ios']['version_name']} 状态：{$_configs['unpack'][$list['version'][$v['app_id']]['debug']['android']['status']]}</span>
                		<br/>
                	{/if}
                	
                	{if $list['version'][$v['app_id']]['debug']['ios']}
                		<span>ios：{$list['version'][$v['app_id']]['debug']['ios']['version_name']} 状态：{$_configs['unpack'][$list['version'][$v['app_id']]['debug']['ios']['status']]}</span>
                		<br/>
                	{/if}
                {/if}
        </div>
        
        <div class="common-list-item wd180 overflow">
                <span>{$v['install_num']}</span>
        </div>
        
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$list['app'][$v['app_id']]['name']}</span>
                </a>
        </div>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</li>