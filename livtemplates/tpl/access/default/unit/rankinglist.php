<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px;">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item circle-zt">
            <div class="common-list-cell">
                    <span><a href="./run.php?mid={$relate_module_id}&a=show&sort_id={$v['id']}&infrm=1">排行详情</a><span>
            </div>
        </div>    
        <div class="common-list-item circle-bj">
            <div class="common-list-cell" style="width:48px;">
                    <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
            </div>
        </div>
        <div class="common-list-item circle-sc">
            <div class="common-list-cell">
                    <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
            </div>
        </div>
        <div class="common-list-item circle-zt">
            <div class="common-list-cell">
                    <span id="statusLabelOf{$v['id']}">{$v['status']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr"  style="width:130px;">
            <div class="common-list-cell">
                    <span>{$v['user_name']}<br />{$v['create_time']}</span>
            </div>
        </div>
    </div>
    <div class="circle-title overflow" style="cursor:pointer;">
        <div class="common-list-cell">
             <span id="title_{$v['id']}" class="m2o-common-title">{$v['title']}</span>
        </div>          
   </div>
</li>