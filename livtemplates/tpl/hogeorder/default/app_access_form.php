{template:head}
{code}
if($id)
{
$optext="更新";
$ac="update";
}
else
{
$optext="新增";
$ac="create";
}
{/code}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
            <h2>{$optext}应用</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">标题：</span><input type="text" value='{$formdata["title"]}' name='title' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">应用标识：</span><input type="text" value='{$formdata["app_uniqueid"]}' name='bundle' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">HOST：</span><input type="text" value='{$formdata["host"]}' name='host' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">DIR：</span><input type="text" value='{$formdata["dir"]}' name='dir' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">请求文件：</span><input type="text" value='{$formdata["request_file"]}' name='request_file' class="title">
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">扣库存方法：</span><input type="text" value='{$formdata["sub_store_func"]}' name='sub_store_func' class="title">
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">恢复库存方法：</span><input type="text" value='{$formdata["add_store_func"]}' name='add_store_func' class="title" disabled>
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">商品详情方法：</span><input type="text" value='{$formdata["good_detail_func"]}' name='good_detail_func' class="title" disabled>
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">第三方订单详情方法：</span><input type="text" value='{$formdata["order_detail_func"]}' name='order_detail_func' class="title" disabled>
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">第三方订单支付方法：</span><input type="text" value='{$formdata["pay_func"]}' name='pay_func' class="title" disabled>
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">接口秘钥：</span><input type="text" value='{$formdata["token"]}' name='token' class="title">
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">订单过期时间：</span><input type="text" value='{$formdata["trade_expire_time"]}' name='trade_expire_time' class="title">
                    </div>
                </li>

                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">支付方式：</span>
                        {if $_configs['pay_type']}
                        {foreach $_configs['pay_type'] as $k => $v}
                        <label>
                            <input type="checkbox" value="{$v['uniqueid']}" name="pay_type[]" class="n-h" {if in_array($v['uniqueid'], (array)$formdata['pay_type'])}checked{/if}><span>{$v['title']}</span>
                        </label>
                        {/foreach}
                        {else}
                        <span>无支付方式</span>
                        {/if}
                    </div>
                </li>
            </ul>
            <input type="hidden" name="a" value="{$ac}" />
            <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <br />
            <input type="submit" id="submit_ok" name="sub" value="保存" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
        </form>
    </div>
    <div class="right_version">
        <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
    </div>
</div>

{template:foot}