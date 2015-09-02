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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/setting.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>applant2/common/common_list.css" />
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
<script type="text/javascript">
function hg_switch_tab(id)
{
	var tab = [];
	tab.push('base');
	tab.push('db');
	tab.push('cron');
	if (id == 'cron')
	{
		$('#doset').hide();
	}
	else
	{
		$('#doset').show();
	}
	for (var i=0; i < 3; i++)
	{
		if (tab[i] == id)
		{
			$('#sg_' + id).show();
			$('.setting_title_label').css({'background-color':'#f9f9f9f'});
			$('#setting_title'+id).css({'background-color':'#eee'});
		}
		else
		{
			$('#sg_' + tab[i]).hide();
		}
	}
}
$( function(){
	$('#settingform').on('submit', function(){
		var form = $(this),
			url = form.attr('action');
		url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ajax=1';
		$(this).ajaxSubmit( {
			url : url,
			dataType : 'json',
			success : function( data ){
				if( data['msg'] ){
					form.find('input[type="submit"]').myTip( {
						string : data['msg']
					} );
				}
				else{
					data['callback'] && ( eval( data['callback'] ) );
				}
			}
		} );
		return false;
	})
} )
</script>
<?php if($setting_groups){ ?>
<ul class="setting_ul clearfix">
<?php foreach ($setting_groups AS $k => $v){ ?>
<li onclick="hg_switch_tab('<?php echo $k;?>');" class="setting_title_label" id="setting_title<?php echo $k;?>"><a><h2 class="setting_h2"><?php echo $v;?></h2></a></li>
<?php } ?>
</ul>
<?php } ?>
<form action="settings.php" method="post" enctype="multipart/form-data" class="setting_form ad_form h_l"  name="settingform" id="settingform">
<?php if($setting_groups){ ?>
<?php foreach ($setting_groups AS $k => $v){ ?>
	<?php if($k == 'base'){ ?>
	<div id="sg_<?php echo $k;?>">
		<style type="text/css">
.config_form p {margin:10px 0;}
.config_form label {margin-right:15px;}
.config_form label input {margin-right:5px;}
.config_form input {vertical-align:middle;}
.config_form .dropBtn {width:20px; height:20px; line-height:20px; display:inline-block; text-align:center; cursor:pointer; font-style:normal;}
.terminal_size {padding-left:20px;}
.terminal_size input {width:300px;}
.config_form ul li {margin:10px 0 10px 10px;}
.btn {border:1px solid #CCC; display:inline-block; width:20px; height:20px; line-height:20px; text-align:center; cursor:pointer; margin-left:10px;}
.defStyle {margin-right:10px;}
</style>
<script type="text/javascript">
$(function() {
	/*
	$('input[type="checkbox"]').click(function() {
		if ($(this).attr('checked')) {
			$(this).val('1');
		} else {
			$(this).val('0');
		}
	});
	*/
	$('#addBtn').click(function() {
		var con = '<li><input type="text" name="base[names][]" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" placeholder="数据地址" size="50" /><span id="dropBtn" class="btn">-</span></li>';
		$(this).parent().parent().append(con);
	});
	$('#dropBtn').live('click', function() {
		$(this).parent().remove();
	});
});
</script>
<div class="config_form">
<?php if($settings['base']['icon_size']){ ?>
<p><span>APP图标建议尺寸：</span><input type="text" name="base[icon_max_size][width]" value="<?php echo $settings['base']['icon_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[icon_max_size][height]" value="<?php echo $settings['base']['icon_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['icon_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[icon_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[icon_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<?php if($settings['base']['startup_size']){ ?>
<p><span>APP启动画面建议尺寸：</span><input type="text" name="base[startup_max_size][width]" value="<?php echo $settings['base']['startup_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[startup_max_size][height]" value="<?php echo $settings['base']['startup_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['startup_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[startup_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[startup_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<?php if($settings['base']['guide_size']){ ?>
<p><span>APP引导图建议尺寸：</span><input type="text" name="base[guide_max_size][width]" value="<?php echo $settings['base']['guide_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[guide_max_size][height]" value="<?php echo $settings['base']['guide_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['guide_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[guide_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[guide_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<?php if($settings['base']['module_size']){ ?>
<p><span>APP模块图标建议尺寸：</span><input type="text" name="base[module_max_size][width]" value="<?php echo $settings['base']['module_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[module_max_size][height]" value="<?php echo $settings['base']['module_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['module_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[module_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[module_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<?php if($settings['base']['navBarTitle_size']){ ?>
<p><span>导航栏标题建议尺寸：</span><input type="text" name="base[nav_max_size][width]" value="<?php echo $settings['base']['navBarTitle_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[nav_max_size][height]" value="<?php echo $settings['base']['navBarTitle_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['navBarTitle_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[navBarTitle_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[navBarTitle_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<?php if($settings['base']['magazine_size']){ ?>
<p><span>杂志首页背景建议尺寸：</span><input type="text" name="base[magazine_max_size][width]" value="<?php echo $settings['base']['magazine_size']['max_size']['width'];?>" style="width: 50px;" /> x <input type="text" name="base[magazine_max_size][height]" value="<?php echo $settings['base']['magazine_size']['max_size']['height'];?>" style="width: 50px;" /> (单位：像素)</p>
<?php 
$size = array();
foreach ($settings['base']['magazine_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
 ?>
<p class="terminal_size"><label>Android：<input type="text" name="base[magazine_size][android]" value="<?php echo $size['android'];?>" /></label><label>iOS：<input type="text" name="base[magazine_size][ios]" value="<?php echo $size['ios'];?>" /></label></p>
<?php } ?>
<!--
<?php if(isset($settings['define']['USE_EFFECT'])){ ?>
<p><span>是否启用过渡效果：</span><input type="checkbox" name="define[USE_EFFECT]" value="1"<?php if($settings['define']['USE_EFFECT'] == 1){ ?> checked="checked"<?php } ?> /></p>
<?php } ?>
<?php if($settings['base']['image_type']){ ?>
<p><span>APP上传图片格式：</span>
<?php foreach ($settings['base']['image_type'] as $k => $v){ ?>
<label><input type="checkbox" name="base[pic_type][]" value="<?php echo $k;?>"<?php if(in_array($k, $settings['base']['pic_type'])){ ?> checked="checked"<?php } ?> /><?php echo $v;?></label>
<?php } ?>
</p>
<?php } ?>
-->
<!--
<?php if($settings['template']){ ?>
<p><span>默认风格：</span>
<?php foreach ($settings['template'] as $template){ ?>
<label class="defStyle"><input type="radio" name="define[DEFAULT_STYLE]" value="<?php echo $template['id'];?>"<?php if($settings['define']['DEFAULT_STYLE'] == $template['id']){ ?> checked="checked"<?php } ?> /><?php echo $template['name'];?></label>
<?php } ?>
</p>
<?php } ?>
-->
<?php if($settings['interface']){ ?>
<p><span>默认界面：</span>
<?php foreach ($settings['interface'] as $interface){ ?>
<label class="defStyle"><input type="radio" name="define[DEFAULT_UI]" value="<?php echo $interface['id'];?>"<?php if($settings['define']['DEFAULT_UI'] == $interface['id']){ ?> checked="checked"<?php } ?> /><?php echo $interface['name'];?></label>
<?php } ?>
</p>
<?php } ?>
<p><span>APP引导图数量上限：</span><input type="text" name="define[GUIDE_LIMIT]" value="<?php echo $settings['define']['GUIDE_LIMIT'];?>" style="width:50px;" /></p>
<p><span>APP模块中文名称字符限制：</span><input type="text" name="define[MODULE_NAME_LIMIT]" value="<?php echo $settings['define']['MODULE_NAME_LIMIT'];?>" style="width:50px;" /></p>
<p><span>APP模块英文名称字符限制：</span><input type="text" name="define[MODULE_ENGLISH_LIMIT]" value="<?php echo $settings['define']['MODULE_ENGLISH_LIMIT'];?>" style="width:50px;" /></p>
<p><span>APP创建限制：</span><input type="text" name="define[APP_LIMIT_NUM]" value="<?php echo $settings['define']['APP_LIMIT_NUM'];?>" style="width:50px;" /></p>
<p><span>APP模块限制：</span><input type="text" name="define[MODULE_LIMIT_NUM]" value="<?php echo $settings['define']['MODULE_LIMIT_NUM'];?>" style="width:50px;" /></p>
<p><span>图片域名：</span><input type="text" name="define[REPLACE_IMG_DOMAIN]" value="<?php echo $settings['define']['REPLACE_IMG_DOMAIN'];?>" style="width:150px;" /></p>
<p><span>是否替换图片域名：</span>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="0"<?php if($settings['define']['IS_REPLACE'] == 0){ ?> checked="checked"<?php } ?> />否</label>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="1"<?php if($settings['define']['IS_REPLACE'] == 1){ ?> checked="checked"<?php } ?> />是</label>
</p>
<p><span>天气接口：</span><input type="text" name="define[WEATHER_API]" value="<?php echo $settings['define']['WEATHER_API'];?>" style="width:300px;" /></p>
<p><span>统计接口：</span><input type="text" name="define[STATISTICS_API]" value="<?php echo $settings['define']['STATISTICS_API'];?>" style="width:300px;" /></p>
<?php if($settings['base']['data_url']){ ?>
<p><span>模块数据配置：</span>
	<ul>
		<?php if($settings['base']['data_url']['path']){ ?>
		<li><label>目录地址：<input type="text" name="base[data_url][path]" value="<?php echo $settings['base']['data_url']['path'];?>" placeholder="目录" size="50" /></label><span id="addBtn" class="btn">+</span></li>
		<?php } ?>
		<?php if($settings['base']['data_url']['file']){ ?>
			<?php foreach ($settings['base']['data_url']['file'] as $mark => $file){ ?>
		<li>
			<input type="text" name="base[names][]" value="<?php echo $file['name'];?>" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" value="<?php echo $mark;?>" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" value="<?php echo $file['url'];?>" placeholder="数据地址" size="50" /><span id="dropBtn" class="btn">-</span>
		</li>
			<?php } ?>
		<?php } ?>
	</ul>
</p>
<?php } ?>
</div>
	</div>
	<?php } ?>
	<?php if($k == 'db'){ ?>
	<div id="sg_<?php echo $k;?>" style="display:none;"><ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">主机名：</span>
			<input class="form_ul_input" type="text" value="<?php echo $settings['db']['host'];?>" name='db[host]'>
			<font class="important" style="color:red">IP或者域名，必填</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">用户名：</span>
			<input class="form_ul_input" type="text" value="<?php echo $settings['db']['user'];?>" name='db[user]'>
			<font class="important" style="color:red">数据库连接用户名</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">密码：</span>
			<input class="form_ul_input" type="text" value="" name='db[pass]'>
			<font class="important" style="color:red">数据库连接密码</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">数据库名：</span>
			<input class="form_ul_input" type="text" value="<?php echo $settings['db']['database'];?>" name='db[database]'>
			<font class="important" style="color:red">选用的数据库</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">前缀：</span>
			<input class="form_ul_input" type="text" value="<?php echo $settings['db']['dbprefix'];?>" name='db[dbprefix]'>
			<font class="important">数据库表前缀</font>
		</div>
	</li>	<li class="i"style="padding-top: 5px;">
		<div class="form_ul_div">
			<span  class="title">长连接：</span>
			<div style="display:inline-block;width:255px">
<?php if(!is_array($option)){ ?>
<?php 
$option = array(1 => '是', 0 => '否', );
 ?>
<?php } ?>
<?php foreach ($option AS $hg_k => $hg_v){ ?>
<?php 
if ($hg_k == $settings['db']['pconncet'])
{
	$checked = ' checked="checked"';
}
else
{
	$checked = '';
}
 ?>
<label><input type="radio" name="db[pconncet]" value="<?php echo $hg_k;?>"<?php echo $checked;?> class="n-h"/><span><?php echo $hg_v;?></span></label>
<?php } ?></div>
			<font class="important">是否启用长连接</font>
		</div>
	</li>
</ul>
	</div>
	<?php } ?>
	<?php if($k == 'cron'){ ?>
	<div id="sg_<?php echo $k;?>" style="display:none;">
<script type="text/javascript">
function hg_chg_state(id, clew)
{
	$('#is_use_' + id).html(clew);
}
function hg_show_mod_input(id)
{
	$('#space_' + id).hide();
	$('#space_input_' + id).show();
	$('#space_input_' + id).focus();
}
function hg_submit_space(id)
{
	var space = $('#space_input_' + id).val();
	var oldspace = $('#space_' + id).html();
	if (oldspace != space)
	{
		var url = 'settings.php?app_uniqueid=<?php echo $app_uniqueid;?>&id=' + id + '&a=modify_space&space=' + space;
		hg_ajax_post(url);
	}
	else
	{
		hg_chg_space(id, space);
	}
}
function hg_chg_space(id, space)
{
	$('#space_' + id).show();
	$('#space_input_' + id).hide();
	$('#space_' + id).html(space);
}
</script>
<!-- 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	<tr class="h" align="left" valign="middle">
		<th width="50" class="left"></th>
		<th class="left">名称</th>
		<th width="200" class="left">任务文件</th>
		<th class="left">执行间隔</th>
		<th class="left">下次执行时间</th>
		<th class="left">是否启用</th>
	</tr>
<?php if($crontabs){ ?>
		<?php foreach ($crontabs AS $k => $v){ ?>
		<tr title="<?php echo $v['brief'];?>">
		<td class="left"></td>
		<td class="left"><?php echo $v['name'];?>&nbsp;</td>
		<td class="left"><?php echo $v['file_name'];?>&nbsp;</td>
		<td class="left"><span id="space_<?php echo $v['id'];?>" onclick="hg_show_mod_input(<?php echo $v['id'];?>);"><?php echo $v['space'];?></span><input type="text" size="3" id="space_input_<?php echo $v['id'];?>" value="<?php echo $v['space'];?>" onblur="hg_submit_space(<?php echo $v['id'];?>)" style="display:none;" />s</td>
		<td class="left"><?php echo $v['run_time'];?></td>
		<th class="left"><a href="settings.php?app_uniqueid=<?php echo $app_uniqueid;?>&id=<?php echo $v['id'];?>&a=chgstate" id="is_use_<?php echo $v['id'];?>" _op="<?php echo $v['op'];?>" onclick="return hg_ajax_post(this,'', 0);"><?php echo $v['is_use'];?></a></th>
		</tr>
		<?php } ?>
<?php } ?>
</table>
 --><ul class="common-list public-list">
	<li class="common-list-head">
		<div class="common-list-left">
			<div class="common-list-item wd100">名称</div>
			<div class="common-list-item kong wd50"></div>
		</div>
		<div class="common-list-right">
			<div class="common-list-item wd80">执行间隔</div>
			<div class="common-list-item wd150">下次执行时间</div>
			<div class="common-list-item wd80">是否启用</div>
		</div>
		<div class="common-list-biaoti">
			<div class="common-list-item">任务文件</div>
		</div>
	</li>
</ul>
<ul class="common-list public-list">
<?php if($crontabs){ ?>
	<?php foreach ($crontabs AS $k => $v){ ?>
	<li class="common-list-data">
		<div class="common-list-left">
			<div class="common-list-item wd100"><span style="cursor:pointer;"><?php echo $v['name'];?></span></div>
			<div class="common-list-item kong wd50"></div>
		</div>
		<div class="common-list-right">
			<div class="common-list-item wd80"><span style="cursor:pointer;"><span id="space_<?php echo $v['id'];?>" onclick="hg_show_mod_input(<?php echo $v['id'];?>);"><?php echo $v['space'];?></span><input type="text" size="3" id="space_input_<?php echo $v['id'];?>" value="<?php echo $v['space'];?>" onblur="hg_submit_space(<?php echo $v['id'];?>)" style="display:none;" />s</span></div>
			<div class="common-list-item wd150"><span style="cursor:pointer;"><?php echo $v['run_time'];?></span></div>
			<div class="common-list-item wd80"><span style="cursor:pointer;"><a href="settings.php?app_uniqueid=<?php echo $app_uniqueid;?>&id=<?php echo $v['id'];?>&a=chgstate" id="is_use_<?php echo $v['id'];?>" _op="<?php echo $v['op'];?>" onclick="return hg_ajax_post(this,'', 0);" style="color:#8FA8C6;"><?php echo $v['is_use'];?></a></span></div>
		</div>
		<div class="common-list-biaoti biaoti-transition">
			<div class="common-list-item"><span style="cursor:pointer;"><?php echo $v['file_name'];?></span></div>
		</div>
	</li>
	<?php } ?>
<?php } ?>
</ul>
	</div>
	<?php } ?>
<?php } ?>
<?php } ?>
    <input type="hidden" name="a" value="set" />
    <input type="hidden" name="app_uniqueid" value="<?php echo $app_uniqueid;?>" />
    <input class="setting_button" id="doset" type="submit" name="s" value="<?php if(!$settings['define']['INITED_APP'] || $settings['define']['INITED_APP'] == 'false'){ ?>开始使用<?php } else { ?>修改配置<?php } ?>" />
</form>
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
			name = '空闲转码服务器';
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