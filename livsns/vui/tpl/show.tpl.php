<?php
/* $Id: show.tpl.php 3553 2011-04-12 09:12:35Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="vui">
	<div class="con-left">
		<div class="station_top">
		<?php 
			if($sta_id)
			{
			?>
			<a class="get" href="<?php echo hg_build_link("my_station.php");?>">我的频道设置</a>
			<?php 
			}
			else 
			{
			?>
			<a class="set" href="<?php echo hg_build_link("my_station.php");?>"></a>
			<?php             
			}
			?>
			<a class="default" href="javascript:void(0);" onclick="open_create();">创建专辑</a>
			<a class="default" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."upload.php");?>">上传视频</a>
			<span>已创建<b><?php echo $album_total;?></b>个专辑，上传<b><?php echo $user_info['video_count'];?></b>个视频</span>
			
		</div>
		<div class="station_content">
			<div class="con_top">我关注的频道更新</div>
			<div class="pop" id="pop">
				<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
				<div id="pop_s"></div>
			</div>
			<ul class="con_middle">
			<?php 
			if(is_array($con_station))
			{
				$totals = count($con_station);
				$i = 1;
				$num = 0;
				foreach($con_station as $k => $v)
				{
					$num = count($v['programe']);
					if(is_array($v['programe']))
					{		
						$video = array();
						$programe = array();
						foreach($v['programe'] as $key => $value)	
						{
							$programe[$key]['programe_name'] = $value['programe_name'];
							$programe[$key]['id'] = $value['id'];
							$value['video']['programe_name'] = $value['programe_name'];
							$value['video']['programe_id'] = $value['id'];
							$video[] = $value['video'];
						}
						$border = "";
						if($totals != 1 && $i != $totals)
						{
							$border = "border_ok";
						}
						else 
						{
							$border = "border_no";
						}
					?>
					<li class="<?php echo $border;?>">
						<a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$v['user_id']));?>"><img class="station_logo" src="<?php echo $v['small'];?>"/></a>
						<ul class="his_list">
							<?php 
							$url = hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$v['id']));
							?>
							<li><a href="<?php echo $url;?>"><?php echo $v['web_station_name'];?>:</a>
								<?php 
									if(is_array($programe))
									{
//										$num = $total;
										$num = 1;
										foreach($programe as $kp => $vp)
										{?>
										<a style="color:#333;" title="<?php echo $vp['programe_name'];?>" href="<?php echo $url."#".$vp['id']?>"><?php echo hg_cutchars($vp['programe_name'],8,"..");?></a>
										<?php 
										$num ++;
										}
									}
								?>
							</li>
							<li>
							<?php 
								if(is_array($video))
								{?>
									<ul class="pre" id="pre_<?php echo $v['id'];?>">
									<?php 
									
									$j = 1;
										foreach($video as $ks => $vs)
										{
											$salt = hg_rand_num(2);
											?>
										<li>
											<div id="pres_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" style="position: relative;padding:5px 0;width:122px">
												<img src="<?php echo $vs['schematic'];?>"/>
												<a href="javascript:void(0);" onclick="scaleVideo(<?php echo $vs['id']+$vs['programe_id']+$salt;?>,<?php echo $v['id'];?>);">
													<img class="play_bt" src="<?php echo RESOURCE_DIR?>img/feedvideoplay.gif"/>
												</a>
												<input id="vt_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="<?php echo $vs['title'];?>"/>
												<input id="vl_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="<?php echo $vs['streaming_media'];?>"/>
												<input id="vu_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="<?php echo $url."#".$vs['programe_id'];?>"/>
											</div>
										</li>
										<?php 	
										$j++;
										}
									?>
									</ul>
								<?php 	
								}
							?>
								<div id="v_<?php echo $v['id'];?>" style="display:none;"></div>
							</li>
							<li class="clear" style="color:#CBCBCB;font-size:12px;padding: 5px 0;"><?php echo date("Y-m-d H:i:s",$v['update_time']);?></li>
						</ul>
						<div class="clear"></div>
					</li>
				<?php
				$i++;
					}		
				}
			}
			else 
			{
				echo "<li>".hg_show_null(' ','您暂未关注其他频道',1)."</li>";
			}
			?>
			<li><?php echo $showpages;?></li>
			</ul>
			
			<div class="con_bottom clear"></div>
		</div>
	</div>
	<?php include hg_load_template('my_right_menu');?>
	<div class="clear1"></div>
</div>
<?php include hg_load_template('foot');?>