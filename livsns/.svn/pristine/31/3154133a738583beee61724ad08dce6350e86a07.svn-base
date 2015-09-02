<?php
/* $Id: blacklist.tpl.php 3069 2011-03-28 02:35:44Z chengqing $ */

?>
<?php include hg_load_template('head');?>

<div class="out-bg">
<ul class="out-me">
	<li class="u-con"><a href="<?php echo MAIN_URL;?>">首页</a></li>
	<li class="u-interval"></li>
	<li class="u-con"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array('user_id'=>$user_info['id']));?>">个人空间</a></li>
	<li class="u-interval"></li>
	<?php 
	foreach($this->settings['umenu'] as $key => $value)
	{
		if(SCRIPTNAME == $key && $value)
		{?>
			<li class="u-con"><a href="#"><?php echo $value;?></a></li>
			<li class="u-interval"></li>
		<?php 	
		}
	}?>
	<li class="u-other"></li>
</ul>
<div class="garea">
	<div class="photo">
        <div class="pdline"><img src="<?php echo $user_info['larger_avatar'];?>" width="192" height="192" style="margin-top:6px;"/></div>
        <a class="u-enter" href="<?php echo hg_build_link(SNS_VIDEO."user.php", array('user_id'=>$user_info['id']));?>">点击进入网台页  >></a>
    </div>
    <ul class="pho_cot">
    	<li><span class="namecs">
    			<?php 
    				echo $user_info['username'];
    				if($is_my_page || $relation == 1 || $relation == 3)
    				{
    					echo $user_info['truename']?' ('.$user_info['truename'].')':'';	
    				}  				
    			?>
    		</span></li>
    	<li class="u-per">
    	<div class="cus_btn"></div>
                <span class="txt">个人主页 ：<?php echo SNS_UCENTER . 'user.php?user_id=' . $user_info['id']; ?></span>
    	</li>
    	<li>
            	<ul class="pho_1">
                	<li>性    别：<span class="txt">
                	<?php

                	echo hg_show_sex($user_info['sex']);
                	      
                	if(is_numeric($user_info['birthday']))
                	{
                		echo ' ('.$this->lang['xingzuo'][$user_info['birthday']].')';
                	}
                	else if($user_info['birthday'])
                	{
                		echo ' ('.$user_info['birthday'].')';
                	}
                	?></span></li>
					<li>所在地盘：<span class="txt">
						<?php 
						if($is_my_page)
						{
						?>
						<a href="<?php echo hg_build_link('geoinfo.php');?>"><?php echo $user_info['group_name']?$user_info['group_name']:'暂无';?></a>
						<?php
						}
						else
						{ 
							echo $user_info['group_name'] ? '<a href="' . hg_build_link(SNS_TOPIC, array('d' => 'group' , 'm' => 'thread' , 'group_id' => $user_info['group_id'])) . '">' . $user_info['group_name'] . '</a>' : '暂无';
						}
						?>	
					
					</span></li>
				<?php

				$relation1 = array('email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				$relation_count =0;
				foreach($relation1 as $key =>$value)
				{
					$temp = $user_info[$key];
					if($temp)
					{
						if(strcmp($key,"birthday")==0 && is_numeric($temp))
						{
							echo "<li>".$value."： <span class='txt'>".$this->lang['xingzuo'][$temp]."</span></li>";
						}
						else
						{
							echo "<li>".$value."： <span class='txt'>".$temp."</span></li>";
						}
						$relation_count ++;
					}
					
				}
			?>
                </ul>
    	</li>
    	<li class="u-menu clear">
    	<span class="u-show1">
    		<a onclick="changeContent(2 , <?php echo $id; ?>);" href="javascript:void(0);" id="status_count"><?php echo $user_info['status_count'];?></a>
    	</span>
    	<span class="u-show2">
    		<a href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['attention_count'];?></a>
    	</span>
    	<span class="u-show3">
    		<a href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['followers_count'];?></a>
    	</span>
    	<span class="u-show4">
    		<a onclick="changeContent(1 , <?php echo $id; ?>);" href="javascript:void(0);"><?php echo $user_info['video_count'];?></a>
    	</span>
            	</li>
    	<li class="u-sites clear"><span class="u-site"><?php if($is_my_page)
					{
						echo '我经常出没的地盘';
					}
					else 
					{
						echo 'TA经常出没的地盘';
					}
                	?></span><span class="u-line"></span><a class="u-more" href="group.php?user_id=<?php echo $user_info['id'];?>">查看全部 >></a></li>
    	<li class="u-list clear">
    		                	
                	<ul>
                		<?php 
                		if(!is_array($group))
                		{?>
                			<li>暂未加入任何社区</li>
                		<?php 	
                		}
                		else 
                		{
                			$i = 1;
                			foreach($group as $key=>$value)
                			{
                				if($i<=10)
                				{
                				?>
                				<li><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><img src="<?php echo $value['logo'];?>" width="48" height="48" /></a><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><?php echo hg_cutchars($value['name'],4," ");?></a></li>
                				<?php 
                				}
                			$i++;
                			}
                		}
                		?>
                    </ul>
    	</li>
    	<li></li>
    	<li></li>
    </ul>
</div>
</div>
<div class="u-bg"></div>

<div class="garea">
	<div class="g_larea">
	    <div class="g_are2">
	        	<ul>
	        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'follow.php' , $user_param);?>">关注</a></li>
	        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'fans.php' , $user_param);?>">粉丝</a></li>
	        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'station.php' , $user_param);?>">频道</a></li>
	            <li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'group.php' , $user_param);?>">地盘</a></li>
	            <?php
				if($is_my_page)
				{
	        	?>
	        	<li class="bt"><a href="<?php echo hg_build_link(SNS_UCENTER . 'blacklist.php' , $user_param);?>">黑名单</a></li>
	        	<?php 
				}
	        	?>
	          </ul>
	    </div>
		<div class="g_are4 clear" id="content">
			<div class="content-left">
				
				
						
				<div class="black_list">
				
				<!-- 记录当前弹出框的ID -->
				<input id="showId" type="hidden" name="showId" value="0" />
				
				<?php  
				if($hava_blocks)
				{
					//print_r($black_list);
					
				?>
					<ul class="black_ul">
					
					<?php 
					foreach($black_list as $k => $v)
					{
					?>
						<li class="clear" id="<?php echo 'deleteBlock_' . $v['id']; ?>" >
												
							<div class="attention clear">					
								<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id']));?>"><img src="<?php echo $v['middle_avatar'] ?>" title="<?php echo $v['screen_name']; ?>" /></a>&nbsp;&nbsp;
								<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id'])); ?>"><?php echo $v['screen_name']; ?></a>
								<span class="black-cr" style="margin-left:20px;font-size:12px;color:gray">
							<?php  echo date("m月  d日 H:i:s" , $v['join_time']); ?>	
							</span>
							
							</div>
							
							
							
							<span class="close-concern">
								<a href="javascript:void(0);"  onclick="moveBlocks(<?php echo $v['id']; ?>)"><?php echo $this->lang['destroy_block']; ?></a>
							</span>
												
							<span id="<?php echo 'showMove_' . $v['id']; ?>" class="black-show">
							</span>
						</li>
						
					<?php 
					}		
					?>
									
					</ul>
				<?php
				}
				else
				{ 	
				?>
		
				<p class="no-result"><?php echo $this->lang['no_blocks'];?></p>
				<p style="line-height:20px;font-size:15px;padding-left:20px;padding-bottom:10px;border-bottom:1px solid #CCC;"><?php echo $this->lang['black_explain'];?></p>	
				<?php
				}
				?>		
				</div>
				
			</div>
		</div>
		
	</div>	

	<div class="g_rarea">
	
		<div class="g_bre1">
		<a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id=<?php echo $this->input['user_id'] ? $this->input['user_id'] : $this->user['id'];  ?>">更多>></a>
		我关注的人
      </div>
      <div class="g2_bre2">
            <?php
        	if(!is_array($user_friends)||!$user_fans)
        	{
        		echo hg_show_null(" ", "暂未关注任何人",1);   
        	}
        	else 
        	{?>
        	<ul>
	        	<?php 
	        	$i = 1;
	        	foreach($user_friends as $key=>$value)
	        	{
	        		if($i%2)
	        		{?>
	        		<li>
	            	  <div class="img_left"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img src="<?php echo $value['middle_avatar'];?>" /></a></div>
	                  <div class="txt_right"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a><span class="gztxt"></span></div>
             		</li>
	        		<?php 	
	        		}
	        		else
	        		{?>
	        		<li class="cus_pad">
	            	  <div class="img_left"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img src="<?php echo $value['middle_avatar'];?>" /></a></div>
	                  <div class="txt_right"><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a><span class="gztxt"></span></div>
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
      </div>
      <div class="g_bre3"></div>
	
	
	
    	 <div class="g_bre1">
    	<a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id=<?php echo $this->input['user_id'] ? $this->input['user_id'] : $this->user['id'];  ?>">更多>></a> 
    	 我的粉丝
    	 </div>
         <div class="g4_bre2">
        	<?php
        	if(!is_array($user_fans)||!$user_fans)
        	{
        		echo hg_show_null(" ", "暂无粉丝",1);
        	}
        	else 
        	{?>
        	<ul>
	        	<?php 
	        	$i = 1;
	        	foreach($user_fans as $key=>$value)
	        	{
	        		if($i ==1)
	        		{?>
	        		<li><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else if($i ==4)
	        		{?>
	        		<li><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else 
	        		{
	        		?>
	        			<li class="cus_pad"><img src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 
	        		}
	        		$i++;	
	        	}
	        	?>
        	 </ul>
        	<?php 
        	}
        	?>
        </div>
        <div class="g_bre3"></div>
        
         <div class="g_bre1">
   <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="station.php?user_id=<?php echo $this->input['user_id'] ? $this->input['user_id'] : $this->user['id'];  ?>">更多>></a>      
         我关注的频道 
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
      </div>
        <div class="g_bre3"></div>
        
      
         
        
	</div>

</div>

<?php include hg_load_template('foot');?>