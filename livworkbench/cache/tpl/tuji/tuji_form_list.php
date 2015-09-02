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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/upload_vod.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/column_node.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/hg_sort_box.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/catalog.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/tuji_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/2013/iframe_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>tuji/common/common_publish.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_sort_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>catalog.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/preloadimg.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/auto_textarea.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>tuji/tuji_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_cache.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/publish.js"></script>
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
<?php } ?><style>
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
</script><?php 
if($formdata['edit'])
{
	$is_update = 1;
}
else
{
	$is_update = 0;
}$tuji_info = $formdata['tuji'];
foreach ($tuji_info as $k => $v) {
	$$k = $v;
}
$pics_info = $formdata['pics'];
$pics_info = urlencode(json_encode($pics_info));
$attr_water = array(
	'class' => 'transcoding down_list',
	'show' => 'watert_show',
	'width' => 150,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);$default_water = 0;
$water_arr[$default_water] = '无';
foreach($water_config[0] AS $k => $v)
{
	$water_arr[$v['id']] = $v['config_name'];
}if(!$tuji_info['water_id'])
{
	$tuji_info['water_id'] = $default_water;
}//print_r($water_config_list);
 ?><form action="./run.php?mid=<?php echo $_INPUT['mid'];?>" method="post" enctype="multipart/form-data" id="content_form" title="">
 <div class="common-form-head">
     <div class="common-form-title">
          <h2><?php echo !$id ? '添加' : '更新'; ?>图集</h2>
          <div class="form-dioption-title form-dioption-item">
                <!-- <textarea name="title" type="text" _value="<?php if($title){ ?><?php echo $title;?><?php } else { ?>添加图集标题<?php } ?>" id="title" class="title <?php if($title){ ?>input-hide<?php } ?>" placeholder="添加图集标题"><?php echo $title;?></textarea> -->
                <input name="title" type="text" _value="<?php if($title){ ?><?php echo $title;?><?php } else { ?>添加图集标题<?php } ?>" id="title" class="title <?php if($title){ ?>input-hide<?php } ?> need-word-count" placeholder="添加图集标题" value="<?php echo $title;?>"/>
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                       <input name="tcolor" type="hidden" value="<?php echo $tcolor;?>" id="tcolor" />
                       <input name="isbold" type="hidden" value="<?php if($isbold){ ?>1<?php } else { ?>0<?php } ?>" id="isbold" />
                       <input name="isitalic" type="hidden" value="<?php if($isitalic){ ?>1<?php } else { ?>0<?php } ?>" id="isitalic" />
                      <input name="weight" value="<?php echo $weight;?>" id="weight" type="hidden" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <!--  <input type="submit" id="submit_ok" name="sub" value="确定并继续添加" class="button_6_14" _submit_type="2"/>
		      <input type="submit" id="submit" value="确定" class="button_2_14" style="margin-left:5px;" _submit_type="1"/>-->
		      <input type="submit" id="submit_ok" name="sub" value="保存图集" class="common-form-save" _submit_type="2" />
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
<?php 
    $title = $tuji_info['title'];
    $comment = $tuji_info['comment'];
 ?>	   
<div class="common-form-main tuji-area">
   <?php $formdata = &$tuji_info; ?>
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
    <div class="form-left">
        <div class="form-dioption">
            <div class="form-dioption-inner">
                <div class="form-dioption-brief form-dioption-item">
                    <div style="overflow:hidden;display:none;">
                        <textarea name="comment" id="brief" class="comment <?php if($comment){ ?>input-hide<?php } ?>" style="height:22px;line-height:22px;" placeholder="添加图集摘要"><?php echo $comment;?></textarea>
                    </div>
                    <div contenteditable="true" class="need-word-count" data-left="-50px" data-top="-19px"  id="brief-clone" target="brief" placeholder="添加图集摘要"><?php echo $comment;?></div>
                </div>                <div class="form-dioption-source form-dioption-item">
                    <input name="source" id="source" type="text" value="<?php echo $source;?>" placeholder="来源" style="width:90%;"/>
                </div>
                  <div class="form-dioption-author form-dioption-item">
                    <input name="author" id="author" type="text" value="<?php echo $author;?>" placeholder="作者" style="width:90%;"/>
                </div>                <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                    <label style="color:#9f9f9f;<?php if(!$tuji_info['tuji_sort_id']){ ?>display:none;<?php } ?>">分类： </label><p class="sort-label" _multi="tuji_node"><?php echo $tuji_info['tuji_sort_name'] ? $tuji_info['tuji_sort_name'] : '请选择分类'; ?><img class="common-head-drop" src="<?php echo $RESOURCE_URL;?>tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
                    <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                    <input name="tuji_sort_id" type="hidden" value="<?php echo $tuji_info['tuji_sort_id'];?>" id="sort_id" />
                </div>                <div class="form-dioption-keyword form-dioption-item clearfix">
                    <span class="keywords-del"></span>
                    <span class="form-item" _value="添加关键字" id="keywords-box">
                        <span class="keywords-start">添加关键字</span>
                        <span class="keywords-add">+</span>
                    </span>
                    <input name="keywords" value="<?php echo $keywords;?>" id="keywords" style="display:none;"/>
                </div>
				 <div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至：">发布至</a>
                </div>                <!-- 
                <div class="form-dioption-fabu form-dioption-item">
                    <a>水印:</a>
                    <span class="add-water-pic">点击设置水印</span>
                </div>
                 -->
  				<div id="lumin"></div>
            </div>
        </div>
    </div>
    <!-- 水印设置 -->
    <div class="set-watermark-box">
		<?php if(is_array($water_config_list)){ ?>
    	<ul class="watermark-option clear">
		<?php foreach ($water_config_list[0] as $k => $v){ ?>
			<li data-value="<?php echo $v['config_name'];?>" _id="<?php echo $v['id'];?>" class="<?php if($v['id'] == $tuji_info['water_id'] ){ ?>selected<?php } ?>">
				<div class="watermark-content">
					<?php if($v['img_url']){ ?>
					<a><img src="<?php echo $v['img_url'];?>"></a>
					<?php } ?>
					<p title="<?php echo $v['config_name'];?>" class="name <?php if($v['img_url']){ ?>hasimg<?php } ?>"><?php echo $v['config_name'];?></p>
				</div>
			</li>
		<?php } ?>
		</ul>
		<?php } else { ?>
		暂无水印
		<?php } ?>
		<div class="watermark-btns">
			<div class="handle-btn submit-watermark">确 定</div>
			<div class="handle-btn cancel-watermark">取 消</div>
			<div class="handle-btn del-watermark">去除水印</div>
		</div>
		<a class="arrow"></a>
		<input type="hidden" name="water_id" value="<?php echo $tuji_info['water_id'];?>"/>
		<input type="hidden" name="default_water_id" value="<?php echo $tuji_info['water_id'];?>"/>
	</div>
    <div class="form-middle">
        <div class="form-option" style="position:relative;">
            <div class="clear">                <span class="form-select-all">
                    <span class="form-button-box">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle"><label><input type="checkbox" value="1"/>选择全部<em>(0)</em></label></span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-change-des">
                    <span class="form-button-box form-button-cannot">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">更改描述</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-option-del">
                    <span class="form-button-box">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">删除</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-batch-watermark">
                    <span class="form-button-box form-button-cannot">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">水印</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-option-cancel">取消选择</span>
            </div>
            <div style="position:absolute;right:0;top:0;">
						<span class="form-button-box">
						    <span class="form-button-left"></span>
						    <span class="form-button-middle form-zip">打包上传(只支持zip格式)</span>
						    <span class="form-button-right"></span>
						</span>
                        <input type="file" id="form-zip" style="display:none;"/>
            </div>
            <div style="position:absolute;right:170px;top:0;">
						<span class="form-button-box">
						    <span class="form-button-left"></span>
						    <span class="form-button-middle link-btn">链接上传</span>
						    <span class="form-button-right"></span>
						</span>
            </div>
        </div>
        <div class="form-upload">
            <div class="form-des-box">
                <div class="form-des-number">更改<span></span>图片的描述</div>
                <textarea style="width:97%;margin:6px 10px 10px 12px;height:44px;line-height:22px;"></textarea>
            </div>
            <div class="form-link-box">
                <div class="form-des-number">输入图片的链接地址 每行作为一张图片</div>
                <textarea style="width:97%;height:100px;margin:6px 10px 10px 12px;" name="pic_links"></textarea>
            </div>
            <div class="form-imgs clear" style="position:relative;">
                <div class="form-add" picid="0" style="display:none;">
                	添加图片
                	<a class="watermark-btn the-add">添加水印</a>
                	<input type="hidden" name="water_id" value="<?php echo $tuji_info['water_id'];?>">
                </div>
            </div>
        </div>
    </div>
    <input type="file" multiple class="form-file" />
    <input type="hidden" value="<?php if($is_update){ ?>update_tuji<?php } else { ?>create_tuji<?php } ?>" name="a" />
    <input type="hidden" value="<?php echo $_INPUT['mid'];?>" name="module_id" />
    <input type="hidden" value="<?php echo $tuji_info['id'];?>" name="id" />
    <input type="hidden" name="referto" value="<?php echo $_INPUT['referto'];?>" />
    <input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />    <input type="hidden" name="imgs" id="imgs" value="<?php if($pics_info){ ?><?php echo $pics_info;?><?php } ?>"/> </form>
</div>
<textarea id="imgs-data" style="display:none"><?php if($pics_info){ ?><?php echo $pics_info;?><?php } ?></textarea>
<textarea id="img-tpl" style="display:none;">
    <div class="form-img-each {isfm} form-img-each-transition" index="{index}" sort="{sort}" material_id="{materialid}" picid="{picid}" {ext}>
        <div class="form-img-fm"></div>
        <div class="form-img-option">
            <div class="form-img-option-mask"></div>
            <div class="form-img-option-box">
                <span class="form-img-obig"></span>
                <span class="form-img-oleft"></span>
                <span class="form-img-oright"></span>
                <span class="form-img-odel"></span>
        	    <a class="watermark-btn" _waterid={waterid}>添加水印</a>
            </div>
        </div>
        <div class="form-img-box"><img class="suo" _src="{src}"/></div>
        <div class="form-img-title" title="{title}"><div class="form-img-title-content">{title}</div></div>
        <div class="form-img-center"></div>
        <div class="form-img-reddel">x</div>
    </div>
</textarea><div></div><?php if((!$_INPUT['infrm'] && !$__top)){ ?>
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