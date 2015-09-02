<li class="m2o-each common-list-data clear" _id="{$k}" data-id="{$k}" id="r_{$v['id']}" order_id="{$v['order_id']}"  name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
        		{code}
        		    $_url = '';
        			if(isset($v[0]['icon']))
        			{
        				$_img = $v[0]['icon'];
        				if($_img['dir'])
        				{
        					$_url = $_img['host'] . $_img['dir'] . '40x30/' .  $_img['filepath'] . $_img['filename'];
        				}
        				else
        				{
        					$_url = $_img['host'] . $_img['filepath'] . $_img['filename'] . '!small';
        				}
        			}
        		{/code}
                <img _src="{$_url}" width="40" height="30" />
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd80 overflow">
                <span>{$v[0][version_code]}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v[0][version_name]}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v[1][version_name]}</span>
        </div>
        <div class="common-list-item wd60">
                <span>{$v[0]['status_text']}</span>
        </div>

        <div class="common-list-item wd60">
                <span>{$v[1]['status_text']}</span>
        </div>
        
        <div class="common-list-item wd120">
                <span>{$v[0]['publish_time']}</span>
        </div>
    
        <div class="common-list-item wd120">
            <div class="common-list-cell">
            	<span class="common-user">{$v[0]['user_name']}</span>
                <span class="common-time">{$v[0]['app_create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v[0]['name']}</span>
                </a>
        </div>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</li>