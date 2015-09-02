{code}
$states = array('未启用','启用');
$types  = array(1=>'系统',2=>'用户');
{/code}
<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" >
                <input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/>
                </a>
            </div>
        </div>
    </div>

    <div class="common-list-right">
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell" style="width:72px;">
                <a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&ac=update"> <em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
                <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"> <em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
            </div>
        </div>

        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                <span>{$v['brief']}</span>
            </div>
        </div>
         
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                <span>{$v['url']}</span>
            </div>
        </div>
        
        <!--
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                <span>{$v['ip']}</span>
            </div>
        </div>
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                <span>{$v['port']}</span>
            </div>
        </div>
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                <span>{$v['status']}</span>
            </div>
        </div>
        -->
    </div>

    <div class="common-list-biaoti">
        <div class="common-list-item  biaoti-transition">
            <div class="common-list-cell">
                <span class="m2o-common-title">
                <a href="./run.php?mid={$relate_module_id}&a=show&site_id={$v['id']}&infrm=1">
                {$v['title']}
                </a>
                </span>
            </div>
        </div>
    </div>

</li>

