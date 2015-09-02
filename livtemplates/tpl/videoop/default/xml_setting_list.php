{code}
if(!$_INPUT['article_status'])
{
    $_INPUT['article_status']=1;
}
if(!$_INPUT['date_search'])
{
    $_INPUT['date_search'] = 1;
}
{/code}
{template:head}
{code}
$attrs_for_edit = array('status');
{/code}
{template:list/common_list}
{code}
$js_data['status_color'] = $_configs['status_color'];
{/code}
<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
<script>
function hg_change_status(obj)
{
   var obj = obj[0];
   var status_text = "";
   if(obj.status == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 2)
   {
	   status_text = '已打回';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
   	   var color = globalData.status_color[status_text];
   	   
   	   //console.log(globalData.status_color);
	   $('#statusLabelOf'+obj.id[i]).text(status_text).css('color', color);
	   recordCollection.get(obj.id[i]).set('status', obj.status);
   }
   if($('#edit_show'))
   {
	   hg_close_opration_info();
   }
}
function hg_call_xml_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
	if($('#edit_show'))
	{
		hg_close_opration_info();
	}
}
function hg_call_reindex(id)
{
	console.log(id+'&&&');
}
function hg_call_rexml(id)
{
	console.log(id+'$$$');
}	
</script>
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
    <div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
        <a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="nodeFrame">新增xml配置</a>
    </div>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
    {if !$source}
    <p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">请先新增数据源！</p>
    <script>hg_error_html('#emptyTip',1);</script>
    {else}
    <p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
    <script>hg_error_html('#emptyTip',1);</script>
    {/if}
{else}
    <form method="post" action="" name="listform" class="common-list-form">
        <!-- 头部，记录的列属性名字 -->
        <ul class="common-list news-list">
            <li class="common-list-head public-list-head clear">
                <div class="common-list-left">
                    <div class="common-list-item paixu open-close">
                       <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
                    </div>
                </div>
                <div class="common-list-right">
                    <div class="common-list-item open-close" style="width:150px;">文件名</div>
                    <div class="common-list-item open-close" style="width:100px;">文件大小/M</div>
                    <div class="common-list-item open-close" style="width:100px;">生成间隔/秒</div>
                    <div class="common-list-item open-close" style="width:100px;">有效期/时</div>
                    <div class="common-list-item news-zhuangtai open-close wd60">状态</div>
                    <div class="common-list-item news-ren open-close wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
                    <div class="common-list-item">标题</div>
                </div>
            </li>
        </ul>
        <!-- 主题，记录的每一行 -->
        {code}
        	//hg_pre($list);
        {/code}
        <ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="article" data-order_name="order_id">
        {foreach $list as $k => $v}
            {template:unit/xml_list}
        {/foreach}
        </ul>
        <!-- foot，全选、批处理、分页 -->
        <ul class="common-list public-list">
            <li class="common-list-bottom clear">
                <div class="common-list-left">
                    <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
                </div>
                {$pagelink}
            </li>
        </ul>       
    </form> 
{/if}
</div>   
{template:unit/record_edit}
{template:foot}