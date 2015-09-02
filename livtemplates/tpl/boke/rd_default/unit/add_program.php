<?php 
/* $Id: add_program.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
<style type="text/css">
	#idBoxClose{cursor:pointer;float:right;}
	.lightbox{width:392px;height:auto;display:none;margin:0 auto;width:700px;padding:10px}
	.lightbox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold}
	.lightbox .text{padding: 10px 25px 20px 45px;margin:0;background:#fff;color:#7e7e7e;height:250px;width:614px;}
	.lightbox .text ul{height:200px;*height:170px;}
	.lightbox .text ul li{margin-bottom:7px;color:#000;}
	.lightbox .text ul li.program_bt{text-align:center;}
	.lightbox .text ul li.program_bt input{margin-right: 10px;}
	.lightbox .text .video_list{width: 590px;float:left;}
	.lightbox .text .video_list h4 {padding-left: 50px;}
	.lightbox .text .video_list .video-list{width:594px; height:auto;}
	.lightbox .text .video_list .video-list li{width:173px;float:left;color:#0265ca;padding: 8px 0 0 5px;}
	.lightbox .text .video_list .video-list li a{color:#0265ca;}
	.lightbox .text .video_list .video-list li img{padding-left:4px;}
	#p_brief{*width:525px;}
	.lightbox .text .video_list .video_title{width:594px;height: 31px;background:url("./res/img/program_add_bg.jpg") no-repeat scroll 0 0 transparent;font-size:14px;float:left;}
	.lightbox .text .video_list .video_title li{ width: 71px;margin-top: 3px;float:left;padding-top: 7px;text-align:center;line-height:18px;}
	.lightbox .text .video_list .video_title li.video_title_now{margin-left: 10px;margin-top: 3px;width: 81px;height: 28px;float:left;padding-top: 7px;background:url("./res/img/program_add_now.jpg") no-repeat scroll 0 0 transparent;}
	
	.lightbox_top{background:url(./res/img/Rounded.png) 0 -266px no-repeat;height:16px;font-size:0}
	.lightbox_middle{padding:0 8px;background:url(./res/img/zf_bg.png) repeat-y;width:auto}
	.lightbox_bottom{background:url(./res/img/Rounded.png) 0 -283px no-repeat;height:16px;font-size:0}
	.box{width:490px;height:auto;background:#FFFFFF;border:5px solid #ccc;display:none; margin:0;}
	.box dt{background:#f4f4f4;padding:5px;}
	.box dd{padding:20px; margin:0;}
	.box input{width:100px;height:30px;font-size:16px;margin-left:340px;}
	
	.lightbox .text .video_list .pagelink .pages{height:auto;}
</style>
<div id="idBox" class="lightbox" style="top:10%;left:5%;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="idBoxClose">X</span>{$_lang['add_program']}</h3>
		<div class="text">
		<ul>
			<li style="float:left;">{$_lang['video_name']}<input id="v_name" style="width:257px;" disabled type="text"/></li>
			<li style="float:left;padding-left: 36px;">{$_lang['program_name']}<input id="p_name" style="width:165px;" type="text"/><span id="p_name_tip" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>
			<li class="clear1"></li>
			<li>{$_lang['program_brief']}<textarea id="p_brief" rows="3" cols="62"></textarea></li>
			<li><span style="width: 60px; text-align: right;display:inline-block">时长：</span><a href="javascript:void(0);" id="s_time"></a></li>
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