<!-- 
$magazine_foot = array(
	left => array(
		0 => 'back',
	),
	right => arrar()
);

>>>	lib/images/magazine/footicon
-->
<?php
function getFootSrc( $flag ){
	switch ($flag){
		case 'back' : 
			echo 'javascript:history.go(-1)';
			break;
			
	}
}
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/foot.css" />
<footer class="magazine-foot">
	<?php foreach( $magazine_foot as $k=>$v ){?>
	<aside class="magazine-foot-<?php echo $k?>">
		<?php foreach( $v as $kk=>$vv ){?>
			<a class="magazine-foot-icon <?php echo $vv?>"  href="<?php getFootSrc($vv) ?>"></a>
		<?php }?>
	</aside>
	<?php }?>
</footer>