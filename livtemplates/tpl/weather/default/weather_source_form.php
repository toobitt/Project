{template:head}
{css:ad_style}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
            <h2>新增天气预报源</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div">
                        <span  class="title">服务商：</span><input type="text" value='{$formdata["support_name"]}'  name='support_name' style="width:275px;">
                        <font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title">官方网站：</span><input type="text" name="official_site" value="{$formdata['official_site']}" style="width:275px;"><font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title overflow">天气查询API：</span><input type="text" name="weather_api_url" value="{$formdata['weather_api_url']}"  style="width:275px;"><input type="text" name="weather_api_dir" value="{$formdata['weather_api_dir']}"  style="width:75px;"><font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title overflow">城市查询API：</span><input type="text" name="city_api_url" value="{$formdata['city_api_url']}"  style="width:275px;"><input type="text" name="city_api_dir" value="{$formdata['city_api_dir']}"  style="width:75px;"><font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title overflow">方法名：</span><input type="text" name="inner_func" value="{$formdata['inner_func']}"  style="width:275px;"><font class="important">用于内部函数实现</font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear"><span class="title">启用：</span><label><input type="checkbox" class="n-h" name="is_open" {if $formdata['is_open']}checked="checked"{/if} value="1"></label>
                    </div>
                </li>
            </ul>
            <input type="hidden" name="a" value="{$a}" />
            <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <br />
            <input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
        </form>
    </div>
    <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}