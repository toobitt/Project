<?php
require_once('global.php');
$uid  = $_REQUEST['uid'];
if (!$uid) {
	die('用户不存在');
} 
require_once(ROOT_PATH . 'mode/member_mode.php');
require (ROOT_PATH . 'lib/class/material.class.php');
$member_mode = new member_mode(); 
$objMaterial = new material();
$member_info = $member_mode->detail(''," AND uid ='".$uid."'");
$photo = $objMaterial->get_user_img($uid);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta content="telephone=no" name="format-detection" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<link rel="stylesheet" href="css/reset.css" type="text/css" />
<link rel="stylesheet" href="css/common.css" type="text/css" />
<link rel="stylesheet" href="css/awaken.css" type="text/css" />
<script>
document.write('<script src="js/' +
('__proto__' in {} ? 'zepto.min' : 'jquery.min') +
'.js"><\/script>')
</script>
<script type="text/javascript" src="js/banner.js"></script>
<title>叫醒</title>
</head>
<body>
	<div class="wrap awaken">
		<header>
			<h1 title="亲，要我叫醒你吗？">亲，要我叫醒你吗？</h1>
		</header>
		<section class="banner">
			<ul id="banner" class="clear">
			    <?php
			         foreach ((array) $photo as $k => $v) {
			             $url = $v['img_info']['host'] . $v['img_info']['filepath'] . $v['img_info']['filename'];
                         echo '<li><img title="图片" src="'.$url.'"></li>';
			         }
			    ?>
			</ul>
		</section>
		<article class="content">
			<div class="info">
				<h2 title="玲玲"><?php echo $member_info['username'];?><em class="girl">&nbsp;</em><span><?php echo $member_info['score'];?>分</span></h2>
				<span><?php echo $member_info['brief'];?></span>
			</div>
			<div class="data">
				<ul class="clear">
					<li>鲜花：<?php echo $member_info['flowers_num'];?></li>
					<li>拒付：<?php echo $member_info['refuse_pay_num'];?></li>
					<li>拒约：<?php echo $member_info['refuse_appoint_num'];?></li>
					<li>鸡蛋：<?php echo $member_info['eggs_num'];?></li>
					<li>爽约：<?php echo $member_info['fail_appoint_num'];?></li>
				</ul>
			</div>
			<div class="time">
				<ul class="clear">
					<li><label>可约时段：</label><span><?php echo $member_info['service_stime'];?>-<?php echo $member_info['service_etime'];?></span></li>
					<li><label>可约日期：</label>
					    <?php $member_info['service_date'] = explode(',', $member_info['service_date']);?>
					    <?php
					       $date = array('一', '二', '三', '四', '五', '六', '日');
					       foreach ($date as $k => $v) {
					           $k++;
					           if (in_array($k, $member_info['service_date'])) {
					               echo '<span>'.$v.'</span>';
					           }
                               else {
                                   echo '<span class="notime">'.$v.'</span>';
                               }
					       }
					    ?>
					</li>
				</ul>
			</div>
		</article>
		<aside class="call"><a>约我叫醒你</a></aside>	
	</div>
</body>
</html>