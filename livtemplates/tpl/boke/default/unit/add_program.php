<?php 
/* $Id: add_program.php 524 2011-08-16 02:03:55Z repheal $ */
?>
<div id="idBox" class="lightbox" style="top:10%;left:5%;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="idBoxClose">X</span>{$_lang['add_program']}</h3>
		<div class="text">
		<ul>
			<li style="display:none;">{$_lang['video_name']}<input id="v_name" class="bs" style="width:257px;" disabled type="text"/></li>
			<li>{$_lang['program_name']}<input id="p_name" style="width:495px;height: 24px;" class="bs" type="text"/><span id="p_name_tip" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>
			<li class="clear1"></li>
			<li style="display:none;"><span class="cs">视频简介：</span><textarea id="p_brief" class="bs" rows="2" cols="62"></textarea></li>
			<li><span style="width: 60px; text-align: right;display:inline-block">视频时长：</span><input type="text" class="bs" disabled value="" id="s_time"/></li>
			<li class="program_bt"><input type="button" value="提交" id="add_program_bt"/><input type="button" value="清空" id="reset_program"/></li>
		</ul>
			<input type="hidden" id="v_id" value=""/>
			<input type="hidden" id="s_id" value=""/>
			<input type="hidden" id="v_toff" value=""/>
			<input type="hidden" id="end_time" value="0"/>
				<div id="video_list" class="video_list">
					<ul class="video_title">
						<li class="video_title_now"><a href="javascript:void(0);">我的视频</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(2);">我的收藏</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(3);">搜索</a></li>
					</ul>
					<ul class="video-list">
					{if !$video_info}
						<li>暂无视频<a target="_blank" href="upload.php">上传</a></li>
					{else}
						{foreach $video_info as $key =>$value}
							<li><span>·</span><a href="javascript:void(0);" onclick="add_program({$value['id']},{$sta_id},{$value['toff']})"><?php echo hg_cutchars($value['title'],10,"..");?></a><span id="v_{$value['id']}" style="display:none;">{$value['title']}</span><img src="<?php echo RESOURCE_DIR;?>img/play_bt.jpg"/></li>
						{/foreach}
					{/if}
					</ul>
					{$showpages}
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="lightbox_bottom"></div>
</div>