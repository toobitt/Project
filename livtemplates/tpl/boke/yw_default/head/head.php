<?php 
/* $Id: head.tpl.php 3537 2011-04-11 11:28:07Z yuna $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}_{$_settings['sitename']}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
{css:style}
{css:tv}
{$this->mHeaderCode}
</head>
<body{$this->mBodyCode}>

<div class="header">
	<h1><a href="http://www.ywcity.cn/">义乌网络电视台</a></h1>
	<div class="head">
		<div class="menu">
		   <ul id="tv_menu">
				<li class="home"><a href="http://www.ywcity.cn/">义乌网首页</a></li>
				<li class="live"><a href="http://v.ywcity.cn/live/">直播台</a></li>
				<li class="hudo"><a href="http://v.ywcity.cn/vblog/">互动台</a>
					<ul>
						<li><a href="http://v.ywcity.cn/vblog/folder134/">热点视频</a></li>
						<li><a href="http://v.ywcity.cn/vblog/folder135/">义乌视界</a></li>
						<li><a href="http://v.ywcity.cn/vblog/folder136/">爱音乐</a></li>
						<li><a href="http://v.ywcity.cn/vblog/folder137/">爱娱乐</a></li>
						<li><a href="http://v.ywcity.cn/vblog/folder138/">爱影视</a></li>
						<li><a href="http://v.ywcity.cn/vblog/folder139/">爱新闻</a></li>
					</ul>
				</li>
				<li class="aipai"><a href="http://v.ywcity.cn/apai/">爱拍台</a>
					<ul>
						<li><a href="http://v.ywcity.cn/apai/folder129/">精品图片</a></li>
						<li><a href="http://v.ywcity.cn/apai/folder130/">百姓风采</a></li>
						<li><a href="http://v.ywcity.cn/apai/folder140/">图说天下</a></li>
						<li><a href="http://v.ywcity.cn/apai/folder131/">趣闻趣事</a></li>                               
						<li><a href="http://v.ywcity.cn/apai/folder132/">义乌风尚</a></li>
					</ul>
				</li>
			</ul>               
		</div>
			<!--[if IE 6]>
					<script type="text/javascript" src="/res/template/images/hover.js"></script>
					<script type="text/javascript">
						hover("tv_menu");
					</script>
			<![endif]-->
		<div class="search">
				<form action="/search.php" method="post" id="form">
				  <input class="sea_name" name="k" type="text" autocomplete="off" value="{$name}" />
				  <input class="srh_btn" name="sub" type="submit" value="搜索" />
			  </form>
		</div>
	</div>
</div>
