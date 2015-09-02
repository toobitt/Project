<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
    <div class="common-list-left ">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]">
                <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
            </a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item common-list-pub-overflow news-fabu open-close">
            <div class="common-list-pub-overflow">
            </div>
        </div>

        <div class="common-list-item news-quanzhong open-close wd60">
            <span>{$v['total_fee']}元</span>
        </div>

        <div class="common-list-item news-ren open-close wd100">
            <select name="tracestep"  _trade_number="<?php echo $v['trade_number'];?>" class="trace_step">
                {code}
                foreach ($_configs['trade_status'] as $status => $status_text)
                {
                    if ($v['trade_status'] == $status)
                    {
                        echo '<option value="'.$status.'" selected="selected" >'.$status_text.'</option>';
                    }
                    else
                    {
                        echo '<option value="'.$status.'">'.$status_text.'</option>';
                    }
                }
                {/code}
            </select>
        </div>

        <div class="common-list-item open-close wd100">
            <span class="news-name" style="display: block;">{$v['user_name']}</span>
            <span class="news-time" style="color: #888;font-size: 10px;display: block;width: 130px;">{$v['trade_create_time']}</span>
        </div>

    </div>
    <div class="common-list-i" ></div>
    <div class="common-list-biaoti min-wd" onclick="hg_show_opration_info('{$v['trade_number']}');">
        <div class="common-list-item biaoti-transition">
            <div class="common-list-overflow max-wd">
                <a href="#"  target="formwin">
                    <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['trade_number']}</span>
                </a>
            </div>
        </div>
    </div>
</li>
