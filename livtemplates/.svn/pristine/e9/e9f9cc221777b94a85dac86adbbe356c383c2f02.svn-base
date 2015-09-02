<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
                <img _src="{$v['img']}" width="40" height="30" />
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
        <div class="common-list-item wd80">
                <span>{$v['playcount_format']}</span>
        </div>
        <div class="common-list-item wd80" style="cursor:pointer;"  onclick="hg_audit_carpark({$v['id']});">
                <span id="status_{$v['id']}">{$v['status']}</span>
        </div>
        <div class="common-list-item wd80">
                <span>{$v['play_sort_name']}</span>
        </div>
        
        <div class="common-list-item wd80">
                <span>{$v['publisher']}</span>
        </div>
        
        <div class="common-list-item wd80">
                <span>{$v['copyright_limit']}</span>
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
                <a>
	                <span class="common-list-overflow max-wd fz14" style="display:inline-block;">{$v['title']}</span>
                </a>
        </div>
    </div>
</li>