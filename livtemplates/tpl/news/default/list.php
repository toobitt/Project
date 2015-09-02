<?php 
/* $Id: list.php 32546 2014-06-24 09:05:24Z hujinxia $ */
?>
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
$attrs_for_edit = array('pub_url', 'catalog');
{/code}
{template:list/common_list}
{css:news_list}
{js:2013/cloud_pop}
{js:news/news_list}
{code}
//print_r($list);
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/news_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		{if $_configs['is_cloud']}<a class="add-yuan-btn add-button news mr10"  gmid="{$_INPUT['mid']}" nodevar="news_node">{$_configs['is_cloud']}</a>{/if}
		<a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=detail&infrm=1" target="formwin">添加文稿</a>
		<a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form_outerlink&infrm=1" target="formwin">添加外链</a>
	</div>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
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
					<div class="common-list-item news-fabu common-list-pub-overflow">发布至</div>
                    <div class="common-list-item  news-fenlei open-close wd70">分类</div>
                    <div class="common-list-item news-quanzhong open-close wd60">权重</div>
                    <div class="common-list-item news-zhuangtai open-close wd60">状态</div>
                    <div class="common-list-item news-tuisong open-close wd60">推送</div>
                    <div class="common-list-item news-ren open-close wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">标题</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
        <script>
            function hg_get_ids()
            {
                var inputs = document.getElementsByTagName("input");
                var checkboxArray = [];
                for(var i=0;i<inputs.length;i++){
                    var obj = inputs[i];
                    if(obj.type=='checkbox' && obj.checked == true){
                        checkboxArray.push(obj.value);
                    }
                }
                return checkboxArray;
            }

            function hg_outpush_news()
            {
                var ids = hg_get_ids();
                $(function() {
                    $.post(
                        "./run.php?mid=2890&a=create",
                        {
                            ids: ids,
                            pushType:'news'
                        },
                        function (data) {
                            console.log(data);
                        }
                    )
                })
            }
        </script>
		<ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="article" data-order_name="order_id">
		{foreach $list as $k => $v}
			{template:unit/news_row}
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
					<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
					<a style="cursor:pointer;" onclick="return hg_bacthmove_show(this,'news_node');" data-node ='news_node'>移动</a>
					<a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
					<a style="cursor:pointer;" onclick="return hg_bacthblock_show(this);" name="block">区块</a>
                    {if $v['outpush_status'] == 1}
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this,'','推送',1,'id','','ajax');" name="block">推送</a>
                    {/if}
				</div>
				{$pagelink}
			</li>
		</ul>   	
	</form>	
{/if}
</div>   
<div id="add_share" ></div> 
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
<!-- 移动框 -->
{template:unit/list_move_box}
<script>
</script>
{template:foot}     				