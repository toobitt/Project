
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
			<a href="javascript:void(0);" onclick="scaleImg(<?php echo $value['id'];?>,0)">
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
			<a href="javascript:void(0);" onclick="shlink(<?php echo $value['id'];?>,0)">收起</a>
			<a target="_blank" href="<?php echo $var['ori'];?>">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft(<?php echo $value['id'];?>,0);">左转</a>
			<a href="javascript:void(0);" onclick="runRight(<?php echo $value['id'];?>,0);">右转</a>
		</div>
		<canvas id="canvas_<?php echo $value['id'];?>" onclick="shlink(<?php echo $value['id'];?>,0)" class="imgSmall"></canvas>
		<img id="load_<?php echo $value['id'];?>"  onclick="shlink(<?php echo $value['id'];?>,0)" class="imgSmall" src="<?php echo $var['imgname'];?>"/>
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
					<a href="javascript:void(0);" onclick="scaleImg(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>)">
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
			<a href="javascript:void(0);" onclick="shlink(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>)">收起</a>
			<a target="_blank" href="<?php echo $var['ori'];?>">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>);">左转</a>
			<a href="javascript:void(0);" onclick="runRight(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>);">右转</a>
		</div>
		<canvas id="canvas_<?php echo $transmit_info['id'] + $value['id'];?>" onclick="shlink(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>)" class="imgSmall"></canvas>
		<img id="load_<?php echo $transmit_info['id'] + $value['id'];?>"  onclick="shlink(<?php echo $value['id'];?>,<?php echo $transmit_info['id'];?>)" class="imgSmall" src="<?php echo $var['imgname'];?>"/>
	</div>	
	<div id="v_<?php echo $transmit_info['id'] + $value['id'];?>" class="hidden" style="text-align:center">
	</div>	
	<?php 
	}
	?>
		<div class="speak">
			<span>
				<a href="<?php echo hg_build_link(SNS_MBLOG.'show.php' , array('id' => $transmit_info['id'])); ?>"><?php echo $this->lang['original_transmit'];?>(<?php echo $transmit_info['transmit_count'] + $transmit_info['reply_count'];?>)</a>|
				<a href="<?php echo hg_build_link(SNS_MBLOG.'show.php' , array('id' => $transmit_info['id'])); ?>"><?php echo $this->lang['original_comment'];?>(<span><?php echo $transmit_info['comment_count'];?></span>)</a>
			</span>
			<div class="clear"></div>
		</div> 
		</div>
	</div>
	<?php 
}
?>