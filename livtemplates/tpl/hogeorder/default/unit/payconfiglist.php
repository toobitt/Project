<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
    <div class="common-list-left ">

    </div>
    <div class="common-list-right">

        <div class="common-list-item" style="width:550px;">
            <span>{$v['brief']}</span>
        </div>
        <div class="common-list-item wd70">
            <span style="color:{$_configs['status_color'][$v['status']]};">{$v['status_text']}</span>
        </div>
        <div class="common-list-item wd70">
            <a href="run.php?mid={$_INPUT['mid']}&a=form_{$v['uniqueid']}&pay_type={$v['uniqueid']}"><span>配置</span></a>
        </div>

    </div>

    <div class="common-list-biaoti min-wd">
        <div class="common-list-item biaoti-transition">
            <div class="common-list-overflow max-wd">
                <a href="#"  target="formwin">
                    <img src="{$RESOURCE_URL}/hogepay/{$v['uniqueid']}.jpg" class="biaoti-img" style="width:70px;"/>
                    <span class="m2o-common-title">{$v['title']}</span>
                </a>
            </div>
        </div>
    </div>
</li>
