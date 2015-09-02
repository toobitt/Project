<?php
/* $Id: my_video.tpl.php 3717 2011-04-19 07:37:03Z repheal $ */
?>
<?php include hg_load_template('head');?>
<script type="text/javascript">
// 显示删除提示框
function show_delete(obj , id)
{	
	var x = getXPos(obj);
	var y = getYPos(obj);

	$('#delete_id').val(id);
	$('#delete_notice').css('left' , x);
	$('#delete_notice').css('top' , y);
	$('#delete_notice').css('display' , 'inline-block');
}

// 确定删除视频
function confirm_delete()
{
	var video_id = $('#delete_id').val();
	$('#delete_notice').css('display' , 'none');
	delete_video(video_id);	
	
}

// 删除视频
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

// 取消删除
function cansel_delete()
{
	$('#delete_notice').css('display' , 'none');	
}

//获取控件的绝对位置Y
function getYPos(obj)
{
	var t=obj.offsetTop;

	while(obj=obj.offsetParent)
	{
		t+=obj.offsetTop;
	}

	return t;
}

//获取控件的绝对位置X
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
<?php include hg_load_template('edit_video');?>
<?php include hg_load_template('preview');?>
<?php include hg_load_template('tips');?>
<div class="main_div">
	 <div class="right_window">
	 	<h3><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a>我的视频</h3>
	 	<div class="show_info">
		<?php		
		if($video_info)
		{			
			?>
			<table style="margin-top:10px;font-size:12px;">
			<tr class="video_table_head" ><th>视 频</th><th>名称</th><th>状 态</th><th>发布时间</th><th>播放/评论</th><th>时    长</th><th>管理</th></tr>
			<?php
			foreach($video_info as $k => $v)
			{
				$tong = "";
				if($v['is_show'] == 2)
				{
					$link = hg_build_link(SNS_VIDEO."video_play.php", array('id' =>$v['id']));
					$tar = 'target="_blank"';
					
					$words = "已分享";
					$action ="";
					if(!$v['is_thread'])
					{
						$words = "分享";
						$action = ' onclick = "showMyGroups('.$v['id'].')"';
					}
					$tong = '| <a title="分享到讨论区" href="javascript:void(0);" id="thr_'.$v['id'].'" '.$action.' >'.$words.'</a>';
				}
				else 
				{
					$link = "javascript:void(0);";
					$tar = '';
				}
			?>
			<tr height="72px" id="video_info_<?php echo $v['id'];?>" align="center" class="video_list">
				<td><span><img id="video_img_<?php echo $v['id']; ?>" style="width:67px;height:50px;display:inline-block;"  src="<?php echo $v['schematic']; ?>" title="<?php echo $v['title']; ?>" /></span></td>
				<td><a href="<?php echo $link;?>" title="<?php echo $v['title']; ?>" <?php echo $tar;?> style="margin-left:10px;" id="video_name_<?php echo $v['id'];?>"><?php echo hg_cutchars($v['title'],6," "); ?></a></td>
				<td><?php 
						if($v['state'] == 0)
						{
							echo '<span style="color:blue;">转码中...</span>';
						}
						else
						{

							
							switch($v['is_show'])
							{
								case 0 : echo '<span style="color:blue;">待审核...</span>';break;
								case 1 : echo '<span style="color:red;">未通过审核</span>';break;
								case 2 : echo '<span style="color:green;">已发布</pan>';break;
								case 3 : echo '<span style="color:green;">推荐中</span>';break;
								case 4 : echo '<span style="color:black;">删除审核中...</span>';break;
								default: echo '<span style="color:black;"></span>';
							}															
						} 
					?>
				</td>				
				<?php
				if($v['state'] == 0)
				{
			    ?>
			    <td>——</td><td>——</td><td>——</td><td><span style="color:silver;">删除 | 预览</span> </td>
			    <?php 
				}
				else
				{
				?>
				<!-- <?php if($v['is_show']<2){?><a href="javascript:void(0);" onclick="edit_video(<?php echo $v['id']; ?>);" >编辑</a> | <?php }?> -->
				<td><?php echo date('Y-m-d' , $v['update_time']);  ?></td>
				<td><?php echo $v['play_count']; ?>/<?php echo $v['comment_count']; ?></td>
				<td><?php echo hg_get_video_toff($v['toff']); ?></td>
				<td>
				
					<div style="padding:0 5px;"><span class="delete_button" onclick="show_delete(this , <?php echo $v['id'];?>)">删除</span> | <a href="javascript:void(0);" onclick="preview_video(<?php echo $v['id']; ?>);" >预览 </a> 
					<?php echo $tong;?>
					</div>
				</td> 
				<?php							
				}  
				?>
				<td>
					<input id="video_title_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['title']; ?>"  />
					<input id="video_copyright_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['copyright']; ?>" />
					<input id="video_sort_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['sort_id'];?>" />
					<input id="video_brief_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['brief']; ?>" />	
					<input id="video_tags_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['tags']; ?>" />
					<input id="video_flv_url_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['streaming_media']; ?>" />	
					<input id="video_schematic_<?php echo $v['id']; ?>" type="text" style="display:none;" value="<?php echo $v['schematic']; ?>" />		
				</td>				
			</tr>			
			<?php				
			}			
			?>
			</table>
			<?php echo $showpages; ?>
			<?php
		}
		else
		{
			echo hg_show_null(" ", "你还没有上传视频!",1);
		} 
		?>
		</div>
		<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	<?php include hg_load_template('my_right_menu');?>
	<div id="delete_notice" class="delete_notice">
		<p>删除该视频你将消耗两点积分，确定删除该视频吗?</p>
		<a href="javascript:void(0);" onclick="confirm_delete();" class="queding">确定</a>
		<a href="javascript:void(0);" onclick="cansel_delete();" class="quxiao">取消</a>
		<input id="delete_id" style="display:none;" value="" name="delete_id" />	
	</div>
</div>
<input type="hidden" name="sel_gp_name" id="sel_gp_name" value='' /> 
<?php include hg_load_template('select_group');?>
<?php include hg_load_template('foot');?>