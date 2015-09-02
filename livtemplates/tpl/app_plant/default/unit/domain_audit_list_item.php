
<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd150">
                <span>{$v['app_name']}</span>
        </div>
    	<div class="common-list-item wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60" style="cursor:pointer;" onclick="hg_audit_tpl({$v['id']});">
                <span id="status_{$v['id']}">{$v['status_text']}</span>
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
       
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a onclick="window.open('{$v['web_url']}')">
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['web_url']}</span>
                </a>
        </div>
    </div>
</li>