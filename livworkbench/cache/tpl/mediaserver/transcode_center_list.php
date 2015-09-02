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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/edit_video_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/common/common_list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/2013/index.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/2013/iframe.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>mediaserver/transcode_list.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.switchable-2.0.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>switch_widget.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod_opration.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>transcode_center/transcode.js"></script>
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
<?php } ?><?php 
$list = $transcode_center_list;
//print_r($list);
 ?><script>
/*$(function($){
		var transcode_switch=$('.transcode-switch');
		transcode_switch.each(function(){
			$(this).switchButton();
		});
})*/
</script>
<script>
$(function(){
	(function($){
		var search = $('#transcode_info_list_search'),
		    box=$('.key-search');
		search.find('.serach-btn').click(function(){
			var btn = $(this), open;
			open = btn.data('open');
			btn.data('open', !open);
			box[open ? 'removeClass' : 'addClass']('key-search-open');
		});
	})($);
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div class="choice-area" id="transcode_info_list_search" style="position:absolute;top:1px;left:1px;height:43px;">
		<span class="serach-btn"></span>
	<form target="mainwin" name="searchform" id="searchform" action="" method="get">
	    <div class="key-search">
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
	      <input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;">
	    </div>
		<div class="select-search">
			<?php 
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
				);
			 ?>
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
<?php } ?>			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="<?php echo $_INPUT['mid'];?>" />
			<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
			<input type="hidden" name="_id" value="<?php echo $_INPUT['_id'];?>" />
			<input type="hidden" name="_type" value="<?php echo $_INPUT['_type'];?>" />
		</div>
	</form>
	</div><div class="wrap clear">
      <div class="f">
 		<!-- 新增分类面板 开始-->
 		 <div id="add_auth"  class="trans-module">
 		 	<h2><span class="trans-module-close" onclick="hg_closeAuth();"></span><span id="auth_title">新增转码服务器</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form trans-module-body">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 		 <!-- 新增分类面板结束-->
          <div class="v_list_show">
                <form method="post" action="" name="vod_sort_listform">
	                <ul id="auth_form_list" class="transcode-list">
	                   <?php if($list){ ?>
		       			    <?php foreach ($list  as $k => $v){ ?>
		                      <li id="r_<?php echo $v['id'];?>"  name="<?php echo $v['id'];?>"   orderid="<?php echo $v['order_id'];?>">
   <div class="title" onclick="hg_showAddAuth(<?php echo $v['id'];?>);"><?php echo $v['name'];?></div>
   <div class="transcode-con-area">
      <div class="transcode-con-top">
			<div class="info">
			     <span class="info-detail">当前/等待：</span>
			     <span class="current-num" id="cur_num_<?php echo $v['id'];?>"><?php echo $v['cur_num'];?></span>/<span class="wait-num" id="wait_num_<?php echo $v['id'];?>"><?php echo $v['waiting_tasks'];?></span>
			</div>
			<?php if($v['tasks_status']){ ?>
			<div class="switch-vedio-box" data-id="<?php echo $v['id'];?>"  _src="./run.php?mid=473&server_id=<?php echo $v['id'];?>">
					<ul class="transcode-vediolist">
					 <?php foreach ($v['tasks_status'] as $kk => $vv){ ?>
					     <li>
			  				<div class="vedio-name overflow"><?php echo $vv['id'];?></div>
			  				<div class="trans-jdt" style="float:left;margin-top:4px;">
			  					<div style="width:<?php if($vv['transcode_percent'] < 0 ){ ?>0<?php } else { ?><?php echo $vv['transcode_percent'];?><?php } ?>%;" class="trans-progess"></div>
			  				</div>
			  			</li>
					 <?php } ?>
					 </ul>
	  		</div>
	  		<?php } ?>
  		</div>
  		<div class="arrow-controll">
  		     <span class="vedio-prev prevdisabled" id="vedio-prev<?php echo $v['id'];?>"></span>
             <span class="vedio-next" id="vedio-next<?php echo $v['id'];?>"></span>
  		</div>
   </div>
   <div class="common-switch <?php if($v['is_open']){ ?>common-switch-on<?php } ?>">
       <div class="switch-item switch-left" data-number="0"></div>
       <div class="switch-slide"></div>
       <div class="switch-item switch-right" data-number="100"></div>
    </div>
</li>
		                    <?php } ?>
		                <?php } ?>
	                </ul>
	                <div class="add-transcode" onclick="hg_showAddAuth(0);">新增转码服务器</div>
		            <ul class="common-list" style="display:none;">
				     <li class="common-list-bottom clear">
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
<div class="trans-module middle-module">
     <h2><span class="trans-module-close" id="trans-close"></span><span id="trans_title"></span></h2>
     <div class="middle-module-body">
          <iframe name="transFrame" id="transFrame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true"></iframe>
          <img src="<?php echo $RESOURCE_URL;?>loading2.gif" id="top-loading" style="top: 160px; display: none;">
     </div>
</div>
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