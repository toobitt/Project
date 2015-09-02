<?php 
/* $Id: head.tpl.php 2158 2011-02-19 09:29:50Z yuna $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->page_title . '_' . $this->settings['sitename'];?></title>
<?php echo $extra_header;?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/style.css?<?php echo $this->settings['version']; ?>"/>
<?php 
hg_add_head_element('js-c',"var now_user = '" . $this->user['username'] . "';" . "\r\t" );
echo hg_add_head_element('echo'); 
?>
<!--[if IE 6]>
<script type="text/javascript" src="../ui/res/scripts/DD_belatedPNG.js"></script> 
<script type="text/javascript">
DD_belatedPNG.fix('.rounded-top');
DD_belatedPNG.fix('.rounded-top-right');
DD_belatedPNG.fix('.top');
DD_belatedPNG.fix('comment-content .top');
DD_belatedPNG.fix('.bottom');
DD_belatedPNG.fix('.rounded-top-right-bottom');
DD_belatedPNG.fix('.rounded-top');
DD_belatedPNG.fix('.lightbox_top');
DD_belatedPNG.fix('.lightbox_middle');
DD_belatedPNG.fix('.lightbox_bottom');
</script>
<![endif]-->
</head>
<body<?php echo $html_body_attr;?>>
<div class="blog">
		<div class="header1">
       <h1><a href="<?php echo hg_build_link('index.php'); ?>"><img src="<?php echo RESOURCE_DIR;?>img/login_head01.gif"/></a></h1>
       <div class="link">
       		<ul class="toplink">
       			<li><a href="#">用户空间</a>|</li>
       			<li><a href="#">帮助</a>|</li>
       			<li><a href="#">收藏本站</a></li>
       			<li class="gray"><a href="#"></a></li>
       			<li class="black"><a href="#"></a></li>
       			<li class="red"><a href="#"></a></li>
       			<li class="sky"><a href="#"></a></li>
       		</ul>
       		<div class="ruzhu">
       			<span class="span-red">

       			</span>
       			 人 入驻
       		</div>
       		<div class="fabu">
       			<span class="span-green">

       			</span>
       			条 信息发布
       		</div>
       </div>
    </div>
    <div id="desktop"></div>