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
            <h2>{$optext}收货地址</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">收货人：</span><input type="text" value='{$formdata["contact_name"]}' name='contact_name' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">联系电话：</span><input type="text" value='{$formdata["mobile"]}' name='mobile' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">省份：</span><input type="text" value='{$formdata["prov"]}' name='prov' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">城市：</span><input type="text" value='{$formdata["city"]}' name='city' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">区县：</span><input type="text" value='{$formdata["area"]}' name='area' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">详细地址：</span><input type="text" value='{$formdata["address_detail"]}' name='address_detail' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">邮编：</span><input type="text" value='{$formdata["postcode"]}' name='postcode' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">邮箱：</span><input type="text" value='{$formdata["email"]}' name='email' class="title">
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">设为默认：</span>
                        <label><input type="radio" value="1" name="isdefault" class="n-h" {if $formdata['isdefault'] == 1}checked{/if}><span>是</span></label>
                        <label><input type="radio" value="1" name="isdefault" class="n-h" {if $formdata['isdefault'] == 0}checked{/if}><span>否</span></label>
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