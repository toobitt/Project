<?php 
/* $Id: head.php 10335 2012-08-03 07:39:28Z repheal $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}_{$_settings['sitename']}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

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