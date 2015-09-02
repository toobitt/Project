<?php 
//轮播图
$slide = array(
	0 => array(
		id => 1,
		url => '../../lib/images/pic1.jpg',
		title => '曝光劳模村一万曝光劳模村一万',
		href => '../../tpl/module/news.php'
	),
	1 => array(
		id => 2,
		url => '../../lib/images/pic2.jpg',
		title => '俄客机坠毁50人遇难 多次试降失败后爆炸',
		href => '../../tpl/tuji/tuji.php'
	),
	2 => array(
		id => 3,
		url => '../../lib/images/pic4.jpg',
		title => '中方回应侦察:即便如此也合法',
		href => '../../tpl/module/news.php'
	),
	3 => array(
		id => 1,
		url => '../../lib/images/pic1.jpg',
		title => '事业单位改革逐步取消行政级别',
		href => '../../tpl/module/news.php'
	),
	4 => array(
		id => 1,
		url => '../../lib/images/pic2.jpg',
		title => '事业单位改革逐步取消行政级别',
		href => '../../tpl/tuji/tuji.php'
	),

	
);
?>
<link rel="stylesheet" href="../../lib/css/slider.css">
<script type="text/javascript" src="../../lib/js/slider.js"></script>
<ul id="slider">
	<?php 
	foreach ( $slide as $k => $v ){
	?>
        <li>
            <a href="<?php echo $v['href']?>">
                <img lazyload="<?php echo $v['url']?>"/>
                <span class="title"><?php echo $v['title']?></span>
            </a>
        </li>
     <?php }?>
</ul>
<script>
$( function(){
	var slider = new Slider('slider');
} );
</script>