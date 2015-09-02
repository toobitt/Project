{template:head}
{code}
$list = $vod_mark_live_list;
$image_resource = RESOURCE_URL;
$vodPlayerSwf = RESOURCE_URL.'swf/';

if(!isset($_INPUT['trans_status']))
{
    $_INPUT['trans_status'] = -1;
}

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 4;
}

if(isset($_INPUT['id']))
{
   $id = $_INPUT['id'];
}
else
{
   $id = '';
}

if(!isset($_INPUT['is_finish']))
{
   $_INPUT['is_finish'] = 0;
}

$hg_vod_list_mode = hg_get_cookie('hg_vod_list_mode');
if ($hg_vod_list_mode)
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list_img';
}
else
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list';
}

if(!$_INPUT['_type'])
{
	$_INPUT['_type'] = 3;
}

{/code}

{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:vod_upload_pic_handler}
{js:vod_video_edit}
{js:vod_add_to_collect}
{js:column_node}
{css:column_node}
{css:common/common_list}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:common/common_list}
<script type="text/javascript">
jQuery(function($) {
	$.commonListCache('vod-mark-list');	
});
function hg_t_show(obj)
{
	if($('#text_'+obj).text()=='转码中')
	{
		$('#hg_t_'+obj).css({'display':'block',});
	}
	
}
function hg_t_none(obj)
{
	$('#hg_t_'+obj).css({'display':'none',})
}

function hg_del_keywords()
{
	var value = $('#search_list').val();
	if(value == '关键字')
	{
		$('#search_list').val('');
	}

	return true;
}

function hg_change_status(obj)
{
   var obj = obj[0];
   var status_text = "";
   if(obj.status == 2)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 3)
   {
	   status_text = '被打回';    
   }

   for(var i = 0;i<obj.id.length;i++)
   {
	   $('#text_'+obj.id[i]).text(status_text);
	   if(obj.status == 2)
	   {
    	   if($('#img_sj_'+obj.id[i]).length)
    	   {
    		   $('#img_sj_'+obj.id[i]).removeClass('b');
           }

    	   if($('#img_lm_'+obj.id[i]).length)
    	   {
    		   $('#img_lm_'+obj.id[i]).removeClass('b');
           }
	   }
	   else
	   {
    	   if($('#img_sj_'+obj.id[i]).length)
    	   {
    		   $('#img_sj_'+obj.id[i]).addClass('b');
           }

    	   if($('#img_lm_'+obj.id[i]).length)
    	   {
    		   $('#img_lm_'+obj.id[i]).addClass('b');
           }
       }
   }

   	if($('#edit_show'))
	{
		hg_close_opration_info();
	}
}

var id = '{$id}';
var frame_type = "{$_INPUT['_type']}";
var frame_sort = "{$_INPUT['_id']}";

$(document).ready(function(){

if(id)
{
	hg_show_opration_info(id,frame_type,frame_sort);
}
   
tablesort('vodlist','vodinfo','video_order_id');
$("#vodlist").sortable('disable');
});

</script>
<style>
.common-list-item{width:80px;}
.common-list-biaoti .common-list-item{width:auto;}
.paixu{width:20px;}
.thumb{width:50px;}
.mark{width:100px;}
.movie-time{font-size:10px;color: #888888;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show">
			{template:unit/vod_mark_live_search}
		{if !$list}			
			<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
			<script>hg_error_html('p',1);</script>	
		{else}
			<form method="" action="" name="listform">
				{code}
				if ( $_INPUT['_type'] == 4 ) {
					$headData = array(
						'left' => array(
							'paixu' => '',
							'thumb' => '缩略图'
						),
						'right' => array(
							'option' => '编辑',
							'maliu' => '码流',
							'sort' => '分类',
							'status' => '状态',
							'ren' => '添加人/时间'
						),
						'biaoti' => array(
							'biaoti' => '标题'
						)
					);
				} else {
					$headData = array(
						'left' => array(
							'paixu' => '',
							'thumb' => '缩略图'
						),
						'right' => array(
							'show-date' => '节目日期',
							'duration' => '时长',
							'sort' => '分类',
							'source' => '来源频道',
							'mark' => '已标注(最新标注)',
							'status' => '状态',
						),
						'biaoti' => array(
							'biaoti' => '节目名称'
						)
					);
				}
				{/code}
				<div style="position: relative;">
					<div id="open-close-box">
						<span></span>
						<div class="open-close-title">显示/关闭</div>
						<ul>
						{foreach $headData['right'] as $kk => $vv}
							<li which="{$kk}"><label class="overflow"><input type="checkbox" checked />{$vv}</label></li>
						{/foreach}
						</ul>
					</div>
				</div>
				<ul class="common-list">
					<li class="common-list-head clear">
					{foreach array('left', 'right', 'biaoti') as $v}
						<div class="common-list-{$v}">
						{foreach $headData[$v] as $k => $v}
							<div class="common-list-item {$k}">{$v}</div>
						{/foreach}
						</div>
					{/foreach}
					</li>
				</ul>
				<ul class="common-list vod-mark-list" id="vodlist">
				{foreach $list as $k => $v}
				{if $_INPUT['_type'] == 4}
					{template:unit/vod_list}
				{else}
					{template:unit/vodmarklivelist}
				{/if}
				{/foreach}
				</ul>
				<div class="bottom clear">
					<div class="left">
						<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
						<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setfinish', '设为已完成标注', 1, 'id', '&is_finish=1', 'ajax');"    name="finish">设为已完成标注</a>
						<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设为不需要标注', 1, 'id', '&is_allow=1', 'ajax');"   name="no_mark">设为不需要标注</a>
					</div>
					{$pagelink}
				</div>
			</form>
		{/if}
		</div>
	</div>
</div>
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}