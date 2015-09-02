<?php 
/* $Id: station_play.tpl.php 4161 2011-07-11 01:33:12Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="station">
  <div class="gtop">
	<div class="lc" onmouseover="report_show(<?php echo $station['id'];?>,<?php echo $station['user']['id'];?>);" onmouseout="report_hide(<?php echo $station['id'];?>,<?php echo $station['user']['id'];?>);">
  		<div style="display:none;" id="cons_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>">创建的频道《<?php echo $station['web_station_name'];?>》</div>
		<div style="display:none;" id="ava_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>"><?php echo $station['user']['middle_avatar'];?></div>
		<div style="display:none;" id="user_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>"><?php echo $station['user']['username'];?></div>
		<div style="display:none;" id="url_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>"><?php echo SNS_VIDEO.'station_play.php?sta_id='.$station['id'];?></div>
		<div style="display:none;" id="type_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>">9</div>	
			
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
  	<span class="subsub"><?php echo $station['web_station_name'];?></span>
  	<span style="display:none;" id="vedio_name_container"><?php echo $video_name;?></span>
  	<?php 
    if(is_array($station))
    { if($this->user['id']){?><span onclick="report_play(<?php echo $station['id'];?>,<?php echo $station['user']['id'];?>);" style="color: #0163C9; cursor: pointer; display: none; float: right; font-size: 12px; font-weight: normal;" id="re_<?php echo $station['id'];?>_<?php echo $station['user']['id'];?>"><?php echo $this->lang['report'];?></span><?php }}?> 
  	</div>
  	</div><input type="hidden" id="video_briefs" value="<?php echo $video_brief;?>"/>
  <div class="gtv" id="gtv">
   <div class="gtg">
    <?php 
    if(!is_array($station))
    {
    	echo hg_show_null(" ","暂未创建频道",1);
    }
    else 
    {?>
   	<div class="gt_logo"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor"><img src="<?php echo $station['small'];?>"/></a><div><a id="station_name" href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor"><?php echo hg_cutchars($station['web_station_name'],8," ");?></a></div></div>
    <div class="gt_cont">
    	<ul>
        	<li>创建人：<a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor"><?php echo $station['user']['username'];?></a></li>
            <li>创建时间：<?php echo date("Y-m-d",$station['create_time']);?></li>
            <li>标签：<a title="<?php echo $station['tags']?$station['tags']:'暂无';?>"><?php echo hg_cutchars($station['tags']?$station['tags']:'暂无',24," ");?></a></li>
            <li>频道简介：<a title="<?php echo $station['brief']?$station['brief']:'暂无';?>"><?php echo hg_cutchars($station['brief']?$station['brief']:'暂无',20," ");?></a></li>
            <li>关注次数：<span id="co_<?php echo $station['id']?>"><?php echo $station['collect_count'];?></span></li>
        </ul>
    </div>
         <?php 
         if(!$is_my_page)
         {?>
           <div class="gt_boot" id="con_<?php echo $station['id']?>">
         <?php 
	         if(!$station['relation'])
	         {
	         ?>
	          <a class="gz_get" href="javascript:void(0);" onclick="add_collect(<?php echo $station['id']?>,1,<?php echo $user_id;?>);">关注该频道</a>
	         <?php 
	         }
	         else 
	         {
	         ?>
	         <a class="gz_get" href="javascript:void(0);" onclick="del_concern(<?php echo $station['concern_id']?>,<?php echo $station['id'];?>,<?php echo $user_id;?>,1);">取消关注</a>
	         <?php 	
	         }
	         ?>
    		</div>
        <?php 	
         }   
    }
    ?>
   </div>
    <div class="tv">
      <div class="tv_video" id="container">
		</div>
    </div>
	<div style="float:right">
    <div class="top_bt" onclick="change_list(1);" ></div>
    <div class="tvt" id="tvt">
    	<ul class="cent_bt" id="cent_bt">
	        <?php 
	        if(!is_array($program))
	        {?>
	         	<li>暂未创建节目单<span class="stxt"></span></li>
	        <?php
	        }
			else 
			{
				foreach($program as $key=>$value)
				{
					$start_time = $value['end_time'];
					if($value['video'])
					{
						?>
				         <li title="<?php echo $value['programe_name'];?>" id="line_<?php echo $value['id'];?>"<?php echo $play;?> onclick="play_new(<?php echo $value['id'];?>);"><?php echo hg_cutchars($value['programe_name'],10," ");?><span class="stxt"><?php echo hg_toff_time($value['start_time'], $value['end_time'],0);?></span></li>
				        <?php 
					}
				}
			}
	        ?>
        </ul>
    </div>
    <div class="bot_bt" onclick="change_list(2);"></div>
	</div>
     <div class="tv_vidtt">
        <div id="tv1" class="tv1">
            	<div><img src="<?php echo RESOURCE_DIR;?>img/adico1.gif"  /></div>
                <div id="status_share"><a href="javascript:void(0);" onclick="show_status_share(<?php echo $video_id;?>);">分享到点滴</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico2.gif" /></div>
                <div id="video_collect">
			        <?php 
			        if($relation)
			        {?>
			        <a href="javascript:void(0);">已收藏</a>
			        <?php
			        }
			        else 
			        {?>
			        <a href="javascript:void(0);" onclick="add_collect(<?php echo $video_id;?>,0,<?php echo $this->user['id'];?>);">收藏</a>
			        <?php
			        }
			        ?>
                </div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico3.gif"  /></div>
                <div id="program_bt"><a href="javascript:void(0);" onclick="add_program_out(<?php echo $video_id;?>,<?php echo $n_sta_id;?>,<?php echo $toff;?>)">选入编单</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico4.gif"  /></div>
                <div id="la_2"><a href="javascript:void(0);" onclick="lamps(2,0);">关灯</a></div>
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
      </div>
     </div>
  </div>
</div>
	<div id="share_container" class="panel panelShare" style="display: none;width:548px;"></div>
	<div id="share_status" style="display: none;width:548px;"></div>
	<div id="program_list" style="display: none;width:548px;"></div>
<div class="station">
	<div class="gleft">
    	<div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">网友评论<span class="txt">(全部<span id="count_num" class="tcolor"><?php echo $total_nums?$total_nums:0;?></span>条)</span></div>
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
				<span class="g_tops">网友昵称:<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php');?>"><?php echo $this->user['username'];?></a></span>
				<?php 
				}
				?>
          </form>
			<?php include hg_load_template('comment');?>
          </div>
        </div>
    </div>
    <div class="gright">
    
    <?php 
		if(count($visit)>1)
		{
			$total = $station['click_count'];
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
	        			<li><a title="<?php echo $value['user']['username'];?>" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="<?php echo $value['user']['middle_avatar'];?>" width="50" height="50" /></a><a title="<?php echo $value['user']['username'];?>" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo hg_cutchars($value['user']['username'],4," ");?></a></li>
	        			<?php	
	        			}
	        			else 
	        			{
	        			?>
	        		 	<li class="mars"><a title="<?php echo $value['user']['username'];?>" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="<?php echo $value['user']['middle_avatar'];?>" width="50" height="50" /></a><a title="<?php echo $value['user']['username'];?>" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo hg_cutchars($value['user']['username'],4," ");?></a></li>
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
<?php include hg_load_template('tips');?>
<?php include hg_load_template('foot');?>
