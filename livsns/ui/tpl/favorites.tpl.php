<?php 
/* $Id: favorites.tpl.php 3525 2011-04-11 05:06:19Z chengqing $ */
?>
<?php include hg_load_template('head');?>
<div class="content clear" id="equalize">
	<div class="content-left">
		<div class="news-latest">
		<div class="tp"></div>
		<div class="md"></div>
		</div>
<?php 
if (!empty($statusline)&&is_array($statusline))
{
?>
<ul class="list clear">
<?php
//echo "<pre>";
//print_r($statusline);
//echo "</pre>";
foreach($statusline as $key => $value)
{
	$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
	$text = hg_verify($value['text']);
	$text_show = '：'.($value['text']?$value['text']:$this->lang['forward_null']);
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
	<li class="clear">
		<div class="blog-content">
			<p class="subject clear"><a href="<?php echo SNS_UCENTER.$user_url;?>"><?php echo $value['user']['username'];?></a>
		<?php
		echo $text_show."<br/>";
				?>		
		</p>

<?php if($value['medias'])
{
	?>
	<input id="rot_<?php echo $value['id'];?>" type="hidden" value="0"/>
	<div id="prev_<?php echo $value['id'];?>" style="display:inline-block;">
	<?php 
	foreach($value['medias'] as $mk => $mv)
	{
		$var = array(
			"url" => "",
			"imgname" => "",
			"ori" => "",
			"video_url" => "",
			"video_link" => "",
			"video_img" => "",
			"video_title" => ""
		);
		if(!$mv['type'])
		{
			$var['url'] = $mv['small'];
			$var['imgname'] = $mv['larger'];
			$var['ori'] = $mv['ori'];
			if($var['url'])
			{?>
			<div style="display:inline-block;*display:inline;">
			<a href="javascript:void(0);" onclick="scaleImg(<?php echo $value['id'];?>)">
				<img class="imgBig" src="<?php echo $var['url'];?>"/>
			</a>
			</div>
			<?php 
			}
		}
		else
		{
			$var['video_url'] = $mv['url'];
			$var['video_link'] = $mv['link'];
			$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
			$var['video_title'] = trim($mv['title'])?$mv['title']:$value['text'];
			if($var['video_link']&&$var['video_img']&&$var['video_title'])
			{?>
				<div class="hidden" id="vl_<?php echo $mv['id'] + $value['id'];?>"><?php echo $var['video_link'];?></div>
				<div class="hidden" id="vt_<?php echo $mv['id'] + $value['id'];?>"><?php echo $var['video_title'];?></div>
				<div class="hidden" id="vu_<?php echo $mv['id'] + $value['id'];?>"><?php echo $var['video_url'];?></div>
				<div style="position:relative;display:inline-block;*display:inline;height:auto;">
				<img src="<?php echo $var['video_img'];?>"/>
				<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo(<?php echo $value['id'];?>,<?php echo $mv['id'];?>,<?php echo $mv['self'];?>)">
					<img class="pointer" src="./res/img/feedvideoplay.gif"/>
				</a>
				</div>
		<?php }
		}	
	}
	?>
	</div>
	<div id="disp_<?php echo $value['id'];?>" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink(<?php echo $value['id'];?>)">收起</a>
			<a target="_blank" href="<?php echo $var['ori'];?>">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft(<?php echo $value['id'];?>);">左转</a>
			<a href="javascript:void(0);" onclick="runRight(<?php echo $value['id'];?>);">右转</a>
		</div>
		<canvas id="canvas_<?php echo $value['id'];?>" onclick="shlink(<?php echo $value['id'];?>)" class="imgSmall"></canvas>
		<img id="load_<?php echo $value['id'];?>"  onclick="shlink(<?php echo $value['id'];?>)" class="imgSmall" src="<?php echo $var['imgname'];?>"/>
	</div>	
	<div id="v_<?php echo $value['id'];?>" class="hidden" style="text-align:center">		
		
	</div>	
		<?php		
}
?>
<?php if($transmit_info['text']||!empty($transmit_info['medias']))
{?>
	<div class="comment clear">
	<div class="top"></div>
	<div class="middle clear">
		<p class="subject"><?php echo hg_verify("@".$transmit_info['user']['username'].":".$transmit_info['text'])."<br/>";?>
		</p>
<?php 
	if(is_array($transmit_info['medias']))
	{
		?>
		<input id="rot_<?php echo $transmit_info['id'] + $value['id'];?>" type="hidden" value="0"/>
		<div id="prev_<?php echo $transmit_info['id'] + $value['id'];?>" style="display:inline-block;">
		<?php 
		foreach($transmit_info['medias'] as $mk => $mv)
		{
			$var = array(
				"url" => "",
				"imgname" => "",
				"ori" => "",
				"video_url" => "",
				"video_link" => "",
				"video_img" => "",
				"video_title" => ""
			);
			if(!$mv['type'])
			{
				$var['url'] = $mv['small'];
				$var['imgname'] = $mv['larger'];
				$var['ori'] = $mv['ori'];
				if($var['url'])
				{?>
					<a href="javascript:void(0);" onclick="scaleImg(<?php echo $transmit_info['id'] + $value['id'];?>)">
						<img class="imgBig" src="<?php echo $var['url'];?>"/>
					</a>
				<?php 
				}
			}
			else
			{
				$var['video_url'] = $mv['url'];
				$var['video_link'] = $mv['link'];
				$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
				$var['video_title'] = trim($mv['title'])?$mv['title']:$value['retweeted_status']['text']; 
				if($var['video_link']&&$var['video_img']&&$var['video_title'])
				{
				?>
					<div class="hidden" id="vl_<?php echo $mv['id'] + $transmit_info['id'] + $value['id'];?>"><?php echo $var['video_link'];?></div>
					<div class="hidden" id="vt_<?php echo $mv['id'] + $transmit_info['id'] + $value['id'];?>"><?php echo $var['video_title'];?></div>
					<div class="hidden" id="vu_<?php echo $mv['id'] + $transmit_info['id'] + $value['id'];?>"><?php echo $var['video_url'];?></div>
					<div style="position:relative;display:inline-block;*display:inline;">
					<img src="<?php echo $var['video_img'];?>"/>
					<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo(<?php echo $transmit_info['id'] + $value['id'];?>,<?php echo $mv['id'];?>,<?php echo $mv['self'];?>)">
						<img class="pointer" src="./res/img/feedvideoplay.gif"/>
					</a>
					</div>
		<?php 
				}
			}				
		}?>
	</div>
	<div id="disp_<?php echo $transmit_info['id'] + $value['id'];?>" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink(<?php echo $transmit_info['id'] + $value['id'];?>)">收起</a>
			<a target="_blank" href="<?php echo $var['ori'];?>">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft(<?php echo $transmit_info['id'] + $value['id'];?>);">左转</a>
			<a href="javascript:void(0);" onclick="runRight(<?php echo $transmit_info['id'] + $value['id'];?>);">右转</a>
		</div>
		<canvas id="canvas_<?php echo $transmit_info['id'] + $value['id'];?>" onclick="shlink(<?php echo $transmit_info['id'] + $value['id'];?>)" class="imgSmall"></canvas>
		<img id="load_<?php echo $transmit_info['id'] + $value['id'];?>"  onclick="shlink(<?php echo $transmit_info['id'] + $value['id'];?>)" class="imgSmall" src="<?php echo $var['imgname'];?>"/>
	</div>	
	<div id="v_<?php echo $transmit_info['id'] + $value['id'];?>" class="hidden" style="text-align:center">
	</div>	
	<?php 
	}
	?>
		<div class="speak">
			<span>
				<a href="<?php echo hg_build_link('show.php' , array('id' => $transmit_info['id'])); ?>"><?php echo $this->lang['original_transmit'];?>(<?php echo $transmit_info['transmit_count'] + $transmit_info['reply_count'];?>)</a>|
				<a href="<?php echo hg_build_link('show.php' , array('id' => $transmit_info['id'])); ?>"><?php echo $this->lang['original_comment'];?>(<span><?php echo $transmit_info['comment_count'];?></span>)</a>
			</span>
		</div> 
		</div>
		<div class="bottom"></div>
	</div>
	<?php 
}
?>		
			<div class="speak clear">
				<div class="hidden" id="t_<?php echo $value['id'];?>"><?php echo $title;?></div>
				<div class="hidden" id="f_<?php echo $value['id'];?>"><?php echo $forward_show;?></div>
					<span id = "<?php echo "fa".$value['id']?>" style="position:relative;">
					<?php
					if($value['user']['id'] == $user_info['id'])
					{
					?>
					<a href="javascript:void(0);" onclick="unfshowd(<?php echo $value['id']?>)"><?php echo $this->lang['delete'];?></a>|	
					<?php
					}
					?>	
				
					<a href="javascript:void(0);" onclick="OpenForward('<?php echo $value['id']?>','<?php echo $status_id;?>')"><?php echo $this->lang['forward'].'('.($value['transmit_count']+ $value['reply_count']).')'?></a>|
					<a id="<?php echo "fal".$value['id']?>" href="javascript:void(0);" onclick="unfshow('<?php echo $value['id']?>')"><?php echo $this->lang['uncollect'];?></a>|
					<a href="javascript:void(0);" onclick="getCommentList(<?php echo $value['id']?>,<?php echo $this->user['id']?>)"><?php echo $this->lang['comment'];?>(<span id="comm_<?php echo $value['id']?>"><?php echo $value['comment_count'];?></span>)</a>
				</span>
				<strong><?php echo hg_get_date($value['create_at']);?></strong>
				<strong><?php echo $this->lang['source'].$value['source']?></strong>
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_<?php echo $value['id']?>" value="<?php echo $value['comment_count']?>"/>
			<div id="comment_list_<?php echo $value['id'];?>"></div>
		</div> 
		<a href="<?php echo SNS_UCENTER.$user_url;?>">
		<img src="<?php echo $value['user']['middle_avatar'];?>"/>
		</a>
	</li>
<?php
}
?>
 <li class="more"><?php echo $showpages;?></li>
</ul>
<div style="clear:both;"></div>
<?php
echo $showpages;
}
else 
{
	echo hg_show_null('真不给力，SORRY!',"我暂时还没有任何收藏！");
}
?>
</div>

<div class="content-right">

<div class="pad-all">
	<!-- load userInfo -->
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
	<div class="user">
	<div class="user-set">
		<h5><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>"><?php echo $user_info['username']; ?></a><span><a class="bind" href="<?php echo hg_build_link(SNS_UCENTER.'bind.php'); ?>">绑定</a></span></h5>
		<a href="<?php echo hg_build_link(SNS_UCENTER.'userprofile.php'); ?>">个人设置</a>
		<a href="<?php echo hg_build_link(SNS_UCENTER.'login.php' , array('a' => 'logout')); ?>"><?php echo $this->lang['logout']?></a>
		<div class="user-name">
			<div style="font-size:12px;color:gray;">性别：<?php echo $user_info['sex']?'男':'女';?></div>
			<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="<?php echo hg_build_link(SNS_UCENTER . 'geoinfo.php');?>"><?php echo $user_info['group_name'];?></a></div>
			<?php
				$relation = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				foreach($relation as $key =>$value)
				{
					$temp = $user_info[$key];
					if($temp)
					{
						if(strcmp($key,"birthday")==0 && is_numeric($temp))
						{
							echo '<div style="font-size:12px;color:gray;"><span>'.$value. ' : <span>' . $this->lang['xingzuo'][$temp] . '</div>';
						}
						else
						{
							echo '<div style="font-size:12px;color:gray;"><span style="font-size:12px;color:gray;">'.$value. ' : </span>' . $temp . '</div>';
						}
					}				
				}
			?>
		</div>
	</div> 

	<a href="<?php echo hg_build_link(SNS_UCENTER.'avatar.php'); ?>"><img src="<?php echo $user_info['middle_avatar']; ?>" title="<?php echo $user_info['username']; ?>" /></a>
	</div>
	<?php include hg_load_template('userInfo');?>
		</div>
	    </div>
</div>

<input type="hidden" value="update" name="a" id="a"/>
<input type="hidden" value="点滴" name="source" id="source"/>

<?php include hg_load_template('forward');?>
<?php include hg_load_template('foot');?>