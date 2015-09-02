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
<title><?php echo $this->mTemplatesTitle;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/tuji_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/edit_video_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common_publish.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common.css" />
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
<?php echo $this->mHeaderCode;?>
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>tuji.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>zoomer.js"></script>
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/move_publish.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/preloadimg.js"></script>
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
$list = $tuji_list;
//hg_pre($list);
$image_resource = RESOURCE_URL;
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
if(!isset($_INPUT['tuji_status']))
{
    $_INPUT['tuji_status'] = 0;
}
$columnData = array(
	array(
		'class' => 'tuji-fabu',
		'innerHtml' => '发布至'
	),
	array(
		'class' => 'tuji-fenlei',
		'innerHtml' => '分类'
	),
	array(
		'class' => 'tuji-quanzhong',
		'innerHtml' => '权重'
	),
	array(
		'class' => 'tuji-zhuangtai',
		'innerHtml' => '状态'
	),
	array(
		'class' => 'tuji-ren',
		'innerHtml' => '添加人/时间'
	)
);
$headData = array(
	'class' => 'tuji-list',
	'innerHtml' => array(
		'left' => array(
			'innerHtml' => array(
				array(
					'class' => 'tuji-paixu',
					'innerHtml' => '<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order(\'tujilist\');"  title="排序模式切换/ALT+R"></a>',
				),
				array(
					'class' => 'tuji-fengmian',
					'innerHtml' => '缩略图'
				)
			)
		),
		'right' => array(
			'innerHtml' => $columnData,
		),
		'biaoti' => array(
			'innerHtml' => array(
				array(
					'class' => 'tuji-biaoti',
					'innerHtml' => '标题'
				)
			)
		)
	)
);
$bottomData = array(
	'audit' => '审核',
	'back' => '打回',
	'delete' => '删除'
); 
$emptyData = array(
	'describe' => '没有您要找的内容！',
	'id' => 'tujilist'
);
 ?>
<?php 
$status_key = 'status_display';
$attrs_for_edit = array(
	'id', 'click_num', 'click_count' ,'comm_num', 'img_src', 'img_count', 'downcount','pub_url', 'catalog'
);
 ?>
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
<style type="text/css">
<?php 
$styles = array(
    1 => array(
        1 => array(-15, 8, 0),
        2 => array(37, 3, 3),
        3 => array(48, 15, -8)
    ),
    2 => array(
        1 => array(-15, 12, -5),
        2 => array(35, 0, 0),
        3 => array(-25, 9, 8)
    ),
    3 => array(
        1 => array(45, 8, -5),
        2 => array(-20, 1, 5),
        3 => array(10, 16, 5)
    ),
    4 => array(
        1 => array(40, 3, 8),
        2 => array(-20, 10, 0),
        3 => array(30, 19, 0)
    )
);
$preStyle = array('-moz-', '-webkit-', '-ms-', '-o-', '');
foreach($styles as $k => $v){
    foreach($v as $kk => $vv){
        echo '.rotate-transform-'.$k.' .rotate-item-'.$kk.'{';
        foreach($preStyle as $vvv){
            echo $vvv.'transform:translate('.$vv[0].'px, '.$vv[1].'px) rotate('.$vv[2].'deg);';
        }
        echo '}';
    }
}
 ?>
</style>
<div class="" <?php if($_INPUT['infrm']){ ?>style="display:none"<?php } ?>>
	<div class="common-list-search" id="info_list_search">
	    <span class="serach-btn"></span>
		<form name="searchform" id="searchform" action="" method="get"
			onsubmit="return hg_del_keywords();" target="">
			<div class="select-search">
				<?php 
								$attr_source = array(
									'class' => 'transcoding down_list',
									'show' => 'tuhji_status_show',
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
	<span class="input_middle"><a><em></em><label id="display_<?php echo $attr_source['show'];?>" class="overflow" <?php if($attr_source['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $_configs['image_upload_status'][$_INPUT['status']];?></label></a></span>
	<ul id="<?php echo $attr_source['show'];?>" style="display:none;"  class="<?php echo $attr_source['show'];?> defer-hover-target">
		<?php if($attr_source['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="status_key" class="status_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $attr_source['method'];?>', '<?php echo $attr_source['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($_configs['image_upload_status']){ ?>
		<?php foreach ($_configs['image_upload_status'] as $k => $v){ ?>
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
				<li style="cursor:pointer;" <?php echo $attr_source['extra_li'];?>><a <?php if($attr_source['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $attr_source['state'];?>,'<?php echo $attr_source['show'];?>','status<?php  echo $attr_source['more']?'_'.$attr_source['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $attr_source['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
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
<?php if($attr_source['more']){ ?>
	<input type="hidden" name="status[<?php echo $attr_source['more'];?>]"  id="status_<?php echo $attr_source['more'];?>"  value="<?php echo $_INPUT['status'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="status" value="<?php echo $_INPUT['status'];?>"/>
<?php } else { ?>
<input type="hidden" name="status"  id="status"  value="<?php echo $_INPUT['status'];?>"/>
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
				<input type="hidden" name="a" value="show" /> <input type="hidden"
					name="mid" value="<?php echo $_INPUT['mid'];?>" /> <input type="hidden"
					name="infrm" value="<?php echo $_INPUT['infrm'];?>" /> <input type="hidden"
					name="_id" value="<?php echo $_INPUT['_id'];?>" /> <input type="hidden"
					name="_type" value="<?php echo $_INPUT['_type'];?>" />
				<input type="hidden" name="node_en" value="<?php echo $_INPUT['node_en'];?>" />
			</div>
			<div class="text-search">
				<div class="button_search">
					<input type="submit" value="" name="hg_search"
						style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
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
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<?php if($_configs['is_cloud']){ ?><a class="add-yuan-btn add-button news mr10"  gmid="<?php echo $_INPUT['mid'];?>" nodevar="tuji_node"><?php echo $_configs['is_cloud'];?></a><?php } ?>
		 <a class="blue mr10" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=tuji_form&infrm=1" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增图集</em></span>
	               <span class="right"></span>
	            </a>
	            <a class="gray mr10" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=configuare&infrm=1" target="mainwin">
	                <span class="left"></span>
	                <span class="middle"><em class="set">配置图集库</em></span>
	                <span class="right"></span>
	             </a>
	</div>
</div>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
			<!-- 新增图集模板开始 -->
		 	<div id="add_tuji"  class="single_upload" style="min-height:1300px;">
				<h2><span class="b" onclick="hg_closeTuJiTpl();"></span><span id="tuji_title">新增图集</span></h2>
				<div id="tuji_contents_form"  class="upload_form" style="height:1300px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 新增图集模板结束 -->
 		    <!-- 移动图集模板开始 -->
		 	<div id="move_tuji"  class="single_upload">
				<h2><span class="b" onclick="hg_showMoveTuJi();"></span><span id="move_title">移动图集</span></h2>
				<div id="tuji_sort_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 移动图集模板结束 -->
    			<div style="position: relative;">
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
                <form method="post" action="" name="listform" style="display:block;position:relative;" class="common-list-form">
                	<ul class="common-list tuji-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu open-close">
                                     <a title="排序模式切换/ALT+R" onclick="hg_switch_order('tujilist');"  class="common-list-paixu"></a>
                                </div>
                                <!-- <div class="common-list-item open-close wd150">缩略图</div> -->
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item tuji-fabu common-list-pub-overflow">发布至</div>
                                <div class="common-list-item  tuji-fenlei open-close wd80">分类</div>
                                <div class="common-list-item tuji-quanzhong open-close wd60">权重</div>
                                <div class="common-list-item tuji-zhuangtai open-close wd60">状态</div>
                                <div class="common-list-item tuji-ren open-close wd100">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">标题</div>
					        </div>
                        </li>
                     </ul>
                    <ul class="tuji-list common-list public-list hg_sortable_list" id="tujilist" data-table_name="tuji" data-order_name="order_id">
					<?php if($list){ ?>
                   	<?php foreach ($list as $k => $v){ ?>
                    	<li order_id="<?php echo $v['order_id'];?>" class="common-list-data clear" _id="<?php echo $v['id'];?>"  id="r_<?php echo $v['id'];?>" name="<?php echo $v['id'];?>" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="<?php echo $v[$primary_key];?>" title="<?php echo $v[$primary_key];?>" />
	     </a>
	  </div>
	  <!--  <div class="common-list-item open-close wd150">
	       <div class="rotate-box rotate-transform-<?php echo rand(1, 4); ?>">
		     <div class="rotate-item rotate-item-1">
			   <div class="rotate-inner"><?php if(($v['img_src'][0])){ ?><img class="rotate-img" <?php if($v['fetch_one_li_model']){ ?>src<?php } else { ?>_src<?php } ?>="<?php echo $v['img_src'][0];?>" /><?php } ?></div>
		    </div>
		   <div class="rotate-item rotate-item-2">
			  <div class="rotate-inner"><?php if(($v['img_src'][1])){ ?><img class="rotate-img" <?php if($v['fetch_one_li_model']){ ?>src<?php } else { ?>_src<?php } ?>="<?php echo $v['img_src'][1];?>" /><?php } ?></div>
		   </div>
		  <div class="rotate-item rotate-item-3"></div>
	</div>
	  </div>-->
   </div>
   <div class="common-list-right">
		<div class="common-list-item common-list-pub-overflow tuji-fabu open-close">
		  <div class="common-list-pub-overflow">
		   <?php if(($v['pub'])){ ?>
		    <?php foreach ($v['pub'] as $kk => $vv){ ?> 
		    	<?php $cu = $vv; ?>
		    	<?php if(($v['pub_url'][$kk])){ ?>
		    		<?php if((is_numeric($v['pub_url'][$kk]))){ ?>
		    		<a href="./redirect.php?id=<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $cu;?></span></a>
		    		<?php } else { ?>
						<a href="<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $cu;?></span></a>   			
		    		<?php } ?>			
		    	<?php } else { ?>
		    		<span class="common-list-pre-pub"><?php echo $cu;?></span>
		    	 <?php } ?>  	
			<?php } ?>
          <?php } ?>
          </div>
		</div>
		<div class="common-list-item wd80 overflow tuji-fenlei open-close">
		     <span><?php echo $v['sort_name'];?></span>
		</div>
<?php 
$v['weight'] = $v['weight'] ? $v['weight'] : 0;
 ?>
<div class="common-list-item wd60 news-quanzhong tuji-quanzhong open-close">
	<div class="common-quanzhong-box">
		<div class="common-quanzhong-box<?php echo $v['weight'];?>" _level="<?php echo $v['weight'];?>">
			<div class="common-quanzhong" style="background:<?php echo create_rgb_color($v['weight']); ?>">
				<span class="common-quanzhong-label"><?php echo $v['weight'];?></span>
			</div>
		</div>
	</div>
</div>
		<div class="common-list-item wd60 tuji-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="<?php echo $v['id'];?>" _state="<?php echo $v['status_display'];?>" id="statusLabelOf<?php echo $v['id'];?>" style="color:<?php echo $list_setting['status_color'][$v['status_display']];?>;"><?php echo $v['status'];?></span>
			</div>
		</div>
		<div class="common-list-item wd100 tuji-ren open-close">
		     <span class="tuji-name"><?php echo $v['user_name'];?></span>
		     <span class="tuji-time"><?php echo $v['create_time'];?></span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info(<?php echo $v['id'];?>);"></div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
	        <a  class="common-list-overflow max-wd"  href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=tuji_form&infrm=1&id=<?php echo $v['id'];?>" target="formwin">
	        <?php if($v['cover_img']){ ?><img  _src="<?php echo $v['cover_img'];?>"  class="biaoti-img"/> <?php } ?>
	        <span class="m2o-common-title"><?php echo $v['title'];?></span>
	        </a>
	        <?php if(($v['img_count'] > 0)){ ?><span class="tuji-total">(<?php echo $v['img_count'];?>)</span><?php } ?>
		</div>
   </div>
</li>
                    <?php } ?>
                    <?php } else { ?>
                        <li>
 <p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;"><?php echo $emptyData['describe'];?></p>
 <script>hg_error_html('p', 1);</script>
</li>
                    <?php } ?>
                    </ul>
                    <ul class="common-list public-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
								<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
								<a style="cursor:pointer;" onclick="return hg_bacthmove_show(this,'tuji_node');">移动</a>
                       			<a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
                       			<a style="cursor:pointer;" onclick="return hg_bacthblock_show(this);" name="block">区块</a>
                       		</div>
                       		<?php echo $pagelink;?>
                    	</li>
                    </ul>
                    <div class="edit_show">
						<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
						<div id="edit_show"></div>
					</div>
    			</form>
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
</div>
<div id="add_share"></div>
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=tuji_form&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=move_form&id=${id}&nodevar=tuji_node" data-node='tuji_node'>移动</a>
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
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			{{each img_src}}
				{{if _index == 0 || _index == 1 || _index == 2}}
					<span class="record-edit-img-wrap"><img _key="${_index}" src="${_value}"></span>
				{{/if}}
			{{/each}}
			<span class="record-edit-img-wrap">${img_count} P</span>
		</div>
		<div class="record-edit-line"></div>
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
		<div class="record-edit-line"></div>
		<div class="record-edit-info">
			{{if click_num}}<span>访问:${click_num}</span>{{/if}}
			{{if down_num}}<span>下载:${down_num}</span>{{/if}}
			{{if share_num}}<span>分享:${share_num}</span>{{/if}}
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
	</div>
	<div class="record-edit-play">
	</div>
</div>
<style>
#tuji_pics_show{width:346px;height:304px;}
#tuji_pics_show img{left:52px important!;}
#picinfo{display:none;}
</style>
<script type="tpl" id="vedio-tpl">
<div id="tuji_pics_show" class="tuji_pics_show">
  	  <img src="<?php echo $image_resource;?>black.jpg" id="tuji_content_img" style="position:absolute;left:0px;top:0px;width:346px;" />
  	  <div id="over_tip" style="width:200px;height:100px;position:absolute;left:25%;top:30%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;"></div>
  	  <div style="width:45px;height:20px;position:absolute;left:10px;top:280px;background:black;text-align:center;">
  	     	<div style="color:white;line-height:20px;">封面</div>
  	  </div>
  	  <input type="hidden" name="isover" id="isover" value="0" />
	  <div class="arrL" title="点击浏览上一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic(${id},0);"></div>
	  <div class="arrR" title="点击浏览下一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic(${id},0);"></div>
	  <div class="btnPrev" style="display:none;" id="left_btn"  onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic(${id},0);"><a href="#"></a></div>
	  <div class="btnNext" style="display:none;" id="right_btn" onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic(${id},0);"><a href="#"></a></div>
  </div>
<span class="record-edit-close"></span>
</script>
<!-- 移动框 -->
<!-- 移动框 -->
<style>
.result-tip {overflow: hidden; z-index: -1; position: absolute;top: 170px;left: 50%;font-size: 25px;background: #ffffff;text-align: center;margin-left: -200px;
border-radius: 4px;min-width: 240px;width:auto;height: 60px;border: 4px solid #6ba4eb;-webkit-box-shadow: 0 0 4px #cccccc #000000; -moz-box-shadow: 0 0 4px #cccccc #000000;
box-shadow: 0 0 4px #cccccc #000000;line-height: 60px;-webkit-transition: all 3s linear;-moz-transition: all 3s linear;-o-transition: all 3s linear;
-ms-transition: all 3s linear;transition: all 3s linear;opacity: 0;}
</style>
<div id="move_box_publish" class="common-list-ajax-pub">
	<div class="common-list-pub-title">
		<p>正在移动</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<div id="move_body">
		<form  action="run.php?mid=<?php echo $_INPUT['mid'];?>&a=move" method="post" ><style>
.common-hg-publish .publish-result ul, .common-hg-publish .publish-result-empty{height:190px;}
</style><div class="publish-box common-hg-publish" id="publish-box-<?php echo $hg_name;?>">
	<div class="publish-result" >
		<p class="publish-result-title" _title="移动">移动至：</p>
		<ul>		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
		</div>
	</div>
	<input type="hidden" class="publish-hidden" name="node_id" value="" />
	<input type="hidden" class="publish-name-hidden" name="content_id" value="" />
</div>
			<div><span class="publish-box-save">保存</span></div>
		</form>
		<input type="hidden" name="id" value="">
	</div>
	<span class="common-list-pub-close"></span>
</div>
<script type="text/javascript">
$(function(){
    $('.tuji-fengmian').on('click', function(){
        return;
        var li = $(this).closest('.common-list-data');
        if(li.hasClass('open')){
            li.removeClass('open');
        }else{
            $('.common-list-data.open').removeClass('open');
            li.addClass('open');
        }
        hg_open_tuji(li.attr('_id'));
    });
    $('.rotate-img').each(function(){
        $(this).preLoadImg({
            height : 60,
            height : 45,
            src : $(this).attr('_src'),
            loading : true,
            callback : function(){
                $(this).removeAttr('_src');
            }
        });
    });
    /*缓存页面的打开的标题个数*/
    $.commonListCache('tuji-list');
});
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