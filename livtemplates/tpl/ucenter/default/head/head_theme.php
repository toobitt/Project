<?php 
/* $Id: head_theme.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}_{$_settings['sitename']}</title>
{$extra_header}
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
{css:style}
{css:site}
{$this->mHeaderCode}
<!--  
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/base.css?<?php echo $this->settings['version']; ?>"/>
-->
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/style.css?{$_settings['version']}"/>

{if $_user['id'] > 0}


	<script type="text/javascript">
	window.onload = function()
	{  
		//加载页面时自动运行
		getnotify("getnotify");
		//间隔请求
		setInterval("getnotify(\'getnotify\')",30000);
		//setTimeout("getnotify()",100);
	}
	</script>
{/if}
{code}
	echo hg_add_head_element('echo'); 
{/code}
</head>
<body {$html_body_attr} class="img_none">
<script type="text/javascript" src="top.php"></script>
<div class="blog">
<div id = "notify" style="position:relative;clear:both;width:175px; float:right; z-index:9999"><div id='flownotify'></div></div>
	<div class="header">
       <h1><a href="<?php echo hg_build_link('index.php'); ?>"><img src="./res/img/logo.png"/></a></h1>
    </div>
{template:unit/tips}