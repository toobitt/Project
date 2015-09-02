<?php 
/* $Id: user.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="garea">
	<div class="photo">
    	<div style="font-size:0px;line-height:0px;"><img src="./res/img/hc_toppic.gif" width="215" height="3" /></div>
        <div class="pdline"><img src="<?php echo $user_info['larger_avatar'];?>" width="192" height="192" style="margin-top:6px;"/></div>
        <div style="font-size:0px;line-height:0"><img src="./res/img/hc_toppic3.gif" width="215" height="10" /></div>
    </div>
    <div class="pho_cot">
    	<div class="pho_hd"><span class="namecs"><?php echo $user_info['username'];?></span><!--已有<span class="txt">113</span>人访问，<span class="txt">517</span>个积分--></div>
        <div class="pho_bk"><span class="txt">最近点滴：</span><?php echo $last_status['text']?hg_cutchars($last_status['text'],40," "):"暂无";?><?php echo hg_get_date($last_status['create_at']);?><!--<span class="pho_hf"><a href="#">回复</a></span>--></div>
        <div class="pho_ar">
        	<div class="pho_1">
            	<ul>
                	<li>性    别：<span class="txt">
                	<?php 
                	switch ($user_info['sex'])
                	{
                		case 0:
                			echo "保密";
                			break;
                		case 1:
                			echo "男";
                			break;
                		case 2:
                			echo "女";
                			break;
                		default:
                			break;
                	}
                	?></span></li>
					<li>所在地盘：<span class="txt">
						<?php 
						if($this->user['id'] == $this->input['user_id'])
						{
						?>
						<a href="<?php echo hg_build_link('geoinfo.php');?>"><?php echo $user_info['group_name']?$user_info['group_name']:'暂无';?></a>
						<?php
						}
						else
						{ 
							echo $user_info['group_name']?$user_info['group_name']:'暂无';
						}
						?>	
					
					</span></li>
				<?php

				$relation1 = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				$relation_count =0;
				foreach($relation1 as $key =>$value)
				{
					if($relation_count>=4){break;}
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
            </div>
            <div class="pho_2">
            	<div>
            		点滴:<span class="txt"><a href="<?php echo hg_build_link(SNS_MBLOG."user.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['status_count'];?></a></span>
            		关注:<span class="txt"><a href="<?php echo hg_build_link(SNS_MBLOG."follow.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['attention_count'];?></a></span>
            		粉丝:<span class="txt"><a href="<?php echo hg_build_link(SNS_MBLOG."fans.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['followers_count'];?></a></span>
            		视频:<span class="txt"><a href="<?php echo hg_build_link(SNS_VIDEO."my_video.php", array('user_id'=>$user_info['id']));?>"><?php echo $user_info['video_count'];?></a></span>
            	</div>
                <div class="pho_st">加入的社区:
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
                				if($i == 1)
                				{?>
                				<li><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><img src="<?php echo $value['logo'];?>" width="48" height="48" /></a><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><?php echo hg_cutchars($value['name'],3," ");?></a></li>
                				<?php 	
                				}
                				if($i>1 && $i<8)
                				{?>
                				<li class="pad"><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><img src="<?php echo $value['logo'];?>" width="48" height="48" /></a><a title="<?php echo $value['name'];?>" href="<?php echo $value['href'];?>"><?php echo hg_cutchars($value['name'],3," ");?></a></li>
                				<?php 	
                				}
                				$i++;
                			}
                		}
                		?>
                    </ul>
                </div>
               <div class="cus_btn"><!--<a href="#"><img src="./res/img/hc_sub1.gif" width="110" height="27" /></a>&nbsp;-->
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
        </div>
    </div>
</div>
<div class="garea">
	<div class="g_larea">
    	<div class="g_are1"></div>
        <div class="g_are2">
        	<ul>
        		<li id="t_2"  style="width:100px;">个人详细资料</li>
        	</ul>
        </div>
        <div class="g_are3"></div>
        <div class="g_are4" id="content">
        <div class="content-left" id="status_list" style="height:620px;">
			<div class="more_profile">
			<ul>
			
			<?php

				$relation1 = array('truename'=>'真实姓名','location'=>'所在地','birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
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
					}
					
				}
			?>
			</ul>
			</div>
		</div>
        </div>
        <div style="font-size: 0pt; line-height: 0pt;"><img src="./res/img/hc_pic_btns.gif"/></div>
    </div>
    <div class="g_rarea">
    	 <div class="g_bre1">关注我的</div>
         <div class="g4_bre2">
        	<?php
        	if(!is_array($fans))
        	{
        		echo hg_show_null(" ", "暂未关注任何人",1);
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
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
        <div class="g_bre1">关注的地盘</div>
  		<div class="g3_bre2">
                  		<?php 
                		if(!is_array($group))
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
      </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
      <div class="g_bre1">我关注的</div>
      <div class="g2_bre2">
            <?php
        	if(!is_array($friends))
        	{
        		echo hg_show_null(" ", "暂无任何人关注你",1);
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
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
         
        
</div>
    </div>
<?php include hg_load_template('forward');?>
<?php include hg_load_template('status_pub');?>   
<?php include hg_load_template('foot');?>
