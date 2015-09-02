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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/common/common_publish.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>underscore.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>Backbone.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_list.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
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
<?php } ?><?php 
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);/*状态控件*/
$status_source = array(
	'class' => 'transcoding down_list',
	'show' => 'status_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = 0;
}
 ?><?php $list_setting['status_color'] = $_configs['status_color'];
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
</div><script type="text/javascript">
function hg_audit_tpl(id)
{
	var url = "run.php?mid="+gMid+"&a=audit&id="+id;
	hg_ajax_post(url);
}/*审核回调*/
function hg_audit_tpl_callback(obj)
{
	var obj = eval('('+obj+')');
	$('#status_'+obj.id).text(obj.status_text);
}
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"<?php if($_INPUT['infrm']){ ?> style="display:none"<?php } ?>>
	<a class="blue mr10"  href="?mid=<?php echo $_INPUT['mid'];?>&a=create">
		<span class="left"></span>
		<span class="middle"><em class="add">新增</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
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
<?php } ?>							<?php 
$status_source['class'] = $status_source['class'] ? $status_source['class']:'transcoding down_list';
$status_source['show'] = $status_source['show'] ? $status_source['show'] :'transcoding_show';
$status_source['type'] = $status_source['type'] ? 1:0;
if($status_source['width'] && $status_source['width'] != 104 ){
	$width = $status_source['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $status_source['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $status_source['show'];?>','<?php echo $status_source['extra_div'];?>', this);" onmousemove="<?php echo $status_source['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $status_source['show'];?>','<?php echo $status_source['extra_div'];?>', this);<?php echo $status_source['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $status_source['show'];?>" class="overflow" <?php if($status_source['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $_configs['general_audit_status'][$_INPUT['status']];?></label></a></span>
	<ul id="<?php echo $status_source['show'];?>" style="display:none;"  class="<?php echo $status_source['show'];?> defer-hover-target">
		<?php if($status_source['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="status_key" class="status_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $status_source['method'];?>', '<?php echo $status_source['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($_configs['general_audit_status']){ ?>
		<?php foreach ($_configs['general_audit_status'] as $k => $v){ ?>
			<?php if($status_source['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($status_source['is_sub'])
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
			if($status_source['href'])
			{
				if(!strpos($status_source['href'],'fid='))
				{
					$expandhref=$status_source['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$status_source['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $status_source['extra_li'];?>><a <?php if($status_source['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $status_source['state'];?>,'<?php echo $status_source['show'];?>','status<?php  echo $status_source['more']?'_'.$status_source['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $status_source['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($status_source['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $status_source['select_column'];?>" /><?php } ?>
</div>
<?php if($status_source['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $status_source['time_name'];
$end_time = 'end_time' . $status_source['time_name'];
 ?>
	<div class="input" <?php if($_INPUT['status'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxstatus">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timestatus" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_INPUT['status'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxstatus">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timestatus" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['status'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_datestatus" class="btn_search" />
<?php } ?>
<?php if($status_source['more']){ ?>
	<input type="hidden" name="status[<?php echo $status_source['more'];?>]"  id="status_<?php echo $status_source['more'];?>"  value="<?php echo $_INPUT['status'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="status" value="<?php echo $_INPUT['status'];?>"/>
<?php } else { ?>
<input type="hidden" name="status"  id="status"  value="<?php echo $_INPUT['status'];?>"/>
<?php } ?>
<?php } ?>
<?php if($status_source['para']){ ?>
	<?php foreach ($status_source['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>" />
							<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
							<input type="hidden" name="_id" value="<?php echo $_INPUT['_id'];?>" />
							<input type="hidden" name="_type" value="<?php echo $_INPUT['_type'];?>" />
	                    </div>
	                    <div class="right_2">
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
                   </form>
                </div>
                <form method="post" action="" name="vod_sort_listform">
                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
			 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
			                    </div>
                            </div>
                            <div class="common-list-right">                                <div class="common-list-item wd60">删除</div>
                                <div class="common-list-item wd100">融合</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item wd60">ID</div>
                                <div class="common-list-item wd200">应用</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    <?php if($list){ ?>
		       			    <?php foreach ($list  as $k => $v){ ?>
<li class="common-list-data clear" _id="<?php echo $v['id'];?>" id="r_<?php echo $v['id'];?>" name="<?php echo $v['id'];?>"   orderid="<?php echo $v['order_id'];?>" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="<?php echo $v[$primary_key];?>" title="<?php echo $v[$primary_key];?>"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=delete&id=<?php echo $v['id'];?>"><em class="b3"></em></a>
            </div>
        </div>
        <script>
            function hg_outpush_control(id,status)
            {
                $(function(){
                    $.get("./run.php?mid=<?php echo $_INPUT['mid'];?>&a=update&id="+id+"&status="+status,function(){
                           window.location.reload();
                    })
                })
            }
        </script>
        <div class="common-list-item wd100" style="cursor:pointer;" onclick="hg_outpush_control(<?php echo $v['id'];?>,<?php echo $v['status'];?>)">
            <span id="status_<?php echo $v['id'];?>">
                <?php if($v['status'] == 1){ ?>
                已打开
                <?php } else { ?>
                已关闭
                <?php } ?>
            </span>
        </div>
        <div class="common-list-item wd100">
            <div class="common-list-cell">
                 <span class="common-user"><?php echo $v['user_name'];?></span>
                 <span class="common-time"><?php echo $v['create_time'];?></span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1">
        <div class="common-list-item biaoti-transition">
        		<a>
	                <span class="common-list-overflow wd60 fz14 m2o-common-title" style="display:inline-block;"><?php echo $v['id'];?></span>
                </a>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <?php echo $v['name'];?>
            </div>
        </div>
    </div>
</li>		                    <?php } ?>
		                <?php } else { ?>
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的<?php echo $_configs['general_audit_status'][$_INPUT['status']];?>内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				<?php } ?>
	                </ul>
		            <ul class="common-list public-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
					   </div>
		               <?php echo $pagelink;?>
		            </li>
		          	</ul>	
    			</form>
    			<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
				</div>
            </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
</div>
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