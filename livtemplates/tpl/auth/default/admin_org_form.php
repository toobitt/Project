{css:ad_style}
{css:column_node}
{js:column_node}
<form action="run.php" method="post" enctype="multipart/form-data" class="ad_form h_l" id="admin_org" name="admin_org" onsubmit="return hg_ajax_submit('admin_org');">
    <ul class="form_ul">
        <li class="i">
            <div class="form_ul_div">
                <span  class="title">组织名称：</span>
                <input  type="text" name="org_name" style="width:440px;"  class="info-title info-input-left t_c_b" value="{$formdata['name']}" />
            </div>
        </li>
        <li class="i">
            <div class="form_ul_div">
                <span class="title">组织简介：</span>
                <textarea rows="2" class="info-description info-input-left t_c_b" name="org_desc" >{$formdata['brief']}</textarea>
            </div>
        </li>
        <li class="i">
            <div class="form_ul_div clear">
                <span class="title">上级组织：</span>
                {code}
                $hg_attr['node_en'] = 'admin_org';
                {/code}
                {template:unit/class,father_org_id,$formdata['fid'], $node_data}
            </div>
        </li>
    </ul>
    <input type="hidden" name="a" value="{$a}" />
    <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
    <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
    <br />
    <input type="submit" name="sub" value="{$optext}" class="button_6_14" />
</form>