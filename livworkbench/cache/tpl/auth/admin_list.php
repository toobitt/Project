
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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/contribute_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/edit_video_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/admin_list.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>contribute.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/common_list.js"></script>
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
<?php } ?><script type="text/javascript">
$(function(){
	tablesort('contribute_list','admin','order_id');
	$("#contribute_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"<?php if($_INPUT['infrm']){ ?> style="display:none"<?php } ?>>
		<a href="?mid=<?php echo $_INPUT['mid'];?>&a=form<?php echo $_ext_link;?>" class="button_6" style="font-weight:bold;">新增用户</a>
		<a class="gray mr10" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=configuare&infrm=1" target="mainwin">
			<span class="left"></span>
			<span class="middle"><em class="set">配置权限</em></span>
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
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 120,
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['admin_time'] = $_INPUT['admin_time'] ? $_INPUT['admin_time'] : 1;								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'sort_audit',
									'width' => 120,
									'state' => 0,
								);
								$appendRole = $appendRole[0];
								$default = -1;
								$appendRole[$default] = '所有角色';
								$_INPUT['admin_role'] = $_INPUT['admin_role'] ? $_INPUT['admin_role'] : -1;
							 ?>							<?php 
$audit_css['class'] = $audit_css['class'] ? $audit_css['class']:'transcoding down_list';
$audit_css['show'] = $audit_css['show'] ? $audit_css['show'] :'transcoding_show';
$audit_css['type'] = $audit_css['type'] ? 1:0;
if($audit_css['width'] && $audit_css['width'] != 104 ){
	$width = $audit_css['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $audit_css['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $audit_css['show'];?>','<?php echo $audit_css['extra_div'];?>', this);" onmousemove="<?php echo $audit_css['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $audit_css['show'];?>','<?php echo $audit_css['extra_div'];?>', this);<?php echo $audit_css['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $audit_css['show'];?>" class="overflow" <?php if($audit_css['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $appendRole[$_INPUT['admin_role']];?></label></a></span>
	<ul id="<?php echo $audit_css['show'];?>" style="display:none;"  class="<?php echo $audit_css['show'];?> defer-hover-target">
		<?php if($audit_css['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="admin_role_key" class="admin_role_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $audit_css['method'];?>', '<?php echo $audit_css['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($appendRole){ ?>
		<?php foreach ($appendRole as $k => $v){ ?>
			<?php if($audit_css['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($audit_css['is_sub'])
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
			if($audit_css['href'])
			{
				if(!strpos($audit_css['href'],'fid='))
				{
					$expandhref=$audit_css['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$audit_css['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $audit_css['extra_li'];?>><a <?php if($audit_css['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $audit_css['state'];?>,'<?php echo $audit_css['show'];?>','admin_role<?php  echo $audit_css['more']?'_'.$audit_css['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $audit_css['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($audit_css['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $audit_css['select_column'];?>" /><?php } ?>
</div>
<?php if($audit_css['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $audit_css['time_name'];
$end_time = 'end_time' . $audit_css['time_name'];
 ?>
	<div class="input" <?php if($_INPUT['admin_role'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxadmin_role">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timeadmin_role" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_INPUT['admin_role'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxadmin_role">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timeadmin_role" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['admin_role'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_dateadmin_role" class="btn_search" />
<?php } ?>
<?php if($audit_css['more']){ ?>
	<input type="hidden" name="admin_role[<?php echo $audit_css['more'];?>]"  id="admin_role_<?php echo $audit_css['more'];?>"  value="<?php echo $_INPUT['admin_role'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="admin_role" value="<?php echo $_INPUT['admin_role'];?>"/>
<?php } else { ?>
<input type="hidden" name="admin_role"  id="admin_role"  value="<?php echo $_INPUT['admin_role'];?>"/>
<?php } ?>
<?php } ?>
<?php if($audit_css['para']){ ?>
	<?php foreach ($audit_css['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?>							<?php 
$time_css['class'] = $time_css['class'] ? $time_css['class']:'transcoding down_list';
$time_css['show'] = $time_css['show'] ? $time_css['show'] :'transcoding_show';
$time_css['type'] = $time_css['type'] ? 1:0;
if($time_css['width'] && $time_css['width'] != 104 ){
	$width = $time_css['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $time_css['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $time_css['show'];?>','<?php echo $time_css['extra_div'];?>', this);" onmousemove="<?php echo $time_css['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $time_css['show'];?>','<?php echo $time_css['extra_div'];?>', this);<?php echo $time_css['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $time_css['show'];?>" class="overflow" <?php if($time_css['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $_configs['date_search'][$_INPUT['admin_time']];?></label></a></span>
	<ul id="<?php echo $time_css['show'];?>" style="display:none;"  class="<?php echo $time_css['show'];?> defer-hover-target">
		<?php if($time_css['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="admin_time_key" class="admin_time_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $time_css['method'];?>', '<?php echo $time_css['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($_configs['date_search']){ ?>
		<?php foreach ($_configs['date_search'] as $k => $v){ ?>
			<?php if($time_css['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($time_css['is_sub'])
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
			if($time_css['href'])
			{
				if(!strpos($time_css['href'],'fid='))
				{
					$expandhref=$time_css['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$time_css['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $time_css['extra_li'];?>><a <?php if($time_css['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $time_css['state'];?>,'<?php echo $time_css['show'];?>','admin_time<?php  echo $time_css['more']?'_'.$time_css['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $time_css['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($time_css['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $time_css['select_column'];?>" /><?php } ?>
</div>
<?php if($time_css['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $time_css['time_name'];
$end_time = 'end_time' . $time_css['time_name'];
 ?>
	<div class="input" <?php if($_INPUT['admin_time'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_boxadmin_time">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_timeadmin_time" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_INPUT['admin_time'] == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_boxadmin_time">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_timeadmin_time" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_INPUT['admin_time'] == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_dateadmin_time" class="btn_search" />
<?php } ?>
<?php if($time_css['more']){ ?>
	<input type="hidden" name="admin_time[<?php echo $time_css['more'];?>]"  id="admin_time_<?php echo $time_css['more'];?>"  value="<?php echo $_INPUT['admin_time'];?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="admin_time" value="<?php echo $_INPUT['admin_time'];?>"/>
<?php } else { ?>
<input type="hidden" name="admin_time"  id="admin_time"  value="<?php echo $_INPUT['admin_time'];?>"/>
<?php } ?>
<?php } ?>
<?php if($time_css['para']){ ?>
	<?php foreach ($time_css['para'] as $k => $v){ ?>
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
	            <form method="post" action="" name="pos_listform">
	                <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="admin-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"></a></div>
                                <div class="contribute-fengmian common-list-item">头像</div>
                            </div>
                            <div class="common-list-right">
                                <div class="admin-bj common-list-item open-close">编辑</div>
                                <div class="admin-sc common-list-item open-close">删除</div>
                                <div class="admin-js common-list-item open-close">角色</div>
                                <div class="admin-bdmb common-list-item open-close">绑定密保</div>
                                <div class="admin-tjr common-list-item open-close">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close server-biaoti">用户名</div>
					        </div>
                        </li>
                    </ul>
		        	<ul class="common-list" id="contribute_list">
						<?php if($list){ ?>
			       			<?php foreach ($list as $k => $v){ ?>
			                	<li class="common-list-data clear"  id="r_<?php echo $v['id'];?>"    name="<?php echo $v['id'];?>"   orderid="<?php echo $v['order_id'];?>">
	<div class="common-list-left">
        <div class="common-list-item admin-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="<?php echo $v[$primary_key];?>" title="<?php echo $v[$primary_key];?>"  /></a>
            </div>
        </div>
        <?php 
        	$avatar = '';
        	if ($v['avatar'])
        	{
        		$avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x36/'.$v['avatar']['filepath'].$v['avatar']['filename'];
        	}else{
        		$avatar = $RESOURCE_URL.'avatar.jpg';
        	}
         ?>
        <div class="contribute-fengmian common-list-item"><em><img alt="头像" src="<?php echo $avatar;?>" width="41px" height="37px"></em></div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item admin-bj">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item admin-sc">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid=<?php echo $_INPUT['mid'];?>&a=delete&id=<?php echo $v['id'];?>"><em class="b3"></em></a>
            </div>
        </div>
        <div class="common-list-item admin-js">
            <div class="common-list-cell">
              <span id="contribute_sort_<?php echo $v['id'];?>"><?php echo $v['name'];?></span>
            </div>
        </div>
        <div class="common-list-item admin-bdmb">
            <div class="common-list-cell">
			    <span id="contribute_cardid_<?php echo $v['id'];?>"> <?php if($v['cardid']){ ?><a href="infocenter.php?a=get_user_mibao&id=<?php echo $v['id'];?>">下载密保卡</a><?php } else { ?>未绑定<?php } ?></span>
            </div>
        </div>
        <div class="common-list-item admin-tjr">
            <div class="common-list-cell">
                <span><?php echo $v['user_name_add'];?></span>
			   <span class="create-time"><?php echo $v['create_time'];?></span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item admin-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <span class="common-list-overflow admin-biaoti-overflow">
		          <span id="contribute_title_<?php echo $v['id'];?>" class="m2o-common-title"><?php echo $v['user_name'];?></span>
               </span>
            </div>  
	    </div>
   </div>
</li> 			                <?php } ?>
			  			<?php } else { ?>
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				<?php } ?>
		            </ul>
			        <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
			            	<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
						    <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
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
	<!--发布模板-->
	<span class="vod_fb" id="vod_fb"></span>
	<div id="vodpub" class="vodpub lightbox">
		<div class="lightbox_top">
			<span class="lightbox_top_left"></span>
			<span class="lightbox_top_right"></span>
			<span class="lightbox_top_middle"></span>
		</div>
		<div class="lightbox_middle">
			<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('<?php echo $RESOURCE_URL;?>close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
			<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">			</div>
		</div>
		<div class="lightbox_bottom">
			<span class="lightbox_bottom_left"></span>
			<span class="lightbox_bottom_right"></span>
			<span class="lightbox_bottom_middle"></span>
		</div>
	</div>
	<!--发布-->
	<div id="infotip"  class="ordertip"></div>
</body>
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