<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $month['title']?>月计划及实时进度</title>
<link href="reset.css" type="text/css" rel="stylesheet" />
<link href="works.css" type="text/css" rel="stylesheet" />
</head>

<?php
$date = $_REQUEST['t'];
if(!$date){
    $date = date('Ym');
}
$cache = './week_cache/' . $date . '.php';
if(is_file($cache)){
    require $cache;
}
?>

<body>
<div class="main week-area">
<h1><?php echo $month['title']?>工作计划及实时进度<span class="tip-mark">(打对号表示已完成)</span></h1>
<div class="week-head m2o-flex m2o-flex-center">
	<div class="week-task">任务内容</div>
	<div class="week-target m2o-flex-one">完成目标</div>
	<div class="week-real-plan  m2o-flex-one">实际完成度</div>
	<div class="week-time">启动时间</div>
	<div class="week-time">计划完成时间</div>
	<div class="week-people">执行人</div>
	<div class="week-descr">备注</div>
</div>
<div class="week-main">
<?php 
if( $month && $month['weeks'] )
{
	foreach( $month['weeks'] as $k => $v ){
		if( $weekrecord[$k] ){
			echo "<div class='week-each-box'>";
				echo "<h3>" .$v."</h3>";
				echo "<ul class='week-list'>";
	
				foreach( $weekrecord[$k] as $kk => $vv ){
					echo "<li class='m2o-flex m2o-flex-center'>";
						if( $vv['done'] ){
							echo "<div class='week-done'>√</div>";
						}
						echo "<div class='week-task'>" .$vv['task']."</div>";
						echo "<div class='week-target m2o-flex-one'>" .$vv['target']."</div>";
						echo "<div class='week-real-plan m2o-flex-one'>" . $vv['real_plan']. "</div>";
						echo "<div class='week-time'>" . $vv['start_time']. "</div>";
						echo "<div class='week-time'>" . $vv['end_time']. "</div>";
						echo "<div class='week-people'>" .$vv['people'] ."</div>";
						echo "<div class='week-descr'>" .$vv['remark'] ."</div>";
					echo "</li>";
				}
				echo "</ul>";
			echo "</div>";	
		}
	}
}
?>
</div>
</div>
<div style="position:fixed;background:#fff;height:38px;right:0;top:0;width:270px;border:1px dashed #ccc;">
	<a class="fast-style" href="http://localhost/livworkbench/index.php" target="_blank">本地m2o</a>
	<a class="fast-style" style="right:140px"  href="http://10.0.1.40/livworkbench/index.php" target="_blank">40m2o</a>
	<a class="fast-style" style="right:210px"  href="works.php" target="_blank">返回</a>
</div>
</body>
</html>