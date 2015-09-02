<script type="text/javascript">
    var vs = hg_get_cookie('video_subinfo');
    $(document).ready(function(){
        $('#video_subinfo').css('display',vs?vs:'block');
    });
</script>

{code}
$order_info = $formdata['order'];
$item = $formdata['item'];
$address = $formdata['address'];
$pay_type = $formdata['pay_type'];
$trade_flow = $formdata['trade_flow'];
{/code}

{if $order_info['id']}
<div class="info clear cz" style="heigth:auto;min-height:0;" id="vodplayer_{$formdata['order']['trade_number']}">
    <span onclick="hg_close_opration_info();" title="关闭/ALT+Q" style="background: url('../../.././../livtemplates/tpl/lib/images/bg-all.png') -67px -70px no-repeat;width:26px;height:26px;top:2px;right:3px;display:inline-block;font-size:0;cursor:pointer;position:absolute;"></span>

    <h5>订单详情</h5>
    <p>订单状态：{$order_info['trade_status_text']}</p>
    <p>订单总金额(含运费): {$order_info['total_fee']}</p>
    <p>运费: {$order_info['delivery_fee']}</p>

    <p><span>收货人:</span> <span>{$address['contact_name']}</span> <span>{$address['mobile']}</span></p>
    {code}
        $address_string = $address['prov'] . $address['city'] . $address['area'] . $address['address_detail'];
    {/code}
    <p>收货地址: {$address_string}</p>

    <div style="clear: both">
        <p>订单号：{$order_info['trade_number']}</p>

        {if $order_info['out_trade_number']}
        <p>第三方订单号：{$order_info['out_trade_number']}</p>
        {/if}
        <p>第三方订单状态：{$order_info['out_trade_status_text']}</p>
        <p>创建时间：{$order_info['trade_create_time_format']}</p>

        {if $order_info['trade_deal_time_format']}
        <p>成交时间：{$order_info['trade_deal_time_format']}</p>
        {/if}

        {if $order_info['trade_confirm_time_format']}
        <p>确认时间：{$order_info['trade_confirm_time_format']}</p>
        {/if}

        <p>过期时间：{$order_info['trade_expire_time_format']}</p>
    </div>
</div>
<div class="info clear cz">
    <h5>订单商品</h5>
    <ul>
        {foreach $item as $k => $v}
        <li>
            <p>{$v['title']}</p>
            <p>{$v['product_fee']}元</p>
            <p>X{$v['product_nums']}</p>
            <p>{$v['departdate']}</p>
            <p>{$v['cc']}</p>
        </li>
        {/foreach}
    </ul>

</div>

<div class="info clear cz">
    <h5>交易流水</h5>
    <ul>
    <li>
    <span>支付平台</span>
    <span>交易类型</span>
    <span>交易流水号</span>
    <span>交易总额</span>
    <span>交易状态</span>
    </li>
    {foreach $trade_flow as $k => $v}
    <li>
        <span>{$v['pay_platform']}</span>

        <span>{$v['trade_type_text']}</span>

        <span>{$v['qn']}</span>

        <span>{$v['trade_fee']}</span>
        <span>{$v['trade_status_text']}</span>

    </li>
    {/foreach}
    </ul>
</div>

{else}
<div class="info clear cz" style="heigth:auto;min-height:0;" id="vodplayer_{$formdata['order']['trade_number']}">
    <span onclick="hg_close_opration_info();" title="关闭/ALT+Q" style="background: url('../../.././../livtemplates/tpl/lib/images/bg-all.png') -67px -70px no-repeat;width:26px;height:26px;top:2px;right:3px;display:inline-block;font-size:0;cursor:pointer;position:absolute;"></span>
    <span>此订单已经不存在,请刷新页面更新</span>
</div>
{/if}
