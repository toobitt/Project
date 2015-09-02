<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right" style="width:850px;">
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
        <div class="common-list-item wd80 overflow">
                <span>{$v['version']}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v['systemversion']}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v['platform']}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v['ddversion']}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{if $v['isdev']}是{else}否{/if}</span>
        </div>
        <div class="common-list-item wd180 overflow">
                <span>{$v['description']}</span>
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
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['app_name']}</span>
                </a>
        </div>
    </div>
</li>