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
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/vod_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>news/column_form.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column/sort.js"></script>
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
<div id="hg_page_menu" class="head_op_program controll-area"<?php if($_INPUT['infrm']){ ?> style="display:none"<?php } ?>>
</div><script>
(function(){
    var configBaseUrl = './run.php?mid='+ gMid +'&a=';
    window['configUrl'] = {
        ajax : 'route2node.php?nodevar=news_node&fid={{fid}}&mid=' + gMid,
        create : configBaseUrl + 'create',
        update : configBaseUrl + 'update',
        sort : configBaseUrl + 'sort',
        'delete' : configBaseUrl + 'delete'
    };
})();
</script><div class="column-outer-box">
    <span class="column-left-button"><</span>
    <span class="column-right-button">></span>
    <div class="column-bg"></div>
    <div class="column-box">
        <div class="column-inner-box">
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
                <ul class="column-ul">
                    <?php foreach ($sort_list as $kk => $vv){ ?>
                    <li _id="<?php echo $vv['id'];?>" _fid="<?php echo $vv['fid'];?>" id="sort-<?php echo $vv['id'];?>">
                        <a class="column-edit-button" href="#<?php echo $vv['id'];?>" onclick="return false;" title="<?php echo $vv['id'];?>"></a>
                        <?php if(!$vv['is_last']){ ?>
                        <a class="column-next-button" href="javascript:;">&gt;</a>
                        <?php } else { ?>
                        <a class="column-delete-button" href="javascript:;">—</a>
                        <?php } ?>
                        <span class="column-name"><?php echo $vv['name'];?></span>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
                <ul class="column-ul">
                </ul>
            </div>
        </div>
    </div>
</div><textarea id="column-tpl-add" style="display:none;">
    <div class="column-input">
        <div class="column-input-text"><span class="column-input-add"></span><input type="text"/><span class="column-submit">OK</span><button class="column-cancel">NO</button></div>
        <div class="column-input-tip">提交中...</div>
    </div>
</textarea>
<textarea id="column-tpl-edit" style="display:none;">
    <div class="column-edit">
        <div class="column-edit-inner">
            <div class="column-edit-text"><a class="column-edit-button" href="javascript:;"></a><input type="text"/><span class="column-edit-submit">OK</span></div>
            <div class="column-edit-tip">提交中...</div>
        </div>        <div class="column-select-box">
            <div class="column-select-parent">
                <span class="column-select-button">切换父分类</span>
                <span class="column-select-cancel">取消</span>
                <div class="column-parents">
                    <div>原来的分类：<span class="column-old-parents"></span></div>
                    <div>现在的分类：<span class="column-now-parents"></span></div>
                </div>
            </div>
        </div>
    </div>
</textarea>
<textarea id="column-tpl-li" style="display:none;">
    <li _id="{{id}}" _fid="{{fid}}" id="sort-{{id}}">
        <a class="column-edit-button" href="#{{id}}" onclick="return false;" title="{{id}}"></a>
        {{next}}
        {{del}}
        <span class="column-name">{{name}}</span>
    </li>
</textarea>
<textarea id="column-tpl-next" style="display:none;">
    <a class="column-next-button" href="javascript:;">&gt;</a>
</textarea>
<textarea id="column-tpl-delete" style="display:none;">
    <a class="column-delete-button" href="javascript:;">—</a>
</textarea>
<textarea id="column-tpl-child" style="display:none;">
    <div class="column-each">
        <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
        <ul class="column-ul">
            <li><img src="<?php echo $RESOURCE_URL;?>loading2.gif" style="width:20px;"/></li>
        </ul>
    </div>
</textarea>
<?php if((!$_INPUT['infrm'] && !$__top)){ ?>
	<?php if(SCRIPT_NAME != 'login'){ ?>
		<div class="footer <?php echo $hg_name;?>"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span><span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } else { ?>
		<div class="footer login_footer"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span><span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
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
function hg_open_widows(){
	var id = $('#livUpload_div');
	if(id.css('display')=='none')
	{
		$('#livUpload_small_windows').animate({'width':'278px'},function(){
				id.show();
				id.animate({'height':'200px'});
				$('.livUpload_text').addClass('b');
			});
	}
	else{
		id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'200px'},function(){
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
<?php } ?>
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