<?php 
/* $Id: user.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
?>
<?php include hg_load_template('head');?>

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

<span style="float:left;width:18px;text-align:left;margin-right:5px;padding-right: 5px;display:block;"></span>

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
        <a class="u-enter" href="<?php echo SNS_VIDEO;?>">点击进入频道  >></a>
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
    	<div class="cus_btn">
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
			<li id="t_2" onclick="changeContent(2 , <?php echo $id; ?>);" class="bt">点  滴</li>
        	<!--<li id="t_1" onclick="changeContent(1 , <?php echo $id; ?>);" class="bt_d">视  频</li>
        	--><li id="t_3" onclick="changeContent(3 , <?php echo $id; ?>);" class="bt_d">相  册</li>
			<li id="t_4" onclick="changeContent(4 , <?php echo $id; ?>);" class="bt_d">贴  子</li> 
          </ul>
        </div>
        <div class="g_are4" id="content">
	        <div class="content-left" id="status_list">
				<?php
				if (!empty($statusline)&&is_array($statusline))
				{
				?>
					<ul class="mblog">
					<?php
					foreach($statusline as $key => $value)
					{
						$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
						
						$text = hg_verify($value['text']);
						$text_show = $value['text']?$value['text']:$this->lang['forward_null'];
						if($value['reply_status_id'])
						{
							$forward_show = '//@'.$value['user']['username'].' '.$text_show;
							$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
							$status_id = $value['reply_user_id'];
						}
						else
						{
							$forward_show = '';
							$title = $this->lang['forward_one'].$value['text'];
							$status_id = $value['member_id'];
						}
						$text_show = hg_verify($text_show);
						$transmit_info=$value['retweeted_status'];
					?>
						<li class="my-blog" id="mid_<?php echo $value['id'];?>"  onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
							<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
							<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
							<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
							<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
							<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">3</div>
		
		
							<div class="blog-content">
								<p class="subject clear"><a href="<?php echo SNS_UCENTER.$user_url;?>"><?php echo $value['user']['username'];?>：</a>
								<?php echo $text_show."<br/>";?>		
								</p>
								<?php include hg_load_template('statusline_content');?>		
								<div class="speak">
									<div class="hidden" id="t_<?php echo $value['id'];?>"><?php echo hg_verify($title);?></div>
									<div class="hidden" id="f_<?php echo $value['id'];?>"><?php echo $forward_show;?></div>
									<span id = "<?php echo "fa".$value['id']?>" style="position:relative;">
										<?php if($is_my_page && $this->user['id'] > 0)
										{?>
										<a href="javascript:void(0);" onclick="unfshowd(<?php echo $value['id']?>)"><?php echo $this->lang['delete'];?></a>	
										<?php }?>
										<a href="javascript:void(0);" onclick="OpenForward('<?php echo $value['id']?>','<?php echo $status_id;?>')"><?php echo $this->lang['forward'].'('.($value['transmit_count']+$value['reply_count']).')'?></a>|
										<a  id="<?php echo "fal".$value['id']?>" href="javascript:void(0);" onclick="favorites('<?php echo $value['id']?>','<?php echo $this->user['id'];?>')"><?php echo $this->lang['collect'];?></a>|
										<a href="javascript:void(0);" onclick="getCommentList(<?php echo $value['id']?>,<?php echo $this->user['id']?>)"><?php echo $this->lang['comment'];?>(<span id="comm_<?php echo $value['id'];?>"><?php echo $value['comment_count'];?></span>)</a>
									</span>
									<strong><?php echo hg_get_date($value['create_at']);?></strong>
									<strong><?php echo $this->lang['source'].$value['source']?></strong>
										<?php 
					if($this->user['id'])
					{?>
				<a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">
			<?php echo $this->lang['report'];?></a>	
					<?php 	
					}
				?>
								</div> 
								<input type="hidden" name="count_comm" id="cnt_comm_<?php echo $value['id']?>" value="<?php echo $value['comment_count']?>"/>
								<div id="comment_list_<?php echo $value['id'];?>"></div>
							</div> 
							<a href="<?php echo SNS_UCENTER.$user_url;?>">
		<img style="border:1px solid #ccc;padding:1px;" src="<?php echo $value['user']['middle_avatar'];?>"/>
		</a>
						</li>
					<?php 
					}
					?>
				 </ul>
				<?php
				echo $showpages;
				}
				else
				{
					echo hg_show_null(' ',"暂未发布任何点滴！",1);
				}
				?>
			</div>
			<div class="clear"></div>
        </div>
    </div>
    
    
    
    <div class="g_rarea">
    	 
       <?php
		if($this->user['id'] > 0)
		{
       
       ?>     
      <div class="g_bre1">
      <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
      <?php
      
      	if($this->user['id'] > 0)
      	{
      		if($is_my_page)
			{
				echo '我关注的人';
			}
			else
			{
				echo 'TA关注的人';
			} 
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
      <div class="g_bre3" ></div>
      <?php 
		}
      ?>
      
      
      <?php
		if($this->user['id'] > 0)
		{
      ?>
      <div class="g_bre1">
      <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
    	<?php
    	if($this->user['id'] > 0)
    	{
    		if($is_my_page)
			{
				echo '我的粉丝';
			}
			else
			{
				echo 'TA的粉丝';
			} 
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
	        		<li><img title="<?php echo $value['username'];?>" src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else if($i ==4)
	        		{?>
	        		<li><img title="<?php echo $value['username'];?>" src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		<?php 	
	        		}
	        		else 
	        		{
	        		?>
	        			<li class="cus_pad"><img title="<?php echo $value['username'];?>" src="<?php echo $value['middle_avatar'];?>" width="50" height="50" /><a title="<?php echo $value['username'];?>" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
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
        <div class="g_bre3"></div>
        <?php
		}
        ?>
        <div class="g_bre1"> 
        <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="station.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
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
        <div class="g_bre3"></div>
         
        
	</div>
	
	
</div>
<div class="pop" id="pop">
						<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
						<div id="pop_s"></div>
					</div>	
<?php include hg_load_template('forward');?>
<?php include hg_load_template('status_pub');?>   
<?php include hg_load_template('foot');?>
