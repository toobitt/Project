{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$list = $circle_list;
{/code}
{template:list/common_list}
{css:circle_list}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
    <div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
        <a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">添加圈子</a>
    </div>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$circle_list}
    <p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
    <script>hg_error_html('#emptyTip',1);</script>
{else}
    <!-- 搜索表单 -->
    <div class="common-list-search" id="info_list_search">
      <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
        <div class="right_1">
            {code}
                $attr_date = array(
                    'class' => 'colonm down_list data_time',
                    'show' => 'colonm_show',
                    'width' => 104,/*列表宽度*/
                    'state' => 1,/*0--正常数据选择列表，1--日期选择*/
                );
            {/code}
            {template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
            <input type="hidden" name="a" value="show" />
            <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="_id" value="{$_INPUT['_id']}" />
            <input type="hidden" name="_type" value="{$_INPUT['_type']}" />
        </div>
        <div class="right_2">
            <div class="button_search">
                <input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
            </div>
            {template:form/search_input,k,$_INPUT['k']}                        
        </div>
        </form>
    </div>
    
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
                    <div class="common-list-item open-close circle-ms">描述</div>
                    <div class="common-list-item open-close">状态</div>
                    <div class="common-list-item open-close wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
                    <div class="common-list-item">标题</div>
                </div>
            </li>
        </ul>
        <!-- 主题，记录的每一行 -->
        <ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="article" data-order_name="order_id">
        {foreach $circle_list as $k => $v}
            {template:unit/circlelist}
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

<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>

<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
<script type="text/javascript">
	function hg_call_sort_del(id)
	{
		 var ids=id.split(",");
		 for(var i=0;i<ids.length;i++)
		 {
			$("#r_"+ids[i]).remove();
		 }
	}
</script>   
{template:foot}