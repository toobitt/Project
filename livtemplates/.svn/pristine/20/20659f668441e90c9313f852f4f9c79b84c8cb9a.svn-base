{template:head}
{css:ad_style}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
            <h2>新增应用</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div">
                        <span  class="title">应用名称：</span><input type="text" value='{$formdata["name"]}'  name='name' style="width:275px;">
                        <font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title">应用标识：</span><input type="text" name="app_uniqueid" value="{$formdata['app_uniqueid']}" style="width:275px;"><font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div"><span class="title overflow">版本：</span><input type="text" name="version" value="{$formdata['version']}"  style="width:275px;"><font class="important"></font>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div">
						<span class="title">描述：</span>
						<textarea class="t_c_b" onblur="textarea_value_onblur(this,'这里输入描述');" onfocus="textarea_value_onfocus(this,'这里输入描述');" cols="" rows="" name="brief">{$formdata['brief']}</textarea>
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