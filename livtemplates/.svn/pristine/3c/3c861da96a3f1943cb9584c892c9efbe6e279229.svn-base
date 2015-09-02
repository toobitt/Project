<?php 
/* $Id: list.php 11195 2012-08-30 05:18:49Z yudoudou $ */
?>
{template:head}
{code}
$menuData = array(
	'a' => 'form',
	'label' => '添加文章'
); 

$columnData = array(
    'news-fabu' => '发布至',
    'news-shr' => '审核人',
    'news-ren' => '作者/时间'
); 

$headData = array(
	'left' => array(
		'news-paixu' => '<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order(\'newslist\');"  title="排序模式切换/ALT+R"></a>'
	),
	'right' => array(
		'news-fabu' => '发布至',
		'news-fenlei' => '分类',
		'news-quanzhong' => '权重',
		'news-zhuangtai' => '状态',
		'news-pinglun' => '评论/点击',
		'news-ren' => '添加人/时间'
	),
	'title' => array(
		'news-biaoti' => '标题'
	)
);

$bottomData = array(
	'audit' => '审核',
	'back' => '打回',
	'delete' => '删除'
); 

$emptyData = array(
	'describe' => '没有您要找的内容！',
	'id' => 'newslist'
);

{/code}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:news_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:tree/animate}
<script type="text/javascript">
function header()
{
	var node_type = $('#node_type').val();
	var url="run.php?a=change_node&node_type="+node_type+"&mid={$_INPUT['mid']}";
	hg_request_to(url);
}
var id = '{$id}';
var frame_type = "{$_INPUT['_type']}";
var frame_sort = "{$_INPUT['_id']}";

$(document).ready(function(){
if(id)
{
   hg_show_opration_info(id,frame_type,frame_sort);
}
tablesort('newslist','article','order_id');
$("#newslist").sortable('option', 'cancel', '.common-list-head');
$("#newslist").sortable('disable');
});
</script>


<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
{template:list/list_menu}
{template:list/ajax_pub}
<div class="content clear">
	<div class="f">			
    	<div class="right v_list_show">
    		{template:unit/news_search}
			{template:list/list_column}
            <form method="post" action="" name="listform" style="display:block;position:relative;">
            	<ul class="news-list common-list" id="newslist">
					{template:list/list_head}
				{if $list}
            	{foreach $list as $k => $v}
            		{template:unit/news_row}
            	{/foreach}
            	{else}
            		{template:list/list_empty}
            	{/if}
            	</ul>
            	{template:list/list_bottom}
    		</form>
    		{template:list/list_edit}
    	</div>
    </div>
</div>   
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
</body>  


<script type="text/javascript">
function hg_fabu(id)
{
	$("#fabu_"+id).show();
}

function hg_back_fabu(id)
{
	$("#fabu_"+id).hide();
}

function hg_call_news_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
}
function hg_audit_call(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$('#text_'+ids[i]).text('已审核');
	}

	if($('#edit_show'))
	{
		hg_close_opration_info();
	}
}
function hg_back_call(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$('#text_'+ids[i]).text('待审核');
	 }

	if($('#edit_show'))
	{
		hg_close_opration_info();
	}
}

function hg_material_indexpic(id,m_id)
{
	var url = './run.php?'+'mid=' + gMid + '&a=update_indexpic&id=' + id + '&m_id=' + m_id;
	hg_request_to(url);
}

function hg_material_indexpic_call(data)
{
	var obj = eval('(' + data + ')');
	if(obj.indexpic)
	{
	
		$(".img_"+obj.id).attr('src',obj.small);
	}
}

$(document).ready(function(){
 	$(".list li span.right a.cz").hover(function(){
		$(this).parent().children("span.rr_1").hide();
		$(this).parent().children("span.rr_2").show();

	},function(){
		$(this).parent().children("span.rr_1").show();
		$(this).parent().children("span.rr_2").hide();

	});
	$("span.rr_2").hover(function(){
		$(this).show();
		$(this).parent().children("span.rr_1").hide();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 -16px');
	},function(){
		$(this).hide();
		$(this).parent().children("span.rr_1").show();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 0');
	});
});
</script>

<script>
$(function(){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    /*缓存页面的打开的标题个数*/
    $.commonListCache('news-list');

    /*图集的新增和编辑方式是新打开iframe*/
    $.initOptionIframe();
});
</script>
{template:foot}     				