<?php
/* $Id: my_program.tpl.php 3386 2011-04-07 01:54:52Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="main_div">
	<div class="right_window">
		<h3><a href="javascript:void(0);" onclick="add_program(0,0,0)">添加</a>
		<?php echo $station['web_station_name']?$station['web_station_name']."的节目单":"频道未创建";	?></h3>
			<div class="show_info">
				<div class="program_show">
					<ul  class="program_list" id="program_list">
					<?php
					$start_time=0;
					if(!is_array($program_info))
					{?>
						<li id="default_list">默认分类</li>
					<?php 
					}
					else {
						$count = count($program_info);
						$i = 1;
						foreach($program_info as $key =>$value)
						{
							$start_time = $value['end_time'];
							$p_id = $value['id'];
							?>
								<li id="p_show_<?php echo $value['id'];?>" onmouseover="edit_del(<?php echo $value['id'];?>,1);" onmouseout="edit_del(<?php echo $value['id'];?>,0);">
									
									<div class="program_time">
									<img src="<?php echo RESOURCE_DIR;?>img/play_default.png"/><?php echo hg_toff_time($value['start_time'],$value['end_time']);?>
									</div>
									<div class="program_name"><a target="_blank" title="<?php echo $value['programe_name'];?>" id="program_name_<?php echo $value['id'];?>"href="<?php echo hg_build_link('station_play.php',  array('sta_id'=>$value['sta_id'],'user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['programe_name'],10," ");?></a></div>
									<div class="program_manage" id="p_<?php echo $value['id'];?>">
										<a href="javascript:void(0);" onclick="program(<?php echo $sta_id;?>,<?php echo $value['id'];?>,1);">编辑</a>
										<a href="javascript:void(0);" onclick="program(<?php echo $sta_id;?>,<?php echo $value['id'];?>,2);">删除</a>
										<?php if($i!=1)
										{?>
											<a href="javascript:void(0);" onclick="program(<?php echo $sta_id;?>,<?php echo $value['id'];?>,3);">上移</a>
										<?php 
										}
										if($i!=$count)
										{?>
											<a href="javascript:void(0);" onclick="program(<?php echo $sta_id;?>,<?php echo $value['id'];?>,4);">下移</a>
										<?php 	
										}
										?>
									</div>
								</li>
								<li id="p_edit_<?php echo $value['id'];?>" style="display:none">
									<ul>
										<li><input id="p_name_<?php echo $value['id'];?>" type="text" value="<?php echo $value['programe_name'];?>"/><span id="p_name_tip_<?php echo $value['id'];?>" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>
										<li><textarea id="p_brief_<?php echo $value['id'];?>" rows="3" cols="21"><?php echo $value['brief'];?></textarea></li>
										<li><input type="button" value="提交" id="eidt_bt_<?php echo $value['id'];?>"/><input type="button" value="取消" onclick="program_back(<?php echo $sta_id;?>,<?php echo $value['id'];?>)"/></li>
									</ul>
								</li>							
						<?php 	
							$i++;
						}
					}
					?>
					</ul>
					
				<input type="hidden" id="start_time" value="<?php echo $start_time;?>"/>
				<input type="hidden" id="p_id" value="<?php echo $p_id;?>"/>
				</div>
				
			</div>
			<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	<?php include hg_load_template('my_right_menu');?>
	</div>
<?php include hg_load_template('add_program');?>
<?php include hg_load_template('tips');?>
<?php include hg_load_template('foot');?>