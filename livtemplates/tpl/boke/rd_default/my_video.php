<?php
/* $Id: my_video.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
<script type="text/javascript">
/* 显示删除提示框*/
function show_delete(obj , id)
{	
	var x = getXPos(obj);
	var y = getYPos(obj);

	$('#delete_id').val(id);
	$('#delete_notice').css('left' , x);
	$('#delete_notice').css('top' , y);
	$('#delete_notice').css('display' , 'inline-block');
}

/* 确定删除视频*/
function confirm_delete()
{
	var video_id = $('#delete_id').val();
	$('#delete_notice').css('display' , 'none');
	delete_video(video_id);	
	
}

/* 删除视频*/
function delete_video(video_id)
{	
	var target = '#video_info_' + video_id;
	$.ajax({
		url: "my_video.php",
		type: 'POST',
		dataType: 'html',
		timeout: 5000,
		cache: false,
		data: {a: "delete",
			  id: video_id
		},
		error: function(){
			alert('Ajax request error!');
		},
		success: function(response){
			$(target).remove();
			var info_video_count = 	parseInt($("#liv_info_video_count").html());
			if(info_video_count)
			{
				$("#liv_info_video_count").html(info_video_count - 1);
			}	
			
		}
		});	
}

/* 取消删除*/
function cansel_delete()
{
	$('#delete_notice').css('display' , 'none');	
}

/*获取控件的绝对位置Y*/
function getYPos(obj)
{
	var t=obj.offsetTop;

	while(obj=obj.offsetParent)
	{
		t+=obj.offsetTop;
	}

	return t;
}

/*获取控件的绝对位置X*/
function getXPos(obj)
{
	var l=obj.offsetLeft;

	while(obj=obj.offsetParent)
	{
		l+=obj.offsetLeft;
	}

	return l;
}

function show_edit(id)
{
	var obj_img = '#video_edit_img_' + id;
	var obj_edit = '#video_edit_' + id;

	$(obj_img).hide();
	$(obj_edit).show();

}

function show_img(id)
{
	var obj_img = '#video_edit_img_' + id;
	var obj_edit = '#video_edit_' + id;

	$(obj_img).show();
	$(obj_edit).hide();
}

</script>
<div class="main_div vui">
	 <div class="right_window con-left">
	 <div class="station_content">	
	 	<h3 class="con_top"><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a>我的视频</h3>
	 	<div class="show_info">
		{if $video_info}
			<table style="font-size:12px;">
			<tr class="video_table_head" ><th>视 频</th><th>名称</th><th>状 态</th><th>发布时间</th><th>播放/评论</th><th>时    长</th><th>管理</th></tr>
			{foreach $video_info as $k => $v}
			{code}
				$tong = "";
			{/code}
			{if $v['is_show'] == 2}
			{code}
				$link = hg_build_link(SNS_VIDEO."video_play.php", array('id' =>$v['id']));
				$tar = 'target="_blank"';
				
			{/code}
			{else}
			{code}
				$link = "javascript:void(0);";
				$tar = '';
			{/code}
			{/if}
			<tr height="72px" id="video_info_{$v['id']}" align="center" class="video_list">
				<td><span><img id="video_img_{$v['id']}" style="width:67px;height:50px;display:inline-block;"  src="{$v['schematic']}" title="{$v['title']}" /></span></td>
				<td><a href="{$link}" title="{$v['title']}" {$tar} style="margin-left:10px;" id="video_name_{$v['id']}"><?php echo hg_cutchars($v['title'],6," "); ?></a></td>
				<td>
				{if $v['state'] == 0}
				{code}
					echo '<span style="color:blue;">转码中...</span>';
				{/code}
				{else}
				{code}
					switch($v['is_show'])
						{
							case 0 : echo '<span style="color:blue;">待审核...</span>';break;
							case 1 : echo '<span style="color:red;">未通过审核</span>';break;
							case 2 : echo '<span style="color:green;">已发布</pan>';break;
							case 3 : echo '<span style="color:green;">推荐中</span>';break;
							case 4 : echo '<span style="color:black;">删除审核中...</span>';break;
							default: echo '<span style="color:black;"></span>';
						}
				{/code}
				{/if}
					</td>		
				{if $v['state'] == 0}
					<td>——</td><td>——</td><td>——</td><td><span style="color:silver;">删除 | 预览</span> </td>
				{else}
					<!-- <?php if($v['is_show']<2){?><a href="javascript:void(0);" onclick="edit_video(<?php echo $v['id']; ?>);" >编辑</a> | <?php }?> -->
					<td><?php echo date('Y-m-d' , $v['update_time']);  ?></td>
					<td>{$v['play_count']}/{$v['comment_count']}</td>
					<td><?php echo hg_get_video_toff($v['toff']); ?></td>
					<td>
					<div><span class="delete_button" onclick="show_delete(this , {$v['id']})">删除</span> | <a href="javascript:void(0);" onclick="preview_video({$v['id']});" >预览 </a> 
					</div>
					</td> 
				{/if}
				<td>
					<input id="video_title_{$v['id']}" type="text" style="display:none;" value="{$v['title']}"  />
					<input id="video_copyright_{$v['id']}" type="text" style="display:none;" value="{$v['copyright']}" />
					<input id="video_sort_{$v['id']}" type="text" style="display:none;" value="{$v['sort_id']}" />
					<input id="video_brief_{$v['id']}" type="text" style="display:none;" value="{$v['brief']}" />	
					<input id="video_tags_{$v['id']}" type="text" style="display:none;" value="{$v['tags']}" />
					<input id="video_flv_url_{$v['id']}" type="text" style="display:none;" value="{$v['streaming_media']}" />	
					<input id="video_schematic_{$v['id']}" type="text" style="display:none;" value="{$v['schematic']}" />		
				</td>				
			</tr>
			{/foreach}
			
			</table>
			{$showpages}
		{else}
		{code}
			$null_title = "sorry!!!";
			$null_text = "你还没有上传视频";
			$null_type = 1;
			$null_url = $_SERVER['HTTP_REFERER'];
		{/code}
		{template:unit/null}
		{/if}
		</div>
		<div class="con_bottom clear"></div>
		</div>
	</div>
	{template:unit/my_right_menu}
	<div id="delete_notice" class="delete_notice">
		<p>删除该视频你将消耗两点积分，确定删除该视频吗?</p>
		<a href="javascript:void(0);" onclick="confirm_delete();" class="queding">确定</a>
		<a href="javascript:void(0);" onclick="cansel_delete();" class="quxiao">取消</a>
		<input id="delete_id" style="display:none;" value="" name="delete_id" />	
	</div>
</div>
<input type="hidden" name="sel_gp_name" id="sel_gp_name" value='' /> 
{template:unit/select_group}
{template:unit/edit_video}
{template:unit/preview}
{template:foot}