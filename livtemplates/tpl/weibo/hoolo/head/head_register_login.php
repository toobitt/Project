<?php 
/* $Id: head_register_login.php 10335 2012-08-03 07:39:28Z repheal $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}_{$_settings['sitename']}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<!--  
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/base.css?<?php echo $this->settings['version']; ?>"/>
-->
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>style.css" />

{if $_user['id'] > 0}
	{code}
		echo '
		<script type="text/javascript">
		window.onload = function()
		{  
			/*加载页面时自动运行*/
			getnotify("getnotify");
			/*间隔请求*/
			setInterval("getnotify(\'getnotify\')",30000);
			/*setTimeout("getnotify()",100);*/
		}
		</script>';
	{/code}
{/if}
{$this->mHeaderCode}
</head>
<body {$_mBodyCode} class="img_h">
<script type="text/javascript" src="top.php"></script>
<div class="registering">
<div id = "notify" style="position:relative;clear:both;width:175px; float:right; z-index:9999"><div id='flownotify'></div></div>
	<div class="header">
       <h1><a href="{code} echo hg_build_link('index.php'); {/code}"><img src="./res/img/logo.jpg"/></a></h1>
    </div>