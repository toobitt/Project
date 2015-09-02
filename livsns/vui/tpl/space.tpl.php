<?php 
/* $Id: space.tpl.php 4066 2011-06-13 02:06:26Z repheal $ */
?>
<?php include hg_load_template('head');?>
<script type="text/javascript">
<!--
$(document).ready(function(){	
	chang_img = function(id,sta_id,user_id){
		$("#pic_href").attr("href",$("#vs_"+id).attr("href"));
		$("#pic_ct").attr('src',$("#bs_"+id).html());
	}
});
//-->
</script>
<script type="text/javascript">
<!--

$(document).ready(function (){

	//根据参数显示不同的页面
	show_content = function(type)
	{
		switch (type)
		{
			case 'status' : changeContent(2 , <?php echo $id; ?>);break;
			case 'video'  : changeContent(1 , <?php echo $id; ?>);break;
			case 'albums' : changeContent(3 , <?php echo $id; ?>);break;
			case 'thread' : changeContent(4 , <?php echo $id; ?>);break; 
			default: onchange(2 , <?php echo $id; ?>);
		}
	};

	var type = document.location.hash.substring(1);
	
	if(type)
	{
		show_content(type);
	}	
});
//-->
</script>

<?php 
if(is_array($station))
{
?>
<div class="space">
  <div class="pic_top1"></div>
  <div class="pic_cet1">
    <div class="pic_c">
      <div class="pic_ct1">
        <ul>
          <li class="pic_logo"><a title="<?php echo $station['web_station_name'];?>" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"><img src="<?php echo $station['small'];?>" /></a></li>
          <li class="lktt"><a class="s-name" title="<?php echo $station['web_station_name'];?>" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"><?php echo hg_cutchars($station['web_station_name'],8," ");?></a> <a title="<?php echo $station['brief']?$station['brief']:'暂无';?>"><?php echo hg_cutchars($station['brief']?$station['brief']:'暂无',25," ");?></a></li>
          <li class="lkbt" id="collect_<?php echo $station['id']?>">
          <?php 
          if(is_array($program))
          {?>
          <a class="plays" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"></a>
          <?php	
          }
          ?>
          <?php 
          if(!$is_my_page)
          {
	          if(!$station['relation'])
	          {?>
	          	<a class="gz_get" href="javascript:void(0);" onclick="add_concern(<?php echo $station['id']?>,1,<?php echo $id;?>);"></a>
	          <?php 
	          }
	          else 
	          {?>
				<a class="gz_del" href="javascript:void(0);" onclick="del_concern(<?php echo $station['concern_id']?>,<?php echo $station['id'];?>,<?php echo $id;?>,1);"></a>
	          <?php 	
	          }
          }
          ?>
          </li>
        </ul>
        <div class="clear"></div>
      </div>
      <?php 
      	if(is_array($program))
      	{?>
      	<div class="pic_ct2"><a id="pic_href" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$program[0]['sta_id']))."#1";?>" target="_blank"><img id="pic_ct" src="<?php echo $program[0]['video']['bschematic'];?>" width="680" height="274" /></a></div>
	      <div class="pic_ct3">
	        <div class="pic_sub">今日节目单：</div>
	        <div class="pic_cot">
	          <ul>
	          <?php 
	          	$i = 1;
	          	foreach($program as $key=>$value)
	          	{?>
	          	<li onmousemove="chang_img(<?php echo $value['id'];?>,<?php echo $value['sta_id'];?>,<?php echo $value['user_id'];?>)"><div class="list_left"><a id="bs_<?php echo $value['id'];?>" style="display:none;"><?php echo $value['video']['bschematic'];?></a><a title="<?php echo $value['programe_name'];?>" id="vs_<?php echo $value['id'];?>" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$value['sta_id']))."#". $value['id'];?>"><?php echo hg_cutchars($value['programe_name'],10," ");?></a></div><div class="list_right"><?php echo hg_toff_time($value['start_time'],$value['end_time'],0);?></div></li>
	          	<?php 
	          	$i++;	
	          	}
	          ?>
	          </ul>
	        </div>
	      </div>
      	<?php 	
      	}
      	else 
      	{
      		echo hg_show_null(" ", "暂未增加节目单",1);
      	}
      	?>
    </div>
  </div>
  <div class="pic_bot1"></div>
</div>
<?php 
}
?>
<div class="space">
	<div class="g_larea" >
        <div class="g_are2">
        	<ul>
	        	<li class="bt">视频</li>
           </ul>
        </div>
        
        
        <div class="g_are4" id="content">
        	<?php 
	if(!is_array($video)||!$video)
	{
		echo hg_show_null(" ","暂未上传视频",1);
	}
	else 
	{?>
		<ul class="video"><?php 
		foreach($video as $key => $value)
		{
			?>
				<li class="cus_pad"><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="<?php echo $value['title']; ?>" src="<?php echo $value['schematic'];?>" width="122" height="91" /></a><a title="<?php echo $value['title']; ?>" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：<?php echo $value['play_count'];?></span><span class="txt">评论：<?php echo $value['comment_count'];?></span></li>
			<?php
		}
		?>
		</ul>
		<div class="clear"></div>
	<?php 
	}
	echo $showpages;
	?>
        <div class="clear"></div>
        </div>
    </div>
    <!--<div class="g_rarea">
    
    	<div class="g_bre1">台 长</div>
        <div class="g_bre2">
	        	<div class="pic_img">
	        		<a href="<?php echo hg_build_link("user.php", array('user_id'=>$user_info['id']));?>">
	        			<img src="<?php echo $user_info['larger_avatar'];?>" width="127" height="128"/>
	        		</a>
	        		<span class="txt">
	        			<a href="<?php echo hg_build_link("user.php", array('user_id'=>$user_info['id']));?>">
	        			<?php echo $user_info['username'];?>
	        			</a>
	        		</span>
	        	</div>
	                <div class="pic_iis">
	                	<ul>
	                		
	                    	<li><span class="tcolor">性别：</span><?php echo hg_show_sex($user_info['sex']);?></li>
							<li><span class="tcolor">所在地盘：</span>
								<?php
								if($is_my_page)
								{
								?>
								<a href="<?php echo hg_build_link('geoinfo.php');?>"><?php echo $user_info['group_name'] ? $user_info['group_name'] : '未标注';?></a>
								<?php 
								}
								else
								{ 	
									echo $user_info['group_name'] ? '<a href="' . hg_build_link(SNS_TOPIC, array('d' => 'group' , 'm' => 'thread' , 'group_id' => $user_info['group_id'])) . '">' . $user_info['group_name'] . '</a>' : '未标注';
								}
								?>
								
							</li>
							<?php
								$relation1 = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
								foreach($relation1 as $key =>$value)
								{
									$temp = $user_info[$key];
									if($temp)
									{
										if(strcmp($key,"birthday")==0 && is_numeric($temp))
										{
											echo "<li><span class='tcolor'>".$value."</span>： ".$this->lang['xingzuo'][$temp]."</li>";
										}
										else
										{
											echo "<li><span class='tcolor'>".$value."</span>： ".$temp."</li>";
										}
									}
									
								}
							?>
							<li class="urls"><span class="tcolor">个人主页：</span><?php echo SNS_UCENTER . 'user.php?user_id=' . $user_info['id']; ?></li>
	                    </ul>
	                </div>
	                <div class="pic_coss">
	                	<ul>
	                    	<li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;"><?php echo $user_info['attention_count'];?></div><div><a style="color:black;" href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$user_info['id']));?>">关注</a></div></li>
	                        <li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;"><?php echo $user_info['followers_count'];?></div><div><a style="color:black;" href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$user_info['id']));?>">粉丝</a></div></li>
	                        <li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;" id="status_count"><?php echo $user_info['status_count'];?></div><div><a style="color:black;cursor:pointer;" onclick="changeContent(2 , <?php echo $user_info['id']; ?>);">点滴</a></div></li>
	                        <li><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;"><?php echo $user_info['video_count'];?></div><div><a style="color:black;cursor:pointer;" onclick="changeContent(1 , <?php echo $user_info['id']; ?>);">视频</a></div></li>
	                    </ul>
	                </div>
	          <div class="pic_bottn clear"> <a href="#"><img src="./res/img/hc_send1.gif"  /></a>&nbsp;
	          <?php 
	                if(!$is_my_page)
	                {
                		if($relation == 0)    //该用户已在黑名单中
						{
						?>
						<div class="blacklist">
						<span class="follw" id="<?php echo 'add_' . $id ?>">已加入黑名单</span>
						<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock(<?php echo $id; ?>);">解除</a></span>
						</div>
						<?php		
						}
						if($relation == 1)    //源用户和目标用户互相关注
						{
						?>
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id ?>"><a class="mul-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
						</div>
						<?php 		
						}	
						if($relation == 2)    //源用户关注了目标用户
						{
						?>
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id ?>"><a class="been-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
						</div>
						<?php		
						}	
						if($relation == 3 || $relation == 4)	  //目标用户关注了源用户或源用户和目标用户没有关系 
						{
						?>	
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id; ?>"><a class="concern" href="javascript:void(0);" onclick="addFriends(<?php echo $id;?> , <?php echo $relation;?>);"></a></span>
						<span class="close-follw" id="deleteFriend"></span>
						</div>
						<?php 		
						}
	                }?>
	          </div>
        </div>
        
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
        
        <div class="g_bre1">
		<a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="station.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
        <?php
        if($this->user['id'] > 0)
        {
			if($is_my_page)
			{
				echo '我关注的频道';
			}
			else
			{
				echo 'TA关注的频道';
			}
        }
        else
        {
        	echo 'TA关注的频道';
        }  
    	?> 
    	</div>
      <div class="g2_bre2">
       <?php
        	if(!is_array($concern)||!$concern)
        	{
        		echo hg_show_null(" ", "暂未关注任何频道",1);
        	}
        	else 
        	{?>
        	<ul>
	        	<?php 
	        	unset($concern['total']);
	        	$i = 1;
	        	foreach($concern as $key=>$value)
	        	{
	        		if($i<7)
	        		{
		        		if($i%2)
		        		{?>
		        		<li>
		            	  <div class="img_left"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><img src="<?php echo $value['small'];?>" width="46px" height="46px"/></a></div>
		                  <div class="txt_right"><a title="<?php echo $value['web_station_name'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['web_station_name'],4," ");?></a>
		                  <span id="gz_<?php echo $value['id'];?>" class="gztxt"><?php 
		                  if(!$value['relation'])
				          {?>
				        	<a href="javascript:void(0);" onclick="add_concern(<?php echo $value['id']?>,1,<?php echo $value['user_id'];?>);">+关注</a>
				          <?php }
				          else 
				          {?>
				          		已关注
				          <?php 	
				          }
				          ?></span></div>
	              		</li>
		        		<?php 	
		        		}
		        		else
		        		{?>
		        		<li class="cus_pad">
		            	  <div class="img_left"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><img src="<?php echo $value['small'];?>" width="46px" height="46px"/></a></div>
		                  <div class="txt_right"><a title="<?php echo $value['web_station_name'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['web_station_name'],4," ");?></a>
						<span id="gz_<?php echo $value['id'];?>" class="gztxt"><?php 
		                  if(!$value['relation'])
				          {?>
				        	<a href="javascript:void(0);" onclick="add_concern(<?php echo $value['id']?>,1,<?php echo $value['user_id'];?>);">+关注</a>
				          <?php }
				          else 
				          {?>
				          		已关注
				          <?php 	
				          }
				          ?></span></div>
	             		</li>
		        		<?php 
		        		}
	        		}
	        		$i++;	
	        	}
	        	?>
        	 </ul>
        	<?php 
        	}
        	?>
        	<div class="clear"></div>
      </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
         
        <?php

        if($this->user['id'] > 0)
        {
        ?>
        
        <div class="g_bre1">
      <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
      <?php
		if($is_my_page)
		{
			echo '我关注的人';
		}
		else
		{
			echo 'TA关注的人';
		} 
    	?> 
    	</div>
      <div class="g2_bre2">
            <?php
        	if(!is_array($friends)||!$friends)
        	{
        		echo hg_show_null(" ", "暂未关注任何人",1);
        	}
        	else 
        	{?>
        	<ul>
	        	<?php 
	        	$i = 1;
	        	foreach($friends as $key=>$value)
	        	{
	        		if($i%2)
	        		{?>
	        		<li>
	            	  <div class="img_left"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img title="<?php echo $value['username'];?>" src="<?php echo $value['middle_avatar'];?>" /></a></div>
	                  <div class="txt_right"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],3," ");?></a><span class="gztxt"></span></div>
             		</li>
	        		<?php 	
	        		}
	        		else
	        		{?>
	        		<li class="cus_pad">
	            	  <div class="img_left"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img title="<?php echo $value['username'];?>" src="<?php echo $value['middle_avatar'];?>" /></a></div>
	                  <div class="txt_right"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],3," ");?></a><span class="gztxt"></span></div>
             		</li>
	        		<?php 
	        		}
	        		$i++;	
	        	}
	        	?>
        	 </ul>
        	<?php 
        	}
        	?>
        	<div class="clear"></div>
      </div>
      <div class="g_bre3" ><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
      
      <?php
        }
      ?>
        
        
        <?php
		if($this->user['id'] > 0)
		{
        
        ?>
         <div class="g_bre1">
         <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
        	<?php
		if($is_my_page)
		{
			echo '我的粉丝';
		}
		else
		{
			echo 'TA的粉丝';
		} 
    	?>
    	</div>
        <div class="g4_bre2">
            <?php
        	if(!is_array($fans)||!$fans)
        	{
        		echo hg_show_null(" ", "暂无粉丝",1);
        	}
        	else 
        	{?>
        	<ul>
	        	<?php 
	        	$i = 1;
	        	foreach($fans as $key=>$value)
	        	{
	        		if($i ==1)
	        		{?>
	        		<li><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /></a><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else if($i ==4)
	        		{?>
	        		<li><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /></a><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else 
	        		{
	        		?>
	        			<li class="cus_pad"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /></a><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 
	        		}
	        		$i++;	
	        	}
	        	?>
        	 </ul>
        	<?php 
        	}
        	?>
        	<div class="clear"></div>
        </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
        
        <?php 
		}
        ?>
        
        <div class="g_bre1">
        <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="group.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
        <?php
        if($this->user['id'] > 0)
        {
			if($is_my_page)
			{
				echo '我加入的地盘';
			}
			else
			{
				echo 'TA加入的地盘';
			}
        }
        else
        {
        	echo 'TA加入的地盘';
        }  
    	?>
    	</div>
  <div class="g3_bre2">
                  		<?php 
                		if(!is_array($group)||!$group)
                		{
                			echo hg_show_null(" ", "暂未关注地盘",1);	
                		}
                		else 
                		{?>
                		<ul>
                		<?php 
                		$i = 1;
                			foreach($group as $key=>$value)
                			{
                				if($i<9)
                				{
                				?>
								<li> <a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>" ><?php echo hg_cutchars($value['name'],6," ");?></a>   </li>
                			<?php 	
                				}
                				$i++;
                			}
                			?>
                			</ul>
						<?php
                		}
                		?>
                		<div class="clear"></div>
        </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
    </div>
    --></div>
<?php include hg_load_template('foot');?>
