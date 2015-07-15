<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta http-equiv="refresh" content="4;url=<?= $_GET['url']?>"/>
<h3><?=$_GET['error']?></h3>
<?php if(isset($_GET['url']) && !empty(trim($_GET['url']))){?>
页面即将跳转....<br/>
若无反应，请点击<a href="<?= $_GET['url']?>">手动跳转</a>
<?php } ?>
</html>