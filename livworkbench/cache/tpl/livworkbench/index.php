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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/2013/index.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>iframe/option_iframe.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>domcached/domcached-0.1-jquery.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/index.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/authtip.js"></script>
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
<!--
<style>
html,body{height:100%;width:100%;}
#scroll-box{height:100%;width:100%;overflow:auto;}
</style>
-->
<?php 
$dir = $RESOURCE_URL.'menu2013/app/';
$style = '';
$style2x = '';
$checkImgs = array();
$appendMenusClone = array();
$numer = 0;
foreach($menu_apps as $key => $val)
{
    foreach($val as $kk => $vv)
	{
		$appendMenusClone[$vv['app_uniqueid']] = $vv;
        $checkImgs[$vv['app_uniqueid']] = $dir . $vv['app_uniqueid'] . '.png';
        $style .= '.app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai.png);}';
        $style .= "\n";
        $style .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'.png);}';
        $style .= "\n";
        $style .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ':hover .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai.png);}';
        $style .= "\n";
        $style2x .= '.app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai-2x.png);}';
        $style .= "\n";
        $style2x .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-2x.png);}';
        $style .= "\n";
        $style2x .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ':hover .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai-2x.png);}';
        $style .= "\n";
		$numer++;
    }
}
$number = ceil($numer / 16);
$checkImgsLength = count($checkImgs);
$myapps = array();
$custom = trim($_user['app_custom_menus']);
$myapp_bs = $custom ? explode(',', $custom) : array();
$myapp_count = count($myapp_bs);
if($myapp_count){
	foreach($myapp_bs as $val){
		$appendMenusClone[$val] && $myapps[] = $appendMenusClone[$val];
	}
}
$colors = array('0091db', 'e5012c', '59b630', 'ffb40e', 'a747a7', 'cccd19', 'ed4988', '61cccd');
$menuColors = array();
$index = 0;
$max = count($colors) - 1;
foreach($menu_group as $key => $val){
    if($index == $max){
        $index = 0;
    }
    $menuColors[$key] = $colors[$index];
    $index++;
}
 ?>
<script>
var checkImgs = <?php echo json_encode($checkImgs); ?>;
var noImgs = [];
var checkLength = <?php echo $checkImgsLength;?>;
var checkIndex = 0;
$.each(checkImgs, function(i, n){
    var img = new Image();
    img.uniqueid = i;
    img.onload = function(){
        checkIndex++;
        if(checkIndex >= checkLength){
            checkResult();
        }
    };
    img.onerror = function(){
        checkIndex++;
        noImgs.push(this.uniqueid);
        if(checkIndex >= checkLength){
            checkResult();
        }
    };
    img.src = n;
});
var checkResultState = false;
function checkResult(){
    if(checkResultState){
        return;
    }
    checkResultState = true;
    $(function(){
        var style = '';
        var style2x = '';
        $.each(noImgs, function(i, n){
            style += '.app-item.app-' + n + ' .app-item-name{background-image:url(<?php echo $dir;?>default-bai.png) !important;}';
            style += '.index-apps .app-item.app-' + n + ' .app-item-name{background-image:url(<?php echo $dir;?>default.png) !important;}';
            style += '.index-apps .app-item.app-' + n + ':hover .app-item-name{background-image:url(<?php echo $dir;?>default-bai.png) !important;}';
            style2x += '.app-item.app-' + n + ' .app-item-name{background-image:url(<?php echo $dir;?>default-bai-2x.png) !important;}';
            style2x += '.index-apps .app-item.app-' + n + ' .app-item-name{background-image:url(<?php echo $dir;?>default-2x.png) !important;}';
            style2x += '.index-apps .app-item.app-' + n + ':hover .app-item-name{background-image:url(<?php echo $dir;?>default-bai-2x.png) !important;}';
        });
        style += '@media only screen and (-webkit-min-device-pixel-ratio: 2),only screen and (-moz-min-device-pixel-ratio: 2),only screen and (-o-min-device-pixel-ratio: 2/1),only screen and (min-device-pixel-ratio: 2) {';
        style += style2x;
        style += '}';
        $('<style>'+ style +'</style>') .appendTo('body');
    });
}
</script>
<style>
<?php 
echo '.app-item-name{background:url(' . $RESOURCE_URL .'menu2013/app/default.png) no-repeat left center;background-size:30px;}';
echo '.index-apps .app-item-name{background-size:48px;}';
echo $style;
echo '@media only screen and (-webkit-min-device-pixel-ratio: 2),only screen and (-moz-min-device-pixel-ratio: 2),only screen and (-o-min-device-pixel-ratio: 2/1),only screen and (min-device-pixel-ratio: 2) {';
echo '.app-item-name{background-image:url(' . $RESOURCE_URL .'menu2013/app/default-2x.png);background-size:30px;}';
echo '.index-apps .app-item-name{background-image:url(' . $RESOURCE_URL .'menu2013/app/default-2x.png);background-size:48px;}';
echo $style2x;
echo '}';
 ?>
</style>
<?php 
if(0){
echo '<div style="1display:none;position:fixed;right:0px;top:0px;bottom:0px;z-index:100000000;background:#fff;width:200px;overflow:auto;">';
echo '<div><input style="width:100px;" id="find-app-name-uniqueid" placeholder="搜索"/></div>';
echo '<script>
$(function($){
$("#find-app-name-uniqueid").on("change blur", function(){
    var val = $.trim($(this).val());
    if(!val){
        $(".app-name-uniqueid").show();
    }else{
        $(".app-name-uniqueid").filter(function(){
            var check = $(this).data("name").indexOf(val) == -1;
            $(this).show();
            return check;
        }).hide();
    }
});
});
</script>';
foreach($appendMenusClone as $_kk => $_vv){
    echo '<div class="app-name-uniqueid" data-name="' . $_vv['name'] . '">' . $_vv['name'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $_vv['app_uniqueid'] . '</div>';
}
echo '</div>';
}
 ?>
<div id="scroll-box">
<div class="menu-box fixed">
    <div class="menu-top">
        <div class="menu-inner">
            <div class="menu-info">
                <a href="./index.php" class="menu-logo"></a>
                <span class="home-title">新媒体综合运营平台</span>
                <span class="app-title" style="display:none;"></span>
            </div>
            <div class="menu-other">
                <div class="user-box">
                    <a target="mainwin" href="./infocenter.php" class="user-info" _selfname="个人设置">
                        <?php 
                        $avatar = $_user['avatar'];
                        $pic = $avatar['host'] . $avatar['dir'] . '48x48/' . $avatar['filepath'] . $avatar['filename'];
                         ?>
                        <span class="user-pic" style="background-image:url(<?php echo $pic;?>);"></span>
                        <span class="user-name"><?php echo $_user['user_name'];?></span>
                    </a>
                    <ul class="user-option">
						<?php if($_user['group_type'] <= $_settings['max_admin_type']){ ?>
						<li class="appstore-option"><a href="appstore.php" target="formwin">应用商店</a></li>
						<li class="upgrade-option"><a href="upgrade.php">平台更新</a></li>
						<li class="stats-option"><a target="mainwin" href="stats.php" _selfname="运行状态">运行状态</a></li>
						<?php if($_settings['hostmanage']){ ?><li class="stats-option"><a href="<?php echo $_settings['hostmanage'];?>" target="mainwin" _selfname="云主机">云主机</a></li><?php } ?>
						<?php } ?>
                        <li class="infocenter-option"><a target="mainwin" href="./infocenter.php" _selfname="个人设置">个人设置</a></li>
                        <li class="logout-option"><a href="./login.php?a=logout">退出</a></li>
                    </ul>
                </div>
                <a href="#" class="menu-app">应用</a>
                <a href="#" class="menu-home on" style="display:none;">主页</a>
            </div>
        </div>
    </div>
    <div class="app-box hidden">
        <div class="app-inner">
            <div class="app-list app-list-normal">
                <?php foreach ($myapps as $key => $val){ ?>
                    <?php 
                    $href = $val['url'] . ($val['id'] ? '&menuid='.$val['id'] : '');
                     ?>
                    <a target="mainwin" href="<?php echo $href;?>" _color="<?php echo $menuColors[$val['father_id']];?>" class="app-item app-item-<?php echo $menuColors[$val['father_id']];?> app-<?php echo $val['app_uniqueid'];?>" _app="<?php echo $val['app_uniqueid'];?>">
                        <span class="app-item-name"><?php echo $val['name'];?></span>
                        <span class="app-item-del">x</span>
                    </a>
                <?php } ?>
                <?php for ($reduce = 8 - count($myapps); $reduce > 0; $reduce--){ ?>
                    <a href="javascript:;" class="app-item app-kong"></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="app-options">
        <div class="app-cat fenye">
            <span class="app-cat-fenzu" _type="fenzu">分组</span>
            <span class="app-cat-fenye" _type="fenye">分页</span>
        </div>
        <span class="app-set">设置</span>
        <div class="app-tag">
            <ul class="app-tag-fenzu" _type="fenzu">
                <?php foreach ($menu_group as $key => $val){ ?>
                <li _name="<?php echo $val['name'];?>" _index="<?php echo $key;?>"><span class="tag-span"></span><span class="tag-name"><?php echo $val['name'];?></span></li>
                <?php } ?>
            </ul>
            <ul class="app-tag-fenye" _type="fenye">
                <?php for ($index = 1; $index <= $number; $index++){ ?>
                <li _index="<?php echo $index;?>"><span class="tag-span"></span><span class="tag-name"><?php echo $index;?></span></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="brige-box"></div>
<div class="index-box">
    <div class="index-inner">
        <div class="index-apps index-apps-fenye" style="display:none;">
        <?php 
        $index = 1;
        $kai = false;
         ?>
        <?php foreach ($menu_apps as $key => $val){ ?>
        <?php if($val){ ?>
            <?php foreach ($val as $kk => $vv){ ?>
                <?php 
                $currentYe = ceil($index / 16);
                $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                 ?>
                <?php if($index % 16 == 1){ ?>
                    <?php 
                        $kai = true;
                     ?>
                    <div class="app-each" _fenye="<?php echo $currentYe;?>">
                        <span class="app-each-title"><?php echo $currentYe;?></span>
                <?php } ?>
                <?php if(!$vv['disabled']){ ?>
                <a target="mainwin" href="<?php echo $href;?>" _color="<?php echo $menuColors[$key];?>" _app="<?php echo $vv['app_uniqueid'];?>" class="app-item app-item-<?php echo $menuColors[$key];?> app-<?php echo $vv['app_uniqueid'];?>">
                    <span class="app-item-name"><?php echo $vv['name'];?></span>
                </a>
                <?php } else { ?>
                <a href="javascript:;" class="app-item-disabled app-item-<?php echo $menuColors[$key];?> app-<?php echo $vv['app_uniqueid'];?>"><span class="app-item-name"><?php echo $vv['name'];?></span></a>
                <?php } ?>
                <?php if($index % 16 == 0){ ?>
                    <?php 
                        $kai = false;
                     ?>
                    <br/>
                    </div>
                <?php } ?>
                <?php 
                $index++;
                 ?>
            <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php if($kai){ ?>
            <br/>
            </div>
        <?php } ?>
        </div>
        <div class="index-apps index-apps-fenzu" style="display:none;">
        <?php foreach ($menu_apps as $key => $val){ ?>
        <?php if($val){ ?>
            <div class="app-each app-each-<?php echo $menuColors[$key];?>" _fenzu="<?php echo $key;?>">
                <span class="app-each-title"><?php echo $menu_group[$key]['name'];?></span>
                <?php foreach ($val as $kk => $vv){ ?>
                    <?php 
                    $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                     ?>
                    <?php if(!$vv['disabled']){ ?>
                    <a target="mainwin" href="<?php echo $href;?>" _color="<?php echo $menuColors[$key];?>" _app="<?php echo $vv['app_uniqueid'];?>" class="app-item app-item-<?php echo $menuColors[$key];?> app-<?php echo $vv['app_uniqueid'];?>">
                        <span class="app-item-name"><?php echo $vv['name'];?></span>
                    </a>
                    <?php } else { ?>
                    <a href="javascript:;" class="app-item-disabled app-item-<?php echo $menuColors[$key];?> app-<?php echo $vv['app_uniqueid'];?>"><span class="app-item-name"><?php echo $vv['name'];?></span></a>
                    <?php } ?>
                <?php } ?>
                <br/>
            </div>
        <?php } ?>
        <?php } ?>
        </div>
    </div>
</div>
<img src="<?php echo $RESOURCE_URL;?>loading2.gif" id="top-loading"/>
<div class="top-box" style="display:none;opacity:0;">
    <div class="top-nav"></div>
    <div id="livwinarea1" class="top-iframe-box">
        <iframe frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" src="<?php echo $iframe_attr;?>" name="mainwin" id="mainwin" class="top-iframe"></iframe>
    </div>
</div>
</div>
<iframe frameborder="no" scrolling="auto" hidefocus="hidefocus" allowtransparency="true" style="display:none;opacity:0;" name="formwin" id="formwin" class="top-iframe-form"></iframe>
<script>
<?php 
$host = '';
$request = $_SERVER["REQUEST_URI"];
if(strpos($request, 'livworkbench') !== false){
    $host = $host.'/livworkbench';
}
 ?>
var serverHost = '<?php echo $host;?>';
window.featherEditorConfig = {
    imgrecvServer: serverHost +"/canvas/check.php",
    imgrecvBase: serverHost +"/canvas/",
    imgtrackServer: serverHost +"/canvas/check.php",
    jsonp_imgserver: serverHost +"/canvas/getImg.php",
    featherTargetAnnounce: serverHost +"/canvas/feather_target_announce.html",
    featherFilterAnnounce: serverHost +"/canvas/feather_filter_announce.html",
    feather_baseURL: serverHost +"/canvas/"
}
</script>
<script src="./canvas/js/feather.js"></script>
<script>
$(function(){
    window['featherEditor'] = window['featherEditor'] || new Aviary.Feather({
        apiKey: '111',
        apiVersion: 2,
        language : 'zh_hans',
        tools: 'all',
        maxSize : 3000,
        onSave: function(imageID, newURL) {
            $('body').trigger('_picsave', [newURL]);
        }
    });
});
</script>
<?php 
$__top = true;
 ?>
<?php 
foreach ($menu_apps as $apps) {
	foreach($apps as $app) {
		$mid_to_app_uniqueid[$app['module_id']] = $app['app_uniqueid'];
	}
}
 ?>
<script>
(function($){
    $.globalData = (function(){
        var cache = {};
        return {
            set : function(key, value, append){
                if(append){
                    $.extend(cache[key], value);
                }else{
                    cache[key] = value;
                }
            },
            get : function(key){
                return $.type(cache[key]) == 'undefined' ? '' : cache[key];
            },
            destroy : function(key){
                delete cache[key];
            }
        }
    })();
})(jQuery);
$.globalData.set('quanzhong', <?php  echo json_encode($weight ? $weight : array());  ?>);
$.globalData.set('mid_to_app_uniqueid', <?php  echo json_encode($mid_to_app_uniqueid);  ?>);
$.globalData.set('setting_prms', <?php  echo $_user['prms_menus'] ? json_encode($_user['prms_menus']) : '{}';  ?>);
$.globalData.set('MAX_ADMIN_TYPE', <?php echo MAX_ADMIN_TYPE; ?>);
</script>
<!-- ***授权到期*** -->
<?php if($licenseInfo["tip_way"] || ($licenseInfo['expire_time'] && $licenseInfo["leftday"] < 30)){ ?>
<?php if($licenseInfo['expire_time']){ ?>
<?php 
$string = '您当前使用的M2O产品还不是正式授权，到期时间为' . $licenseInfo["expire"] . '，感谢您的使用和建议！';
 ?>
<?php } else { ?>
<?php 
$string = '您当前使用的M2O产品还不是正式授权，感谢您的使用和建议！';
 ?>
<?php } ?>
<script>
$.m2oAuth = {
    type : '<?php echo $licenseInfo["tip_way"];?>' || '<?php echo $_REQUEST["t"];?>' || 'tc',
    duration : '<?php echo $licenseInfo["tip_dur"];?>' || '<?php echo $_REQUEST["d"];?>' || 10,
    string : '<?php echo $string;?>'
};
</script>
<?php } ?>
<?php if($licenseInfo['openhelp']){ ?>
<script type="text/javascript">
var helpWinState = 0;
function hg_openHelp()
{
	if (helpWinState == 0)
	{
		var close = '<span  onclick="hg_openHelp();" style="position: fixed; z-index: 10000002; right: 26px; top: 16px; width: 16px; height: 16px; line-height: 14px; cursor: pointer; border-radius: 2px; color: rgb(255, 255, 255); font-size: 16px; font-weight: bold; text-align: center; background: rgb(186, 38, 38);">x</span>';
		var html = close + '<iframe frameborder="no" hidefocus="hidefocus" allowtransparency="true" src="http://service.liv.cn/?_m2ocode=<?php echo $_m2ocode;?>" name="helpwin" id="helpwin" style="width:100%;height:100%;overflow:hidden;"></iframe>';
		$('#rightpanel').html(html);
		$('#rightpanel').show();
		helpWinState = 2;
	}
	else if(helpWinState == 1)
	{
		$('#rightpanel').show();
		helpWinState = 2;
	}
	else if (helpWinState > 0)
	{
		$('#rightpanel').hide();
		helpWinState = 1;
	}
}
var gTimer, gBTitle = '';
function hg_setTitle(title, san)
{
	top.document.title = title + '<?php echo $this->mTemplatesTitle;?>';
	clearTimeout(gTimer);
	if (san == 1)
	{
		gTimer = setTimeout("hg_setTitle('　　　　　　', 2)", 1000);
	}
	else if (san == 2)
	{
		gTimer = setTimeout("hg_setTitle('" + gBTitle + "', 1)", 1000);
	}
}
function hg_alarmreply(data)
{
	data = eval(data);
	try
	{
		if (data.ErrorCode)
		{
			return;
		}
	}
	catch (e)
	{
	}
	if (data.count.reply > 0)
	{
		if (data.count.reply > 99)
		{
			data.count.reply = '...';
		}
		$('#newhelpreply').html(data.count.reply);
		$('#newhelpreply').show();
		helpWinState = 0;
		gBTitle = '【' + data.count.reply + ' 条新回复】';
		hg_setTitle(gBTitle, 1);
	}
	else
	{
		$('#newhelpreply').html('');
		$('#newhelpreply').hide();
		hg_setTitle('<?php echo $this->mTemplatesTitle;?>', 0);
	}
	setTimeout("hg_check_newreply()", data.timeout);
}
function hg_check_newreply()
{
	var script = document.createElement("script");
	script.id='scriptid1';
	script.src = 'http://service.liv.cn/newreply.php?_m2ocode=<?php echo $_m2ocode;?>&callback=hg_alarmreply';
	document.body.appendChild(script);
	document.getElementById('scriptid1').remove();
}
hg_check_newreply();
</script>
<div id="helpframe" class="m2o-to-top" style="cursor:pointer;display: block;font-size: 14px;bottom: 74px;right: 20px;background: #666;width: 54px;height: 24px;text-align: center;color: #fff;padding-top: 6px;" onclick="hg_openHelp();"><span id="newhelpreply" style="position: fixed; z-index: 10000002; width: 18px; height: 18px; line-height: 16px; border-radius: 9px; color: #fff; font-size: 10px; text-align: center; background: red;right:14px;bottom:94px;display:none">0</span>帮助</div>
<style type="text/css">
		#rightpanel{  display:none;position: fixed;height: 83%;width: 80%;right: 20px;top:10px;border: #ccc solid 1px;background: #ddd;overflow: hidden;padding: 4px;bottom: 106px;z-index: 9999;}
</style>
<div id="rightpanel"></div>
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