<div class="albums_info">
<?php

if(is_array($albums_info))
{
?>
	<ul class="albums_list"> 
<?php	
	foreach($albums_info as $k => $v)
	{
?>  
	<li class="albums">
		<div class="albums_cover">
			
			<img style="border:5px solid #DAD9D7;padding:2px;" id="album_img_<?php echo $v['albums_id'];?>" src="<?php echo fetch_picture_path($v,PHOTO_SIZE3)?>" />
			
			<div class="albums_title">				
				<a title="<?php echo $v['albums_name']; ?>" href="<?php echo ALBUMS_URL;?>?m=albums&amp;albums_id=<?php echo $v['albums_id'];?>&amp;a=albums_view"  ><?php echo hg_cutchars($v['albums_name'] , 5 , '...');?></a>						
			</div>						
		</div>
	</li>
<?php				
	}
?> 
	</ul>
<?php		
}
else
{
	echo hg_show_null(" ","暂未创建相册",1);
} 

?>
<div class="clear"></div>
</div>