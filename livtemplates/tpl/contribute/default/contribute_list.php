<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
$suobei = $contribute_list[0]['suobei'];
$claim = $contribute_list[0]['claim'];
$off_claim = $contribute_list[0]['off_claim'];
//print_r($suobei);
$contribute = $contribute_list[0]['data'];
$list = &$contribute;
{/code}
{template:head}
{css:contribute_style}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{js:contribute}
{template:list/common_list}
{css:contribute_list}
<script type="text/javascript">
	function forwardSuobei(id) {
		var url = "run.php?mid=" + gMid + "&a=forward&id=" + id;
		hg_ajax_post(url);
	}

	function hg_forwardSuobei_back(json) {
		var json_data = $.parseJSON(json);
		for (var a in json_data) {
			$('#forward_' + json_data[a]).html('重新' + $("#forward_name").val());

		}
	}

	(function() {
		var cache = new AjaxCache;
		var base = 'run.php?mid=' + gMid + '&a=show_pic&id=';
		var render;
		$(function() {
			render = _.template($('#show_pic_tpl').html());
		});
		window.hg_show_change = function(id) {
			var url = base + id;

			cache.get(url, {
				after : function(data) {
					data = data[0];

					data.id = id;
					var html = render({
						'$v' : data
					});
					if ($('#img_box_' + id).css('display') == 'none') {
						if (!$('#img_box_' + id).html().trim()) { 
							$('#img_box_' + id).html(html);
						}
						$('#img_box_' + id).slideDown();
					} else {
						$('#img_box_' + id).slideUp();
					}
				},
				type : 'json'
			});
		};
	})();
	function change_indexpic(program_id, id, img) {

		var url = './run.php?mid=' + gMid + '&a=update_indexpic&content_id=' + program_id + '&id=' + id;
		var src = img.src;
		$('#img_' + program_id).attr('src', src);
		hg_ajax_post(url);
	}

	function indexpic_back() {
		;
	}

	function change_indexpic_by_vod_pic(program_id, id, src) {
		var url;
		url = 'run.php?mid=' + gMid + '&a=update_indexpic_by_vod_pic&content_id=' + program_id + '&vodid=' + id + '&img_src=' + src;
		hg_ajax_post(url);
	}

	function vod_pic_back(data) {
		data = JSON.parse(data);
		var src = $.createImgSrc(data, {
			width : 40,
			height : 30
		});
		var id = data.content_id;
		$('#img_' + id).attr('src', src);
	}

	$(function() {
		var program_id, btn, uploading = false;
		var form = $('#uploadForm');
		var file = $('#uploadForm').find('input');

		file.change(function() {
			form.find('[name=content_id]').val(program_id);
			uploading = true;
			form.ajaxSubmit({
				semantic : true,
				success : function(data) {
					uploading = false;
					try {
						data = $.parseJSON(data);
						if (data.msg) {
							alert('文件格式不正确!');
						} else {
							var src = '';
							src = data[0]['host'] + data[0]['dir'] + '40x30/' + data[0]['filepath'] + data[0]['filename'];
							if (data[0]['host']) {
								btn.before('<img src="' + src + '"  onclick="change_indexpic(' + data[0]['cid'] + ',' + data[0]['id'] + ', this)" />');
							}
						}
					} catch (e) {
					}
				},
				data : {
					program_id : program_id,
					ajax : 1
				}
			});
		});
		$('.common-list').on('click', '.uploadBtn', function() {
			if (uploading)
				return;
			btn = $(this);
			program_id = btn.data('program_id');
			file.trigger('click');
		});
	});

	function claim(id) {
		var url = "run.php?mid=" + gMid + "&a=claim&id=" + id;
		hg_ajax_post(url);
	}

	function hg_claim_back(json) {
		var data = $.parseJSON(json);
		var ids = data.id.split(',');
		var msg = data.org_name;
		ids.forEach(function(id) {
			$('#contribute_claim_' + id).text(msg);
		});
	}

	function off_claim(id) {
		var url = "run.php?mid=" + gMid + "&a=off_claim&id=" + id;
		hg_ajax_post(url);
	}

	function hg_off_claim_back(json) {
		var data = $.parseJSON(json);
		var ids = data.id.split(',');
		var msg = data.msg;
		ids.forEach(function(id) {
			//$('#contribute_claim_' + id).attr('onclick','claim('+id+')');
			//$('#contribute_claim_' + id).attr('onclick','claim('+id+')');
			$('#contribute_claim_' + id).text('');
			$('#contribute_claim_' + id).prepend('<a href="javascript:void(0)" onclick="claim(' + id + ')" style="color:red">' + msg + '</a>');
		});
	}

	function hg_contribute_publish(id) {
		App.trigger('batch:column_publish', id);
	}
</script>
<style>
	.fl {
		display: inline-block;
		width: 80px;
	}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&infrm=1" target="nodeFrame"> <span class="left"></span> <span class="middle"><em class="add">新增报料</em></span> <span class="right"></span> </a>
	</div>
	<div class="content clear">
		<div class="f">
			<div class="right v_list_show">
				<div class="search_a" id="info_list_search">
					<span class="serach-btn"></span>
					<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
						<div class="select-search">
							{code}
							$time_css = array(
							'class' => 'transcoding down_list',
							'show' => 'time_item',
							'width' => 120,
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							$_INPUT['contribute_sort_time'] = $_INPUT['contribute_sort_time'] ? $_INPUT['contribute_sort_time'] : 1;

							$audit_css = array(
							'class' => 'transcoding down_list',
							'show' => 'sort_audit',
							'width' => 120,
							'state' => 0,
							);
							$default_audit = -1;
							$_configs['contribute_audit'][$default_audit] = '所有状态';
							$_INPUT['contribute_sort_audit'] = $_INPUT['contribute_sort_audit'] ? $_INPUT['contribute_sort_audit'] : -1;
							
							$report_css = array(
							'class' => 'transcoding down_list',
							'show' => 'sort_report',
							'width' => 120,
							'state' => 1,
							);
							$report_audit = -1;
							$_configs['contribute_report'][$report_audit] = '记者';
							$_INPUT['contribute_sort_report'] = $_INPUT['contribute_sort_report'] ? $_INPUT['contribute_sort_report'] : -1;
							
							{/code}

							{template:form/search_source,contribute_sort_audit,$_INPUT['contribute_sort_audit'],$_configs['contribute_audit'],$audit_css}
							{template:form/search_source,contribute_sort_time,$_INPUT['contribute_sort_time'],$_configs['date_search'],$time_css}
							{template:form/search_source,contribute_sort_report,$_INPUT['contribute_sort_report'],$_configs['contribute_report'],$report_css}
							
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
							<input type="hidden" id="forward_name" value="{$suobei['display_name']}">
						</div>
						<div class="text-search">
							<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
							</div>
							{template:form/search_input,k,$_INPUT['k']}
						</div>
						<div class="custom-search">
						{code}
							$attr_creater = array(
								'class' => 'custom-item',
								'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
								'place' =>'添加人'
							);
						{/code}
						{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
					</div>
					</form>
				</div>
				{if !$contribute}
					<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html('#emptyTip',1);</script>
				{else}
				<form method="post" action="" name="pos_listform">
					<!-- 标题 -->
					<ul class="common-list public-list-head">
						<li class="common-list-head clear">
							<div class="common-list-left">
								<div class="contribute-paixu common-list-item">
									<a class="common-list-paixu" onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"></a>
								</div>
								<div class="contribute-fengmian common-list-item">
									索引图
								</div>
							</div>
							<div class="common-list-right">
								{if $claim}
								<div class="contribute-bmrl common-list-item open-close wd100">
									部门认领
								</div>
								{/if}
								<div class="contribute-fb common-list-item open-close wd100">
									发布至
								</div>
								<div class="contribute-bj common-list-item open-close wd60">
									编辑
								</div>
								<div class="contribute-sc common-list-item open-close wd60">
									删除
								</div>
								<div class="contribute-fl common-list-item open-close wd80">
									分类
								</div>
								<div class="contribute-zt common-list-item open-close wd60">
									状态
								</div>
								<div class="contribute-khd common-list-item open-close wd100">
									客户端
								</div>
								<div class="contribute-blr common-list-item open-close wd120">
									报料人/时间
								</div>
							</div>
							<div class="common-list-biaoti">
								<div class="common-list-item">
									报料标题
								</div>
							</div>
						</li>
					</ul>
					<ul class="common-list public-list hg_sortable_list" data-order_name="order_id" id="contribute_list">
						{if $contribute}
						{foreach $contribute as $k => $v}
						{template:unit/contribute_list}
						{/foreach}
						{/if}
					</ul>
					<ul class="common-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
								<a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '', 'ajax');" style="cursor:pointer;">审核</a>
								<a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'back', '打回', 1, 'id', '', 'ajax');" style="cursor:pointer;">打回</a>
								<a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
								{if $suobei['is_open']}
								<a name="batdeforward"  onclick="return hg_ajax_batchpost(this, 'forward', '{$suobei[display_name]}', 1, 'id', '', 'ajax');" style="cursor:pointer;">{$suobei['display_name']}</a>
								{/if}
								{if $claim}
								<a name="batclaim"  onclick="return hg_ajax_batchpost(this, 'claim', '认领', 1, 'id', '', 'ajax');" style="cursor:pointer;">认领</a>
								{/if}
								{if $off_claim}
								<a name="batoffclaim"  onclick="return hg_ajax_batchpost(this, 'off_claim', '取消认领', 1, 'id', '', 'ajax');" style="cursor:pointer;">取消认领</a>
								{/if}
							</div>
							{$pagelink}
						</li>
					</ul>

				</form>
				{/if}
				<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
				</div>
			</div>
		</div>
	</div>

	<!--发布-->
	<div id="infotip"  class="ordertip"></div>
	<!-- 图片上传表单 -->
	<from id="uploadForm" style="display:none;" method="post" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data">
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="a" value="upload" />
		<input type="hidden" name="content_id" />
		<input type="file" name="Filedata" />
	</from>

	<script type="tpl" id="show_pic_tpl">
		<% if ($v['pic']) { %>
		<% $.each($v['pic'], function(key, val) { %>
		<img src="<%= $.createImgSrc(val, { width: 40, height: 30 }) %>"  onclick="change_indexpic(<%= $v.id %>, <%= val['material_id'] %>, this)" />
		<% }); %>
		<% } %>
		<% if ($v['vod_pic']) { %>
		<% $.each($v['vod_pic'], function(key, val) { %>
		<img src="<%= val %>" width="40" height="30"  onclick="change_indexpic_by_vod_pic(<%= $v.id %>,<%= $v['vodid'] %>, '<%= val %>')" />
		<% }); %>
		<% } %>
		<div class="uploadBtn" data-program_id="{$v[$primary_key]}">+</div>
	</script>
</body>
{template:foot}