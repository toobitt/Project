<?php 
/* $Id: head_profile.php 10335 2012-08-03 07:39:28Z repheal $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$_mTemplatesTitle}_{$_settings['sitename']}</title>
{$extra_header}
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>style.css?{$_settings['version']}" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>site.css?{$_settings['version']}" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/style.css?{$_settings['version']}"/>-->
<?php 
echo hg_add_head_element('echo'); 
?>
</head>
<body>
<div class="ucenter-con">
<div class="head_top">
	<div class="logo"></div>
    <div class="hright">
    	<div class="hlink">
        	<ul class="link_txt">
            	<li><a href="#">用户空间</a></li>
                <li class="lpd">|</li>
                <li><a href="#">帮助</a></li>
                <li class="lpd">|</li>
                <li><a href="#">收藏本站</a></li>
            </ul>
            <ul class="css_chang">
            	<li class="cs1"></li>
                <li class="cs2"></li>
                <li class="cs3"></li>
                <li class="cs4"></li>
            </ul>
        </div>
        <div class="logins">
        	<div class="login_form">
            	<form action="<?php echo hg_build_link('login.php');?>" method="post">
                	<ul class="formlist">
				        {if !$_user['id']}
						{else}
							<li>{$_lang['tone_a']}<a href="<?php echo hg_build_link('user.php');?>">{$_user['username']}</a></li>
							<li><a href="<?php echo hg_build_link('my_station.php');?>">{$_lang['my']}</a></li>
							<li><a href="<?php echo hg_build_link('upload.php');?>">{$_lang['upload']}</a></li>	
							<li><a href="login.php?a=logout">{$_lang['loginout']}</a></li>
						{/if}
                    </ul>
                </form>
            </div>
            <div class="login_link">
            	<ul>
                	<li><a class="linkbgs" href="#">发视频</a></li>
                    <li><a class="linkbgs" href="#">晒图片</a></li>
                    <li><a class="linkbgs" href="#">看电视</a></li>
                    <li><a class="linkbgs" href="#">听广播</a></li>
                    <li><a class="linkbgs" href="#">说两句</a></li>
                </ul>
            </div>
            <div class="login_link"></div>
        </div>
    </div>
</div>
<div class="nav_bg">
	<div class="nav_menu">
    	<div class="tab_menu">
        	<ul class="tab_btn">
            	<li><a href="#">首页</a></li>
                <li><a href="#">H.Live</a></li>
                <li><a href="#">H.City</a></li>
                <li><a href="#">H.Channel</a></li>
            </ul>
        </div>
        <div class="sea_form">
        	<form action="" method="post">
        	  <input class="sea_name" name="sname" type="text" />
        	  <input class="sea_sub" name="sub" type="button" value="搜索" />
          </form>
        </div>
    </div>
</div>
