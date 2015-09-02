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
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/setting.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/common/common_list.css" />
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
    tab.push('watermark');
	if (id == 'cron')
	{
		$('#doset').hide();
	}
	else
	{
		$('#doset').show();
	}
	for (var i=0; i < 4; i++)
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
				data['callback'] && ( eval( data['callback'] ) );
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
<?php if(DEVELOP_MODE){ ?>
<div style="padding:10px;color:red;font-size:14px">若有基础设置提供给用户，请增加模板tpl/<?php echo $app_uniqueid;?>/default/setting/base.php(此信息仅在开发模式下可见)</div>
<div style="font-size:14px;padding:4px;">模板规则如下</div>
<ul style="padding:4px;">
<li>1. 提供常量配置,配置表单name为define[常量名]，表单的value值为{$settings['define']['常量名']}</li>
<li>2. 提供变量配置,配置表单name为base[变量名]，表单的value值为{$settings['base']['变量名']}，支持二维数组</li>
<li>3. 若对变量修改前做处理，请修改接口<?php echo $app_uniqueid;?>/configuare.php中定义settings_process方法，此方法将input数据处理并覆盖input即可</li>
</ul>
事例如下：
<div style="padding:10px">
<?php echo $example;?>
</div>
<?php } ?>
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
<style type="text/css">
    .sure {position: absolute;bottom: -7px;left: -50px;width: 40px;text-align: center;height: 28px;line-height: 28px;background: #8fa8c6;cursor:pointer;color:#FFFFFF;border-radius:2px;}
</style>
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
			<div class="common-list-item wd80">
                <span style="cursor:pointer;">
                    <span id="space_<?php echo $v['id'];?>" onclick="hg_show_mod_input(<?php echo $v['id'];?>);"><?php echo $v['space'];?></span>
                    <input type="text" size="3" id="space_input_<?php echo $v['id'];?>" value="<?php echo $v['space'];?>" onblur="hg_submit_space(<?php echo $v['id'];?>)" style="display:none;" />s
                </span>
            </div>
			<div class="common-list-item wd150">
                <span style="cursor:pointer; position: relative;" class="cron-next-time">
                    <span class="next_time" _id="<?php echo $v['id'];?>"><?php echo $v['run_time'];?></span>
                    <a class="sure" style="display: none;">更新</a>
                    <input type="text" size="23"  _id="<?php echo $v['id'];?>" class="next_time_input date-picker hasDatepicker" _time="true" _second=true value="<?php echo $v['run_time'];?>" style="display:none;" />
                </span>
            </div>
			<div class="common-list-item wd80"><span style="cursor:pointer;"><a href="settings.php?app_uniqueid=<?php echo $app_uniqueid;?>&id=<?php echo $v['id'];?>&a=chgstate" id="is_use_<?php echo $v['id'];?>" _op="<?php echo $v['op'];?>" onclick="return hg_ajax_post(this,'', 0);" style="color:#8FA8C6;"><?php echo $v['is_use'];?></a></span></div>
		</div>
		<div class="common-list-biaoti biaoti-transition">
			<div class="common-list-item"><span style="cursor:pointer;"><?php echo $v['file_name'];?></span></div>
		</div>
	</li>
	<?php } ?>
<?php } ?>
</ul><script type="text/javascript">$(document).ready(function(){
    $(".cron-next-time .next_time").click(function(){
        $(this).hide();
        $(this).parent().find("a.sure").show();
        $(this).parent().find("input.next_time_input").show().focus();
    });    $('.cron-next-time a.sure').click(function(){
        var time_input = $(this).parent().find("input.next_time_input"),
            time_span = $(this).parent().find("span.next_time");        var next_time = $(time_input).val();
        var old_next_time = $(time_span).html();
        if (next_time != old_next_time) {            var id = $(time_input).attr("_id");
            if (id) {
                $.ajax({
                    type: "GET",
                    url: 'settings.php?app_uniqueid=<?php echo $app_uniqueid;?>&id=' + id + '&a=modify_next_time&next_time=' + next_time + '&ajax=1'
                });
            } else {
                $(this).myTip({
                    string: "数据异常,修改失败"
                });
            }
        }        $(this).hide();
        $(time_input).hide();
        $(time_span).show().html(next_time);
    });
});</script>
	</div>
	<?php } ?>
    <?php if($k == 'watermark'){ ?>
    <div id="sg_<?php echo $k;?>" style="display:none;"><?php $watermark_list = array();
foreach ((array)$watermark['watermark_list'] as $k => $v )
{
    $watermark_list[$v['id']] = $v['config_name'];
}
$watermark_list[-1] = '不使用水印';
$watermark_list[0] = '继承水印设置'; ?>
<ul class="form_ul">
    <li class="i"style="padding-top: 5px;">
        <div class="form_ul_div">
            <span  class="title">水印设置：</span>
            <div style="display:inline-block;width:255px">
<?php if($watermark_list){ ?>
<select name="watermark[watermark_id]"<?php echo $hg_attr['onchange'];?> <?php echo $hg_attr['style'];?>>
<?php foreach ($watermark_list AS $hg_k => $hg_v){ ?>
<?php 
if ($hg_k == $watermark['watermark_id'])
{
	$selected = ' selected="selected"';
}
else
{
	$selected = '';
}
 ?>
<option value="<?php echo $hg_k;?>"<?php echo $selected;?>><?php echo $hg_v;?></option>
<?php } ?>
</select>
<?php } else { ?>
未设定选择数据
<?php } ?></div>
            <font class="important"></font>
        </div>
    </li>
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