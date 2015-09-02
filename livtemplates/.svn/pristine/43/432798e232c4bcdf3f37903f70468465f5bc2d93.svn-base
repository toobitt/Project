<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
    <div class="common-list-left ">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]">
                <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
            </a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item" style="width:450px;">
            <div class="common-list-cell">
            <span>{$v['brief']}</span>
            </div>
        </div>

        <div class="common-list-item wd100 news-zhuangtai">
            <div class="common-switch-status">
                <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$v['status_text']}</span>
            </div>
        </div>
        <div class="common-list-item wd100 news-ren">
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
    <div class="common-list-biaoti">
        <div class="common-list-item biaoti-transition">
            <div class="common-list-overflow">
                <a href="{$href}">
                    {code}
                    $indexpic_url = '';
                    if ($v['indexpic']) {
                    $indexpic_url = $v['indexpic'][0]['host'] . $v['indexpic'][0]['dir'] .'40x30/'. $v['indexpic'][0]['filepath'] . $v['indexpic'][0]['filename'];
                    }
                    {/code}
                    {if $indexpic_url}
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
