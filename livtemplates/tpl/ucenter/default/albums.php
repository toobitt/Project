<div class="albums_info">


{if is_array($albums_info)}


	<ul class="albums_list"> 
   {foreach $albums_info as $k => $v}

	<li class="albums">
		<div class="albums_cover">
			
			<img style="border:5px solid #DAD9D7;padding:2px;" id="album_img_{$v['albums_id']}" src="<?php echo fetch_picture_path($v,PHOTO_SIZE3)?>" />
			
			<div class="albums_title">				
				<a title="{$v['albums_name']}" href="<?php echo ALBUMS_URL;?>?m=albums&amp;albums_id={$v['albums_id']}&amp;a=albums_view"  ><?php echo hg_cutchars($v['albums_name'] , 5 , '...');?></a>						
			</div>						
		</div>
	</li>
   {/foreach}
	</ul>
{else}
 {code}
	$null_title = "sorry!!!";
	$null_text = "暂未创建相册";
	$null_type = 1;
	$null_url = $_SERVER['HTTP_REFERER'];
 {/code}
 {template:unit/null}
{/if}

<div class="clear"></div>
</div>