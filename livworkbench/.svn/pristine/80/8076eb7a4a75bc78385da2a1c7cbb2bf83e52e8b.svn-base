<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>电子报 demo</title>
<link rel="stylesheet" type="text/css" href="../reset.css" />
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery-ui-min.js"></script>
<script src="../js/jquery.tmpl.min.js"></script>
<script src="hotarea_preview.js"></script>
</head>
<?php
$epaper_name = "东方早报";
?>
<body>
<style>
.global-preview{position:fixed;top:0;bottom:0;left:0;width:480px;height:600px;background:#fff;border:1px solid #ccc;}
.hover-box{position:relative;height:100%;}
.hover-box img{position:absolute;width:100%;height:100%;}
.hover-box .items{position:absolute;width:100%;height:100%;}
.hover-box .hover-item{position:absolute;background:transparent;border:2px solid transparent;}
.hover-box .hover-item.show{background:rgba(255,0,0,.3);border-color:#f00;cursor:pointer;}
.hover-box .hover-item .title{display:none;position:absolute;z-index:10;top:30%;left:30%;background:#fff9d8;text-align:center;border-radius:4px;}
.hover-box .hover-item.show .title{display:block;}
.hover-box .hover-item h5{line-height:20px;background:#86b84b;color:#fff;text-align:left;text-indent:20px;}
.hover-box .hover-item h3{line-height:24px;font-size:16px;padding:10px 20px;white-space: nowrap;}
</style>
<div class="global-preview">
	<span class="close-preview"></span>
	<div class="hover-box">
		<img src="http://img.dev.hogesoft.com:233/material/epaper/img/2013/12/20131227093122qF2Y.jpg">
		<div class="items">
			<!-- hotarea -->
		</div>
	</div>
</div>
<script type="text/x-jquery-tmpl" id="hot-item-tpl">
<div class="hover-item" _id="${id}" style="top:{{= top}};left:{{= left}};width:{{= width}};height:{{= height}}">
	<div class="title">
		<h5><?php echo $epaper_name?></h5>
		<h3>{{= title}}</h3>
	</div>
</div>
</script>
</body>
</html>