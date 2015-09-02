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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/2013/iframe.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/2013/list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/program_day.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>program/common/common_category.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>live/my-ohms.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>program/program_library.js"></script>
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
//print_r($list);
 ?>
<div id="ohms-instance" style="position:absolute;display:none;"></div>
<div style="display:none">
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<!-- <a type="button" class="button_6" href="run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=form" target="mainwin">新增节目库</a> -->
		<!-- <a class="gray mr10" href="run.php?mid=<?php echo $_INPUT['main_mid'];?>&a=frame" target="mainwin">返回节目单</a> -->
	</div>
</div>
<div class="wrap common-list-content">
	 <ul class="library-list clear">
	 	<li class="library-each library-add">
	 		<div class="m2o-flex">
		 		<div class="library-img"><img src=""/>节目图片</div>
		 		<div class="library-info m2o-flex-one">
		 			<div class="library-name"><input type="text" name="title" placeholder="节目名称" value=""/>
		 				<div class="set"><em class="li_save" data-type="add" title="新增节目">新增</em></div>
		 			</div>
		 			<div class="library-item"><label>时间：</label><input type="text" class="start_time ohms" placeholder="节目开始时间" readonly="readonly" value="" /></div>
		 			<div class="library-item"><label>描述：</label><input type="text" class="brief" name="brief" value=""/></div>
		 			<div class="library-item"><label>时期：</label>
	 					<ul class="period">
	 						<li><input type="checkbox" name="event_day" class="event_day" value="1" id="event_day"/><label for="event_day">每天</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="1" id="week_day_1"/><label for="week_day_1">星期一</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="2" id="week_day_2"/><label for="week_day_2">星期二</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="3" id="week_day_3"/><label for="week_day_3">星期三</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="4" id="week_day_4"/><label for="week_day_4">星期四</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="5" id="week_day_5"/><label for="week_day_5">星期五</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="6" id="week_day_6"/><label for="week_day_6">星期六</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="7" id="week_day_7"/><label for="week_day_7">星期日</label></li>
	 					</ul>
	 				</div>
		 		</div>
	 		</div>
	 	</li>
	 	<?php foreach ($list as $k=> $base){ ?>
	 	<li class="library-each" data-id="<?php echo $base['id'];?>" channel_id="<?php echo $base['channel_id'];?>">
	 		<div class="m2o-flex">
		 		<div class="library-img">
		 			<?php if($base['indexpic']){ ?><img src="<?php echo $base['indexpic'];?>"/><?php } ?>节目图片
		 		</div>
		 		<div class="library-info m2o-flex-one">
		 			<div class="library-name"><input type="text" name="title" disabled="disabled" placeholder="节目名称" value="<?php echo $base['title'];?>"/>
		 				<div class="set"><em class="li_edit" data-type="edit" title="编辑节目">编辑</em><em class="li_del" data-type="del" title="删除节目">删除</em></div>
		 			</div>
		 			<div class="library-item"><label>时间：</label><input type="text" class="start_time" placeholder="节目开始时间" disabled="disabled" value="<?php echo $base['start_time'];?>" />
	 				</div>
		 			<div class="library-item"><label>描述：</label><input type="text" class="brief" disabled="disabled" name="brief" value="<?php echo $base['brief'];?>"/>
	 				</div>
	 				<div class="library-item"><label>时期：</label>
	 					<ul class="period">
	 						<?php 
								$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
							 ?>
	 						<li><input type="checkbox" name="event_day" value="1" class="event_day" id="event_day_<?php echo $base['id'];?>" <?php if(count($base['week_day'])==7){ ?>checked<?php } ?> disabled="disabled"/><label for="event_day_<?php echo $base['id'];?>">每天</label></li>
	 						<?php foreach ($week_day_arr as $key => $value){ ?>
								<li><input type="checkbox" name="week_day[]" value="<?php echo $key;?>" id="week_day_<?php echo $base['id'];?><?php echo $key;?>" <?php foreach ($base['week_day'] as $k => $v){ ?><?php if($v == $key){ ?>checked<?php } ?><?php } ?> disabled="disabled"/><label for="week_day_<?php echo $base['id'];?><?php echo $key;?>"><?php echo $value;?></label></li>
							<?php } ?>
	 					</ul>
	 				</div>
		 		</div>
	 		</div>
	 	</li>
	 	<?php } ?>
	 	<input type="file" name="index_pic" accept="image/png,image/jpeg" class="image-file" style="display: none;">
	 </ul>
	  <div class="record-bottom m2o-flex m2o-flex-center">
	  	 <div class="record-operate">
	  	 	<input type="checkbox" name="checkall" class="checkAll" title="全选" />
	  	    <a name="delete" data-method="delete" class="batch-delete">删除</a>
	  	 </div>
	  	 <div class="m2o-flex-one">
	  	 <?php echo $pagelink;?>
	  	 </div>
	 </div>
</div>
<script type="text/x-jquery-tmpl" id="list-each-tpl">
<li class="library-each" data-id="${id}">
	<div class="m2o-flex">
 		<div class="library-img">
 			{{if index_img}}<img src="${index_img}"/>{{/if}}节目图片
 		</div>
 		<div class="library-info m2o-flex-one">
 			<div class="library-name"><input type="text" name="title" disabled="disabled" placeholder="节目名称" value="${title}"/>
 				<div class="set"><em class="li_edit" data-type="edit">编辑</em><em class="li_del" data-type="del">删除</em></div>
 			</div>
 			<div class="library-item"><label>时间：</label><input type="text" class="start_time" placeholder="节目开始时间" disabled="disabled" value="${start_time}" />
			</div>
 			<div class="library-item"><label>描述：</label><input type="text" disabled="disabled" name="brief" value="${brief}"/>
			</div>
			<div class="library-item"><label>时期：</label>
				<ul class="period">
					<li><input type="checkbox" name="event_day" value="1" class="event_day" id="event_day_${id}" disabled="disabled"/><label for="event_day_${id}">每天</label></li>
					<li><input type="checkbox" name="week_day[]" value="1" id="week_day_${id}1" disabled="disabled"/><label for="week_day_${id}1">星期一</label></li>
					<li><input type="checkbox" name="week_day[]" value="2" id="week_day_${id}2" disabled="disabled"/><label for="week_day_${id}2">星期二</label></li>
					<li><input type="checkbox" name="week_day[]" value="3" id="week_day_${id}3" disabled="disabled"/><label for="week_day_${id}3">星期三</label></li>
					<li><input type="checkbox" name="week_day[]" value="4" id="week_day_${id}4" disabled="disabled"/><label for="week_day_${id}4">星期四</label></li>
					<li><input type="checkbox" name="week_day[]" value="5" id="week_day_${id}5" disabled="disabled"/><label for="week_day_${id}5">星期五</label></li>
					<li><input type="checkbox" name="week_day[]" value="6" id="week_day_${id}6" disabled="disabled"/><label for="week_day_${id}6">星期六</label></li>
					<li><input type="checkbox" name="week_day[]" value="7" id="week_day_${id}7" disabled="disabled"/><label for="week_day_${id}7">星期日</label></li>
				</ul>
			</div>
 		</div>
	</div>
</li>
</script>