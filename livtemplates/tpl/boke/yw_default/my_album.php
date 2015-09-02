<?php
/* $Id: my_album.php 87 2011-06-21 07:10:24Z repheal $ */
?>
{template:head}
<div class="main_div vui">
	<div class="right_window con-left">
	<div id="album_info" class="station_content">
	<h3 class="con_top"><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a><a href="javascript:void(0);"onclick="create_album(1);">+创建专辑</a>我的专辑</h3>
	<div class="show_info">
			<div class="album">
				<ul class="album_ul">
					{if $album_info}
						{foreach $album_info as $key => $value}
						<li class="album_li" onmousemove="album_mouse({$value['id']},0)" onmouseout="album_mouse({$value['id']},1)">
							<a href="javascript:void(0);" onclick="manage_album_video({$value['id']});"><img src="{$value['cover']}"/></a>
							<div id="album_na_{$value['id']}" class="album_na">
								<a href="javascript:void(0);" onclick="manage_album_video({$value['id']});"><?php echo hg_cutchars($value['name'],8," ");?>({$value['video_count']})</a>
							</div>
							<div id="album_ma_{$value['id']}" class="album_ma" style="display:none;">
<!--								<a target="_blank" href="<?php echo hg_build_link('user_album_video.php', array('id'=>$value['id'],'user_id'=>$value['user_id']));?>">预览</a>-->
								<a href="javascript:void(0);" onclick="del_album({$value['id']});">删除</a>
								<a href="javascript:void(0);" onclick="edit_album_info({$value['id']});">编辑</a>
							</div>
						</li>
					{/foreach}
					{else}
					<li>
						{code}
							$null_title = "sorry!!!";
							$null_text = "暂未创建专辑";
							$null_type = 1;
							$null_url = $_SERVER['HTTP_REFERER'];
						{/code}
						{template:unit/null}
					</li>
					{/if}
				</ul>
				<div class="clear1"></div>
				{$showpages}
			</div>
	</div>
	<div class="con_bottom clear"></div>
	</div>
	</div>
{template:unit/my_right_menu}
</div>
{template:unit/move_album_video}
{template:foot}