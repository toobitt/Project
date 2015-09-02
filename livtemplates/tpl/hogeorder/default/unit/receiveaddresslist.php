<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
    <div class="common-list-left ">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]">
                <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
            </a>
        </div>
    </div>
    <div class="common-list-right">

        <div class="common-list-item open-close wd100">
            <span>{$v['mobile']}</span>
        </div>

        <div class="common-list-item open-close" style="width: 400px;">
            {code}
                $address = $v['prov'] . $v['city'] . $v['area'] . $v['address_detail'];
            {/code}
            <span>{$address}</span>
        </div>

        <div class="common-list-item open-close wd100">
            <span>{$v['postcode']}</span>
        </div>

        <div class="common-list-item open-close wd120" style="width: 200px;">
            <span>{$v['email']}</span>
        </div>

        <div class="common-list-item open-close wd70">
            <span class="news-name">{$v['user_name']}</span>
        </div>

    </div>
    <div class="common-list-i" ></div>
    <div class="common-list-biaoti min-wd" style="min-width: 120px;">
        <div class="common-list-item biaoti-transition">
            <div class="common-list-overflow max-wd">
                <a href="#"  target="formwin">
                    <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['contact_name']}</span>
                </a>
            </div>
        </div>
    </div>
</li>
