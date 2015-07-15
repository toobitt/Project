<!DOCTYPE html>
<?php
if(!defined('BASE_URL'))
{
    define('BASE_URL', 'http://sign.com');
    define('BASE_URI', 'http://sign.com/index.php');
}
?>
<html>
<head>
<meta charset="utf-8">
<meta title="错误提示">
<?php if($_GET['url']){ ?>
<meta http-equiv="refresh" content="1;url=<?= $_GET['url']?>"/>
<?php } ?>
<style>
    *{margin:0px;padding:0px}
    .main{width:800px;height:204px;border:2px solid lightslategrey;margin-top:10%;}
    .headMessage{width:796px;height:40px;line-height:40px;font-weight:bold}
    .info{font-size:19px;float:left;padding:10px 10px}
    .head{width:796px;height:90px;text-align:center;line-height:90px}
    .message{width:796px;height:70px;}
</style>
<link rel="stylesheet" href="<?=BASE_URL?>/public/bootstrap/dist/css/bootstrap.css">
<script src="<?=BASE_URL?>/public/bootstrap/dist/js/jQuery.js"></script>
<script src="<?=BASE_URL?>/public/bootstrap/dist/js/bootstrap.js"></script>
</head>
<body>
    <center>
        <div class="main">
            <div class="headMessage bg-primary"><span class="info glyphicon glyphicon-info-sign"></span>提示信息</div>
            <div class="head"><?=$_GET['error']?></div>
            <div class="message">
                <?php if(isset($_GET['url']) && !empty(trim($_GET['url']))){?>
                    页面即将跳转....<br/>
                    若无反应,请点击<a href="<?= $_GET['url']?>" style="cursor:pointer">手动跳转</a>
                <?php } ?>
            </div>
        </div>
    </center>
</body>
</html>