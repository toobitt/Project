<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>{$this->mTemplatesTitle}_{$_settings['name']}</title>
<link href="./favicon.ico" rel="shortcut icon">
{$this->mHeaderCode}
{csshere}
{css:actionlist}
{css:reset}
{css:common}
{css:youth}

<!--[if IE 6]>
	<link href="{$CSS_FILE_URL}ie6.css" rel="stylesheet" />
<![endif]-->

<!--[if lt IE 9]>
	<script src="{$SCRIPT_URL}html5shiv.js"></script>
<![endif]-->
{jshere}
{js:qingao/jquery}
{css:jquery.alerts}
{js:qingao/jquery.alerts}
{js:qingao/common_qingao}
{js:qingao/huadong_2}
{js:qingao/member_weibo}
</head>
<body class="trend">
	<header class="header">
		<div class="wrap">					
			<h1 class="logo"><a href="http://www1.youth2014.com/" title="青网">青网</a></h1>
			<ul class="mainmenu">
				<li class="mitem1"><a href="http://sns.youth2014.com/#">动态</a></li>
				<li class="mitem2"><a href="http://www1.youth2014.com/activity/">行动</a></li>
				<li class="mitem3"><a href="http://www1.youth2014.com/group/">圈子</a></li>
			</ul>
			<div class="searchs">
				<form action="#"><input type="text" class="search_key" value="成员/圈子/活动/话题" /><input type="submit" value="搜素" class="search_btn" /></form>
			</div>
			{if !$_user['id']}
			<div class="share_box"><!--share_on-->
				<div class="share_inner">
					<a href="#" class="share_sina">微博</a>
					<a href="#" class="share_renren">微博</a>
					<a href="#" class="share_douban">微博</a>
					<a href="#" class="share_qq">微博</a>
				</div>
			</div>
			{/if}
			<div class="member">
				{if $_user['id']}
			<style type="text/css">
              .header .wrap{background:url({$RESOURCE_URL}qingao/my-header.png) no-repeat center top;height:72px;}
              .logo{margin:0 0 0 26px}
              .mainmenu{margin:44px 0 0 12px}
              .searchs{margin:44px 0 0 26px}
              .member{margin:48px 0 0 8px}
            </style>
				<div class="mlogin">
					<a href="member.php?uid={$_user['id']}" class="person_home" title="{$_user['nick_name']}"></a>
					<a href="#" class="person_mail" title="私信"><!--<span>1</span>--></a>
					<a href="#" class="person_feedback" title="公告"><!--<span>2</span>--></a>
					<a href="http://www1.youth2014.com" class="person_mypage" title="首页"><!--<span>3</span>--></a>
					<a href="login.php?a=logout" class="person_logout" title="退出"><!--<span>4</span>--></a>
				</div>
				{else}
				<a href="register.php" class="member_register">注册</a>
				<a href="login.php" class="member_login">登录</a>
				{/if}
			</div>
		</div>
		<div class="headb"></div>
	</header>
	<section class="wrap">
	<div class="gtotal"><span class="gtotal_m">{$member_total}&nbsp;会员</span><span class="gtotal_a">{$action_total}&nbsp;<a href="http://sns.youth2014.com/activitys.php" style="color:#AAAAAF;">行动</a></span><span class="gtotal_g">{$group_total}&nbsp;<a href="http://sns.youth2014.com/groups.php" style="color:#AAAAAF;">圈子</a></span></div>