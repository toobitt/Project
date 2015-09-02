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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/edit_video_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/mark_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/2013/button.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/column_node.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common_publish.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/video_yun.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/vod_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_datepicker.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery.switchable-2.0.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_switchable.js"></script>
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_upload_pic_handler.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_video_edit.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_add_to_collect.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>technical_review.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>flat_pop/base_pop.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>flat_pop/link_vodupload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>tree/animate.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/tip.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>underscore.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>Backbone.js"></script>
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jqueryfn_custom/uploadify/jquery.uploadify.js"></script>
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
$list = $vodinfo_list;
//hg_pre($list);
//hg_pre($vod_config);
$image_resource = RESOURCE_URL;
$vodPlayerSwf = RESOURCE_URL.'swf/';
if(!isset($_INPUT['trans_status']))
{
    $_INPUT['trans_status'] = -2;
}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
if(isset($_INPUT['id']))
{
   $id = $_INPUT['id'];
}
else
{
   $id = '';
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
 ?>
<?php 
$status_key = 'status_display';
$audit_value = 2;
$back_value = 3;
$back_label = '被打回';
$attrs_for_edit = array(
	'frame_rate', 
	'bitrate',
	'download',
	'retranscode_url',
	'vod_leixing', 
	'aspect',
	'format_duration',
	'video_duration',
	'video_totalsize',
	'video_resolution',
	'aspect',
	'audio',
	'sampling_rate',
	'video_audio_channels',
	'video',
	'isfile_name',
	'is_allow',
	'pub_url',
	'is_do_morebit',
	'is_morebitrate_ok',
	'is_forcecode_ok',
	'is_forcecode',
	'app_uniqueid',
	'object_id',
	'video_m3u8',
	'catalog',
	'is_link',
	'swf',
	'status',
);
//print_r($list);
 ?>
<script>
<?php 
$arr['file_title'] = '视频批量上传';
$arr['upload_url'] = $_configs['App_mediaserver']['protocol'] . $_configs['App_mediaserver']['host'] . $port . '/' . $_configs['App_mediaserver']['dir'] . 'admin/create.php';
$arr['file_types'] = $_configs['flash_video_type'];
$arr['description'] = 'Videos Upload';
$arr['flagId'] = 'moreFlag';
$arr['button_left'] = '900px';
$arr['button_top'] = '125px';
$arr['padding_left'] = '5';
$arr['padding_top'] = '2';
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['token'] = $_user['token'];
$arr['mid'] = $_INPUT['mid'];
$arr['upload_type'] = 0;
$arr['file_size_limit'] = $transcode_server[0]['max_size']*1024*1024;/*视频限制大小*/
$params = json_encode($arr);
 ?>
var livUploder_params = <?php echo $params;?>;
</script>
<?php if($_configs['cloud_video']['open'] == 2 || $_configs['cloud_video']['open'] ==4){ ?>
<script src="<?php echo $SCRIPT_URL;?>vod/init_uploader.js"></script>
<?php } ?>
<?php $list_setting['status_color'] = $_configs['status_color'];
$status_key = isset($status_key) ? $status_key : 'state';
$audit_value = isset($audit_value) ? $audit_value : 1;
$audit_label = isset($audit_label) ? $audit_label : '已审核';
$back_value = isset($back_value) ? $back_value : 2;
$back_label = isset($back_label) ? $back_label : '已打回';$default_attrs_for_edit = array(
	'id', 'title', 'status', 'state', 'special_id', 'click_num', 'share_num', 'expand_id','block',
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
    	if( $(this).data('noclick') ) return;
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
		<p class="publish-result-title" _title="发布">发布至：</p>
		<ul>
			<?php foreach ($publish['selected_items'] as $item){ ?>
			<li _id="<?php echo $item['id'];?>" _name="<?php echo $item['name'];?>" data-auth="<?php echo $item['is_auth'];?>" _siteid="<?php echo $item['siteid'];?>" title="<?php echo $item['show_name'];?>">
				<input type="checkbox" checked="checked" class="publish-checkbox" <?php if(!$item['is_auth']){ ?>style="visibility: hidden;"<?php } ?>/>
				<span><?php echo $item['showName'];?></span>
			</li>
			<?php } ?>
		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>
		<div class="extend-item" style="margin-top:5px;">
			<label style="margin-left:-10px;">发布时间：</label><input style="width:113px;height:18px;margin:0;" name="pub_time" value="<?php echo $publish['pub_time'];?>" class="date-picker" _time=true/>
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
		<p class="publish-result-title" _title="发布">发布至：</p>
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
	<input type="hidden" class="publish-showname-hidden" name="show_name" value="" />
	<input type="hidden" class="publish-column-hidden" name="col_id" value="" />
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
		<p class="publish-result-title" _title="发布">发布至：</p>
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
			<img src="<?php echo $RESOURCE_URL;?>loading2.gif" class="wait" />
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
	function changeStatusLabel(status, model)
	{
		 var color;
		 var status_text = "";
		 var obj = model;
		if(status == 2)
	       {
	    	   status_text = '已审核';
	    	   color = '<?php echo $list_setting['status_color'][1];?>';
	       }
	       else if(status == 3)
	       {
	    	   status_text = '被打回';
	    	   color = '<?php echo $list_setting['status_color'][2];?>';    
	       }
		 $('#text_'+model.id).text(status_text).css('color', color).attr('_state', status);
		 if(status == 2)
  	   {
      	   if($('#img_sj_'+obj.id).length)
      	   {
      		   $('#img_sj_'+obj.id).removeClass('b');
             }
      	   if($('#img_lm_'+obj.id).length)
      	   {
      		   $('#img_lm_'+obj.id).removeClass('b');
             }
  	   }
  	   else
  	   {
      	   if($('#img_sj_'+obj.id).length)
      	   {
      		   $('#img_sj_'+obj.id).addClass('b');
             }
      	   if($('#img_lm_'+obj.id).length)
      	   {
      		   $('#img_lm_'+obj.id).addClass('b');
             }
         }
	}
	function hg_change_status(obj)
	{
	   var color;
	   var obj = obj[0];
       for(var i = 0;i<obj.id.length;i++)
       {
    	   recordCollection.get(obj.id[i]).set('state', obj.status);
       }
	}
    var id = '<?php echo $id;?>';
    var frame_type = "<?php echo $_INPUT['_type'];?>";
    var frame_sort = "<?php echo $_INPUT['_id'];?>";
function header()
{
	var node_type = $('#node_type').val();
	var url="run.php?a=change_node&node_type="+node_type+"&mid=<?php echo $_INPUT['mid'];?>";
	hg_request_to(url);
}
function hg_pickup_callback(obj)
{
	var data = eval('('+obj+')');
	$('a[name="pickupvideo"]').myTip( {
		string : '视频已成功提取在 : ' + data.path + ' 目录下',
		delay : 5000,
		width : 450
	} );
}
/*function hg_showmainwin(html, selfurl, nodetype)
{
	if(parent)
	{
		if(parseInt(nodetype))
		{
			parent.$('#append_menu').hide();
		}
		else
		{
			parent.$('#append_menu').show();
		}
		parent.$('#hg_node_node').html(html);
		parent.$('#nodeFrame').attr('src',selfurl);
	}
	else
	{
		if(parseInt(nodetype))
		{
			$('#append_menu').hide();
		}
		else
		{
			$('#append_menu').show();
		}
		$('#append_menu').hide();
		$('#hg_node_node').html(html);
		$('#nodeFrame').attr('src',selfurl);
		$('#hg_node_node').html(html);
	}
}*/
</script>
<style type="text/css">
.vod-quanzhong{ width:60px; }
#vedio-player{position:absolute;right:380px;top:-120%;z-index:1;transition:top .3s;-webkit-transition:top .3s;width:346px;height:264px;background:#000;}
.show-transcode-box{cursor:pointer;}
.transcode-box{display:none;position:absolute;z-index:10;width:200px;padding:10px 20px;background:#4c4c4c;top:0;}
.transcode-box .transcode-info{color:#eee;padding:10px 0;}
.transcode-box p{line-height:2;}
.transcode-box .title{color:#eee;width:60px;}
.transcode-box .handler-btns{border-top:1px solid #555;padding:10px 0;}
.transcode-box .handler-btn{display:inline-block;background: #414141;height: 28px;line-height: 28px;color: #fff;padding:0 15px;}
.transcode-box .handler-btn:hover{background-color: #393738;}
.transcode-box .close-btn{position:absolute;width: 22px;height: 28px;top: 0;right: -23px;border-left: 1px solid #3e3e3e;box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.6);cursor: pointer;background: url("<?php echo $RESOURCE_URL;?>common/icon_close.png") no-repeat center center #4c4c4c;}
.force_recodec{height: 20px;line-height: 20px;padding: 0px 5px;background: #5C99CF;display: block;color: #fff;border-radius: 2px;position: absolute;left: 160px;bottom: 25px;}
</style>
<script>
var client_id = '<?php echo $_configs['cloud_video']['client_id'];?>';
</script>
<?php if($_configs['cloud_video']['open'] == 1 || $_configs['cloud_video']['open'] ==4){ ?>
<script src="<?php echo $SCRIPT_URL;?>livmedia/upYun.js"></script>
<?php } ?>
<div class="" <?php if($_INPUT['infrm']){ ?>style="display:none"<?php } ?>>
<div id="hg_page_menu" class="head_op"<?php if($_INPUT['infrm']){ ?> style="display:none"<?php } ?>>
	<?php 
		$is_open = $_configs['cloud_video']['open'];
		switch($is_open)
		{
			case 1:
			 ?>
			<div class="add-button mr10 add-yunvideo-btn">新增云视频</div>
			<?php 
			if($_configs['is_cloud'])
			{
				 ?>
				<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="<?php echo $_INPUT['mid'];?>"><?php echo $_configs['is_cloud'];?></a>
				<?php 
			}
			break;
			case 2:
			 ?>
			<a class="add-button mr10 flash-position">新增视频</a>
			<?php 
			if($_configs['is_cloud'])
			{
				 ?>
				<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="<?php echo $_INPUT['mid'];?>"><?php echo $_configs['is_cloud'];?></a>
				<?php 
			}
			break;
			case 3:
			 ?>
			<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="<?php echo $_INPUT['mid'];?>"><?php echo $_configs['is_cloud'];?></a>
			<?php 
			break;
			case 4:
			 ?>
			<div class="add-button mr10 add-yunvideo-btn">新增云视频</div>
			<a class="add-button mr10 flash-position">新增视频</a>
			<a class="add-yuan-btn add-button news mr10"  nodevar="vod_media_node" gmid="<?php echo $_INPUT['mid'];?>"><?php echo $_configs['is_cloud'];?></a>
			<a class="add-button mr10 pop-linkupload"><?php echo $_configs['is_link'];?></a>
			<?php 
			break;
			default:
			break;
		}
	 ?>
</div>
<div class="search_a" id="info_list_search">
				  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1 select-search">
						<?php 
							$attr_type = array(
								'class' => 'transcoding down_list',
								'show' => 'node_type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'header()',
							);
							$attr_source = array(
								'class' => 'transcoding colonm down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_weight = array(
								'class'  => 'colonm down_list data_time',
								'show'   => 'weight_show',
								'width'  => 104 /*列表宽度*/,
							);							
							$video_upload_status[-2] = '全部状态';
							foreach ($_configs['video_upload_status'] as $index => $item) {
								$video_upload_status[$index] =  $item;
							}
							/*$type_search = array(0=>'媒资分类', 1=>'网站栏目', 2=>'手机栏目');*/
							$_type_search = array(0=>"媒资分类");
							foreach($type_search as $k=>$v)
							{
								$_type_search[$k] = $v .'栏目';
							}
							$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
						 ?>
						<?php 
							$column_default = $_INPUT['pub_column_id'] ? $_INPUT['pub_column_id'] : 0;
							if( $column_default ==0 ) {
								$column_list = 	array(
									0 => '栏目'
								);
							}else{
								$column_list = split(',', $_INPUT['pub_column_name'] );
							}
							$attr_column = array(
								'class' => 'pub_column_search down_list',
								'show' => 'pub_column_show',
								'select_column' => $_INPUT['pub_column_name'],
								'width' => 90,/*列表宽度*/
								'state' => 4 /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*,4--栏目搜索*/
							);
						 ?>
						<!--<?php 
$attr_type['class'] = $attr_type['class'] ? $attr_type['class']:'transcoding down_list';
$attr_type['show'] = $attr_type['show'] ? $attr_type['show'] :'transcoding_show';
$attr_type['type'] = $attr_type['type'] ? 1:0;
if($attr_type['width'] && $attr_type['width'] != 104 ){
	$width = $attr_type['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $attr_type['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $attr_type['show'];?>','<?php echo $attr_type['extra_div'];?>', this);" onmousemove="<?php echo $attr_type['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_type['show'];?>','<?php echo $attr_type['extra_div'];?>', this);<?php echo $attr_type['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_type['show'];?>" class="overflow" <?php if($attr_type['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $_type_search[$default_node_type];?></label></a></span>
	<ul id="<?php echo $attr_type['show'];?>" style="display:none;"  class="<?php echo $attr_type['show'];?> defer-hover-target">
		<?php if($attr_type['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="node_type_key" class="node_type_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $attr_type['method'];?>', '<?php echo $attr_type['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($_type_search){ ?>
		<?php foreach ($_type_search as $k => $v){ ?>
			<?php if($attr_type['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($attr_type['is_sub'])
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
			if($attr_type['href'])
			{
				if(!strpos($attr_type['href'],'fid='))
				{
					$expandhref=$attr_type['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_type['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_type['extra_li'];?>><a <?php if($attr_type['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_type['state'];?>,'<?php echo $attr_type['show'];?>','node_type<?php  echo $attr_type['more']?'_'.$attr_type['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_type['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($attr_type['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $attr_type['select_column'];?>" /><?php } ?>
</div>
<?php if($attr_type['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $attr_type['time_name'];
$end_time = 'end_time' . $attr_type['time_name'];
 ?>
	<div class="input" <?php if($default_node_type == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxnode_type">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timenode_type" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($default_node_type == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxnode_type">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timenode_type" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($default_node_type == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_datenode_type" class="btn_search" />
<?php } ?>
<?php if($attr_type['more']){ ?>
	<input type="hidden" name="node_type[<?php echo $attr_type['more'];?>]"  id="node_type_<?php echo $attr_type['more'];?>"  value="<?php echo $default_node_type;?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="node_type" value="<?php echo $default_node_type;?>"/>
<?php } else { ?>
<input type="hidden" name="node_type"  id="node_type"  value="<?php echo $default_node_type;?>"/>
<?php } ?>
<?php } ?>
<?php if($attr_type['para']){ ?>
	<?php foreach ($attr_type['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
-->
						<?php 
$attr_source['class'] = $attr_source['class'] ? $attr_source['class']:'transcoding down_list';
$attr_source['show'] = $attr_source['show'] ? $attr_source['show'] :'transcoding_show';
$attr_source['type'] = $attr_source['type'] ? 1:0;
if($attr_source['width'] && $attr_source['width'] != 104 ){
	$width = $attr_source['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $attr_source['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $attr_source['show'];?>','<?php echo $attr_source['extra_div'];?>', this);" onmousemove="<?php echo $attr_source['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_source['show'];?>','<?php echo $attr_source['extra_div'];?>', this);<?php echo $attr_source['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_source['show'];?>" class="overflow" <?php if($attr_source['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $video_upload_status[$_INPUT['trans_status']];?></label></a></span>
	<ul id="<?php echo $attr_source['show'];?>" style="display:none;"  class="<?php echo $attr_source['show'];?> defer-hover-target">
		<?php if($attr_source['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="trans_status_key" class="trans_status_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $attr_source['method'];?>', '<?php echo $attr_source['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($video_upload_status){ ?>
		<?php foreach ($video_upload_status as $k => $v){ ?>
			<?php if($attr_source['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($attr_source['is_sub'])
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
			if($attr_source['href'])
			{
				if(!strpos($attr_source['href'],'fid='))
				{
					$expandhref=$attr_source['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_source['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_source['extra_li'];?>><a <?php if($attr_source['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_source['state'];?>,'<?php echo $attr_source['show'];?>','trans_status<?php  echo $attr_source['more']?'_'.$attr_source['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_source['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($attr_source['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $attr_source['select_column'];?>" /><?php } ?>
</div>
<?php if($attr_source['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $attr_source['time_name'];
$end_time = 'end_time' . $attr_source['time_name'];
 ?>
	<div class="input" <?php if($_INPUT['trans_status'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxtrans_status">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timetrans_status" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_INPUT['trans_status'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxtrans_status">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timetrans_status" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['trans_status'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_datetrans_status" class="btn_search" />
<?php } ?>
<?php if($attr_source['more']){ ?>
	<input type="hidden" name="trans_status[<?php echo $attr_source['more'];?>]"  id="trans_status_<?php echo $attr_source['more'];?>"  value="<?php echo $_INPUT['trans_status'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="trans_status" value="<?php echo $_INPUT['trans_status'];?>"/>
<?php } else { ?>
<input type="hidden" name="trans_status"  id="trans_status"  value="<?php echo $_INPUT['trans_status'];?>"/>
<?php } ?>
<?php } ?>
<?php if($attr_source['para']){ ?>
	<?php foreach ($attr_source['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_date['class'] = $attr_date['class'] ? $attr_date['class']:'transcoding down_list';
$attr_date['show'] = $attr_date['show'] ? $attr_date['show'] :'transcoding_show';
$attr_date['type'] = $attr_date['type'] ? 1:0;
if($attr_date['width'] && $attr_date['width'] != 104 ){
	$width = $attr_date['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $attr_date['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $attr_date['show'];?>','<?php echo $attr_date['extra_div'];?>', this);" onmousemove="<?php echo $attr_date['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_date['show'];?>','<?php echo $attr_date['extra_div'];?>', this);<?php echo $attr_date['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_date['show'];?>" class="overflow" <?php if($attr_date['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $_configs['date_search'][$_INPUT['date_search']];?></label></a></span>
	<ul id="<?php echo $attr_date['show'];?>" style="display:none;"  class="<?php echo $attr_date['show'];?> defer-hover-target">
		<?php if($attr_date['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="date_search_key" class="date_search_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $attr_date['method'];?>', '<?php echo $attr_date['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($_configs['date_search']){ ?>
		<?php foreach ($_configs['date_search'] as $k => $v){ ?>
			<?php if($attr_date['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($attr_date['is_sub'])
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
			if($attr_date['href'])
			{
				if(!strpos($attr_date['href'],'fid='))
				{
					$expandhref=$attr_date['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_date['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_date['extra_li'];?>><a <?php if($attr_date['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_date['state'];?>,'<?php echo $attr_date['show'];?>','date_search<?php  echo $attr_date['more']?'_'.$attr_date['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_date['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($attr_date['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $attr_date['select_column'];?>" /><?php } ?>
</div>
<?php if($attr_date['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $attr_date['time_name'];
$end_time = 'end_time' . $attr_date['time_name'];
 ?>
	<div class="input" <?php if($_INPUT['date_search'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxdate_search">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timedate_search" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_INPUT['date_search'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxdate_search">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timedate_search" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['date_search'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_datedate_search" class="btn_search" />
<?php } ?>
<?php if($attr_date['more']){ ?>
	<input type="hidden" name="date_search[<?php echo $attr_date['more'];?>]"  id="date_search_<?php echo $attr_date['more'];?>"  value="<?php echo $_INPUT['date_search'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="date_search" value="<?php echo $_INPUT['date_search'];?>"/>
<?php } else { ?>
<input type="hidden" name="date_search"  id="date_search"  value="<?php echo $_INPUT['date_search'];?>"/>
<?php } ?>
<?php } ?>
<?php if($attr_date['para']){ ?>
	<?php foreach ($attr_date['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
$attr_column['class'] = $attr_column['class'] ? $attr_column['class']:'transcoding down_list';
$attr_column['show'] = $attr_column['show'] ? $attr_column['show'] :'transcoding_show';
$attr_column['type'] = $attr_column['type'] ? 1:0;
if($attr_column['width'] && $attr_column['width'] != 104 ){
	$width = $attr_column['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $attr_column['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $attr_column['show'];?>','<?php echo $attr_column['extra_div'];?>', this);" onmousemove="<?php echo $attr_column['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $attr_column['show'];?>','<?php echo $attr_column['extra_div'];?>', this);<?php echo $attr_column['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_column['show'];?>" class="overflow" <?php if($attr_column['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $column_list[$column_default];?></label></a></span>
	<ul id="<?php echo $attr_column['show'];?>" style="display:none;"  class="<?php echo $attr_column['show'];?> defer-hover-target">
		<?php if($attr_column['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="pub_column_id_key" class="pub_column_id_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $attr_column['method'];?>', '<?php echo $attr_column['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($column_list){ ?>
		<?php foreach ($column_list as $k => $v){ ?>
			<?php if($attr_column['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($attr_column['is_sub'])
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
			if($attr_column['href'])
			{
				if(!strpos($attr_column['href'],'fid='))
				{
					$expandhref=$attr_column['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$attr_column['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $attr_column['extra_li'];?>><a <?php if($attr_column['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_column['state'];?>,'<?php echo $attr_column['show'];?>','pub_column_id<?php  echo $attr_column['more']?'_'.$attr_column['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_column['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($attr_column['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $attr_column['select_column'];?>" /><?php } ?>
</div>
<?php if($attr_column['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $attr_column['time_name'];
$end_time = 'end_time' . $attr_column['time_name'];
 ?>
	<div class="input" <?php if($column_default == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxpub_column_id">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timepub_column_id" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($column_default == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxpub_column_id">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timepub_column_id" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($column_default == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_datepub_column_id" class="btn_search" />
<?php } ?>
<?php if($attr_column['more']){ ?>
	<input type="hidden" name="pub_column_id[<?php echo $attr_column['more'];?>]"  id="pub_column_id_<?php echo $attr_column['more'];?>"  value="<?php echo $column_default;?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="pub_column_id" value="<?php echo $column_default;?>"/>
<?php } else { ?>
<input type="hidden" name="pub_column_id"  id="pub_column_id"  value="<?php echo $column_default;?>"/>
<?php } ?>
<?php } ?>
<?php if($attr_column['para']){ ?>
	<?php foreach ($attr_column['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>
						<?php 
if($attr_weight['width'] && $attr_weight['width'] != 104 ){
	$width = $attr_weight['width'];
}else{
	$width = 90;
}
$_INPUT['start_weight'] = isset($_INPUT['start_weight']) ? $_INPUT['start_weight'] : -1;
$_INPUT['end_weight'] = isset($_INPUT['end_weight']) ? $_INPUT['end_weight'] : -1;
if ( $_INPUT['start_weight'] + $_INPUT['end_weight'] == -2 ) {
	$weightLabelShow = "所有权重";
} elseif ($_INPUT['start_weight'] == -1) {
	$weightLabelShow = "权重小于".$_INPUT['end_weight'];
} elseif ($_INPUT['end_weight'] == -1) {
	$weightLabelShow = "权重大于".$_INPUT['start_weight'];
} else {
	$weightLabelShow = "权重(".$_INPUT['start_weight']."-".$_INPUT['end_weight'].")";
}
$_INPUT['start_weight'] = $_INPUT['start_weight'] == -1 ? 0 : $_INPUT['start_weight'];
$_INPUT['end_weight'] = $_INPUT['end_weight'] == -1 ? 100 : $_INPUT['end_weight'];
 ?>
<style>
.weight-box{display:none;position:relative;width:262px;border:1px solid #cdcdcd;background:#fff;z-index:10000;}
.weight-box em{font-style:normal;}
.mb8{margin-bottom:8px;}
.mt10{margin-top:10px;}
.ml10{margin-left:10px;}
.weight-box .dotline{margin-bottom:10px;padding:0 10px 10px;background:url(<?php echo $RESOURCE_URL;?>dottedLine.png) repeat-x bottom;}
.common-weight .item{display:inline-block;margin-right:20px;}
.common-weight .item input{float:left;margin-right:8px;}
.common-weight .item .weight-radio{float:left;margin:2px 10px 0 0;}
.common-weight .item .number{cursor:pointer;vertical-align:middle;}
.common-weight .item .number i{font-style:normal;}
.common-weight .item .number .start{margin-right:5px;}
.common-weight .item .number .end{margin-left:5px;}
.weight-box .weight-list{width:260px;}
.weight-box .weight-list ul{position:relative;width:252px;padding:0 0 8px 0;top:8px;left:6px;border:0;}
.weight-box .weight-list li{height:36px;width:114px;border:1px solid #d8d8d8;border-radius:2px;background:#f5f5f5;float:left;margin:2px 5px;text-align:left;}
.weight-list .weight-number{font-size:10px;height:24px;width:24px;line-height:16px;border-radius:24px;display:block;float:left;background:#fc1712;margin:6px 5px;text-align:center;}
.weight-list .weight-number span{display:block;width:auto;height:16px;margin:4px auto;border-radius:16px;}
.weight-list .weight-describe{border:0;color:#868686;font-size:12px;display:inline;line-height:36px;}
.weight-box .define-weight{padding:10px 10px 20px 10px;}
.weight-box .define-weight .txt{width:34px;text-align:center;padding:0;}
.weight-box .define-weight .btn{width:65px;height:24px;border:1px solid #adadad;border-radius:2px;margin-left:15px;background:#e2e3e5;}
.helpBox{cursor:pointer;}
.helpBox:hover a.help-icon{color:#ff9b01;}
.helpInfo{display:none;position:absolute;top:-11px;left:-2px;padding-left:18px;}
.helpInfo div{width:110px;height:170px;border:1px solid #ccc;padding:15px 10px 15px;background:#fff8ee;color:#ff9b01;border-left:0;overflow:hidden;line-height:1.6;}
.helpBox:hover .helpInfo{display:block;}
.slider-weight-box{vertical-align:middle;padding:0 10px;width:230px;}
.slider-weight-box i{display:inline-block;height:14px;line-height:12px;}
#weightSlider{float:left;width:165px;height:6px;margin:2px 15px 0 10px;border-radius:2px;}
.ui-slider-horizontal .ui-slider-range{background:#6d6d6d;}
.weightSliderLable{display:block;}
.slider-weight-box .start,.slider-weight-box .end{float:left;font-style:normal;}
.current-quanzhong .ui-widget-content .ui-state-default,.slider-weight-box .ui-widget-content .ui-state-default{width:18px;height:18px;background:url(<?php echo $RESOURCE_URL;?>slider_button.png) no-repeat;border:0;}
.slider-number{display:block;position:absolute;top:24px;width:20px;height:19px;line-height:22px;color:#333;background:url(<?php echo $RESOURCE_URL;?>slider_num_bg.png) no-repeat;font-size:11px;-webkit-text-adujst:none;text-align:center;}
.current-quanzhong .ui-widget-content .ui-state-default, .slider-weight-box .ui-widget-content .ui-state-default{top:-3px!important;width:12px!important;height:12px!important;background:-webkit-linear-gradient(#d0cfcf,#9d9d9d)!important;background:-moz-linear-gradient(#d0cfcf,#9d9d9d)!important;border-radius:50%!important;}
/*.slider-weight-box .ui-widget-content .ui-state-default:last-of-type{background:-webkit-linear-gradient(#3e9e16,#ca5b02)!important;background:-moz-linear-gradient(#3e9e16,#ca5b02)!important;}*/
.weight-box .ui-widget-content{background:#6d6d6d;border:0;border-radius:2px!impotant;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
        .ui-widget-content .ui-state-default{background-image:url(<?php echo $RESOURCE_URL;?>slider_button-2x.png);background-size:100%;}
        .slider-number{background-image:url(<?php echo $RESOURCE_URL;?>slider_num_bg-2x.png);background-size:100%;}
}
</style>
<script>
$(function ($) {
	var box = $(".weightPicker .weight-box");
	var needHide = true;	function render() {
		if ( !top.$.globalData ) return;
    	var configWeight = top.$.globalData.get('quanzhong');
    	if (configWeight) {
    		var el = $.tmpl( $('#search_weight_list_tpl').html(), {mydata: configWeight}, {} );
    		box.prepend(el);
    	}
	}
	render();
	box
		.hover(function () {
			needHide = false;
		}, $.noop)
		.on("click", ".weight-list li", function () {
			box.hide();
			needHide = true;
			var value = $(this).data('weight').split(',');
			$("#start_weight").val( value[0] );
			$("#end_weight").val( value[1] );
			$("#searchform").submit();
		})
		.on("click", ".cancel", function () { box.hide();needHide = true; })
		.on("click", ".submitBtn", function () {
			box.hide();
			needHide = true;
			$("#searchform").submit();
		});
	var weight_search = <?php echo json_encode($_configs['weight_search']); ?>;
		values = [<?php echo $_INPUT['start_weight'];?>, <?php echo $_INPUT['end_weight'];?>],
		num1 = $('#weight_num1'),
		num2 = $('#weight_num2');
		num1.val(values[0]);
		num2.val(values[1]);
	num1.on('blur', function() {
		var max = num2.val();
		var val = parseInt(num1.val());
		if ( !isNaN(val) && val >= 0 && val <= max ) {
		} else {
			val = 0;
		}
		$("#weightSlider").slider('values', [val, max]).trigger('slide');
		refreshValues([val, max]);
	});
	num2.on('blur', function() {
		var min = num1.val();
		var val = parseInt(num2.val());
		if ( !isNaN(val) && val <= 100 && val >= min ) {
		} else {
			val = 100;
		}
		$("#weightSlider").slider('values', [min, val]).trigger('slide');
		refreshValues([min, val]);
	});
	function refreshValues(values) {
		num1.val(values[0]);
		num2.val(values[1]);
		$('#start_weight').val(values[0]);
		$('#end_weight').val(values[1]);
	}
	$("#weightSlider").slider({
		create: function () {
			var start= $(this).find("a:first"), 
			    end= $(this).find("a:last");
		},
		animate: true,
		range: true,
		max: 100,
		min: 0,
		values: values,
		slide: function (e, ui) {
			values = ui.values;
			refreshValues(values);
		}
	});
});
</script>
<div style="width:<?php  echo $width . 'px' ?>;" class="colonm down_list weightPicker">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label class="overflow" id="display_colonm_show"><?php echo $weightLabelShow;?></label></a></span>
	<div class="weight-box defer-hover-target">
		<!-- 模板在下面 -->
		<div class="define-weight clear">
		  <div class="mb8">
		  	<span>权重范围：</span>
		  	<span style=""><input id="weight_num1" type="text" class="txt">－<input id="weight_num2" type="text" class="txt"></span>
		  	<input type="submit" value="确定" class="btn">	
		  </div>
		  <input type="hidden" name="start_weight" id="start_weight" value="<?php echo $_INPUT['start_weight'];?>" />
		  <input type="hidden" name="end_weight" id="end_weight" value="<?php echo $_INPUT['end_weight'];?>" />
		  <div class="slider-weight-box mt10">
			  <i class=start>0</i>
			  <div id="weightSlider"></div>
			  <i class="end">100</i><br/>
		  </div>
		</div>
	</div>
</div>
<script type="tpl" id="search_weight_list_tpl">
<div class="weight-list">
<ul class="dotline">
	{{each mydata}}
	<li data-weight="${_value.begin_w},${_value.end_w}">
		<span class="weight-number" style="background: transparent;"><span>≥${_value.begin_w}</span></span><a class="weight-describe">${_value.title}</a>
	</li>
	{{/each}}
</ul>
</div>
</script>
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>" />
						<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
						<input type="hidden" name="_id" value="<?php echo $_INPUT['_id'];?>" />
						<input type="hidden" name="_type" value="<?php echo $_INPUT['_type'];?>" />
                    </div>
                    <div class="right_2 text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						<?php 
if($hg_attr['width'] && $hg_attr['width'] != 104 ){
	$width = $hg_attr['width'];
}else{
	$width = 90;
}
if($hg_attr['is_sub'])
	{
		$is_submit = 0;
	}
	else
	{
		$is_submit = 1;
	}
 ?>
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
<div class="search input clear <?php if($hg_attr['class']){ ?><?php echo $hg_attr['class'];?><?php } ?>" id="search_k" style="width:<?php  echo $width . 'px' ?>;">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><input style="width:<?php  echo $width . 'px' ?>;" type="text" <?php if($hg_attr['state']==3 || !$hg_attr['state']){ ?>onblur="if( hg_blur_value(<?php echo $is_submit;?>) ){}"<?php } ?> class="<?php if($hg_attr['state'] == 2 ){ ?>autocomplete<?php } ?>" name="k" id="search_list_k" value="<?php if($_INPUT['k']){ ?><?php echo $_INPUT['k'];?><?php } ?>" placeholder="<?php echo $hg_attr['place'];?>"  speech="speech" <?php if(!$hg_attr['place']){ ?>x-webkit-speech="x-webkit-speech"<?php } ?> x-webkit-grammar="builtin:translate" onkeydown='if(event.keyCode==13) return false;'/></span>
</div>                        
                    </div>
                    <div class="custom-search">
						<?php 
							$attr_creater = array(
								'class' => 'custom-item',
								'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
								'place' =>'添加人'
							);
						 ?>
						<?php 
if($attr_creater['width'] && $attr_creater['width'] != 104 ){
	$width = $attr_creater['width'];
}else{
	$width = 90;
}
if($attr_creater['is_sub'])
	{
		$is_submit = 0;
	}
	else
	{
		$is_submit = 1;
	}
 ?>
<script type="text/javascript">
function hg_del_keywords()
{
	var value = $('#search_list_user_name').val();
	if(value == '关键字')
	{
		$('#search_list_user_name').val('');
	}
	return true;
}
$(document).ready(function(){
	$("#search_list_user_name").focus(function(){
		$("#search_user_name").addClass("search_width");
	});
	$("#search_list_user_name").blur(function(){
		$("#search_user_name").removeClass("search_width");
	});	
});
</script>
<div class="search input clear <?php if($attr_creater['class']){ ?><?php echo $attr_creater['class'];?><?php } ?>" id="search_user_name" style="width:<?php  echo $width . 'px' ?>;">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><input style="width:<?php  echo $width . 'px' ?>;" type="text" <?php if($attr_creater['state']==3 || !$attr_creater['state']){ ?>onblur="if( hg_blur_value(<?php echo $is_submit;?>) ){}"<?php } ?> class="<?php if($attr_creater['state'] == 2 ){ ?>autocomplete<?php } ?>" name="user_name" id="search_list_user_name" value="<?php if($_INPUT['user_name']){ ?><?php echo $_INPUT['user_name'];?><?php } ?>" placeholder="<?php echo $attr_creater['place'];?>"  speech="speech" <?php if(!$attr_creater['place']){ ?>x-webkit-speech="x-webkit-speech"<?php } ?> x-webkit-grammar="builtin:translate" onkeydown='if(event.keyCode==13) return false;'/></span>
</div>
					</div>
                    </form>
</div>
</div>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
		   <div id="technical_review"  class="single_upload" style='height:1300px;'>
				<h2><span class="b" onclick="hg_closeTechnicalReviewTpl();"></span>技审信息</h2>
				<div id="technical_info" class="upload_form"></div>
		   </div>
			<!--新增视频-->
		  <div id="add_videos"  class="single_upload">
				<h2><span class="b" onclick="hg_closeButtonX();"></span>新增视频</h2>
				<h3 id="single_select" class="select_item" onclick="hg_add_single_video(this)">上传单个文件<span class="a"></span></h3>
				<h3 id="more_select" class="select_item" onclick="hg_add_more_videos(this);">上传多个文件<span class="b"></span></h3>
				<!-- 
				<h3 id="live_select" class="select_item" onclick="hg_load_timeShift(this)" <?php if($_configs['App_live']){ ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>从直播时移获取<span class="c"></span></h3>
				 -->
				<div id="hg_single_select" class="upload_form"></div>
				<div id="hg_more_select" class="upload_form"></div>
				<div id="hg_live_select" class="upload_form"></div>
		  </div>
			<!--新增视频结束-->
			<!--添加视频至集合开始-->
		  <div id="add_to_collect"  class="single_upload">
				<h2><span class="b" onclick="hg_closeAddToCollectTpl();"></span>添加视频至集合</h2>
				<div id="add_to_collect_form" class="upload_form" style="background:none;"></div>
		  </div>
			<!--添加视频至集合结束-->
			<?php if(!$list){ ?>
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('p',1);</script>
			<?php } else { ?>
                <?php 
                $columnData = array(
					array(
						'class' => 'vod-fabu',
						'innerHtml' => '发布至'
					),
					array(
						'class' => 'vod-maliu',
						'innerHtml' => '码流'
					),
					array(
						'class' => 'vod-fenlei',
						'innerHtml' => '分类'
					),
					array(
						'class' => 'vod-quanzhong',
						'innerHtml' => '权重'
					),
					array(
						'class' => 'vod-zhuangtai',
						'innerHtml' => '状态'
					),
					array(
						'class' => 'vod-ren',
						'innerHtml' => '添加人/时间'
					)
				);
                 ?>
               <!-- <div style="position: relative;">
	<div id="open-close-box">
		<span></span>
		<div class="open-close-title">显示/关闭</div>
		<ul>
		<?php foreach ($columnData as $kk => $vv){ ?>
			<li which="<?php echo $vv['class'];?>"><label class="overflow"><input type="checkbox" checked /><?php echo $vv['innerHtml'];?></label></li>
		<?php } ?>
		</ul>
	</div>
</div>
 --> 
                <form method="post" action="" name="listform" style="display:block;position:relative;">
                    <ul class="vod-list common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
                                   <a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a>
                                </div>
                                <div class="common-list-item wd60">缩略图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="vod-fabu common-list-item open-close common-list-pub-overflow" which="vod-fabu">发布至</div>
                                <div class="vod-maliu common-list-item open-close wd70" which="vod-maliu">码流</div>
                                <div class="vod-fenlei common-list-item open-close wd80" which="vod-fenlei">分类</div>
                                <div class="vod-quanzhong common-list-item open-close wd60" which="vod-quanzhong">权重</div>
                                <div class="vod-zhuangtai common-list-item open-close wd60" which="vod-zhuangtai">状态</div>
                                <div class="vod-zhuangtai common-list-item open-close wd60" which="vod-tuisong">推送</div>
                                <div class="vod-ren common-list-item open-close wd100" which="vod-ren">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">标题</div>
					        </div>
                        </li>
                    </ul>
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
                        function hg_outpush_vod()
                        {
                            var ids = hg_get_ids();
                            $(function() {
                                $.post(
                                    "./run.php?mid=2890&a=create",
                                    {
                                        ids: ids,
                                        pushType:'vod'
                                    },
                                    function (data) {
                                        console.log(data);
                                    }
                                )
                            })
                        }
                    </script>
                    <ul class="vod-list common-list public-list hg_sortable_list" data-order_name="video_order_id" data-table_name="vodinfo" id="vodlist">
					<?php foreach ($list as $k => $v){ ?>
						<li class="common-list-data clear" _id="<?php echo $v['id'];?>" id="r_<?php echo $v['id'];?>" name="<?php echo $v['id'];?>" video_order_id="<?php echo $v['video_order_id'];?>" cname="<?php echo $v['cid'];?>" corderid="<?php echo $v['order_id'];?>">
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input id="<?php echo $v[$primary_key];?>" type="checkbox" name="infolist[]"  value="<?php echo $v[$primary_key];?>" title="<?php echo $v[$primary_key];?>" onclick="hg_get_ids()" /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
        	<?php 
	        	$img = '';
	        	if( is_array($v['img_info']) && $v['img_info']['filename'] ){
	        		$img = hg_bulid_img($v['img_info'], 40, 30);
	        	}else{
	        		$img =	$RESOURCE_URL.'video/video_default.png';
	        	}
        	 ?> 
        	<img _src="<?php echo $img;?>" width="40" height="30" onclick="hg_get_img(<?php echo $v['id'];?>);" id="img_<?php echo $v['id'];?>" title="点击(显示/关闭)截图 " />
        </div>
    </div>
    <div class="common-list-right">
        <div class="vod-fabu common-list-item common-list-pub-overflow">
            	<div class="common-list-pub-overflow">
                <?php 
                $step = '';
                 ?>
                <?php if($v['pub']){ ?>           	
                    <?php foreach ($v['pub'] as $kk => $vv){ ?>
					    	<?php if($v['pub_url'][$kk]){ ?>
					    		<?php if(is_numeric($v['pub_url'][$kk])){ ?>
					    			<a href="./redirect.php?id=<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $step;?><?php echo $vv;?></span></a>
					    		<?php } else { ?>
					    		    <a href="<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $step;?><?php echo $vv;?></span></a>
					    		<?php } ?>												    	
					    	<?php } else { ?>
					    		<span class="common-list-pre-pub"><?php echo $step;?><?php echo $vv;?></span>
					    	<?php } ?>
                           <?php 
                          	 $step = ' ';
                            ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="vod-maliu common-list-item wd70">
                <span style="background:<?php echo $v['bitrate_color'];?>"  id="bitrate_<?php echo $v['id'];?>"><?php echo $v['bitrate'];?></span>
        </div>
        <div class="vod-fenlei common-list-item wd80">
            <div class="overflow"><span style="color:<?php echo $v['vod_sort_color'];?>" id="sortname_<?php echo $v['id'];?>"><?php echo $v['vod_sort_id'];?></span></div>
        </div>
<?php 
$v['weight'] = $v['weight'] ? $v['weight'] : 0;
 ?>
<div class="common-list-item wd60 news-quanzhong vod-quanzhong open-close">
	<div class="common-quanzhong-box">
		<div class="common-quanzhong-box<?php echo $v['weight'];?>" _level="<?php echo $v['weight'];?>">
			<div class="common-quanzhong" style="background:<?php echo create_rgb_color($v['weight']); ?>">
				<span class="common-quanzhong-label"><?php echo $v['weight'];?></span>
			</div>
		</div>
	</div>
</div>
        <div class="vod-zhuangtai common-list-item wd60" >
               <div <?php if(in_array($v['status_display'], array(1, 2, 3))){ ?>class="common-switch-status"<?php } ?>>
                <span id="text_<?php echo $v['id'];?>" class="zt_a <?php if($v['status_display']== -1 || $v['status_display']== 0){ ?>show-transcode-box<?php } ?>" _id="<?php echo $v['id'];?>" _state="<?php echo $v['status_display'];?>" _stateflag="<?php if($v['status_display']==-1){ ?>transcode-failed<?php } elseif($v['status_display']==0) { ?>transcoding<?php } ?>" style="color:<?php echo $list_setting['status_color'][$v['status']];?>;"><?php echo $v['status'];?></span>
               </div>
                <span id="tool_<?php echo $v['id'];?>" style="display:<?php if($v['status_display'] == 0){ ?>block;<?php } else { ?>none;<?php } ?>" class="zt_b"  title="" >
                    <span class="jd" id="status_<?php echo $v['id'];?>" style="width:0px;" ></span>
                </span>
        </div>
        <div class="vod-tuisong common-list-item wd60">
         <?php if($v['pid']==1){ ?>
             <span class="vod-push">CRE</span>
         <?php } ?>
        </div>
        <div class="vod-ren common-list-item wd100">
                 <span id="hg_t_<?php echo $v['id'];?>" class="hg_t_time" style="display:none;background:#EEEFF1;height:38px;" onmouseover="hg_control_status(this,1);" onmouseout="hg_control_status(this,0);">
                 <?php if($v['status_display'] == -1 && $v['vod_leixing'] != 4){ ?>
                	<a class="button_6"  href="run.php?mid=<?php echo $_INPUT['mid'];?>&id=<?php echo $v['id'];?>&a=retranscode" onclick="return hg_ajax_post(this,'重新转码',0);"  style='margin-left:24px;margin-top:7px;' >重新转码</a>
                 <?php } elseif($v['status_display'] == 0) { ?>
                	<input type='button' value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask(<?php echo $v['id'];?>,1);" />
                 <?php } elseif($v['status_display'] == 4) { ?>
                	<input type='button' value='恢复' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask(<?php echo $v['id'];?>,0);"  />
                 <?php } elseif($v['vod_leixing'] != 4) { ?>
                	<!--
                	<a class="button_6"  href="run.php?mid=<?php echo $_INPUT['mid'];?>&id=<?php echo $v['id'];?>&a=multi_bitrate" onclick="return hg_ajax_post(this,'新增多码流',0);"  style='margin-left:24px;margin-top:7px;' >新增多码流</a>
                	-->
                 <?php } ?>
                </span>
                <span class="vod-name"><?php echo $v['addperson'];?></span>
                <span class="vod-time"><?php echo $v['create_time'];?></span>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info(<?php echo $v['id'];?>);"></div>
    <div class="common-list-biaoti" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1">
        <div class="common-list-item biaoti-transition">
                <span class="c_a">
                <span class="c_a">
                    <?php if($v['collects']){ ?>
                        <span class="jh"><em id="img_jh_<?php echo $v['id'];?>"  onclick="hg_get_collect_info(<?php echo $v['id'];?>,<?php echo $_INPUT['mid'];?>);"    onmouseover="hg_fabu_jh(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu_jh(<?php echo $v[id];?>)" ></em></span>
                    <?php } ?>
                    <?php if($v['colname']){ ?>
                        <?php echo $v['colname'];?>
                    <?php } else { ?>
                        <?php if($v['pubinfo'][1]){ ?>
                             <span class="lm"><em class="<?php if($v['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>"  id="img_lm_<?php echo $v['id'];?>"    onmouseover="hg_fabu(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu(<?php echo $v[id];?>)"></em></span>
                        <?php } ?>
                        <?php if($v['pubinfo'][2]){ ?>
                            <span class="sj"><em class="<?php if($v['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>"  id="img_sj_<?php echo $v['id'];?>"    onmouseover="hg_fabu_phone(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu_phone(<?php echo $v[id];?>)"></em></span>
                        <?php } ?>
                    <?php } ?>
                </span>
                <a id="t_<?php echo $v['id'];?>" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1" target="formwin">
                <?php $each_title = $v['title'] ? $v['title'] : '无标题'; ?>
                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;"><?php echo $each_title;?></span>
                <?php if($v['starttime']){ ?>
                <span class="vod-date"><?php echo $v['starttime'];?></span>
                <?php } ?>
                <?php if(!$v['is_link']){ ?><span class="vod-duration" id="duration_<?php echo $v['id'];?>"><?php echo $v['duration'];?></span><?php } ?>
                </a>
                <?php if($v['is_link']){ ?>
                <a class="link-upload" title="外部视频"></a>
                <?php } ?>
      </div>
    </div>
    <div class="content_more clear" id="content_<?php echo $v['id'];?>"  style="display:none;">
         <div id="show_list_<?php echo $v['id'];?>" class="pic_list_r">
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
        </div>
         <div id="add_img_content_<?php echo $v['id'];?>"   class="add_img_content">
           <div id="add_from_compueter_<?php echo $v['id'];?>"></div>
         </div>
    </div>
</li>
<?php if($v['childs']){ ?>
	<?php foreach ($v['childs'] as $c){ ?>
		                 <li class="clear"   onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]" style="visibility:hidden;" /></a>
							<a class="slt"><img src="<?php echo $c['img'];?>"  width="40" height="30" />
							</a>
						</span>
	                        <span class="right">
								<a class="fb"><em class="b2" ></em></a>
								<a class="ml" ><em style="background:<?php echo $c['bitrate_color'];?>"><?php echo $c['bitrate'];?></em></a>
								<a class="fl"><em style="color:<?php echo $c['vod_sort_color'];?>" class=" overflow"><?php echo $c['vod_sort_id'];?></em></a>
								<a class="zt">
								   <em><span  class="zt_a"><?php echo $c['status'];?></span></em>
								</a>
								<span  class="hg_t_time" style="display:none"></span>
								<a class="tjr"><em><?php echo $c['addperson'];?></em><span><?php echo $c['create_time'];?></span></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;" >
								<span class="c_a">
									<?php if($c['collects']){ ?>
										<span class="jh"><em id="img_jh_<?php echo $c['id'];?>"></em></span>
									<?php } ?>
									<?php if($c['colname']){ ?>
										<?php echo $c['colname'];?>
									<?php } ?>
									<?php if($c['pubinfo'][2]){ ?>
										<span class="sj"><em class="<?php if($c['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>" ></em></span>
									<?php } ?>
								</span>
								<a><?php echo $c['title'];?>
								<?php if($c['starttime']){ ?>
								<span class="date"><?php echo $c['starttime'];?></span>
								<?php } ?>
								<strong ><?php echo $c['duration'];?></strong></a>
						</span>
                    </li>   
	<?php } ?>
<?php } ?>
					<?php } ?>             
                    </ul>
                    <ul class="common-list public-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
								<!--<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 0, 'id', '', 'ajax');"    name="batmove" >移动</a>-->
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
								<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
								<!-- <a style="cursor:pointer;"  onclick="return hg_moreVideosToCollect(this);"   name="batadd_to_collect">添加到集合</a> -->
								<?php if($_configs['App_video_split']){ ?>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置拆条', 1, 'id', '&is_allow=0', 'ajax');"   name="allow_mark">允许拆条</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'setmark', '设置拆条', 1, 'id', '&is_allow=1', 'ajax');"   name="no_mark">不允许拆条</a>
								<?php } ?>
								<?php if($_configs['App_special']){ ?>
								<a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
								<?php } ?>
								<a style="cursor:pointer;"  onclick="return hg_extractVod(this);"   name="pickupvideo">批量提取视频</a>
                                <?php if($v['outpush'] == 1){ ?>
                                <a style="cursor:pointer;"   onclick="return hg_ajax_batchpost(this,'batchpush'	,'推送',1,'id','','ajax');" name="outpush">推送</a>
                                <?php } ?>
                       		</div>
                       		<?php echo $pagelink;?>
                    	</li>
                    </ul>
                    <div class="edit_show">
						<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
						<div id="edit_show"></div>
					</div>
    			</form>
			<?php } ?>
	</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
   <div id="add_share" style="box-shadow:0 0 3px #555;padding:0 12px 12px 12px;background:#f0f0f0;display:none;position:fixed;top:50px;left:150px;z-index:100000;border:1px solid #f5f5f5;border-radius:5px;width:500px;min-height:300px;overflow:auto;"></div> 
<script type="text/x-jquery-tmpl" id="fast-set-tpl">
<?php 
$default_water = $water_default['water_default'];
if( $default_water ){
$default_water_name = '系统预设水印';
}
$default_server = array( 
	'id' => 0,
	name => '空闲'
);
 ?>
<div class="set-area-title" data-max="<?php echo $transcode_server[0]['max_size'];?>">设置</div>
<ul class="set-area-nav">
	<li class="server_id">转码服务器(<span class="select-item">空闲</span>)</li>
	<li class="water_id">水印(<span class="select-item"><?php if($default_water){ ?><?php echo $default_water_name;?><?php } else { ?>无<?php } ?></span>)</li>
	<li class="mosaic_id">马赛克(<span class="select-item">无</span>)</li>
	<li class="vod_config_id">转码配置(<span class="select-item">无</span>)</li>
</ul>
<div id="fastset">
<div class="fast-set">
	<div class="set-item server clear" data-name="server_id">
		<div class="transcode_server" >
			<ul>
				<?php foreach ($transcode_server[0]  as $k => $v){ ?>
				<?php if(count($v)>2){ ?>
				<li  data-id="<?php echo $v['id'];?>" data-name="<?php echo $v['name'];?>" data-set="true" data-type="server" class="<?php if($v['id'] == -1){ ?>select<?php } ?>" title="转码中:<?php if($v['transcode_on']){ ?><?php echo $v['transcode_on'];?><?php } else { ?>0个<?php } ?>;等待中:<?php if($v['ranscode_wait']){ ?><?php echo $v['ranscode_wait'];?><?php } else { ?>0个<?php } ?>">
					<span><?php echo $v['name'];?></span>
					<span class="flag"></span>
				</li>	
				<?php } ?>
				<?php } ?>  
			</ul>
		</div>	
	</div><div class="set-item water-area clear" data-name="water_id">
		<div class="watermark">
			<div class="title">水印列表</div>
			<ul>
				<?php foreach ($water_pic[0]  as $k => $v){ ?>
				<li data-id="<?php echo $v['id'];?>"  data-name="<?php echo $v['name'];?>" data-set="true"  data-type="water">
					  <img src="<?php echo $v['water_pic'];?>"/>
					  <span><?php echo $v['name'];?></span>
					  <span class="flag"></span>
				</li>	
				<?php } ?>
				<?php if($default_water){ ?>
				<li class="select" data-id=""  data-name="<?php echo $default_water_name;?>" data-set="true"  data-type="water">
					  <img src="<?php echo $default_water;?>"/>
					  <span><?php echo $default_water_name;?></span>
					  <span class="flag"></span>
				</li>
				<?php } ?>	
			</ul>
		</div>		<div class="water-position set-item" data-name="water_pos">
			<div class="title">水印位置</div>
			<ul>
			<li data-id="0,0" data-name="左上">左上</li>
			<li data-id="1,0" data-name="中上">中上</li>
			<li data-id="2,0" data-name="右上">右上</li>
			<li data-id="0,1" data-name="左中">左中</li>
			<li data-id="1,1" data-name="中中">中中</li>
			<li data-id="2,1" data-name="右中">右中</li>
			<li data-id="0,2" data-name="左下">左下</li>
			<li data-id="1,2" data-name="中下">中下</li>
			<li data-id="2,2" data-name="右下">右下</li>
			</ul>
		</div></div>	<div class="set-item mosaic clear" data-name="mosaic_id" >
			<ul>
				<?php foreach ($mosaic[0]  as $k => $v){ ?>
				<li  data-id="<?php echo $v['id'];?>" data-name="<?php echo $v['name'];?>"  data-set="true">
					<span><?php echo $v['name'];?></span>
					<span class="flag"></span>
				</li>	
				<?php } ?>   
			</ul>
	</div>	<div class="set-item vod_config mosaic clear" data-name="vod_config_id" >
			<ul>
				<?php foreach ($vod_config[0]  as $key => $val){ ?>
				<li  data-id="<?php echo $val['id'];?>" data-name="<?php echo $val['name'];?>"  data-set="true">
					<span><?php echo $val['name'];?></span>
					<span class="flag"></span>
				</li>	
				<?php } ?>   
			</ul>
	</div>	
		<input type="hidden" class="fast-set-hidden" name="server_id" value=""/>
		<input type="hidden" class="fast-set-hidden"  name="mosaic_id" value=""/>
		<input type="hidden" class="fast-set-hidden"  name="water_id" value=""/>
		<input type="hidden" class="fast-set-hidden"  name="water_pos" value=""/>
		<input type="hidden" class="fast-set-hidden"  name="no_water" value="<?php if(!$default_water){ ?>1<?php } ?>"/>
		<input type="hidden" class="fast-set-hidden" name="vod_config_id" value=""/>
</div>
</div>
</script><!-- 快速设置弹窗 end -->
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=audit&audit=${ state == globalData.auditValue ? 0 : 1 }&id=${id}" 
				onclick="return hg_ajax_post(this, '{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}', 0, 'hg_change_status');">
				{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}
			</a>
		</div>
		<div class="record-edit-btn-area clear">
			<?php if($_configs['App_publishcontent']){ ?>
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a>
			<?php } ?>
			<?php if($_configs['App_share']){ ?>
			{{if !(expand_id == 0)}}
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=share_form&id=${_.values(pub_url)[0]}" onclick="return hg_ajax_post(this, '分享', 0);">分享</a>
			{{/if}}
			<?php } ?>
			<?php if($_configs['App_special']){ ?>
			<a href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=special&id=${id}&infrm=1">专题</a>
			<?php } ?>
			<?php if($_configs['App_block']){ ?>
			<a>区块</a>
			<?php } ?>
			<?php if($_configs['video_cloud']['open']){ ?>
				{{if +is_link}}
				<a>已同步</a>
				{{else}}
				<a class="sync_letv" id="sync_letv${id}" data-size="${video_totalsize}" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=sync_letv&id=${id}"><?php echo $_configs['video_cloud']['title'];?></a>
				{{/if}}
			<?php } ?>
		</div>		<!--add-->
		<div class="record-edit-btn-area clear">
		   <a href="./run.php?mid=2890&a=create&pid=${id}&pushType=vod">推送</a>
		</div>
		<!--add end-->		<?php if($_configs['video_cloud']['open']){ ?>
		{{if !+is_link}}
		<div class="record-edit-btn-area clear sync_letv_progress_box" style="display:none;background:none;border:0;">
			<div style="background:#fff;height:4px;border-radius:4px;"><span class="sync_letv_progress" style="display:inline-block;vertical-align:top;border-radius:4px;height:100%;width:0;background:#5C99CF;"></span></div>
		</div>
		{{/if}}
		<?php } ?>
		{{if catalog}}
		<div class="record-catalog-info">
			<span>编目信息</span>
			<ul>
			{{each catalog}}
				{{if _value }}
				<li><label>${_value.zh_name}：</label>
					{{if typeof( _value.value ) == 'string'}}
						<p>${_value.value}</p>
					{{else}}
						<p class="clear">
						{{each _value.value}}
							{{if _value.host}}
							<span class="record-edit-img-wrap"><img src="${_value.host}${_value.dir}${_value.filepath}${_value.filename}"></span>
							{{else}}
							<span>${_value}</span>
							{{/if}}
						{{/each}}
						</p>
					{{/if}}
				</li>
				{{/if}}
			{{/each}}
			</ul>
		</div>
		{{/if}}
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${img_info.host}${img_info.dir}135x65/${img_info.filepath}${img_info.filename})"></span>
				<span class="maliu-label">${bitrate}</span>
				<span class="record-edit-info-shower">详情</span>
			</div>
			<div>
				{{if is_link == 0}}
				<!-- 下载转码好的视频的功能必须排除:转码失败 转码中 已取消 已暂停 -->
				{{if status != -1}}
					{{if status != 0}}
						{{if status != 5}}
							{{if status != 4}}
							<a class="record-edit-btn" data-notouteriframe="true" href="${download}?id=${id}&access_token=<?php echo $_user['token'];?>">下载</a>
							{{/if}}
						{{/if}}
					{{/if}}
				{{/if}}
				<!-- 重新转码视频的功能出现在:转码失败 已取消 并且排除标注归档的视频 -->
				{{if status == -1}}
					{{if vod_leixing != 4}}
					<a class="record-edit-btn retranscode" data-notouteriframe="true"  href="${retranscode_url}?id=${id}&access_token=<?php echo $_user['token'];?>">重新转码</a>
					{{/if}}
				{{/if}}
				{{if status == 5}}
					{{if vod_leixing != 4}}
					<a class="record-edit-btn retranscode" data-notouteriframe="true"  href="${retranscode_url}?id=${id}&access_token=<?php echo $_user['token'];?>">重新转码</a>
					{{/if}}
				{{/if}}
				<!-- 下载源视频 只要上传上传到mediaserver的视频都可以 所以此处不做限制 -->
				{{if vod_leixing != 4}}<a class="record-edit-btn" data-notouteriframe="true"  href="${download}?id=${id}&need_source=1&access_token=<?php echo $_user['token'];?>">下载源</a>{{/if}}
				{{if object_id}}
                	<a class="record-edit-btn" target="_blank"  href="./download.php?a=right&object_id=${object_id}">版权信息</a>
                {{/if}}
				<?php if($_configs['technical_swdl']){ ?>
				<a class="record-edit-btn cancel">技审</a>
				<?php } ?>
				<!-- 此处的快编与拆条 可以归纳为同为标注归档的操作 必须排除:转码失败 转码中 已取消 已暂停 -->
				{{if status != -1}}
					{{if status != 0}}
						{{if status != 4}}
							{{if status != 5}}
								{{if vod_leixing != 4}}
									<?php if($_configs['App_video_fast_edit']){ ?>
										<a class="record-edit-btn editor-hover"  href="./run.php?a=relate_module_show&app_uniq=video_fast_edit&mod_uniq=video_fast_edit&video_id=${id}<?php echo $_pp;?>" target="mainwin">快编</a>
									<?php } ?>
								{{/if}}
								{{if is_allow == 0 }}
									<?php if($_configs['App_video_split']){ ?>
										<a class="record-edit-btn"  href="./run.php?a=relate_module_show&app_uniq=video_split&mod_uniq=video_split&video_id=${id}<?php echo $_pp;?>" target="mainwin">拆条</a>
									<?php } ?>
								{{/if}}
							{{/if}}
						{{/if}}
					{{/if}}
				{{/if}}
				{{/if}}
			</div>
		</div>
		<div class="record-edit-line"></div>
		<div class="record-edit-info">
			{{if click_count != 0}}<span>访问:${click_count}</span>{{/if}}
			{{if downcount != 0}}<span>下载:${downcount}</span>{{/if}}
			{{if share_num != 0}}<span>分享:${share_num}</span>{{/if}}
		</div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>	<div class="push-edit-confirm">
    		<p>确定将该内容推送到CRE吗？</p>
    		<div class="record-edit-line"></div>
    		<div class="record-edit-confirm-btn">
    			<a class="push-btn">确定</a>
    			<a>取消</a>
    		</div>
    		<span class="push-edit-confirm-close"></span>
    </div>	<div class="record-edit-play">
	</div>
	<div class="record-edit-more-info">
	</div>
</div>
<script type="tpl" id="vedio-tpl">
<div style="width:360px;height:300px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{{if is_link==1}}${swf}{{else}}<?php echo RESOURCE_URL ?>swf/vodPlayer.swf?11122713{{/if}}" width="360" height="300">
	<param name="movie" value="<?php echo RESOURCE_URL ?>swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
    <!--<param name="flashvars" value="videoUrl=${video_m3u8}&autoPlay=true&aspect=${aspect}">-->
  <span class="record-edit-back-close"></span>
</script><!--<script type="tpl" id="vedio-tpl">
<video src="${hostwork}/${video_path}${video_filename}"  controls="controls" autoplay="autoplay" width="360" height="300">
	您的浏览器不支持 video 标签。
</video>
</script>-->
<script type="tpl" id="record-info-tpl">
<ul>
	<li>时长:<span>${video_duration}</span></li>
    <li>文件大小:<span>${video_totalsize}</span></li>
    <li>视频编码:<span>${video}</span></li>
    <li>平均码流:<span>${bitrate}</span></li>
    <li>视频帧率:<span>${frame_rate}</span></li>
    <li>分辨率:<span>${video_resolution}</span></li>
    <li>宽高比:<span>${aspect}</span></li>
    <li>音频编码:<span>${audio}</span></li>
    <li>音频采样率:<span>${sampling_rate}</span></li>
    <li>声道:<span>${video_audio_channels}</span></li>
	<li>视频来自与应用:<span>${app_uniqueid}</span></li>
    <li>是否是物理文件:<span>${isfile_name}</span></li>
    <li>是否经过多码流处理:<span>${is_do_morebit}</span></li>
    <li>多码流处理是否成功:<span>${is_morebitrate_ok}</span></li>
 	<li>是否经过强制转码:<span>${is_forcecode_ok}</span>{{if status}}{{if is_forcecode_ok=="否"}}<a class="force_recodec retranscode" _href="${retranscode_url}?id=${id}&access_token=<?php echo $_user['token'];?>&force_recodec=1">强制转码</a>{{/if}}{{/if}}</li>
</ul>
<span class="record-edit-back-close"></span>
</script>
<?php 
foreach (array('start_time', 'end_time', 'date_search', 'start_weight', 'end_weight', 'k', 'trans_status','user_name','pub_column_id') as $v) 
{
	$conditions[$v] = $_INPUT[$v];
}
$conditions['vod_leixing'] = $_INPUT['_type'];
$conditions['vod_sort_id'] = $_INPUT['_id'];
$conditions['k'] = $conditions['k'] ? $conditions['k'] : $_INPUT['key'];
 ?>
<script type="text/x-jquery-tmpl" id="transcode-box-tpl">
	<div class="transcode-info">
		{{if ajaxReturn == 'success'}}
			{{if status == 'running'}}
			<p><span class="title">状态：</span>正在转码中</p>
			<p><span class="title">已完成：</span>{{= transcode_percent}}%</p>
			<p><span class="title">剩余时间：</span>约{{= Math.floor(parseInt(transcode_lefttime)/60)}}分钟</p>
			{{/if}}
			{{if status == 'waiting'}}
			<p><span class="title">状态：</span>等待转码中</p>
			<p><span class="title">任务权重：</span>{{= waiting_task_weight}}</p>
			{{/if}}
			{{if status == 'callback_failed'}}
			<p><span class="title">状态：</span>转码失败</p>
			{{/if}}
		{{else}}
			任务id不存在，当前没有转码任务
		{{/if}}
	</div>
	{{if status == 'callback_failed'}}
	<div class="handler-btns">
		<a class="handler-btn re-transcode" _id="{{= id}}">重新转码</a>
	</div>
	{{/if}}
	<a class="close-btn"></a>
</script>
<!-- 重新转码 -->
<script type="text/javascript">
$(function(){
	$('#record-edit').on('click' , '.retranscode' , function(event){
		var self = $(event.currentTarget);
		if( self.data('ajax') ){
			return false;
		}
		self.text('转码中...').data('ajax',true);
		$.ajax({
			url : self.attr('href'),
			dataType : 'jsonp',
			success : function(json){
				if(json['ErrorText']){
					var tip = json.ErrorText;
					_tip(self , tip);
					self.data('ajax',false);
					self.text('重新转码');
					return false;
				}
			}
		})
		return false;
	})
	function _tip(self , tip){
		self.myTip({
			string : tip,
			delay: 1000,
			width : 150,
			dtop : 0,
			dleft : 80,
		});
	}
})
</script>
<script type="text/javascript">
$(function(){
	(function(){
		function Transcode(el){
			this.init();
			var _this = this;
			this.el = $('.transcode-box');
			this.tpl = $('#transcode-box-tpl');
			this.el.on('click','.close-btn',function(){
				_this.hide();
			});
			this.el.on('click','.re-transcode',function(){
				// console.log($(this).attr('_id'));
			});
		};
		$.extend(Transcode.prototype,{
			init : function(){
				$('<div class="transcode-box"></div>').appendTo('body');
			},
			show : function(self){
				var state = self.attr('_state');
				var pp = self.offset();
				var sHeight = self.outerHeight(),
					sWidth = self.outerWidth();
	            var	eHeight = this.el.outerHeight(),
	            	eWidth = this.el.outerWidth();
				var left = pp.left - eWidth - 30,
	            	top = pp.top;
            	var dHeight = $(document).outerHeight();
            	this.el.find('.close-btn').css({top:0,bottom:'auto'});
				if(top + eHeight > dHeight){
					this.el.find('.close-btn').css({bottom:0,top:'auto'});
	                top = pp.top + sHeight - eHeight;
				}
	            this.el.css({
	                left : left + 'px',
	                top : top + 'px'
	            });
				this.el.slideDown(300);
			},
			hide : function(){
				this.el.slideUp(300);
			},
			refresh : function( self,json ){
				this.tpl.tmpl(json).appendTo(this.el.empty());
				this.show(self);
			}
		});
		$.hg_transcode = new Transcode();
		$('.common-list').on('click','.show-transcode-box',function(){
			var self = $(this);
			var type = '';
			if( self.attr('_state')== 0 ){
				type = 1;
			}else if( self.attr('_state')== -1 ){
				type = 0;
			}
			var url = './run.php?mid='+ gMid +'&a=get_video_status',
				data = {
					ajax : 1,
					id : self.closest('li').attr('_id'),
					type : type
				};
			$.globalAjax(self,function(){
				return $.getJSON(url,data,function(json){
					/*
					json = [{
						"return":"fail",
						"reason":"task not found",
						"id":"111"
					}];
					*/
					$.hg_transcode.el.hide();
					if( json['callback'] ){
						eval( json['callback'] );
						return;
					}
					if( json[0]['return'] ){
						json[0]['ajaxReturn'] = json[0]['return'];
						$.hg_transcode.refresh(self,json[0]);
					}else{
						//console.log('返回值为空');
					}
				});
			});
		});
	})($);
});
</script>
<script type="text/javascript">
var _type = <?php echo $_INPUT['_type'] ? $_INPUT['_type'] : -1; ?>;
var statusSettings = {
	1: ['待审核', "#8ea8c8"],
	2: ['已审核', "#17b202"],
	3: ['被打回', "#f8a6a6"]	
};
/*判断当前页面是不是第一页*/
function hg_checkFirstPage()
{
	if(!$('span[id^="pagelink_"]').length)
	{
		return true;
	}
	return ($('#pagelink_1').length && $('#pagelink_1').hasClass('page_cur'))?true:false;
}
/*获取当前页面最大id*/
function hg_get_maxid()
{
	var maxid = 0;
	$('#vodlist').children().each(function() {
		maxid = Math.max(maxid, $(this).attr('_id'));
	});
	return hg_checkFirstPage() ? maxid : -1;
}
/*获取当前页面转码中的id*/
function hg_get_trans_id()
{
	var trans_ids = new Array();
	$('span[id^="text_"]', $('#vodlist')).each(function(){
		if($(this).text() == '转码中') {
			trans_ids.push( $(this).attr('_id') );
		}
	});
	return trans_ids.join() || 0;
}
/*判断当前页面有没有正在转码中,有的话,检测时间变为隔3秒检测一次,没有则变为5秒检测一次*/
function changeCheckTime()
{
	return gtransIds ? 3000 : 5000; 
}
/*请求视频转码的信息*/
function hg_getvideoinfo(maxid, trans_ids, conditions)
{
	var mpara = '', 
		transpara = '', 
		html = '';
	if (maxid && maxid != -1)
	{
		mpara = '&since_id=' + maxid;
	}
	transpara = '&transids=' + trans_ids;
	html = $.getScript('run.php?mid=<?php echo $_INPUT['mid'];?>&a=getinfo&ajax=1' + mpara + '&' + $.param(conditions) + transpara);
}
/*获取视频转码的转码信息去，更新页面*/
function hg_panduan(obj)
{
	var obj = obj[0];
	/*增长列表*/
	$.each(obj.add_data || [], function (i, item) {
		gmaxId = Math.max(gmaxId, item.id);
		gtransIds += (',' + item.id); 
		if (item.id && !$("r_" + item.id).length) {
			hg_single_video({ "vodid": item.vodid, "id": item.id });
		}
	});
	/*对正在转码中的列表进行操作*/
	$.each(obj.status_data || [], function (i, item) {
		var id = item.id, percent = item.transcode_percent, status = item.status,
			text = $("#text_"+id),
			tool = $("#tool_"+id);
		if (!id) return;
		if ( status >= 1 && status <= 3 ) 
		{
			gtransIds = gtransIds.replace(',' + id, '').replace(id + ',', '');/*两次replace次序不要颠倒*/
			$('#hg_t_'+id).html('');
			text.html( statusSettings[status][0] ).css('color', statusSettings[status][1]).attr("_state", status); 
			tool
				.prev().addClass("common-switch-status")
				.parent().removeAttr("onmouseover").removeAttr("onmouseout")
        	  	.end().end().remove();
		} 
		else if (status == -1) 
		{
			gtransIds = gtransIds.replace(',' + id, '').replace(id + ',', '');
			text.html("转码失败").attr("_state", status);
			$('#hg_t_'+id).html('<a class="button_6" href="run.php?mid=<?php echo $_INPUT['mid'];?>&id=' + id + '&a=retranscode" onclick="return hg_ajax_post(this, \'重新转码\', 0);" style="margin-left:24px;margin-top:7px;" >重新转码</a>');
		}
		else 
		{  
			$("#status_"+id).css("width", Math.ceil( tool.width() * percent / 100 ));
		} 
	});
	/*开始下一次请求*/
	setTimeout(function () {
		hg_getvideoinfo(gmaxId, gtransIds, cond);
	}, gcheckTime);
}
function changeSynletv_state(is_link, record) {
	var id = record.id,
		is_link = +is_link;
	is_link && $("#sync_letv" + id).text('已同步');
}
/*乐视TV同步后回调，改变这条记录的is_link*/
function hg_synletv_state(obj) {
	var obj = obj[0];
    hg_close_opration_info();
	if (obj.errmsg) {
		top.jAlert(obj.errmsg, '失败提醒');
		return;
	}
	$.each(obj, function (i, id) {
		recordCollection.get(id).set('is_link', 1);
	});
	$.sync_letv_progress.css(width,'100%');
}
/*乐视TV2M/s模拟显示同步进度*/
function hg_synletv_progress( target ){
	var SPEED = 2;	//模拟速度为2M/s
	var size = target.data('size'),
		reg_number_size = size.match(/^\d+(\.)?\d*/g);
		units = ['GB','MB','KB','Bytes'];
	$.sync_letv_progress_box = target.closest('#record-edit').find('.sync_letv_progress_box');
	$.sync_letv_progress = $.sync_letv_progress_box.find('.sync_letv_progress');
	if( $.isArray( reg_number_size ) && reg_number_size.length ){
		var number_size = reg_number_size[0];
		$.sync_letv_progress_box.show();
		/*以MB为单位计算视频数值大小*/
		$.each( units, function( key, value ){
			if( size.indexOf( value ) > -1 ){
				switch( value ){
					case 'GB' :
						number_size*=1024;
						break;
					case 'MB' : 
						number_size = number_size;
						break;
					case 'KB' : 
						number_size = number_size/1024;
						break;
					case 'Bytes' : 
						number_size = number_size/1024/1024;
						break;
					default : 
						number_size = number_size;
				}
				return false;
			}
		} );
		var need_time = ( number_size/SPEED)*1000;   //毫秒
		$.sync_letv_progress.animate( { width : '98%', },need_time );
	}
}
function hg_close_synletv_progress(){
	setTimeout( function(){
		$.sync_letv_progress_box.remove();
	}, 1000 );
}
var cond = <?php echo json_encode($conditions); ?>;
var gtransIds = hg_get_trans_id(); 
var gcheckTime = 5000;
var gmaxId = hg_get_maxid();
$(function ($) {
	hg_getvideoinfo(gmaxId, gtransIds, cond);
});
 /*求两个索引数组的差集*/
 function  array_diff(arr1,arr2)
 {
	 var arr3 = new Array();
	 for(var i=0; i < arr1.length; i++)
     {
		 var flag = true;
		 for(var j=0; j < arr2.length; j++)
	     {
			 if(arr1[i] == arr2[j])
			 {
				 flag = false;
		     }
		 }
		 if(flag)
		 {
			 arr3.push(arr1[i]);
		 }
	  }
	  return arr3;
 }
 /*时间格式化函数*/
 function  hg_TimeFormat(time)
 {
	 if(time < 60)
	 {
		 return time + '秒钟';
	 }
	 else if(time >= 60 && time < 3600)
	 {
		 return  Math.round(time/60) + '分钟';
	 }
	 else if(time >= 3600)
	 {
		 return Math.round(time/3600) + '小时';
	 }
 }
 function hg_control_status(obj,e)
 {
	 if(e)
	 {
		 $(obj).show();
	 }
	 else
	 {
		 $(obj).hide();
	 }
 }
 /*控制转码的状态*/
 function hg_controlTranscodeTask(id,type)
 {
	 var op = type?'pause':'resume';
	 var url = "run.php?mid="+gMid+"&a=control_transcode&type="+op+"&id="+id;
	 hg_ajax_post(url);
 }
 function hg_overControlTranscode(obj)
 {
	 // console.log(obj);
	 var obj = eval('('+obj+')');
	 if(obj.op == 'pause' && obj.return == 'success')
	 {
		 $("#text_"+obj.id).text("已暂停").addClass('show-transcode-box');
		 var html = "<input type='button'  value='恢复' class='button_6' style='margin-left:24px;margin-top:7px;' onclick='hg_controlTranscodeTask("+obj.id+",0);' />";
         $('#hg_t_'+obj.id).html(html);
	 }
	 if(obj.op == 'resume' && obj.return == 'success')
	 {
		 $("#text_"+obj.id).text("转码中");
   	  	 $("#tool_"+obj.id).show();
		 var html = "<input type='button'  value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;' onclick='hg_controlTranscodeTask("+obj.id+",1);' />";
         $('#hg_t_'+obj.id).html(html);
	 }
 }
function hg_extractVod( dom ){
	var isLink = true;
	var checked = $(dom).closest('form').find('input:checked:not([name="checkall"])');
	if( checked && checked.length ){
		checked.closest('li').each(function(){
			var	$this = $(this);
			if( $this.find('.common-list-biaoti').find('.link-upload').length ){
				isLink = false;
				return false;
			}
		});
	}
	if( isLink ){
		return hg_ajax_batchpost(dom, 'pickup_video', '批量提取视频', 1, 'id', '', 'ajax');
	}else{
		var msg = '批量提取视频中不能包含链接上传的视频';
		jAlert ? jAlert(msg, '批量提取视频提醒').position(dom) : alert(msg);
	}
}
 $( function(){
	 /*填充视频设置到top层*/
	 ( function($){
		 var box = top.$('#livUpload_div').find('.set-area'),
		 	content = $('#fast-set-tpl').html();
		 box.empty().append( content );
		 box.trigger( 'initlocalStorage' );
		 var popupload = $('.pop-linkupload', parent.document),
		 	idname = 'link-vodupload';
		 popupload.click(function(){
		 	var pop = $('body').find('#' + idname);
		 	if( pop.length ){
		 		pop.link_vodupload('show');
		 	}else{
			 	var configInfo = {
			 		id : idname,
					width : 600,
					height : 260,
					ptop : '130',
					savebtn : false,
					modalHead : false,
					popTitle : '提取视频'
			 	}
			 	var voduploadPop = $.modalPop( idname );
			 	voduploadPop.link_vodupload( configInfo );
		 	}
		 });
	 } )($);
} );
</script>
<?php if((!$_INPUT['infrm'] && !$__top)){ ?>
	<?php if(SCRIPT_NAME != 'login'){ ?>
		<div class="footer <?php echo $hg_name;?>"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span>
<?php if($licenseInfo['expire_time']){ ?>
<span<?php if($licenseInfo['leftday'] < 30){ ?> class="alert"<?php } ?>>到期时间：<?php echo $licenseInfo['expire'];?>, 还有<?php echo $licenseInfo['leftday'];?>天到期</span>
<?php } else { ?>
<span>永久授权</span>
<?php } ?>
<span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } else { ?>
		<div class="footer login_footer"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span>
<?php if($licenseInfo['expire_time']){ ?>
<span<?php if($licenseInfo['leftday'] < 30){ ?> class="alert"<?php } ?>>到期时间：<?php echo $licenseInfo['expire'];?>, 还有<?php echo $licenseInfo['leftday'];?>天到期</span>
<?php } else { ?>
<span>永久授权</span>
<?php } ?>
<span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
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
$(function($){
	var MC = $('#livUpload_div');
	/*初始化本地存储*/
	MC.find('.set-area').on( 'initlocalStorage', function(){
		$(this).find('.set-item').each( function(){
			var key = $(this).data( 'name' );
			var localData = localStorage.getItem( key );
			if( localData ){
				localData = localData.split( '|' );
				if( localData.length ){
					MC.find('input[name="'+ key + '"]').val( localData[0] );
					$(this).find('li').filter( function(){
						var id = $(this).data('id');
						return ( id == localData[0] );
					} ).trigger('click');
					MC.find( 'li.' +key ).find('.select-item').text( localData[1] );
				}
			}
		} );
	} );
	MC.on('click','.water_pic li',function(e){
		var self = $(e.currentTarget);
		var obj =self.find('p');
		obj.toggleClass('select');
		self.siblings().find('p').removeClass('select');
	});
	MC.on( 'click', '.set-upload', function(){
		if( $(this).hasClass('disable') ) return;
		//if( window.numFilesQueued ){
			window.livUpload.start();
			$(this).addClass('disable');
		//}
	} );
	MC.on( 'click', '.set-area-nav>li', function( event ){
		var self = $( event.currentTarget ),
			index = self.index(),
			current_item = MC.find( '.fast-set>div' ).eq( index );
		self.addClass( 'select' ).siblings().removeClass( 'select' );
		current_item.addClass( 'show' ).siblings().removeClass( 'show' );
	} );
	MC.on( 'click', '.set-item li', function( event ){
		var self = $( event.currentTarget ),
			item = self.closest( '.set-item' ),
			hidden_name = item.data('name'),
			type = self.data('type'),
			hidden_input = MC.find( 'input[name="' + hidden_name + '"]' ),
			no_water = MC.find( 'input[name="no_water"]' ),
			current_nav = MC.find('.set-area-nav>li.select').find('.select-item'),
			id = '',
			name = '无';
		if( type == 'server' ){
			name = '空闲';
		}
		self.toggleClass( 'select' ).siblings().removeClass('select');
		if( self.hasClass('select') ){
			id = self.data( 'id' ),
			name = self.data( 'name' );
			if( type == 'water' ){
				no_water.val('');
			}
		}else{
			if( type == 'water' ){
				no_water.val('1');
			}
		}
		self.data( 'set' ) && current_nav.text( name );
		hidden_input.val( id );
		localStorage.setItem( hidden_name, id + '|' + name );
	} );
});
function hg_open_widows(){
	var id = $('#livUpload_div');
	if(id.css('display')=='none')
	{
		$('#livUpload_small_windows').animate({'width':'406px'},function(){
				id.show();
				id.animate({'height':'auto'});
				$('.livUpload_text').addClass('b');
			});
	}
	else{
		id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'406px'},function(){
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
		<span class="close-tip"></span>
		<div class="set-button set-upload">上传</div>
		<div class="set-button set-editor">编辑</div>
		<div class="set-area"></div>
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
<?php } else { ?>
<?php } ?>
/*实例化日期选择器*/
$(window).load( function(){
	$('html').find('.date-picker').removeClass('hasDatepicker').hg_datepicker();
} );
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