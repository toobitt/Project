<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php echo $_scroll_style;?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
-->
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/common/common_publish.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/edit_video_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>road/mark_style.css" />
<script type="text/javascript">
var gPixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
var RESOURCE_URL = '<?php echo $RESOURCE_URL;?>';
var SCRIPT_URL = '<?php echo $SCRIPT_URL;?>';
var client_id = 1;
var gMid = '<?php echo $_INPUT['mid'];?>';
var gMenuid = '<?php echo $_INPUT['menuid'];?>';
var gRelate_module_id = '<?php echo $relate_module_id;?>';
var gToken = "<?php echo $_user['token'];?>";
var show_conf_menu='<?php echo $show_conf_menu;?>';
</script>
<?php echo $this->mHeaderCode;?><script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery-ui-min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>global.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alertbox.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alertbox.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>md5.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>ajax.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfupload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfupload.queue.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>fileprogress.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>handlers.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>livUpload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfobject.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>lazyload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/pic_edit.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alert/jquery.alerts.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_opration.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>contribute_sort.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>underscore.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>Backbone.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_list.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_cache.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/record.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/record_view.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/weight_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/action_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/publish_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/share_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/list_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/publish.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/special.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/block.js"></script>
<script>
jQuery(function($){
    $.pixelRatio = gPixelRatio;
    if($.pixelRatio > 1){
        $('img.need-ratio').each(function(){
            $(this).attr('src', $(this).attr('_src2x'));
        });
    }
});
</script>
<?php if($_INPUT['infrm']){ ?>
<style type="text/css">
	/*body{background:#fff;}
	.wrap{border:0;box-shadow:none;padding:10px 0 10px 10px;}*/
	.wrap .search_a{padding:0}
</style>
<?php } ?>
</head>
<body<?php echo $this->mBodyCode;?><?php echo $_scroll_style;?>>
<?php 
//hg_pre($_nav);
 ?>
<?php if($_nav){ ?>
<div class="nav-box">
     <div class="choice-area" id="hg_info_list_search">
      </div>
      <div class="controll-area fr mt5" id="hg_parent_page_menu">
       </div>
</div>
<?php } ?>
<?php 
$_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : 1;
$_INPUT['cat'] = $_INPUT['cat'] ? $_INPUT['cat'] : 0;
$_INPUT['area'] = $_INPUT['area'] ? $_INPUT['area'] : 0;
$_INPUT['hot'] = $_INPUT['hot'] ? $_INPUT['hot'] : 0;
$_INPUT['_scoure'] = $_INPUT['_scoure'] ? $_INPUT['_scoure'] : 0;
 ?>
<?php 
$list_area = $list[0]['area'];
$list_cat = $list[0]['cat'];
$list_data = $list[0]['data'];
$list = $list[0]['data'];
 ?>
<?php $list_setting['status_color'] = $_configs['status_color'];
$status_key = isset($status_key) ? $status_key : 'state';
$audit_value = isset($audit_value) ? $audit_value : 1;
$audit_label = isset($audit_label) ? $audit_label : '已审核';
$back_value = isset($back_value) ? $back_value : 2;
$back_label = isset($back_label) ? $back_label : '已打回';$default_attrs_for_edit = array(
	'id', 'title', 'status', 'state', 'special_id', 'click_num', 'share_num', 'expand_id',
	'click_count' ,'comm_num', 
	'img_info', 'downcount', 
	'video_url', 'weight', 
	'sampling_rate',
	'outlink'
);
$attrs_for_edit = isset($attrs_for_edit) ? 
	array_merge($attrs_for_edit, $default_attrs_for_edit) : 
	$default_attrs_for_edit;function utils_pluck(&$arr, $attrs, $status_key) {
	$ret = array();
	$attrs = is_array($attrs) ? $attrs : array($attrs);
	foreach ($arr as $k => $v) {
		foreach($attrs as $attr) {
			$ret[$k][$attr] = $v[$attr];
		}
		$ret[$k]['status'] = $v[$status_key];
		$ret[$k]['state'] = $v[$status_key];
	}
	return $ret;
}
$js_globalData = utils_pluck($list, $attrs_for_edit, $status_key); ?><!-- 待合并 --><script>
globalData = window.globalData || {};
globalData.list = <?php echo json_encode($js_globalData); ?>;
globalData.auditValue = <?php echo $audit_value;?>;
globalData.backValue = <?php echo $back_value;?>;
</script>
<script>
function changeStatusLabel(status, record) {
	var id = record.id;
	if (status == <?php echo $audit_value;?>) {
		label = '<?php echo $audit_label;?>';
		color = '<?php echo $list_setting['status_color'][$audit_value];?>';
	} else {
		label = '<?php echo $back_label;?>';
		color = '<?php echo $list_setting['status_color'][$back_value];?>';
	}
	$("#statusLabelOf" + id).text(label).css('color', color).attr('_state', status);
}
function hg_change_status(obj) {
	var obj = obj[0],
		status = obj.status || obj.state,
		ids = obj.id, color, label;
	hg_close_opration_info();
	if (obj.errmsg) {
		top.jAlert(obj.errmsg, '失败提醒');
		return;
	}
	$.each(ids, function (i, id) {
		recordCollection.get(id).set('state', status);
	});
}
$(function ($) {
	var loading = '<img src="<?php echo $RESOURCE_URL;?>loading2.gif" style="width:25px;position:absolute;left:7px;top:-2px;" />';
    $(".common-list").on("click", '.common-switch-status span', function() {
        if($(this).data('ajax')) return;
        var state = +$(this).attr('_state'),
            id = $(this).attr('_id');
        var me = $(this), url, load;
        $(this).data('ajax', true);
        load = $(loading).appendTo( $(this).parent() );        url = './run.php?mid=' + gMid + '&a=audit&audit='+
        	(state == <?php echo $audit_value;?> ? 0 : 1) + '&id=' + id + '&ajax=1';
        hg_ajax_post(url, '', 0, function (data) {
        	setTimeout(function () {
                me.data('ajax', false);
                hg_change_status(data);
                load.remove();
            }, 200);
        }, false);
    });
});
</script><div id="weight_box">
	<div class="weight-select">
		<div class="arrow">
		</div>
		<div class="current-quanzhong">
			<div class="slider-weight-box">
			  <div id="listWeightSlider"></div>
		    </div>
		</div>
		<!-- 模板在下面 -->
	</div>
</div>
<script type="tpl" id="weight_box_tpl">
	<ul class="quanzhong-list">
		{{each mydata}}
		<li data-weight=${_value.begin_w}>
			<span class="weight-number" style="background:transparent;"><span>≥${_value.begin_w}</span></span><a class="weight-describe">${_value.title}</a>
		</li>
		{{/each}}
	</ul>
</script><!-- 签发框 -->
<div id="vodpub" class="common-list-ajax-pub">
	<div class="common-list-pub-title">
		<p>正在发布</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
		<form name="recommendform" id="recommendform" action="run.php" method="post" class="form" onsubmit="return hg_ajax_submit('recommendform');"><?php 
if (!isset($publish)) {
	$publish = array(
		'selected_items' => array(),
		'selected_ids' => array(),
		'selected_names' => array(),
		'pub_time' => '',
		'default_site' => array(),
		'sites' => array(),
		'items' => array()
	);
}
 ?>
<style>
.common-hg-publish .publish-result ul, .common-hg-publish .publish-result-empty{height:190px;}
</style>
<div class="publish-box common-hg-publish" id="publish-box-<?php echo $hg_name;?>">
	<div class="publish-result <?php if(count($publish['selected_items']) == 0){ ?>empty<?php } ?>" >
		<p class="publish-result-title">发布至：</p>
		<ul>
			<?php foreach ($publish['selected_items'] as $item){ ?>
			<li _id="<?php echo $item['id'];?>" _name="<?php echo $item['name'];?>" _siteid="<?php echo $item['siteid'];?>" title="<?php echo $item['show_name'];?>">
				<input type="checkbox" checked="checked" class="publish-checkbox" <?php if(!$item['is_auth']){ ?>style="visibility: hidden;"<?php } ?>/>
				<span><?php echo $item['showName'];?></span>
			</li>
			<?php } ?>
		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>
		<div>
			<label style="padding:5px;">文件名：</label><input name="custom_filename" value="<?php echo $publish['custom_filename'];?>"  style="width:98px;height:20px;"/>
		</div>
		<div>
			<label style="padding:5px;">发布时间：</label><input name="pub_time" value="<?php echo $publish['pub_time'];?>" class="Wdate" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'})">
		</div>
	</div>
	<div class="publish-site">
		<div class="publish-site-current" _siteid="<?php echo $publish['default_site']['key'];?>">
			<?php echo $publish['default_site']['value'];?>
		</div>
		<span class="publish-site-qiehuan">切换</span>
		<ul>
			 <?php foreach ($publish['sites'] as $key => $each_site){ ?>
			 <li class="publish-site-item <?php if($key == $publish['default_site']['key']){ ?>publish-site-select<?php } ?>" _siteid="<?php echo $key;?>" _name="<?php echo $each_site;?>">
			 	<label><input type="radio" name="publish-sites-<?php echo $hg_name;?>" <?php if($key == $publish['default_site']['key']){ ?>checked="checked"<?php } ?> />
			 	<?php echo $each_site;?></label>
			 </li>
			 <?php } ?>
		</ul>
	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
			<?php if($publish['items']){ ?>
			<div class="publish-each">
				<ul>
					<?php foreach ($publish['items'] as $kk => $vv){ ?>
					<li _id="<?php echo $vv['id'];?>" title="<?php echo $vv['name'];?>" _name="<?php echo $vv['name'];?>" class="one-column <?php if($vv['is_last']){ ?>no-child<?php } ?>">
						<input type="checkbox" class="publish-checkbox" <?php if($vv['is_auth']==2){ ?>style="visibility:hidden;"<?php } ?>/>
						<span class="publish-name"><?php echo $vv['name'];?></span>
						<span class="publish-child">&gt;</span>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
	<input type="hidden" class="publish-hidden" name="column_id" value="<?php echo $publish['selected_ids'];?>" />
	<input type="hidden" class="publish-name-hidden" name="column_name" value="<?php echo $publish['selected_names'];?>" /></div>
			<input type="hidden" name="a" value="publish">
			<input type="hidden" name="ajax" value="1">
			<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>">
			<input type="hidden" name="id" value="${id}">
			<div><span class="publish-box-save">保存</span></div>
		</form>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div><!-- 专题框 --> 
<div id="special_publish">
	<div class="common-list-pub-title">
		<p>正在进行专题发布</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<form action="run.php?mid=<?php echo $_INPUT['mid'];?>&a=push_special" method="post"><div class="publish-box common-hg-special-publish">
	<div class="publish-result <?php if(count($hg_print_selected) == 0){ ?>empty<?php } ?>" >
		<p class="publish-result-title">发布至：</p>
		<ul>
		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>
	</div>
	<div class="publish-site">
		<div class="publish-site-current" _siteid="<?php echo $current_site['key'];?>">
		</div>
		<span class="publish-site-qiehuan" style="opacity:0;">切换</span>
		<ul>
		</ul>
	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
		</div>
	</div>
	<div class="publish-content publish-content-1">
	</div>
	<div class="publish-content publish-content-2">
	</div>
	<input type="hidden" class="publish-hidden" name="special_id" value="" />
	<input type="hidden" class="publish-name-hidden" name="column_name" value="" />
	<input type="hidden" class="publish-column-hidden" name="column_id" value="" />
</div>	<input type="hidden" name="id" value="" />
	<div><span class="publish-box-save">保存</span></div>
	<span class="common-list-pub-close"></span>
	</form>
</div><!-- 区块框 -->
<div id="block_publish">
	<div class="common-list-pub-title">
		<p>正在进行区块发布</p>
		<div>
			<p style="max-width:250px;" class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<form><div class="publish-box common-hg-block-publish">
	<div class="publish-result <?php if(count($hg_print_selected) == 0){ ?>empty<?php } ?>" >
		<p class="publish-result-title">发布至：</p>
		<ul>
		</ul>
		<div class="publish-result-empty">显示已选择的区块</div>
	</div>
	<div class="publish-site">
		<div class="publish-site-current" _siteid="<?php echo $current_site['key'];?>">
		</div>
		<span class="publish-site-qiehuan" style="opacity:0;">切换</span>
		<ul>
		</ul>
	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
		</div>
	</div>
	<div class="publish-content">
	</div>
	<input type="hidden" class="publish-hidden" name="block_id" value="<?php echo $hg_value;?>" />
	<input type="hidden" class="publish-name-hidden" name="block_name" value="<?php echo $hg_value;?>" /></div>
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="a" value="push_block" />
	<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>" />
	<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
	<div><span class="publish-box-save">保存</span></div>
	</form>
	<span class="common-list-pub-close"></span>
</div>
<script type="text/javascript">
	var id = '<?php echo $id;?>';
	var frame_type = "<?php echo $_INPUT['_type'];?>";
	var frame_sort = "<?php echo $_INPUT['_id'];?>";
	function hg_road_delete(id)
	{
		if(confirm('您确定要删除此条记录?'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
			hg_request_to(url);	
		}
	}
	function hg_road_call_delete(json)
	{
		var obj = new Function("return" + json)();
		var ids = obj.id;
		var data = ids.split(",");
		for(i=0;i<data.length;i++)
		{
			$("#r_"+data[i]).slideUp(5000).remove();
		}
		if($("#checkall").attr('checked'))
		{
			$("#checkall").removeAttr('checked');
		}
		if($('#edit_show'))
		{
			hg_close_opration_info();
		}
	}
	$(function(){
		if(id)
		{
		   hg_show_opration_info(id,frame_type,frame_sort);
		}
		tablesort('road_list','road','orderid');
		$("#road_list").sortable('disable');
	});
	function hg_check_auth()
	{
		if($("#auth-info").css("display") == 'none')
		{
			$("#auth-info").show();
			$.get("./run.php?mid=" + gMid + "&a=show_plat_auth&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{key:''},
					function (data)	{
					$("#auth-info").html(data);
				 });	
		}
		else
		{
			var str='<div id="auth-loading"></div>';
			$("#auth-info").html(str);
			$("#auth-info").hide();	
		}
	}
	function hg_request_auth(platid,type)
	{
		$.get("./run.php?mid="+gMid+"&a=request_auth&gmid="+gMid+"&platid="+platid+"&type="+type+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
					function(data) {
					var obj = eval('('+data+')');
					var url = obj[0].url;
					window.open(url);
				});
	}	
</script>
<style sytle="text/html">
#auth-info{position:absolute;right:0px;top:0px;border:1px solid #DDDDDD;border-top:none;background:#EFEFEF;width:420px;min-height:200px;float:left;z-index:4;display:none;padding:10px 10px;}
#auth-info li{margin-bottom:10px;}
#auth-loading{background:url("<?php echo $RESOURCE_URL;?>loading.gif") left no-repeat;width:50px;height:50px;}
</style>
<div id="auth-info">
	<div id="auth-loading"></div>
</div>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" <?php if($_INPUT['infrm']){ ?>style="display:none"<?php } ?>>
   <a class="blue mr10" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=form<?php echo $_ext_link;?>" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增路况</em></span>
		<span class="right"></span>
	</a>
	<a class="blue mr10" id="auth-check" onclick="hg_check_auth();">
		<span class="left"></span>
		<span class="middle"><em class="set">查看授权</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show">
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						<?php 
							$attr_status=array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' =>104,
								'state' =>0,
							);
							$attr_cat=array(
								'class' => 'colonm down_list data_time',
								'show' => 'cat_show',
								'width' =>104,
								'state' =>0,
							);	
							$list[0]['cat'][0] = '所有类型';	
							$list[0]['cat'][1] = '热门易堵';
                           $attr_group=array(
								'class' => 'colonm down_list data_time',
								'show' => 'group_show',
								'width' =>104,
								'state' =>0,
							);
                            $list_cat[0] = '全部路况';
                            $attr_area=array(
								'class' => 'colonm down_list data_time',
								'show' => 'area_show',
								'width' =>104,
								'state' =>0,
							);
  							foreach($list_area as $k=>$v)
                            {
                                 $list_areaarr[$v['id']] = $v['name'];
                            }
                            $list_areaarr[0] = '全部区域';                                                      
                            $list_scoure[0]['cat'][0] = '所有来源';	
							$list_scoure[0]['cat'][1] = '编辑添加'; 
							$list_scoure[0]['cat'][2] = '微博获取';                          
						 ?>
						<?php 
$attr_status['class'] = $attr_status['class'] ? $attr_status['class']:'transcoding down_list';
$attr_status['show'] = $attr_status['show'] ? $attr_status['show']:'transcoding_show';
$attr_status['type'] = $attr_status['type'] ? 1:0;
 ?>
<div class="<?php echo $attr_status['class'];?>" style="width:<?php  echo ($attr_status['width'] ? $attr_status['width'] : 104) . 'px' ?>;"  onmouseover="hg_search_show(1,'<?php echo $attr_status['show'];?>','<?php echo $attr_status['extra_div'];?>', this);" onmousemove="<?php echo $attr_status['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_status['show'];?>','<?php echo $attr_status['extra_div'];?>', this);<?php echo $attr_status['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_status['show'];?>" class="overflow"><?php echo $_configs['status'][$_INPUT['status']];?></label></a></span>
	<ul id="<?php echo $attr_status['show'];?>" style="display:none;"  class="<?php echo $attr_status['show'];?> defer-hover-target">
		<?php foreach ($_configs['status'] as $k => $v){ ?>
		<?php 
			if($attr_status['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($attr_status['href'])
			{
				if(!strpos($attr_status['href'],'fid='))
				{
					$expandhref=$attr_status['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_status['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_status['extra_li'];?>><a <?php if($attr_status['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_status['state'];?>,'<?php echo $attr_status['show'];?>','status<?php  echo $attr_status['more']?'_'.$attr_status['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_status['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
	</ul>
</div>
<?php if($attr_status['state'] == 1){ ?>
	<div class="input" <?php if($_INPUT['status'] == 'other'){ ?> style="width:104px;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:104px;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="start_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['start_time'];?>"/>
		</span>
	</div>
	<div class="input" <?php if($_INPUT['status'] == 'other'){ ?> style="width:104px;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:104px;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="end_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['end_time'];?>"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['status'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date" class="btn_search" />
<?php } ?>
<?php if($attr_status['more']){ ?>
	<input type="hidden" name="status[<?php echo $attr_status['more'];?>]"  id="status_<?php echo $attr_status['more'];?>"  value="<?php echo $_INPUT['status'];?>"/>
<?php } else { ?>
	<input type="hidden" name="status"  id="status"  value="<?php echo $_INPUT['status'];?>"/>
<?php } ?>
<?php if($attr_status['para']){ ?>
	<?php foreach ($attr_status['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_group['class'] = $attr_group['class'] ? $attr_group['class']:'transcoding down_list';
$attr_group['show'] = $attr_group['show'] ? $attr_group['show']:'transcoding_show';
$attr_group['type'] = $attr_group['type'] ? 1:0;
 ?>
<div class="<?php echo $attr_group['class'];?>" style="width:<?php  echo ($attr_group['width'] ? $attr_group['width'] : 104) . 'px' ?>;"  onmouseover="hg_search_show(1,'<?php echo $attr_group['show'];?>','<?php echo $attr_group['extra_div'];?>', this);" onmousemove="<?php echo $attr_group['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_group['show'];?>','<?php echo $attr_group['extra_div'];?>', this);<?php echo $attr_group['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_group['show'];?>" class="overflow"><?php echo $list_cat[$_INPUT['cat']];?></label></a></span>
	<ul id="<?php echo $attr_group['show'];?>" style="display:none;"  class="<?php echo $attr_group['show'];?> defer-hover-target">
		<?php foreach ($list_cat as $k => $v){ ?>
		<?php 
			if($attr_group['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($attr_group['href'])
			{
				if(!strpos($attr_group['href'],'fid='))
				{
					$expandhref=$attr_group['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_group['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_group['extra_li'];?>><a <?php if($attr_group['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_group['state'];?>,'<?php echo $attr_group['show'];?>','cat<?php  echo $attr_group['more']?'_'.$attr_group['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_group['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
	</ul>
</div>
<?php if($attr_group['state'] == 1){ ?>
	<div class="input" <?php if($_INPUT['cat'] == 'other'){ ?> style="width:104px;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:104px;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="start_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['start_time'];?>"/>
		</span>
	</div>
	<div class="input" <?php if($_INPUT['cat'] == 'other'){ ?> style="width:104px;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:104px;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="end_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['end_time'];?>"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['cat'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date" class="btn_search" />
<?php } ?>
<?php if($attr_group['more']){ ?>
	<input type="hidden" name="cat[<?php echo $attr_group['more'];?>]"  id="cat_<?php echo $attr_group['more'];?>"  value="<?php echo $_INPUT['cat'];?>"/>
<?php } else { ?>
	<input type="hidden" name="cat"  id="cat"  value="<?php echo $_INPUT['cat'];?>"/>
<?php } ?>
<?php if($attr_group['para']){ ?>
	<?php foreach ($attr_group['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_cat['class'] = $attr_cat['class'] ? $attr_cat['class']:'transcoding down_list';
$attr_cat['show'] = $attr_cat['show'] ? $attr_cat['show']:'transcoding_show';
$attr_cat['type'] = $attr_cat['type'] ? 1:0;
 ?>
<div class="<?php echo $attr_cat['class'];?>" style="width:<?php  echo ($attr_cat['width'] ? $attr_cat['width'] : 104) . 'px' ?>;"  onmouseover="hg_search_show(1,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);" onmousemove="<?php echo $attr_cat['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);<?php echo $attr_cat['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_cat['show'];?>" class="overflow"><?php echo $list[0]['cat'][$_INPUT['hot']];?></label></a></span>
	<ul id="<?php echo $attr_cat['show'];?>" style="display:none;"  class="<?php echo $attr_cat['show'];?> defer-hover-target">
		<?php foreach ($list[0]['cat'] as $k => $v){ ?>
		<?php 
			if($attr_cat['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($attr_cat['href'])
			{
				if(!strpos($attr_cat['href'],'fid='))
				{
					$expandhref=$attr_cat['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_cat['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_cat['extra_li'];?>><a <?php if($attr_cat['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_cat['state'];?>,'<?php echo $attr_cat['show'];?>','is_hot<?php  echo $attr_cat['more']?'_'.$attr_cat['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_cat['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
	</ul>
</div>
<?php if($attr_cat['state'] == 1){ ?>
	<div class="input" <?php if($_INPUT['hot'] == 'other'){ ?> style="width:104px;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:104px;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="start_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['start_time'];?>"/>
		</span>
	</div>
	<div class="input" <?php if($_INPUT['hot'] == 'other'){ ?> style="width:104px;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:104px;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="end_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['end_time'];?>"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['hot'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date" class="btn_search" />
<?php } ?>
<?php if($attr_cat['more']){ ?>
	<input type="hidden" name="is_hot[<?php echo $attr_cat['more'];?>]"  id="is_hot_<?php echo $attr_cat['more'];?>"  value="<?php echo $_INPUT['hot'];?>"/>
<?php } else { ?>
	<input type="hidden" name="is_hot"  id="is_hot"  value="<?php echo $_INPUT['hot'];?>"/>
<?php } ?>
<?php if($attr_cat['para']){ ?>
	<?php foreach ($attr_cat['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_cat['class'] = $attr_cat['class'] ? $attr_cat['class']:'transcoding down_list';
$attr_cat['show'] = $attr_cat['show'] ? $attr_cat['show']:'transcoding_show';
$attr_cat['type'] = $attr_cat['type'] ? 1:0;
 ?>
<div class="<?php echo $attr_cat['class'];?>" style="width:<?php  echo ($attr_cat['width'] ? $attr_cat['width'] : 104) . 'px' ?>;"  onmouseover="hg_search_show(1,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);" onmousemove="<?php echo $attr_cat['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);<?php echo $attr_cat['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_cat['show'];?>" class="overflow"><?php echo $list_areaarr[$_INPUT['area']];?></label></a></span>
	<ul id="<?php echo $attr_cat['show'];?>" style="display:none;"  class="<?php echo $attr_cat['show'];?> defer-hover-target">
		<?php foreach ($list_areaarr as $k => $v){ ?>
		<?php 
			if($attr_cat['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($attr_cat['href'])
			{
				if(!strpos($attr_cat['href'],'fid='))
				{
					$expandhref=$attr_cat['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_cat['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_cat['extra_li'];?>><a <?php if($attr_cat['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_cat['state'];?>,'<?php echo $attr_cat['show'];?>','area<?php  echo $attr_cat['more']?'_'.$attr_cat['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_cat['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
	</ul>
</div>
<?php if($attr_cat['state'] == 1){ ?>
	<div class="input" <?php if($_INPUT['area'] == 'other'){ ?> style="width:104px;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:104px;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="start_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['start_time'];?>"/>
		</span>
	</div>
	<div class="input" <?php if($_INPUT['area'] == 'other'){ ?> style="width:104px;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:104px;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="end_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['end_time'];?>"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['area'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date" class="btn_search" />
<?php } ?>
<?php if($attr_cat['more']){ ?>
	<input type="hidden" name="area[<?php echo $attr_cat['more'];?>]"  id="area_<?php echo $attr_cat['more'];?>"  value="<?php echo $_INPUT['area'];?>"/>
<?php } else { ?>
	<input type="hidden" name="area"  id="area"  value="<?php echo $_INPUT['area'];?>"/>
<?php } ?>
<?php if($attr_cat['para']){ ?>
	<?php foreach ($attr_cat['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_cat['class'] = $attr_cat['class'] ? $attr_cat['class']:'transcoding down_list';
$attr_cat['show'] = $attr_cat['show'] ? $attr_cat['show']:'transcoding_show';
$attr_cat['type'] = $attr_cat['type'] ? 1:0;
 ?>
<div class="<?php echo $attr_cat['class'];?>" style="width:<?php  echo ($attr_cat['width'] ? $attr_cat['width'] : 104) . 'px' ?>;"  onmouseover="hg_search_show(1,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);" onmousemove="<?php echo $attr_cat['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_cat['show'];?>','<?php echo $attr_cat['extra_div'];?>', this);<?php echo $attr_cat['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_cat['show'];?>" class="overflow"><?php echo $list_scoure[0]['cat'][$_INPUT['_scoure']];?></label></a></span>
	<ul id="<?php echo $attr_cat['show'];?>" style="display:none;"  class="<?php echo $attr_cat['show'];?> defer-hover-target">
		<?php foreach ($list_scoure[0]['cat'] as $k => $v){ ?>
		<?php 
			if($attr_cat['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($attr_cat['href'])
			{
				if(!strpos($attr_cat['href'],'fid='))
				{
					$expandhref=$attr_cat['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_cat['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_cat['extra_li'];?>><a <?php if($attr_cat['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_cat['state'];?>,'<?php echo $attr_cat['show'];?>','_scoure<?php  echo $attr_cat['more']?'_'.$attr_cat['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_cat['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
	</ul>
</div>
<?php if($attr_cat['state'] == 1){ ?>
	<div class="input" <?php if($_INPUT['_scoure'] == 'other'){ ?> style="width:104px;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:104px;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="start_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['start_time'];?>"/>
		</span>
	</div>
	<div class="input" <?php if($_INPUT['_scoure'] == 'other'){ ?> style="width:104px;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:104px;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="end_time" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="<?php echo $_INPUT['end_time'];?>"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['_scoure'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date" class="btn_search" />
<?php } ?>
<?php if($attr_cat['more']){ ?>
	<input type="hidden" name="_scoure[<?php echo $attr_cat['more'];?>]"  id="_scoure_<?php echo $attr_cat['more'];?>"  value="<?php echo $_INPUT['_scoure'];?>"/>
<?php } else { ?>
	<input type="hidden" name="_scoure"  id="_scoure"  value="<?php echo $_INPUT['_scoure'];?>"/>
<?php } ?>
<?php if($attr_cat['para']){ ?>
	<?php foreach ($attr_cat['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>" />
						<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
						<input type="hidden" name="_id" value="<?php echo $_INPUT['_id'];?>" />
						<input type="hidden" name="_type" value="<?php echo $_INPUT['_type'];?>" />
					</div>
					<div class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						<script type="text/javascript">
function hg_del_keywords()
{
	var value = $('#search_list_k').val();
	if(value == '关键字')
	{
		$('#search_list_k').val('');
	}
	return true;
}
$(document).ready(function(){
	$("#search_list_k").focus(function(){
		$("#search_k").addClass("search_width");
	});
	$("#search_list_k").blur(function(){
		$("#search_k").removeClass("search_width");
	});	
});
</script>
<div class="search input clear" id="search_k">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><input type="text" name="k" id="search_list_k" value="<?php if($_INPUT['k']){ ?><?php echo $_INPUT['k'];?><?php } ?>"   speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate"/></span>
</div>                        
					</div>
				</form>
			</div>
			<form action="" method="post" name="listform" style="position: relative;">
				<!-- 标题 -->
               <ul class="common-list public-list-head">
                    <li class="common-list-head clear">
                        <div class="common-list-left" style="width:30px;">
                            <div class="common-list-item" onclick="hg_switch_order('road_list');"  title="排序模式切换/ALT+R"><a class="common-list-paixu"></a></div>
                        </div>
                        <div class="common-list-right">
                        	<div class="common-list-item open-close news-fabu wd80">来源</div>
                        	<div class="common-list-item open-close news-fabu wd80">区域</div>
                      		<div class="common-list-item open-close news-fabu wd80">热门易堵</div>
                        	<div class="common-list-item open-close news-fabu wd80">类型</div>
                            <div class="circle-zt common-list-item open-close wd60">操作</div>
                            <div class="common-list-item open-close wd60">状态</div>
                            <div class="common-list-item wd100">添加人/添加时间</div>
                        </div>
                        <div class="common-list-biaoti ">
					        <div class="common-list-item">内容</div>
				        </div>
                    </li>
                </ul>				
				<ul class="common-list public-list" id="road_list">
					<?php if($list_data && is_array($list_data)){ ?>
						<?php foreach ($list_data as $k => $v){ ?>
							<li class="common-list-data clear"  id="r_<?php echo $v['id'];?>"    name="<?php echo $v['id'];?>"   orderid="<?php echo $v['orderid'];?>">
							    <div class="common-list-left" style="width:30px;">
							        <div class="common-list-item">
							            <div class="common-list-cell">
							                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="<?php echo $v['id'];?>" title="<?php echo $v['id'];?>"/></a>
							            </div>
							        </div>
							    </div>
							    <div class="common-list-right">
									<?php 
										$plog_img = '';										
										if($v['glog'])
										{
												$plog_img = $v['glog']['host'] . $v['glog']['dir'] . '40x30/' . $v['glog']['filepath'] . $v['glog']['filename'];												
										}
									 ?>	
									<!--2013.07.17 -->
									<div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
							                    <span>
							                    <?php 
							                    if($v['user_id']>0)
							                    	echo "编辑添加";
							                    else
							                    	echo "微博获取";
							                     ?>
							                    </span>
							            </div>
							        </div>
							        <!--2013.07.17 -->
									<!--2013.07.12 -->
									<div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
							                    <span>
							                    <?php 
							                    foreach($v['road_area'] as $area)
							                    	echo $area." ";
							                     ?>
							                    </span>
							            </div>
							        </div>
							        <div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
							                    <span><?php if($v['is_hot']==1)echo "热点"; ?></span>
							            </div>
							        </div>
							        <!--2013.07.12 -->						    
								    <div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
							                    <span style="color:<?php echo $v['color'];?>;"><?php echo $v['group_name'];?><?php if($plog_img){ ?><img src="<?php echo $plog_img;?>" style="vertical-align:middle;width:20px;height:20px;"/><?php } ?></span>
							            </div>
							        </div>
							        <div class="common-list-item circle-bj wd60">
							            <div class="common-list-cell" style="width:48px;">
							                    <a title="编辑" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
							                    <a title="删除" href="javascript:hg_road_delete(<?php echo $v['id'];?>);"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
							            </div>
							        </div>
									<?php 
										switch($v['state'])
										{
											case 0:
												$v['status'] = '未审核';
												break;
											case 1:
												$v['status'] = '已审核';
												break;
											case 2:
												$v['status'] = '已打回';
												break;
											default:
												$v['status'] = '未审核';
												break;
										}
									 ?>							        
							        <div class="common-list-item circle-zt wd60">
							            <div class="common-list-cell">
							                   <div class="common-switch-status"><span  id="statusLabelOf<?php echo $v['id'];?>" _id="<?php echo $v['id'];?>" _state="<?php echo $v['state'];?>" style="color:<?php echo $list_setting['status_color'][$v['status']];?>;"><?php echo $v['status'];?></span></div>
							            </div>
							        </div>
							        <div class="common-list-item wd100">
							            <div class="common-list-cell">
							                <span class="common-user"><?php echo $v['uname'];?></span>
			   								<span class="common-time"><?php echo $v['create_time'];?></span>
							            </div>
							        </div>
							    </div>
							    <div class="common-list-biaoti" style="cursor:pointer;"  onclick="hg_show_opration_info(<?php echo $v['id'];?>);">
							    	<div class="common-list-item biaoti-transition">
								        <div class="common-list-cell">
											 <?php 
												$log_img = '';
												if($v['pic'])
												{
													if($v['local_img'])
													{
														$log_img = $v['pic']['host'] . $v['pic']['dir'] . '40x30/' . $v['pic']['filepath'] . $v['pic']['filename'];	
													}
													else
													{
														$log_img = $v['pic']['host'] . $v['pic']['dir'] . $v['picsize']['thumbnail'] . $v['pic']['filepath'] . $v['pic']['filename'];									
													}					
												}
											  ?>	
								        	 <?php if($log_img){ ?><img src="<?php echo $log_img;?>" style="width:40px;height:30px;margin-right:10px;"/><?php } ?>
								        	 <span style="<?php if($v['address']){ ?>display: inline-block;vertical-align: middle;<?php } ?>">
								        	 <span id="title_<?php echo $v['id'];?>"  class="common-list-overflow" style="max-width:350px;<?php if($v['address']){ ?>display:block;<?php } ?>"><?php echo $v['content'];?></span>
								        	 <?php if($v['address']){ ?><span style="color:#999999;font-size:12px;">地点：<?php echo $v['address'];?></span><?php } ?>	
								        	 </span>						      
								        </div> 
							        </div>         
							    </div>	
							</li>
						<?php } ?>
					<?php } else { ?>
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					<?php } ?>
				</ul>
				<ul class="common-list public-list">
					<li class="common-list-bottom clear">
						<div class="common-list-left">
							<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
							 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
						</div>
						<?php echo $pagelink;?>
					</li>
				</ul>
				<div class="edit_show">
					<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
					<div id="edit_show"></div>
				</div>
			</form>
		</div>
		</div>
	</div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
<script type="text/javascript">
</script>
<?php if((!$_INPUT['infrm'] && !$__top)){ ?>
	<?php if(SCRIPT_NAME != 'login'){ ?>
		<div class="footer <?php echo $hg_name;?>"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span><span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } else { ?>
		<div class="footer login_footer"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span><span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } ?>
<?php } ?>
<div id="<?php echo $dialog['id'];?>" class="lightbox" style="display:none;width:452px;">
	<div class="lightbox_top">
		<span class="lightbox_top_left"></span>
		<span class="lightbox_top_right"></span>
		<span class="lightbox_top_middle"></span>
	</div>
	<div class="lightbox_middle">
		<span style="position:absolute;right:25px;top:25px;z-index:1000;"><img width="14" height="14" id="<?php echo $dialog['id'];?>Close" src="<?php echo $RESOURCE_URL;?>close.gif" style="cursor:pointer;"></span>
		<div id="<?php echo $dialog['id'];?>body" class="text" style="max-height:500px">
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
		</div>
	</div>
	<div class="lightbox_bottom">
		<span class="lightbox_bottom_left"></span>
		<span class="lightbox_bottom_right"></span>
		<span class="lightbox_bottom_middle"></span>
	</div>
</div>
<script>
function hg_open_widows(){
	var id = $('#livUpload_div');
	if(id.css('display')=='none')
	{
		$('#livUpload_small_windows').animate({'width':'278px'},function(){
				id.show();
				id.animate({'height':'200px'});
				$('.livUpload_text').addClass('b');
			});
	}
	else{
		id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'200px'},function(){
					$('.livUpload_text').removeClass('b');
				});
			});
	}
}
function livUpload_text_move()
{
	if($('.livUpload_text_b').css('top')=='28px')
	{
		$('.livUpload_text_a').animate({'top':'-28px'});
		$('.livUpload_text_b').animate({'top':'0'},function(){$('.livUpload_text_a').css({'top':'28px'});});
	}
	else{
		$('.livUpload_text_b').animate({'top':'-28px'});
		$('.livUpload_text_a').animate({'top':'0'},function(){$('.livUpload_text_b').css({'top':'28px'});});
	}
}
function hg_goToTop()
{
	$('.livUpload_text_b').css({'top':'28px'});
	$('.livUpload_text_a').css({'top':'0'});
}
function hg_closeProgress()
{
	var frame = hg_findNodeFrame();
	frame.hg_closeButtonX(true);
}
</script>
<span class="upload_flash" id="flash_wrap" style="left:0px;top:0px;"><span id="UploadPlace"></span></span>
<div id="livUpload_windows" style="display:none;">
	<div id="livUpload_div" style="display:none;">
		<div class="livUpload_bg">
			<span class="a"></span>
			<span class="b"></span>
			<span class="c"></span>
		</div>
		<div id="livUploadProgress" ></div>
	</div>
	<div id="livUpload_small_windows">
		<span class="close"></span>
		<div class="livUpload_text" id="livUpload_text">
			<span id="livUpload_text_a"    class="livUpload_text_a"></span>
			<span id="livUpload_text_b"    class="livUpload_text_b" style="top:28px"></span>
			<span id="livUpload_speed"></span>
			<span id="livUpload_rate"></span>
		</div>
		<div id="livUpload_windows_b" style="width:0;"></div>
	</div>
</div>
<?php echo $this->mFooterCode;?>
<script>
/*
 * 把nodeFrame中的搜查和几个按钮提升到mainwin中；resize nodeFrame；
 */
<?php if($_INPUT['infrm']){ ?>
	$(function ($){hg_resize_nodeFrame(true<?php echo $_INPUT['_firstload'] ? ','.$_INPUT['_firstload']: ''; ?>);});
	hg_repos_top_menu();
	setTimeout("hg_resize_nodeFrame(true);", 100);
<?php } ?>
/*
if(top.livUpload.SWF)
{
	top.$('#flash_wrap').css({'left':'0px','top':'0px','position':'absolute'});
	top.setTimeout(function(){top.livUpload.SWF.setButtonDimensions(1,1);},500);
	top.livUpload.currentFlagId = 0;
}
*/
</script>
<div id="dragHelper" style="position: absolute; display: none; cursor: move; list-style-type: none; list-style-position: initial; list-style-image: initial; overflow-x: hidden; overflow-y: hidden; -webkit-user-select: none; "></div>
</body>
</html>