<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title>
<?php echo $this->mHeaderCode;?>
</head>
<body<?php echo $this->mBodyCode;?><?php echo $_scroll_style;?>>
<style>
body{background:#f9f7f8;}
.wrap{width:100%;height:100%;}
.redirect{left:40%;top:40%;position:absolute;<?php if($_INPUT['infrm']){ ?>top:10%;<?php } ?>}
.redirect span{width:77px;height:77px;float:left;background:url(<?php echo $RESOURCE_URL;?>redirect.png);}
.redirect > div{float:left;margin-left:15px;}
.redirect div div:first-child{font-size:28px;line-height:45px;color:#6f6f6f;}
.redirect div div:nth-child(2){font-size:16px;line-height:30px;color:#aaa;}
.redirect a {color:#aaa;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
	.common-list-i{background-image:url(<?php echo $RESOURCE_URL;?>redirect-2x.png);background-size:77px 77px;}
}
</style>
<div class="wrap">
	<div class="redirect">
		<span></span>
		<div>
			<div><?php echo $message;?></div>
			<div><a href="<?php echo $url;?>">正在转向，请稍后...</a></div>
		</div>
	</div>
</div>
<script>
hg_window_destruct = function () {};
<?php if($_INPUT['infrm']){ ?>
try {
	parent.document.documentElement.scrollTop = 0;
	parent.scrollTo(0);
} catch (e) {
}
<?php } ?>
</script>
</body>
</html>