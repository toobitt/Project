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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/news_add.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/bigcolorpicker.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/catalog.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/2013/form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/column_node.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/hg_sort_box.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/common/common_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/news_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/2013/iframe_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/common/common.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/common/common_publish.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>catalog.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>underscore.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>Backbone.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>bigcolorpicker.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>ad.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_news.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_water.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_sort_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/auto_textarea.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>news/news_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>ajax_upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_cache.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/publish.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/special.js"></script>
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
	if ( is_array($formdata ) ) {
		foreach ( $formdata as $k => $v ) {
			$$k = $v;
		}
	}		
	if($id) {
		$optext = $state == -1 ? "添加": "更新";
		$ac="update";
	}
	else {
		$optext="添加";
		$ac="create";
	}
	if(empty($water_angle)) {
		$water_angle = 1;
	}
	if(empty($water_font)) {
		$water_font = 1;
	}
	$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
 ?>
<style>
	#weight_box{z-index:10001;top:60px;left:974px;}
</style><div id="weight_box">
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
</script>
<script>
$.officeconvert = <?php echo isset($_settings['officeconvert']) ? 1 : 0; ?>;
$.maxpicsize = <?php echo $_configs['maxpicsize'] ?  intval($_configs['maxpicsize']) : 640; ?>;
</script>
<script type="text/javascript">
	(function(w) {
		var base = './run.php?mid=' + gMid + '&a=';
		w.gUrl = {
			referInfo: base + 'get_material_info',
			waterList: base + 'water_config_list',
			createWater: base + 'create_water_config',
			upload: base + 'upload',
			transform: base + 'revolveImg',
			referNode: base + 'get_material_node',
			referSketch: base + 'get_sketch_map',
			imgLocal: base + 'img_local'
		};
	})(window);
	var imgList = [], attachList = [], sortList = [];
	<?php foreach ($material as $k => $v){ ?>
		<?php if($v['mark'] == 'img' ){ ?> 
			imgList.push({ "src": "<?php echo $v['url'];?>", "path": "<?php echo $v['path'];?>", "dir": "<?php echo $v['dir'];?>", "material_id": "<?php echo $v['material_id'];?>", "filename": "<?php echo $v['filename'];?>" });
		<?php } else { ?>
			var code = '<?php echo $v["code"];?>'; 
			attachList.push({ "src": "<?php echo $v['url'];?>", "path": "<?php echo $v['path'];?>", "dir": "<?php echo $v['dir'];?>", "id": "<?php echo $v['material_id'];?>", "name": "<?php echo $v['name'];?>", "filesize": "<?php echo $v['filesize'];?>", "type": "<?php echo $v['type'];?>", "code": code});
		<?php } ?>
	<?php } ?>
	<?php foreach ($sort as $k => $v){ ?>
		sortList.push( { sort_id: "<?php echo $k;?>", sort_name: "<?php echo $v;?>" } );
	<?php } ?>
	var currentSort = { sort_id: "<?php echo $sort_id;?>", sort_name: "<?php echo $currentSort[$sort_id];?>" };
	attach_support = '<?php echo $attach_support;?>';
	img_support = '<?php echo $img_support;?>';
</script>
<style>
.edui-default .edui-editor{border:none;}
#edui1_bottombar{display:none;}
.common-form-main .editor-statistics{position:relative;}
.edui-default .edui-editor-toolbarboxouter{border-bottom: 1px solid #ececec;background-color: #f9f9f9;background-image:none;}
.editor-slide-fixed{position:fixed;top:0!important;}
.pic-edit-btn{z-index:10000;}
.draft-overflow {overflow-y: auto;overflow-x: hidden;float: left;width:265px;}
.draft-content {height: 477px;}
.draft-item {border-bottom: 1px solid #ebe7e7;overflow: hidden;margin: 0 10px;cursor: pointer;position: relative;}
.draft-item:hover{background:#f0eff5;}
.draft-title {display: table-cell;height: 30px;padding-left: 5px;vertical-align:middle;white-space:nowrap;max-width:200px;text-overflow:ellipsis;overflow:hidden;margin-left:10px;padding-top:8px;}
.draft-time {float: right;padding-bottom: 5px;margin-right: 30px;}
.draft-option {width: 24px;height: 24px;position: absolute;right: 0;bottom: 0;display: none}
.draft-item:hover .draft-option{display: block;}
.draft-option-del{cursor: pointer;height: 100%; display:block;background:url(<?php echo $RESOURCE_URL;?>news/del.png) #ccc no-repeat center;}
.draft-option-del:hover{background-image: url(<?php echo $RESOURCE_URL;?>news/del_hover.png);}
</style>
<form method="post" enctype="multipart/form-data" id="content_form" onsubmit="return hg_news_submit();" class="ueditor-outer-wrap">
<div class="common-form-head">
	<div class="common-form-title">
		<h2><?php echo $optext;?>文稿</h2>
        <?php 
        //print_r($draft_list);
         ?>
		<div class="form-dioption-title form-dioption-item">
			<!-- <textarea name="title" _value="<?php if($title){ ?><?php echo $title;?><?php } else { ?>添加文稿标题<?php } ?>" id="title" class="title <?php if($title){ ?>input-hide<?php } ?>" placeholder="添加文稿标题"><?php echo $title;?></textarea> -->
			<input name="title" _value="<?php if($title){ ?><?php echo $title;?><?php } else { ?>添加文稿标题<?php } ?>" id="title" class="title <?php if($title){ ?>input-hide<?php } ?>  need-word-count" placeholder="添加文稿标题" value="<?php echo $title;?>" title="<?php echo $title;?>"/>
			<div class="color-selector clearfix">
				<span class="form-title-color"></span>
				<span class="form-title-weight"></span>
				<span class="form-title-italic"></span>
			</div>
			<div id="word-count">
			</div>
			<input name="tcolor" type="hidden" value="<?php echo $tcolor;?>" id="tcolor" />
			<input name="isbold" type="hidden" value="<?php if($isbold){ ?>1<?php } else { ?>0<?php } ?>" id="isbold" />
			<input name="isitalic" type="hidden" value="<?php if($isitalic){ ?>1<?php } else { ?>0<?php } ?>" id="isitalic" />
			<input name="weight" value="<?php echo $weight;?>" id="weight" type="hidden" />
		</div>
		<input type="hidden" name="submit_type" id="submit_type"/>
		<div class="form-dioption-submit" style="width: 270px;">
			<!--  <input type="submit" id="submit_ok" name="sub" value="确定并继续添加" class="button_6_14" _submit_type="2"/>
			<input type="submit" id="submit" value="确定" class="button_2_14" style="margin-left:5px;" _submit_type="1"/>-->
            <?php if(!$id){ ?>
            <!--<a class="common-form-save" href="#" id="submit_draft" style="right:145px;line-height: 34px;">保存草稿</a>-->
            <?php } ?>
			<input type="submit" id="submit_ok" name="sub" value="保存" class="common-form-save" _submit_type="2" />
			<span class="option-iframe-back">关闭</span>
		</div>
		<div id="weightPicker">
<?php 
$weight = $weight ? $weight : 0;
 ?>
<div class="common-list-item wd60 news-quanzhong agd open-close">
	<div class="common-quanzhong-box">
		<div class="common-quanzhong-box<?php echo $weight;?>" _level="<?php echo $weight;?>">
			<div class="common-quanzhong" style="background:<?php echo create_rgb_color($weight); ?>">
				<span class="common-quanzhong-label"><?php echo $weight;?></span>
			</div>
		</div>
	</div>
</div>
		</div>  
    </div>
</div>
<div class="common-form-main">
	<?php
if ((!isset($formdata) || !is_array($formdata)) && (defined('FORMDATA') && FORMDATA)) {} else { 
$item = $formdata;
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
}
$column = new column();
$publish = array();
$publish['sites'] = $column->getallsites();
list($default_site, $default_name) = each($publish['sites']);
reset($publish['sites']);
$publish['items'] = $column->getAuthoredColumns($default_site);
$publish['selected_ids'] = $item['column_id'] ? $item['column_id'] : '';
$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
$publish['default_site'] = each($publish['sites']);
$publish['pub_time'] = $item['pub_time'];$hg_print_selected = array();
foreach ($publish['selected_items'] as $index => $item) {
	$hg_print_selected[$index] = array();
	$current = &$hg_print_selected[$index];
	$current['showName'] = '';
	foreach ($item as $sub_item) {
		if($sub_item['is_auth'])
		{
			$current['is_auth'] = 1;
		}
		$current['id'] = $sub_item['id'];
		$current['name'] = $sub_item['name'];
		$current['showName'] .= $sub_item['name'] . ' > ';
	}
	if(!$current['is_auth'])
	{
		$current['is_auth'] = 0;
	}
	$current['showName'] = substr($current['showName'], 0, -3);
	$selected_names[] = $current['name'];
}
$publish['selected_items'] = $hg_print_selected;
$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';}
?><div class="common-form-pop" id="form_publish" _type="publish" style="top:-450px;position:fixed;z-index:99999;-webkit-transition:top .5s;left:50%;margin-left:-312px;"><?php 
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
<div class="publish-box common-hg-publish" id="publish-box-1">
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
			 	<label><input type="radio" name="publish-sites-1" <?php if($key == $publish['default_site']['key']){ ?>checked="checked"<?php } ?> />
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
	<div class="publish-box-close"></div>	
</div>
	<div class="common-form-pop" id="form_special" data-id="<?php echo $formdata['id'];?>"  _type="special" style="top:-450px;position:fixed;z-index:99999;-webkit-transition:top .5s;left:50%;margin-left:-312px;"><div class="publish-box common-hg-special-publish">
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
</div>	<div class="publish-box-close"></div>	
</div>
<div class="m2o-flex">
	<!-- form-left start -->
	<div class="form-left" style="z-index:1;">
		<div class="left-fix-box">
        	<div class="form-dioption" style="overflow:visible;">
	            <div class="form-dioption-inner">
	                  <div class="form-cioption-indexpic form-cioption-item">
	                    <div class="indexpic-box">
	                        <div class="indexpic" style="font-size:0;">
	                            <?php 
	                            $default_indexpic_url = RESOURCE_URL.'news/suoyin-default.png';
	                            if($indexpic_url){
	                                $indexpicsrc = $indexpic_url['host'].$indexpic_url['dir'].$indexpic_url['filepath'].$indexpic_url['filename'];
	                            }else{
	                                $indexpicsrc = '';
	                            }
	                             ?>
	                            <script>
	                            $(function(){
	                                if($.pixelRatio > 1){
	                                    var index = $('#indexpic_url');
	                                    if(index.attr('_state') < 1){
	                                        index.attr('src', index.attr('_default').replace('.png', '-2x.png')).css('width', '49px');
	                                    }
	                                }
	                            });
	                            </script>
	                            <img style="max-width:160px;max-height:160px;" _src="<?php if(!$indexpicsrc){ ?><?php echo $default_indexpic_url;?><?php } else { ?><?php echo $indexpicsrc;?><?php } ?>" title="索引图" id="indexpic_url" _state="<?php if($indexpicsrc){ ?>1<?php } else { ?>0<?php } ?>" _default="<?php echo $default_indexpic_url;?>"/>
	                        </div>
	                        <span class="indexpic-suoyin <?php if($indexpicsrc){ ?>indexpic-suoyin-current<?php } ?>"></span>
	                    </div>
	                    <input name="indexpic" type="hidden"  id="indexpic" value="<?php echo $indexpic;?>" />
	                </div>
	            <div class="form-dioption-sort form-dioption-item"  id="sort-box">
	                <label style="color:#9f9f9f;<?php if(!$sort_id){ ?>display:none;<?php } ?>">分类： </label><p style="display:inline-block;" class="sort-label" _multi="news_node"> <?php echo $currentSort["$sort_id"];?><img class="common-head-drop" src="<?php echo $RESOURCE_URL;?>tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
					<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
	                <input name="sort_id" type="hidden" value="<?php echo $sort_id;?>" id="sort_id" />
	            </div>
				<div class="form-dioption-fabu form-dioption-item">
					<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至：">发布至</a>
	            </div>
	            <div class="form-dioption-fabu form-dioption-item">
					<a class="common-publish-button overflow" href="javascript:;" _default="发布至专题" _type="special" _prev="发布至专题：">发布至专题</a>
	            </div>
	            <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
	                <span class="keywords-del"></span>
	                <span class="form-item" _value="添加关键字" id="keywords-box" data-title="提取文章内容与标题为关键字">
	                    <span class="keywords-start">添加关键字</span>
	                    <span class="keywords-add">+</span>
	                </span>
	                <input name="keywords" value="<?php echo $keywords;?>" id="keywords" style="display:none;"/>
	            </div>
	        	<div id="lumin">
	        	</div>
			</div>
			<div class="form-dioption-inner form-dioption-inner-second">
                <!-- 
                <div class="form-dioption-quanzhong form-dioption-item">
                    <div>权重设置</div>
                    <div id="quanzhong" class="form-quanzhong">
                        <div class="quanzhong-option">
                            <div class="down"></div>
                            <div class="up"></div>
                        </div>
                        <div class="quanzhong-box<?php echo $weight;?>">
                            <div class="quanzhong"><?php echo $_configs['weight_search'][$weight];?></div>
                            <img src="<?php echo $RESOURCE_URL;?>news/quanzhong-masking.png" usemap="#quanzhong-map" class="quanzhong-masking" />
                            <map name="quanzhong-map" id="quanzhong-map">
                                <area shape="poly" coords="32,0,32,9,42,12,47,4" title="<?php echo $levelLabel[1];?>" />
                                <area shape="poly" coords="49,6,43,12,50,19,58,14,49,6,49,6" title="<?php echo $levelLabel[2];?>" />
                                <area shape="poly" coords="59,16,51,21,53,30,62,30" title="<?php echo $levelLabel[3];?>" />
                                <area shape="poly" coords="54,31,62,31,60,45,59,45,52,41,54,32" title="<?php echo $levelLabel[4];?>" />
                                <area shape="poly" coords="43,51,51,43,59,47,49,58" title="<?php echo $levelLabel[5];?>" />
                                <area shape="poly" coords="33,54,32,63,47,59,42,52" title="<?php echo $levelLabel[6];?>" />
                                <area shape="poly" coords="20,51,16,59,31,63,31,54" title="<?php echo $levelLabel[7];?>" />
                                <area shape="poly" coords="4,47,12,43,19,50,15,57" title="<?php echo $levelLabel[8];?>" />
                                <area shape="poly" coords="0,32,3,46,11,42,9,33" title="<?php echo $levelLabel[9];?>" />
                                <area shape="poly" coords="9,32,0,31,4,16,12,21" title="<?php echo $levelLabel[10];?>" />
                                <area shape="poly" coords="12,19,19,12,14,5,5,15" title="<?php echo $levelLabel[11];?>" />
                                <area shape="poly" coords="21,12,30,9,30,0,16,4,20,11" title="<?php echo $levelLabel[12];?>" />
                            </map>
                        </div>
                    </div>
                    <input name="weight" value="<?php echo $weight;?>" id="weight" type="hidden" />
                </div> -->
            </div>
            <div class="editor-detail"></div>
		</div>
		<div id="editor-count"></div>
    </div>
	</div>
	<!-- form-left end -->
	<!-- 编辑器 start -->
	<textarea name="content" name="content" class="hide-textarea" id="form-edit-box"><?php echo htmlspecialchars_decode($content); ?></textarea>
            <script>
           /*$('#form-edit-box').show();
            setTimeout(function(){
                jQuery('#iframe-mask-loading').click(function(){
                    $(this).hide();
                }).hide();
            }, 1000);*/
            </script>
	<!-- 编辑器 end -->
	<!-- form-middle start -->
    <div class="form-middle m2o-flex-one" style="position:relative;left:0;">
		<!-- 右侧属性 strat -->
		<div class="right-fix-box">
			<div class="form-cioption form-right" style="min-height:500px;">
				<div class="form-dioption-brief form-dioption-item">
	                <div style="display:none;">
	                	<textarea name="brief" id="brief" class="brief <?php if($brief){ ?>input-hide<?php } ?>" placeholder="添加文稿摘要" _value="<?php if($brief){ ?><?php echo $brief;?><?php } else { ?>添加文稿摘要<?php } ?>"><?php echo $brief;?></textarea>
	                </div>
	                <div contenteditable="true" id="brief-clone" data-left="-35px" data-top="-19px" class="need-word-count" target="brief" placeholder="添加文稿摘要"><?php echo $brief;?></div>
                </div>
                <div class="form-dioption-subtitle form-dioption-item">
                    <input name="subtitle" id="subtitle" type="text" value="<?php echo $subtitle;?>" style="display:none;"/>
                    <div contenteditable="true" id="subtitle-clone" class="my-placeholder" target="subtitle" placeholder="设置副题" preval="副题："></div>
                </div>
                <div class="form-dioption-author form-dioption-item">
                    <input name="author" id="author" type="text" value="<?php echo $author;?>" style="display:none;"/>
                    <div contenteditable="true" id="author-clone" class="my-placeholder" target="author" placeholder="作者" preval="作者："></div>
                </div>
                <div class="form-dioption-source form-dioption-item">
                    <input name="source" id="source" type="text" value="<?php echo $source;?>" style="display:none;"/>
                    <div contenteditable="true" id="source-clone" class="my-placeholder" target="source" placeholder="来源" preval="来源："></div>
                </div>
                <div class="form-dioption-source form-dioption-item">
                    <input name="ori_url" id="ori_url" type="text" value="<?php echo $ori_url;?>" style="display:none;"/>
                    <div contenteditable="true" id="ori_url-clone" class="my-placeholder" target="ori_url" placeholder="原始链接" preval="原始链接："></div>
                </div>
                <div class="form-dioption-item ext-laiyuan">
                	<label><input name="other_settings[closecomm]" type="checkbox" value="1" <?php if($other_settings['closecomm']){ ?>checked="checked"<?php } ?> style="width:auto;vertical-align:middle;margin-right:6px;"/>关闭本文评论</label>
                </div>
                <?php if(!$id){ ?>
                   <!-- <div class="form-dioption-item">
                        <span class="draft-button">使用草稿</span>
                    </div>  -->
                <?php } ?>
                <ul class="form-cioption-ext" data-tip="暂时隐藏" style="display:none;">
                    <li><label><input name="istop" type="checkbox" value="1" <?php if($istop){ ?>checked="checked"<?php } ?> id="istop" />本文置顶</label></li>
                    <li><label><input name="istpl" type="checkbox" value="1" <?php if($istpl){ ?>checked="checked"<?php } ?> id="istpl" />独立模板</label></li>
                    <li><label><input name="isssss" type="checkbox" value="1" <?php if($isssss){ ?>checked="checked"<?php } ?> id="istop" />指定文件名</label></li>
                </ul>
			</div>
		</div>
		<!-- 右侧属性end -->
		<div id="iframe-mask-loading" style="background:transparent;border:none;display:none;">
			<img src="<?php echo $RESOURCE_URL;?>loading2.gif"/>
		</div>
            <input type="hidden" name="a" value="<?php echo $ac;?>" />
            <input type="hidden" name="auto_draft" value="<?php echo $_configs['autoSaveDraft'];?>" />
            <input type="hidden" id="id"  name="id" value="<?php echo $formdata['id'];?>" />
            <input type="hidden" name="referto" value="<?php echo $_INPUT['referto'];?>" id="referto" />
            <input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
            <input type="hidden" name="mmid" value="<?php echo $_INPUT['mid'];?>" />
            <input type="hidden" name="app_uniqueid" value="<?php echo $_INPUT['app'];?>" />
            <input type="hidden" name="module_uniqueid" value="<?php echo $_INPUT['mod'];?>" />
            <input type="hidden" name="history_id" id="history_id" value="0" />
			<input type="hidden" name="material_history" id="material_history" value="" />
			<input type="hidden" name="water_config_id" value="<?php echo $water_id;?>" id="water_config_id" />
			<input type="hidden" name="water_config_name" value="<?php echo $water_name;?>"  id="water_config_name"/>
			<?php foreach ($material as $k => $v){ ?>
				<input name="material_id[]"  type="hidden" _id="<?php echo $v['material_id'];?>" value="<?php echo $v['material_id'];?>" />
			<?php } ?>
			<div class="history-info" style="display:none;">
			<?php if(!empty($history_info)){ ?>
				<?php foreach ($history_info as $k => $v){ ?>
					<a href="javascript:void(0);" onclick="hg_article_history(<?php echo $v['id'];?>);" style="margin:2px 0px;margin-left:8px ;float: left;"><?php echo $v['create_time'];?></a>
					<?php } ?>
				<?php } ?>
			</div>
            <div class="material-box" style="display:none;">
            </div>
            <input type="hidden" name="pagetitles" id="pagetitles" value="<?php echo $v['pagetitles'];?>"/>
            <input type="hidden" name="pizhus" id="pizhus" value="<?php echo $v['pizhus'];?>"/>
            <input type="hidden" name="is_first_hand_save" id="is_first_hand_save" value="<?php echo $formdata['is_first_hand_save'];?>" />
    	</div>
    <!-- form-middle end -->
</div>
</div>
<div class="editor-slide-box draft-outer" style="top: 90px; left: 972.5px; height: 520px;display:none;">
    <div class="editor-slide-wrap">
        <div class="editor-slide-inner">
            <div class="editor-slide-head">
                <div class="editor-slide-title">草稿</div>
                <div class="editor-slide-option"><span class="editor-slide-no draft-slide-no"></span></div>
            </div>
            <div class="editor-slide-body">
                   <div class="draft-overflow">
                       <ul class="draft-content">
                           <li class="draft-item">
                               <a class="draft-title" href="./run.php?mid=58&a=detail&draft_id=-1&infrm=1" title="空白草稿" style="height:50px;">空白草稿</a>
                           </li>
                           <?php 
                           $draft_list = $draft_list[0];
                            ?>
                           <?php if(is_array($draft_list) && count($draft_list) > 0 ){ ?>
                           <?php foreach ($draft_list as $k => $v){ ?>
                           <li class="draft-item draft-item-<?php echo $v['id'];?>">
                               <?php 
                                    $v['title'] = $v['title'] ? $v['title'] : '无标题';
                                    if ($v['isauto']) {
                                        $v['title'] = '(自动草稿)' . $v['title'];
                                    }
                                ?>
                               <a href="./run.php?mid=58&a=detail&draft_id=<?php echo $v['id'];?>&infrm=1"">
                               <span class="draft-title" title="<?php echo $v['title'];?>"><?php echo $v['title'];?></span>
                               <span class="draft-time"><?php echo $v['create_time'];?></span>
                               </a>
                               <div class="draft-option">
                                <span class="draft-option-del" _draft_id="<?php echo $v['id'];?>"></span>
                               </div>
                           </li>
                           <?php } ?>
                           <?php } else { ?>
                           <?php } ?>
                       </ul>
                   </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>
$(function(){
    var headHeight = $('.common-form-head').outerHeight(true);
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop(),
       		doClass = ( scrollTop > headHeight + 10 ) ? 'addClass' : 'removeClass';
        $('.left-fix-box')[doClass]('news-fix-left');
        $('.right-fix-box')[doClass]('news-fix-right');
        $('.edit-slide')[doClass]('edit-slide-fixed');
        $('.editor-slide-box')[doClass]('editor-slide-fixed');
        $('.editor-slide-box').css('left',$('.form-right').offset().left + 'px');
        if( doClass == 'removeClass' ){
        	$('.editor-slide-box').css('top','90px');
        }
    });
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