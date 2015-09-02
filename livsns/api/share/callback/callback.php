<?php

function hg_head()
{
	?>
	
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="user-scalable=no,width=device-width*2,initial-scale=0.5,maximum-scale=0.5,minimum-scale=0.5"/>
<title>账号绑定</title>
<style type="text/css">
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,p,blockquote,th,td,hr{margin:0;padding:0;}
table{font-size:inherit;}
fieldset,img{border:0;}
address,caption,cite,code,dfn,em,th,var{font-style:normal;font-weight:normal;}
ol,ul{list-style:none;}
caption,th{text-align:left;}
h1,h2,h3,h4,h5,h6{font-size:100%;}
q:before,q:after{content:'';}
.clearfix:after{content:".";display:block;font-size:0;line-height:0;height:0;clear:both;visibility:hidden;} 
.clearfix{display:inline-table;}
*html .clearfix{height:1%;}
.clearfix{display:block;}
*+html .clearfix{min-height:1%;}
body{color:#19ace1;font-size:28px;background:url(images/authorize-bg.png);-webkit-user-select:none;-webkit-text-size-adjust:none;-webkit-touch-callout:none;-webkit-tap-highlight-color:rgba(0,0,0,0);text-align:center;text-shadow:0 1px 0 #fff;}
.succeed,.failed,.revoked{background:url(images/authorize-status.png) no-repeat;padding-top:180px;position:relative;margin-top:200px;}
.succeed{background-position:center 0;}
.failed,.revoked{background-position:center -300px;}
</style>
</head>
<body>
	<?php
}
function hg_foot($suc = 1)
{
	?>
	<script type="text/javascript">
		document.location.href = '#func=closeWindow&para=<?php echo $suc;?>';
		setTimeout("window.close();", 3000);
		</script>
</body>
</html>
	<?php
	exit();
}

function hg_succ()
{
	hg_head();
	?>
	<div id="auth-msg" class="succeed">账号绑定成功</div>
	<?php
	hg_foot(1);
}
function hg_fail()
{
	hg_head();
	?>
	<div id="auth-msg" class="failed">账号绑定失败</div>
	<?php
	hg_foot(0);
}
function hg_revoke()
{
	hg_head();
	?>
	<div id="auth-msg" class="revoked">账号解绑成功</div>
	<?php
	hg_foot(2);
}

/**
 * callback
 * 
 * */
require('global.php');
 
session_start();
if ($_REQUEST['a'] == 'cancel')
{
	hg_revoke();
}
if(!$_SESSION['id'] && !$_SESSION['plat'] && !$_SESSION['access_plat_token'])
{
	hg_fail();
}
//进行授权操作
require(ROOT_PATH.'lib/class/curl.class.php');
$curl = new curl($gGlobalConfig['App_share']['host'],$gGlobalConfig['App_share']['dir']); 
$curl->setSubmitType('post');
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('id',$_SESSION['id']);
$curl->addRequestData('plat',$_SESSION['plat']);
$curl->addRequestData('access_plat_token',$_SESSION['access_plat_token']);
$curl->addRequestData('code',$_GET['code']);
$curl->addRequestData('openid',$_GET['openid']);
$curl->addRequestData('openkey',$_GET['openkey']);
$curl->addRequestData('appid',$gGlobalConfig['appid']);
$curl->addRequestData('appkey',$gGlobalConfig['appkey']);
$ret = $curl->request('accesstoken.php');
$auth_user_id = empty($ret[0]['auth_user_id'])?'':$ret[0]['auth_user_id'];
if($_SESSION['refer_url'])
{
	header('Location:'.$_SESSION['refer_url']);
}
?>

<?php 
if(!empty($ret[0]['oauth_suc']))
{
	hg_succ();
}
else
{
	hg_fail();
}

?>
