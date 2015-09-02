
<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        {if $v['is_black']}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
            	<a class="btn-box add-black-item" id="add-black_{$v['id']}" href="###" _sort_id="{$v['id']}" _app_id="{$v['app_id']}" _black_seekhelp="0">解封黑名单</a>
            </div>
       </div>
        {else}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
            	<a class="btn-box add-black-item" id="add-black_{$v['id']}" href="###" _sort_id="{$v['id']}" _app_id="{$v['app_id']}" _black_seekhelp="1">加入黑名单</a>
            </div>
       </div>
        {/if}
        
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
            <a>
                <span class="common-list-overflow wd180 fz14 m2o-common-title" style="display:inline-block;">{$v['user_name']}</span>
            </a>
        </div>
        <div class="common-list-item biaoti-transition">
        		<a>
	                <span class="common-list-overflow wd180 fz14 m2o-common-title" style="display:inline-block;">{$v['app_name']}</span>
                </a>
        </div>
        <div class="common-list-item biaoti-transition">
        		<a>
	                <span class="common-list-overflow wd120 fz14 m2o-common-title" style="display:inline-block;">{$v['name']}</span>
                </a>
        </div>
    </div>
</li>