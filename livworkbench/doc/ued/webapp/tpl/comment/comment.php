<?php 
$title = '查看评论';
require '../../lib/head/head.php';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
	right => array(
		0 => 'avatar'
	),
);
$list = array(
	0 => array(
			src => '../../lib/images/images_pic2.jpg',
			no => '180****6449',
			time => '2分钟前',
			info => '他们也不容易啊'
	),
	1 => array(
			src => '../../lib/images/images_pic4.jpg',
			no => '156****5637',
			time => '4分钟前',
			info => '在家门口，日本过不来，日本再过来咱就去西湖演习'
	),
	2 => array(
			src => '../../lib/images/pic4.jpg',
			no => '158****1656',
			time => '5分钟前',
			info => '原来演习是用来吓唬老百姓的啊！'
	),
	3 => array(
			src => '../../lib/images/pic1.jpg',
			no => '147****5876',
			time => '12分钟前',
			info => '那是养鱼池，太监军队胆小如鼠欺负老百姓还行，你就在养鱼池里自娱自乐吧反正也是纳税人的钱'
	),
);
require '../../lib/unit/nav.php';
?>
<link href="../../lib/css/comment.css" type="text/css" rel="stylesheet" />
<section class="main-wrap">
	<div class="comment">
		<ul class="list">
		<?php 
		foreach ( $list as $k => $v ){
		require '../../lib/unit/comment_list.php';
		}?>
		</ul>
	</div>
</section>
<?php 
require '../../lib/footer/footer.php';
?>