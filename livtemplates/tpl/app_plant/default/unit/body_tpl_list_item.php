<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" order_id="{$v['order_id']}"  name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
        		{code}
        		    $_url = '';
        			if($v['img_info'])
        			{
        				$_img = $v['img_info'];
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
    	<div class="common-list-item wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
        
        <div class="common-list-item wd60">
                <span>{$v['type_text']}</span>
        </div>
        
        <div class="common-list-item wd60" style="cursor:pointer;" onclick="hg_audit_tpl({$v['id']});">
                <span id="status_{$v['id']}">{$v['status_text']}</span>
        </div>
    
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['name']}</span>
                </a>
        </div>
    </div>
</li>