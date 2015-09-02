<?php 
/* $Id: upload.tpl.php 1867 2011-01-25 09:09:36Z chengqing $ */
?>
<?php include hg_load_template('head');?>
<div class="gareas clear">
  <div class="gtop" onmouseover="report_show(<?php echo $single_video_info['id'];?>,<?php echo $single_video_info['user']['id'];?>);" onmouseout="report_hide(<?php echo $single_video_info['id'];?>,<?php echo $single_video_info['user']['id'];?>);">
  			<div style="display:none;" id="cons_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>">上传的视频《<?php echo $single_video_info['title'];?>》</div>
			<div style="display:none;" id="ava_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>"><?php echo $single_video_info['user']['middle_avatar'];?></div>
			<div style="display:none;" id="user_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>"><?php echo $single_video_info['user']['username'];?></div>
			<div style="display:none;" id="url_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>"><?php echo SNS_VIDEO.'video_play.php?id='.$single_video_info['id'];?></div>
			<div style="display:none;" id="type_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>">2</div>	
  	<div class="lc">
  	<span class="subleft"><a href="<?php echo $this->nav['main']['index'];?>"><?php echo $this->nav['lang']['index'];?></a></span><span class="subcolor">&gt;</span>
  	<span class="subleft"><a href="<?php echo $this->nav['main']['channel'];?>"><?php echo $this->nav['lang']['channel'];?></a></span><span class="subcolor">&gt;</span>
  	<?php
	if($vip_url)
	{
	?>
	<span class="subleft"><a href="<?php echo $vip_url;?>"><?php echo $username;?></a></span><span class="subcolor">&gt;</span>
	<?php 	
	}
	else
	{
	?> 
	<span class="subleft"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$user_id."#video"));?>"><?php echo $username;?></a></span><span class="subcolor">&gt;</span>
	<?php 	
	} 
  	?>  	  	
  	<span class="subsub" id="vedio_name_container"><?php echo $single_video_info['title'];?></span></div>
  	<input type="hidden" id="video_briefs" value="<?php echo $single_video_info['brief'];?>"/>
    <div class="rc">
  <?php if($this->user['id']){?><span class="rsubcolor2" onclick="report_play(<?php echo $single_video_info['id'];?>,<?php echo $single_video_info['user']['id'];?>);" style="display:none;cursor:pointer;" id="re_<?php echo $single_video_info['id'];?>_<?php echo $single_video_info['user']['id'];?>"><?php echo $this->lang['report'];?></span><?php }?>  
    播放<span class="rsubcolor"><?php echo $single_video_info['play_count']?$single_video_info['play_count']:1;?></span>次<span class="subline">|</span>评论<span class="rsubcolor2"><?php echo $total_nums?$total_nums:0;?></span>次</div>
  </div>
  
   <?php 
  if($single_video_info)
  {?>
  <div class="gtv">
   <div class="gtg">
  </div>
    <div class="tv">
      <div class="tv_video">
			<div id="container"><div id="player">加载中</div></div>
	</div>
    </div>
    <div class="tv_vidtt">
        <div class="tv1">
            	<div><img src="<?php echo RESOURCE_DIR;?>img/adico1.gif"  /></div>
                <div id="status_share"><a href="javascript:void(0);" onclick="show_status_share(<?php echo $single_video_info['id'];?>);">分享到点滴</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico2.gif" /></div>
                <div id="video_collect">
			        <?php 
			        if($single_video_info['relation'])
			        {?>
			        <a href="javascript:void(0);">已收藏</a>
			        <?php
			        }
			        else 
			        {?>
			        <a href="javascript:void(0);" onclick="add_collect(<?php echo $single_video_info['id'];?>,0,<?php echo $this->user['id'];?>);">收藏</a>
			        <?php
			        }
			        ?>
                </div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico3.gif"  /></div>
                <div id="program_bt"><a href="javascript:void(0);" onclick="add_program_out(<?php echo $video_id;?>,<?php echo $n_sta_id;?>,<?php echo $toff;?>)">选入编单</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico4.gif"  /></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico5.gif"  /></div>
                <div>
					<?php 
			        if($this->user['id'])
			        {
			        ?>
			        <a href="javascript:void(0);" onclick="get_comment_plot(1)">评论</a>
			        <?php 
			        }
			        else 
			        {
			        ?>
			        <a href="javascript:void(0);" onclick="get_comment_plot(0)">评论</a>
			        <?php 
			        }
			        ?>
				</div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico6.gif"  /></div>
                <div><a href="javascript:void(0);" onclick="show_share(1,'<?php echo $url?>')">分享</a></div>
                
		<div id="share_container" class="panel panelShare" style="display: none;width:548px;"></div>
     	<div id="share_status" style="display: none;width:548px;"></div>
     	<div id="program_list" style="display: none;width:548px;"></div>            
      </div>
      </div>
  </div>
  <?php   	
  }
  else 
  {
  	echo hg_show_null("抱歉","该视频不存在或已被删除");
  }
  ?>
  
</div>

<div class="garea">
	<div class="gleft">
    <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
    <div class="gsub2">相关视频</div>
    <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
    <div class="gsub4 mar_cus clear">
      <?php 
      if(!is_array($video))
      {
      	echo hg_show_null("","暂无相关视频！",1);
      }
      else 
      { 
      $i = 1;
      foreach($video as $key => $value)
      {
      	if($i ==1)
      	{
	      ?>
		<div class="ottv"><a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><img src="<?php echo $value['schematic'];?>" width="122" height="91" /></a><a style="height: 14px; overflow: hidden; width: 120px;" title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'],19," ");?></a>
	        <p>播放:<?php echo $value['play_count'];?></p>
	        <p>评论:<?php echo $value['comment_count'];?></p>
		</div>
	      <?php
      	}
      	else 
      	{
      		?>
		<div class="ottv mars"><a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><img src="<?php echo $value['schematic'];?>" width="122" height="91" /></a><a style="height: 14px; overflow: hidden; width: 120px;" title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'],19," ");?></a>
	        <p>播放:<?php echo $value['play_count'];?></p>
	        <p>评论:<?php echo $value['comment_count'];?></p>
		</div>
	      <?php
      	} 
      $i++;	
      }	
      }
      ?>
    </div>
    	<div class="gsub1 clear"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">网友评论<span class="txt">(全部<span class="tcolor" id="count_num"><?php echo $total_nums?$total_nums:0;?></span>条)</span></div>
        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
      <div class="gsub4">
       	<div class="g_tare">
       	<form action="<?php echo hg_build_link(SNS_UCENTER.'login.php');?>" method="post">
		<?php if(!$this->user['id'])
		{
		?>
			<input type="hidden" value="dologin" name="a" />
			<input type="hidden" value="<?php echo $this->input['referto'];?>" name="referto" />
			<span class="g_tops">网友昵称:<input class="fm1" type="text" id="username" name="username"/>密码:<input class="fm1" type="password" id="password" name="password" /><input id="login_bt" class="fm_sub" type="submit" value="<?php echo $this->lang['login'];?>" name="submit"/></span>
		<?php 
		}
		else 
		{
		?>
		<span class="g_tops">网友昵称:<a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php');?>"><?php echo $this->user['username'];?></a></span>
		<?php 
		}
		?>
          </form>
			<?php include hg_load_template('comment');?>
          </div>
        </div>
    </div>
    <div class="gright">
	    <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
		<div class="gsub2">视频信息</div>
		<div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
		<div class="gsub5">
			<div class="imgs"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$single_video_info['user']['id']));?>"><img src="<?php echo $single_video_info['user']['middle_avatar'];?>" width="50" height="50"/></a></div>
			<div class="img_txt">
				<div><span class="lc"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$single_video_info['user']['id']));?>"><?php echo $single_video_info['user']['username'];?></a></span><span class="rc"><!--<a class="sline" href="#">发站内站</a>-->
		 		<?php 
		          if(is_array($station))
		          {
		          	if(!$station['myself'])
		          	{
		          		if($station['relation'])
		          		{
		          			echo "已关注";
		          		}
		          		else 
		          		{?>
		          		<span id="collect_<?php echo $station['id'];?>"><a href="javascript:void(0);" onclick="add_collect(<?php echo $station['id'];?>,1,<?php echo $station['user']['id'];?>);">关注TA</a></span>
		          		<?php	
		          		}
		          	}
		          }
		          else 
		          {
		          	echo "已关注";
		          }
		          ?>
				</span></div>
				
				<ul class="img_t">
				<li>发布时间:<?php echo date("Y-m-d H:i:s",$single_video_info['create_time']);?></li>
				<li>标签：<?php echo $single_video_info['tags']?$single_video_info['tags']:'暂无';?></li>
				<li>视频简介：<?php echo $single_video_info['brief']?$single_video_info['brief']:'暂无';?></li>
				<li>视频已加入
				<?php 
			      if(is_array($program))
			      {
			     	$j = 1; 
			      	foreach($program as $key =>$value)
			      	{
			      		if($j<3)
			      		{
		      	?>
		      		<span class="txt"><a href="<?php echo hg_build_link("station_play.php", array('sta_id'=>$value['id']));?>"><?php echo $value['web_station_name'];?></a></span>
		      		<?php
			      		}
			      		$j++;
			      	}
			      	?>
			      		共<b><?php echo $program_total?></b>个频道<?php
			      }
			      ?>
				</li>
				</ul>

      
      
			</div>
		</div>
    <?php 
		if(count($visit)>1)
		{
			$total = $visit_total;
			unset($visit['total']);
			?>
			<div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
	        <div class="gsub2">他们也看过<span class="txt">(全部<span class="tcolor"><?php echo $total;?></span>人次)</span></div>
	        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
	        <div class="gsub4">
	        	<ul>
	        	<?php 
	        	$i = 1;
	        		foreach($visit as $key => $value)
	        		{
	        			if($i==1||$i==6)
	        			{
	        			?>
	        			<li><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="<?php echo $value['user']['middle_avatar'];?>" width="50" height="50" /></a><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo $value['user']['username'];?></a></li>
	        			<?php	
	        			}
	        			else 
	        			{
	        			?>
	        		 	<li class="mars"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="<?php echo $value['user']['middle_avatar'];?>" width="50" height="50" /></a><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo $value['user']['username'];?></a></li>
	        			<?php	
	        			}
	        		$i++;
	        		}
	        	?>
	            </ul>
	        </div>
		<?php	
		}
    ?>
       <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">热门标签</div>
        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
        <div class="gsub4_2">
        	<?php 
        	if(!HOT_TOPIC_URL)
        	{
        		echo hg_show_null(" ","暂未标注地点！",1);
        	}
        	else 
        	{?>
        	 <script src="<?php echo HOT_TOPIC_URL;?>" type="text/javascript"></script> 
        	<?php 	
        	}
        	?>
        </div>
    </div>
</div>
<?php include hg_load_template('status_pub');?>
<?php echo hg_advert('play_1');?>
<?php include hg_load_template('foot');?>