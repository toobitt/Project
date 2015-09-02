{css:setting}

{code}

$watermark_list = array();
foreach ((array)$watermark['watermark_list'] as $k => $v )
{
    $watermark_list[$v['id']] = $v['config_name'];
}
$watermark_list[-1] = '不使用水印';
$watermark_list[0] = '继承水印设置';

{/code}
<ul class="form_ul">
    <li class="i"style="padding-top: 5px;">
        <div class="form_ul_div">
            <span  class="title">水印设置：</span>
            <div style="display:inline-block;width:255px">{template:form/select, watermark[watermark_id], $watermark['watermark_id'], $watermark_list}</div>
            <font class="important"></font>
        </div>
    </li>
</ul>