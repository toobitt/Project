{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{css:ad_style}
{css:common/common_list}
{code}
$attr_for_edit = array('id');
foreach ($list as $k => $v) {
    $less_list[$k] = array();
    foreach ($attr_for_edit as $attr) {
        $less_list[$k][$attr] = $v[$attr];
    }
}
$js_data['list'] = $less_list;
{/code}
<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:ad}
<script type="text/javascript">
    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";
    function hg_road_delete(id)
    {
        if(confirm('您确定要删除此条记录?'))
        {
            var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
            hg_request_to(url); 
        }
    }
</script>   
<style>
<!--
.nodata
{
    color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;
}
-->
</style>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
   <a class="blue mr10" href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&ac=add&id=1" target="nodeFrame">
        <span class="left"></span>
        <span class="middle"><em class="add">新增</em></span>
        <span class="right"></span>
    </a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <form method="post" action="" name="listform">
                    <ul class="common-list public-list-head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item" style="width:35px"></div>
                            </div> 
                             <div class="common-list-right">
                                <!--<div class="circle-tjr common-list-item open-close">操作</div>-->
                                <div class="circle-tjr common-list-item open-close">配送方式</div>
                                <div class="circle-tjr common-list-item open-close">省份</div>
                                <div class="circle-tjr common-list-item open-close">运费</div>
                            </div>  
                            <div class="common-list-biaoti">
                                <div class="common-list-item">
                                    省份
                                </div>
                            </div>                     
                        </li>
                    </ul>
                    <ul class="common-list" id="contri_sortlist">
                        {if $list && is_array($list)}
                            {foreach $list as $k => $v} 
                              {template:unit/DeliveryFeeListRow}
                            {/foreach}
                        {else}
                        <p class="nodata">没有您要找的内容！</p>
                        {/if}
                    </ul>
                    
                  <ul class="common-list">
                    <li class="common-list-bottom clear">
                        <div class="common-list-left">
                        </div>
                        {$pagelink}
                    </li>
                 </ul>  
                </form>
           </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</div>
{template:unit/record_edit}
<script type="text/javascript">
function hg_call_del(id)
{
     var ids=id.split(",");
     hg_close_opration_info();
     for(var i=0;i<ids.length;i++)
     {
        $("#r_"+ids[i]).remove();
     }
}
</script>
{template:foot}
