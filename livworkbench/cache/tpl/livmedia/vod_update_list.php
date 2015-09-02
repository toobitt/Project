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
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/upload_vod.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/vod_update_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/column_node.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/hg_sort_box.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/catalog.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/video/video.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/2013/iframe_form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/2013/list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common_publish.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>upload_vod.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jscroll.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_upload_pic_handler.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_sort_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/auto_textarea.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod/vod_form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>catalog.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>video/jquery.video.new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>video/video_canvas.js"></script>
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
  $weight = $formdata['weight'];
  $markswf_url = RESOURCE_URL.'swf/';
  $image_resource = RESOURCE_URL;
  $levelLabel = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);
 ?>
<!-- 来源控件的数据 -->
<?php 
	$item_up_source = array(
		'class' => 'down_list',
		'show' => 'source_show',
		'width' => 130,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	/*
	if($formdata['source'])
	{
	   $default = $formdata['source'];
	}
	else
	{
	   $default = -1;
	}
	$sources[-1] = '自动';
	foreach($source as $k =>$v)
	{
		$sources[$v['id']] = $v['name'];
	}
	$source_id = 'update_source_id';
	*/
 ?> 
<!-- 分类控件的数据 -->	               	  
<?php 
	$item_up_sort = array(
		'class' => 'down_list',
		'show' => 'up_sort_show',
		'width' => 90,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	foreach($formdata['sort_name'] as $k =>$v)
	{
		$sorts[$v['id']] = $v['name'];
	}
	$vod_sort_id = 'update_sort_id';
	$sort_default = $formdata['vod_sort_id'];
	$sort_default_name = ($formdata['vod_sort_name'] != '无' ? $formdata['vod_sort_name'] : '选择分类');
	//hg_pre($formdata);
 ?> <script>
jQuery(function($){
	var is_link = <?php echo $formdata['is_link'] ? $formdata['is_link'] : 0  ?>;
	if( is_link ){
		return;
	}
    $('.video-btn').on({
        click : function(event){
            var video = $('#video');
            if(!video.is(':ui-video')){
                video.video({
                    slider : false,
                    bj : true,
                    kz : true,
                    autoPlay : true,
                    autoBuffer : true,
                    customEvents : {
                        '_change.video' : function(event, info){
                            $(this).video('option', 'zhen', info['zhen']);
                            var fen = info['fen'];
                            var canvas = $.createCanvas({
                                width : fen[0],
                                height : fen[1]
                            });
                            $(this).data('canvas', canvas);
                            $(this).attr({
                                src : info['src'],
                                poster : info['img']
                            });
                            this.load();
                        },                        'play.video' : function(){
                            $.Timer.start();
                        },                        'pause.video' : function(event, needPause){
                            needPause && this.pause();
                            $.Timer.stop();
                        },                        'timeupdate.video' : function(){
                        },                        'emptied.video' : function(){
                        },                        'seeked.video' : function(){
                        },                        'error.video' : function(){
                        }
                    },                    clickKZ : function(){
                        var $this = $(this);
                        var imgData = $this.data('canvas').getImgFromVideo();
                        var startP = $this.offset();
                        var startWH = [$this.width(), $this.height()];
                        var source = $('.source-img');
                        var endP = source.offset();
                        var endWH = [source.width(), source.height()];
                        var url ="run.php?mid=" + gMid + "&a=preview_pic&admin_id=" + gAdmin['admin_id'] + "&admin_pass=" + gAdmin.admin_pass;
                        var base64img = encodeURIComponent( imgData );
                        $.post( url, { Filedata : base64img, base64 : true},function( data ){
                        	if( $.isArray( data ) && data.length ){
                        		imgData = data[0];
                        	}else{
                        		return;
                        	}
                        	$('<img/>').attr({
	                            src : imgData,
	                            style : 'position:absolute;left:' + startP.left + 'px;top:' + startP.top + 'px;z-index:100000;width:' + startWH[0] + 'px;height:' + startWH[1] + 'px;'
	                        }).appendTo('body').animate({
	                            left : endP.left + 'px',
	                            top : endP.top + 'px',
	                            width : endWH[0] + 'px',
	                            height : endWH[1] + 'px',
	                            opacity : 0
	                        }, 500, function(){
	                            source.find('img').attr('src', imgData);
	                            $(this).remove();
	                        });
	                        $('#source_img_pic').val(imgData);
	                        indexPicEdit();
                        },'json' );
                    }
                });
                var fen = "<?php echo $formdata['video_resolution'];?>";
                fen = fen ? fen.split('*') : [640, 480];
                video.video('changeVideo', {
                    zhen : parseInt("<?php echo $formdata['frame_rate'];?>"),
                    src : "./vod<?php echo $formdata['dir_index'] && $formdata['dir_index'] > 0 ? $formdata['dir_index'] : ''; ?>/" + "<?php echo $formdata['video_path'];?>" + "<?php echo $formdata['video_filename'];?>",
                    poster : "<?php echo $formdata['source_img'];?>",
                    fen : fen
                });                setTimeout(function(){
                    $('.drag-tip').removeClass('on');
                }, 2000);
            }            var isOpen = $(this).data('open');
            $('#video-box').triggerHandler(isOpen ? 'hide' : 'show');
            if(isOpen){
                video.trigger('pause', [true]);
            }
            $(this).data('open', !isOpen);
        }
    });    $('#video-box').on({
        show : function(){
            var vb = $('.video-btn');
            var vbp = vb.offset();
            $(this).css({
                left : vbp.left + vb.outerWidth() + 10 + 'px',
                top : vbp.top + 'px'
            }).show();
        },        hide : function(){
            $(this).hide();
        }
    }).draggable();    $('.drag-close').on({
        click : function(){
            $('.video-btn').trigger('click');
        }
    });
})
</script><style>
.form-dioption-keyword .color,.form-dioption-fabu .color{color: #A5A5A5;}
.form-dioption-item, .form-cioption-item{margin:0;}
.form-dioption-keyword label input{vertical-align:middle;margin-right:5px;}
.form-dioption-keyword label{width:100px;line-height:22px;display:inline-block;}
#keywords-box .a{position:relative;}
.b{width:70px;height:18px;position:absolute;top:-3px;left:0;}
#hoge_edit_play{-moz-transition:all 0.3s ease-in;}
.form-middle-left{overflow:visible;}
.form-title-option span{margin-top:2px;}
</style>
<?php if($formdata['id']){ ?><form  action="./run.php?mid=<?php echo $_INPUT['mid'];?>" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return  hg_toSubmit();" >
<div class="common-form-head vedio-head">
     <div class="common-form-title">
          <h2>编辑视频</h2>
          <div class="form-dioption-title form-dioption-item">
                <!-- <textarea type="text" id="vod-title" name="title"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');" ><?php if($formdata['title']){ ?><?php echo $formdata['title'];?><?php } else { ?>请输入标题<?php } ?></textarea> -->
                <input type="text" id="vod-title" name="title" class="title need-word-count"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');" value="<?php if($formdata['title']){ ?><?php echo $formdata['title'];?><?php } else { ?>请输入标题<?php } ?>" />
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                        <input name="tcolor" type="hidden" value="<?php echo $formdata['tcolor'];?>" id="tcolor" />
		                <input name="isbold" type="hidden" value="<?php if($formdata['isbold']){ ?>1<?php } else { ?>0<?php } ?>" id="isbold" />
		                <input name="isitalic" type="hidden" value="<?php if($formdata['isitalic']){ ?>1<?php } else { ?>0<?php } ?>" id="isitalic" />
                      <input name="weight" value="<?php echo $weight;?>" id="weight" type="hidden" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <input type="submit" value="保存视频" class="common-form-save" />
		      <span class="option-iframe-back">关闭</span>
		  </div>
		  <div id="weightPicker" style="right:246px;">
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
<div class="common-form-main vedio-area">
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
		<div class="form-edit-img">
			<?php 
			if( $formdata['starttime'] ){
			$starttime = date('Y-m-d',$formdata['starttime'] );
			}
			 ?>
			<?php if($starttime){ ?><span class="zhibo-date"><?php echo $starttime;?></span><?php } ?>
			<div class="form-dioption-source-img" style="position:relative;">
				<a class="source-img">
					<img _src="<?php echo $formdata['source_img'];?>" id="pic_face" title="点击图片更换截图" _state="<?php if($formdata['source_img']){ ?>1<?php } else { ?>0<?php } ?>"/>
				</a>
				<p <?php if($formdata['is_link']){ ?>onclick="video_show();"<?php } ?> title="显示、关闭视频/ALT+W" class="video-btn">
					视频预览/截屏<!-- <img style="margin: 0 0 2px 5px;" src="<?php echo $RESOURCE_URL;?>tuji/drop.png" />  -->
				</p>
				<div class="source-img-box">
					<span></span>
					<ul id="add-img">
					<?php foreach ($formdata['snap_img'] as $k => $v){ ?>
						<li class="snap-img"><div class="middle-img-wrap"><img src="<?php echo $v;?>" /></div></li>
					<?php } ?>
						<li class="add-img-button">从电脑添加</li>	
						<div class="clear"></div>
					</ul>
				</div>
			</div>
			<div class="form-dioption-sort form-dioption-item"  id="sort-box">
				<label style="color:#9f9f9f;<?php if($sort_default_name == '选择分类'){ ?>display:none;<?php } ?>">分类： </label><p style="display:inline-block;" class="sort-label" _multi="vod_media_node"><?php echo $sort_default_name;?><img class="common-head-drop" src="<?php echo $RESOURCE_URL;?>tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
						<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
				<input type="hidden" value="<?php echo $sort_default;?>" name="update_sort_id" id="sort_id" />
			</div>
			<div class="form-dioption-keyword form-dioption-item clearfix">
				<span class="keywords-del"></span>
				<span class="form-item" _value="添加关键字" id="keywords-box">
					<span class="keywords-start color">添加关键字</span>
					<span class="keywords-add">+</span>
				</span>
				<input name="keywords" value="<?php echo $formdata['keywords'];?>" id="keywords" style="display:none;"/>
			</div>
			<div class="form-dioption-fabu form-dioption-item">
				<a class="common-publish-button color overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
			</div>
			<div id="lumin"></div>
		</div>
		</div>
		</div>
	</div>
	<div class="form-middle">
        <div class="form-middle-left">
			<div class="vod-info-box clear vod-info-box-with-bottom">
				<div class="vod-info-item">
					<textarea rows="5" name="comment"  id="comment" <?php if($formdata['comment']){ ?><?php echo $formdata['comment'];?><?php } else { ?>class="t_c_b"<?php } ?>  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');"><?php if($formdata['comment']){ ?><?php echo $formdata['comment'];?><?php } else { ?>这里输入描述<?php } ?></textarea>
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom m2o-flex">
				<div class="vod-info-item">
					<label class="input-label" for="subtitle">副题</label>
					<input type="text" name="subtitle" id="subtitle"  value="<?php echo $formdata['subtitle'];?>" />
				</div>
				<div class="vod-info-item laiyuan" style="margin-right:10px;">
					<label class="input-label" >来源</label>
					<input type="text" name="source" value="<?php echo $formdata['source'];?>" style="width:128px;height:20px;" />
				</div>
				<div class="vod-info-item zz" style="float:left;">
					<label class="input-label">作者</label>
					<input type="text" name="author" id="author" value="<?php echo $formdata['author'];?>" />
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom" style="display: none">
				<div class="vod-info-item vod-info-subtitle">
					<label class="input-label" for="subtitle">选项</label>
					<!--<input type="text" name="keywords" id="keywords" value="<?php echo $formdata['keywords'];?>" />-->
					<div class="form-dioption-keyword clearfix">
						<label><input type="checkbox" value="name" />开发评论</label>
						<label><input type="checkbox" value="name" />自动台标</label>
						<label><input type="checkbox" value="name" />附加广告</label>
						<label><input type="checkbox" value="name" />允许打分</label>
						<label><input type="checkbox" value="name" />观看心情</label>
					</div>
				</div>
			</div>
			<input type="hidden" name="submit_type" id="submit_type"/>
        </div>
        <div class="form-middle-right">
        	<div class="vod-details">
        			<a class="content_vodinfo_text" id="video-mark-btn" style="cursor:default;">源视频信息</a>
        			<!--<?php if($formdata['is_fast_edit']){ ?>
						<a class="content_vodinfo_text" data-isneed="need" _href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=video_mark&id=<?php echo $formdata['id'];?>&fast_edit=1<?php echo $_pp;?>" target="mainwin" id="video-mark-btn">源视频快编</a>
					<?php } else { ?>
						<a class="content_vodinfo_text"  href="javascript:void(0);"  onclick="return false;alert('此视频已被标注，不可快编');" id="video-mark-btn">源视频快编</a>
					<?php } ?>-->
        		<ul>
        			<li>时长:<span><?php echo $formdata['video_duration'];?></span></li>
        			<li>文件大小:<span><?php echo $formdata['video_totalsize'];?></span></li>
        			<li>视频编码:<span><?php echo $formdata['video'];?></span></li>
        			<li>平均码流:<span><?php echo $formdata['bitrate'];?></span></li>
        			<li>视频帧率:<span><?php echo $formdata['frame_rate'];?></span></li>
        			<li>分辨率:<span><?php echo $formdata['video_resolution'];?></span></li>
        			<li>宽高比:<span><?php echo $formdata['aspect'];?></span></li>
        			<li>音频编码:<span><?php echo $formdata['audio'];?></span></li>
        			<li>音频采样率:<span><?php echo $formdata['sampling_rate'];?></span></li>
        			<li>声道:<span><?php echo $formdata['video_audio_channels'];?></span></li>
        		</ul>
        	</div>
        	<div class="vod-option" data-tip="暂时隐藏" style="display:none;">
        		<p>选项:</p>
        		<ul>
                	<li><label><input type="checkbox" value="1" name="comment2out">开放评论</label></li>
                	<li><label><input type="checkbox" value="2" name="taibiao">自动台标</label></li>
                	<li><label><input type="checkbox" value="3" name="guanggao">附加广告</label></li>
                	<li><label><input type="checkbox" value="4" name="dafen">允许打分</label></li>
                	<li><label><input type="checkbox" value="5" name="xinqing">观看心情</label></li>
                </ul>
        	</div>
        </div>
	</div>
	<input type="file" id="file" style="display: none;" />
	<input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="<?php echo $formdata['source_img'];?>" />
	<input type="hidden"   id="vod_sort_id"  name="vod_sort_id"  value="" />
	<input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
	<input type="hidden" name="img_src"  id="img_src"  value=""   />
	<input type="hidden" value="<?php echo $a;?>" name="a" />
	<input type="hidden" value="<?php echo $_INPUT['mid'];?>" name="module_id" />
	<input type="hidden" value="<?php echo $formdata['total_num'];?>"  id="total_num" />
	<input type="hidden" value="<?php echo $formdata['page_num'];?>"  id="page_num" />
	<input type="hidden" value="<?php echo $formdata['id'];?>" name="id" id="edit_id" />
	<input type="hidden" name="referto" value="<?php echo $_INPUT['referto'];?>" />
	<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
	<div class="right_version"  style="overflow:hidden;display:none;">
		<h2><a href="<?php echo $_INPUT['referto'];?>">返回上页</a></h2>
		<h2 class="b">历史版本</h2>
			<ul class="u" id="copyright_list">
			<?php if($formdata['update_copyright']){ ?>
			<?php foreach ($formdata['update_copyright'] as $k => $v){ ?>
				<li  onclick="hg_get_copyright(this,<?php echo $v['id'];?>,<?php echo $_INPUT['mid'];?>);"  style="cursor:pointer;"  name="copyright[]" >
					<span class="time"><?php echo $v['update_time'];?></span>
					<span><?php echo $v['update_man'];?>编辑</span>
				</li>
			<?php } ?>
			<?php } ?>	
			</ul>
			<?php if($formdata['total_num'] > $formdata['page_num']){ ?>
			<div class="more"  id="haveMore" onclick="hg_getMoreCopyright(<?php echo $$primary_key;?>,<?php echo $_INPUT['mid'];?>);">更多<span></span></div>
			<?php } ?>
	</div>
</div>
</form>
<div id="hoge_edit_play" style="top:-378px;left:273px;">
<img class="move_img_a" src="" id="img_move" style="width:320px;" />
<object id="1video" type="application/x-shockwave-flash" data="<?php if($formdata['is_link']){ ?><?php echo $formdata['swf'];?><?php } else { ?><?php echo $markswf_url;?>vodPlayer.swf?<?php echo $formdata['time'];?><?php } ?>" width="320" height="270">
	<param name="movie" value="<?php echo $markswf_url;?>vodPlayer.swf?<?php echo $formdata['time'];?>">
	<param name="allowscriptaccess" value="always">
	<param name="wmode" value="transparent">
	<param name="allowFullScreen" value="true">
	<param name="flashvars" value="jsNameSpace=adminDemandPlayer&startTime=<?php echo $formdata['start'];?>&duration=<?php echo $formdata['duration'];?>&videoUrl=<?php echo $formdata['video_url'];?>&videoId=<?php echo $formdata['vodid'];?>&snap=true&autoPlay=true&snapUrl=<?php echo $formdata['snapUrl'];?>&aspect=<?php echo $formdata['aspect'];?>">
</object>
</div><style>
#video-box{position:absolute;left:0;top:0;z-index:10000;display:none;}
.drag-tip{1display:none;position:absolute;left:-2px;top:-30px;height:30px;line-height:30px;width:100%;background:#5C99CF;cursor:move;text-indent:1em;color:#fff;font-weight:bold;border:2px solid #5C99CF;border-bottom:none;}
#video-box:hover .drag-tip, .drag-tip.on{display:block;}
.drag-close{z-index:10;position:absolute;right:-2px;top:-2px;height:30px;width:30px;line-height:30px;text-align:center;background:red;cursor:pointer;color:#fff;text-indent:0;}
</style>
<div id="video-box">
    <div class="drag-tip on">按住此处可以拖动视频<span class="drag-close">x</span></div>
    <video id="video" width="500" height="400"></video>
</div>
<?php } else { ?>
此视频不存在,<a href="./run.php?mid=<?php echo $_INPUT['mid'];?>&infrm=1">请返回</a>
<?php } ?>
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