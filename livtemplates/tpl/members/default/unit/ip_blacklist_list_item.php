
<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        {if $v['deadline'] == -1 && $v['type'] ==2}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="black-status_{$v['id']}" style="color:red;">永久黑名单</a>
            </div>
        </div>
        {elseif $v['deadline'] == -1}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="black-status_{$v['id']}" style="color:#ff8c00;">App黑名单</a>
            </div>
        </div>
        {else}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="black-status_{$v['id']}" style="color:green;">解封状态</a>
            </div>
        </div>
        {/if}

        {if $v['deadline'] == -2}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="btn-box add-black-item" id="add-black_{$v['id']}" href="###" _identifier="{$v['identifier']}" _member_id="{$v['member_id']}" _ip="{$v['ip']}" _black_ip="0" _deadline="0" _type="0">解封黑名单</a>
            </div>
        </div>
        {elseif $v['deadline'] == -1}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="btn-box add-black-item" id="add-black_{$v['id']}" href="###" _identifier="{$v['identifier']}" _member_id="{$v['member_id']}" _ip="{$v['ip']}" _black_ip="1" _deadline="-1" _type="2">加入永久黑名单</a>
            </div>
        </div>
        {else}
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <a class="btn-box add-black-item" id="add-black_{$v['id']}" href="###" _identifier="{$v['identifier']}" _member_id="{$v['member_id']}" _ip="{$v['ip']}" _black_ip="1" _deadline="-1" _type="1">加入App黑名单</a>
            </div>
        </div>
        {/if}

        <div class="common-list-item wd120">
            <div class="common-list-cell">
                <span class="common-user">{$v['identifier']}/</span>
            </div>
            <div class="common-list-cell">
                <span class="common-user">{$v['app_name']}</span>
            </div>
        </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
            <a>
                <span class="common-list-overflow  fz14 m2o-common-title" style="display:inline-block;width: 90px;">{$v['member_id']}</span>
            </a>
            <a>
                <span class="common-list-overflow  fz14 m2o-common-title" style="display:inline-block;width: 90px;">{$v['member_name']}</span>
            </a>
            <a>
                <span class="common-list-overflow  fz14 m2o-common-title" style="display:inline-block;width: 100px;overflow: inherit;">{$v['ip']}</span>
            </a>
        </div>
    </div>
</li>