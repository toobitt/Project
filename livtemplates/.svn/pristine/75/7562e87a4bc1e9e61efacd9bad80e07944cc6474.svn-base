{template:head}

{code}
    $attrs_for_edit = array('pub_url');
{/code}
{template:list/common_list}
{js:news/news_list}
{css:edit_video_list}
{js:vod_opration}

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
                    <div class="common-list-item news-quanzhong open-close wd60">订单价格</div>
                    <div class="common-list-item news-ren open-close wd100">状态</div>
                    <div class="common-list-item news-fenlei open-close wd100">下单人/时间</div>
                </div>
                <div class="common-list-biaoti">
                    <div class="common-list-item">订单编号</div>
                </div>
            </li>
        </ul>
        <!-- 主题，记录的每一行 -->
        <ul class="news-list common-list public-list hg_sortable_list"
            id="newslist" data-table_name="article" data-order_name="order_id">
            {foreach $list as $k => $v}
                {template:unit/orderlist}
            {/foreach}
        </ul>
        <!-- foot，全选、批处理、分页 -->
        <ul class="common-list public-list">
            <li class="common-list-bottom clear">
                <div class="common-list-left">
                    <input type="checkbox" name="checkall" value="infolist" title="全选"
                           rowtag="LI" />
                </div> {$pagelink}
            </li>
        </ul>

        <div class="edit_show">
            <span class="edit_m" id="arrow_show" style="position:absolute;"></span>
            <div id="edit_show"></div>
        </div>

    </form>
    {/if}
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $('.trace_step').change(function(){
            var stat = $(this).children('option:selected').val();
            var trade_number = $(this).attr("_trade_number");
            $.ajax({
                url:'./run.php?mid={$_INPUT['mid']}',
                cache:false,
                type:'POST',
                data: {trade_number : trade_number,status:stat,a:'update_trade_status'},
                success:function(datas)
                {
                    //$('#get_orgs').html(datas);
                }
            });
        });
    });

</script>

<div id="add_share"></div>
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip" class="ordertip"></div>
<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
<!-- 移动框 -->
{template:foot}
