<?php 
/* $Id: head.tpl.php 3537 2011-04-11 11:28:07Z yuna $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}_{if $play}葫芦网{else}{$_settings['sitename']}{/if}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
{if $gKeywords}<meta name="Keywords" content="{$gKeywords}" />{/if}
{if $gDescription}<meta name="Description" content="{$gDescription}" />{/if}
<link href="http://www.hoolo.tv/res/hulusearch/images/newsmallhead2.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
<script type="text/javascript" src="http://www.hoolo.tv/res/zhibo/images/png.js"></script>
<script>
  DD_belatedPNG.fix('#YSel dt,.playcon .nor,.playcon .nor,.playcon a.nor:link,.playcon a.nor:visited');
</script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>site.css" />
{$this->mHeaderCode}
</head>
<body{$_mBodyCode}>
<script type="text/javascript" src="<?php echo SNS_MBLOG;?>top.php"></script>
<script src="http://www.hoolo.tv/js/a23d5c659ecf68023987bdc9271d7f1d.php" type="text/javascript"></script>

<!--[if IE 6]><script type="text/javascript">hover("newhd");</script><![endif]-->
<!--[if IE 6]><script type="text/javascript">hover("nav");</script><![endif]-->
{if $head_line}
<div class="menus">
	<ul class="menu_ul">
		<li class="prefix"></li>	
		{foreach $head_line as $key => $value}
			{if !$value['last']}
				<li class="default"><a href="{$value['url']}">{$value['name']}</a></li>
				<li class="interval"></li>
			{else}
				<li class="default">{$value['name']}</li>
			{/if}
		{/foreach}
		<li class="suffix"></li>
	</ul>
</div>
{/if}