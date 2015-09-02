<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
    <div class="common-list-left ">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]">
                <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
            </a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd100 news-zhuangtai open-close">
            <div class="common-switch-status">
                <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$v['status_text']}</span>
            </div>
        </div>
        <div class="common-list-item wd100 news-ren open-close">
            <span class="news-name">{$v['user_name']}</span>
            <span class="news-time" >{$v['create_time_show']}</span>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
    {code}
    if(!$v['outlink']) {
    $href = './run.php?mid='.$_INPUT['mid'].'&a=form&id='.$v['id'].'&infrm=1';
    $classname = '';
    }
    else {
    $href = './run.php?mid='.$_INPUT['mid'].'&a=form_outerlink&id='.$v['id'];
    /*$classname = 'out-color';*/
    $classanme = '';
    }
    {/code}
    <div class="common-list-biaoti min-wd">
        <div class="common-list-item biaoti-transition">
            <div class="common-list-overflow max-wd">
                <a href="{$href}">
                    {code}
                    $indexpic_url = '';
                    if ($v['indexpic_url']) {
                    $indexpic_url = $v['indexpic_url']['host'] . $v['indexpic_url']['dir'] .'40x30/'. $v['indexpic_url']['filepath'] . $v['indexpic_url']['filename'];
                    }
                    {/code}
                    {if $v['indexpic_url']}
                    <img  _src="{$indexpic_url}"  class="img_{$v['id']} biaoti-img"/>
                    {/if}
                    <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
                    {if $v['outlink']}
                    <a class="news-outer" title="外链"></a>
                    {/if}
                </a>
            </div>
        </div>
    </div>
</li>
