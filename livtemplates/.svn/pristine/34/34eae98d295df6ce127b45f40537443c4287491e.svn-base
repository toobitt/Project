<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_for_edit = array('id', 'status', 'iscopy', 'distribution', 'mtype');
foreach ($content_list as $k => $v) {
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
{css:common/common_list}
{css:vod_style}
{css:content_list}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:common/common_list}
{js:2013/ajaxload_new}
<!-- 待合并 -->
{js:common/ajax_cache}
{js:common/record}
{js:common/record_view}
{js:common/weight_box}
{js:common/action_box}
{js:common/list_bootstrap}

<script type="text/javascript">
	function hg_copy_ad(url, id)
	{
		gADCopySourceID = id;
		if(!gADCopySourceID)
		{
			return;
		}
		hg_ajax_post(url);
		hg_copy_content = function(html) {
			$('.common-list-data:first').before(html);
		};
		return false;
	}
	
	function preview_ad(elem) {
	    if($("#view_box").length > 0 || elem.getElementsByTagName("img").length < 1){return;}
	    this_top = $(elem).offset().top;
	    img_width = $(elem).find("img").height();
	    $("body").append('<div id="view_box" style="position:absolute;border:1px solid #eee;"></div>');
	    var t = (img_width < $(window).height() - this_top - 60) ? this_top : this_top - img_width/2; 
	    $("#view_box").append($(elem).html()).css({
	        left : $(elem).offset().left + 70,
	        top : t,
	        "z-index" : 9999999
	    }).show();
	}

	function hg_adconcell(id)
	{
		if(id)
		{
			var status_color = '{code} echo json_encode($_configs["status_color"]);{/code}';
			stat_color = $.parseJSON(status_color);
			var adcancell_ids = id.split(',');
			for(var n in adcancell_ids)
			{
				recordCollection.get(adcancell_ids[n]).set('status', 6);
				$('#status_'+adcancell_ids[n]).text('下架').css('color', stat_color[6]);
				$('#r_' + adcancell_ids[n]).find("i").addClass('earth_icon_gray');
			}
		}

		hg_close_opration_info();
	}
	function hg_adonline(json)
	{
		var status_text = '{code} echo json_encode($_configs["status_search"]);{/code}';
		stat_text = $.parseJSON(status_text);
		var status_color = '{code} echo json_encode($_configs["status_color"]);{/code}';
		stat_color = $.parseJSON(status_color);
		var ret = $.parseJSON(json);
		if(ret)
		{
			for(var n in ret)
			{
				$('#status_'+n).text(stat_text[ret[n]]).css('color', stat_color[ret[n]]);
				recordCollection.get(n).set('status', 1);
				if(parseInt(ret[n]) == 1)
				{
					$('#r_' + n).find("i").removeClass('earth_icon_gray');
				}
			}
		}
		hg_close_opration_info();
	}
	
</script>
<script>
	$(function(){
		$('#record-edit').on('click','.adv-clone-btn',function(){
			var self = $(this),
				url = self.attr('href'),
				id = self.data('id');
			hg_copy_ad( url, id);
			return false;
		});
		
		$('#record-edit').on('click','.adv-line-btn',function(){
			var name = $(this).attr('_name');
			hg_ajax_post(this, name, 0);
			return false;
		});
		
	});
</script>

<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=content_form{$_ext_link}" class="add-button mr10">新增广告</a>
</div>
<div class="search_a" id="info_list_search">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">

		<div class="select-search">
			{code}
			$attr_date = array(
			'class' => 'colonm down_list data_time',
			'show' => 'colonm_show',
			'width' => 104,/*列表宽度*/
			'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			$attr_status_search = array(
			'class' => 'transcoding down_list',
			'show' => 'status_search_show',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			$_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : -1;
			$attr_customer = array(
			'class' => 'colonm down_list data_time',
			'show' => 'customers',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			$customer[0][0] = '所有发布商'
			);
			$default_customer = $_INPUT['customer'] ? $_INPUT['customer'] : 0;
			{/code}
			{template:form/search_source,customer,$default_customer,$customer[0],$attr_customer}
			{template:form/search_source,status,$_INPUT['status'],$_configs['status_search'],$attr_status_search}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}
		</div>
	</form>
</div>
<div class="common-list-content right" style="float:none;">
{if !$content_list}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">
		没有您要找的内容！
	</p>
	<script>
		hg_error_html('p', 1);
	</script>
{else}
	<form method="post" action="" name="listform">
		<!-- 标题 -->
		<ul class="common-list" id="list_head">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-paixu common-list-item">
						<a class="common-list-paixu" onclick="hg_switch_order('advlist');"  title="排序模式切换/ALT+R"></a>
					</div>
					<div class="content-slt common-list-item">
						缩略图
					</div>
				</div>
				<div class="common-list-right">
					
					<div class="content-zt common-list-item open-close wd50">
						状态
					</div>
					<div class="common-list-item open-close wd80">
						广告商
					</div>
					<div class="common-list-item open-close wd50">
						发布
					</div>
					<!--<div class="content-nw common-list-item open-close">直播播放器</div>
					<div class="content-nw common-list-item open-close">点播播放器</div>
					<div class="content-nw common-list-item open-close">网站广告位</div>-->
					{if isset($_INPUT['_id'])}
					<div class="common-list-item open-close">
						输出/点击
					</div>
					{/if}
					<div class="content-fbr common-list-item open-close wd100">
						发布人/时间
					</div>
				</div>
				<div class="common-list-biaoti ">
					<div class="common-list-item open-close content-biaoti">
						标题
					</div>
				</div>
			</li>
		</ul>
		<ul class="common-list public-list hg_sortable_list" data-order_name="order_id" id="advlist">
			{foreach $content_list as $k => $v}
			{template:unit/content_inner_list}
			{/foreach}
		</ul>
		<ul class="common-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'adcancell', '下架', 1, 'id', '', 'ajax');"    name="batadcancell">下架</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'adonline', '上架', 1, 'id', '', 'ajax');"    name="batadonline">上架</a>
				</div>
				{$pagelink}
			</li>
		</ul>
	</form>
{/if}
</div>
{template:unit/record_edit}
<div class="edit_show">
	<span class="edit_m" id="arrow_show"></span>
	<div id="edit_show"></div>
</div>
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
{template:foot}