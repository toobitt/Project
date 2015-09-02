{template:head}

{code}
$attrs_for_edit = array('pub_url');
{/code}
{template:list/common_list}
{js:news/news_list}


<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display: none"{/if}>
<!--<div class="controll-area fr mt5" id="hg_page_menu" style="display: none">-->
<!--    <a  class="add-button news mr10" href="./run.php?mid={$_INPUT['mid']}&a=download&infrm=1&pay_status={$_INPUT['pay_status']}&trace_step={$_INPUT['trace_step']}&date_search={$_INPUT['date_search']}&title={$_INPUT['title']}">导出</a>-->
<!---->
<!--</div>-->
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height: auto; min-width: auto;">
    {if !$list}
    <p id="emptyTip"
       style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
    <script>hg_error_html('#emptyTip',1);</script>
    {else}
    <form method="post" action="" name="listform" class="common-list-form">
        <!-- 头部，记录的列属性名字 -->
        <ul class="common-list news-list">
            <li class="common-list-head public-list-head clear">
                <div class="common-list-left">
                    <div class="common-list-item paixu open-close">

                    </div>
                </div>
                <div class="common-list-right">
                    <div class="common-list-item open-close wd100">联系电话</div>
                    <div class="common-list-item open-close" style="width: 400px;">地址</div>
                    <div class="common-list-item open-close wd100">邮编</div>
                    <div class="common-list-item open-close wd120" style="width: 200px;">邮件</div>
                    <div class="common-list-item news-fenlei open-close wd70">添加人</div>
                </div>
                <div class="common-list-biaoti" style="min-width: 120px;">
                    <div class="common-list-item">收货人</div>
                </div>
            </li>
        </ul>
        <!-- 主题，记录的每一行 -->
        <ul class="news-list common-list public-list hg_sortable_list"
            id="newslist" data-table_name="article" data-order_name="order_id">
            {foreach $list as $k => $v}
            {template:unit/receiveaddresslist}
            {/foreach}
        </ul>
        <!-- foot，全选、批处理、分页 -->
        <ul class="common-list public-list">
            <li class="common-list-bottom clear">
                <div class="common-list-left">
                    <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
                </div>
                {$pagelink}
            </li>
        </ul>
    </form>
    {/if}
</div>

{template:unit/record_edit_address}
<!-- 移动框 -->
{template:foot}
