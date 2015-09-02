<script type="text/javascript">
<!--

var page = 1;

$(document).ready(function()
{	
	nextVip = function()
	{
		$.ajax({
		        url: 'index.php',
		        type: 'POST',
		        dataType: 'html',
					timeout: 5000,
					cache: false,
		        data: {
					a:'ajax_get_vip',
					page:page,
					total:$('#vip_nums').val()
		        	},
		        error: function() {
		        	tipsport('网络延迟！');
		        },
		        success: function(r) {

			        $('#vip').html(r);
					page++;

					if(page == $('#vip_nums').val())
					{
						page = 0;
					}
		        }
		});    
	};

	addFriends=function(id , relation){
		
		var target = '#add_' + id;
		$.ajax({
			url: "index.php",
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {a: "add_friend",
				  id: id
			},
			error: function(){
				tipsport('网络延迟！');
			},
			success: function(response){
				
				if(relation == 4)          //未知这批人是否关注了我
				{
					$(target).html('<span class="been-concern">√已关注</span>');						
				}				
			}
		});			
	};	
	
});
	

//-->
</script>
<?php 
if ($this->user['id'] != $user_info['id'])
{
	$extuserlink = '?user_id=' . $user_info['id'];
}
?>
		<div class="business">
				<span class="u-show1">
					<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , $user_param);?>"><?php echo $user_info['status_count']; ?></a>
	    		</span>
    			<span class="u-show2">
    				<a href="<?php echo hg_build_link('follow.php' , $user_param);?>"><?php echo $user_info['attention_count']; ?></a>
	    		</span>
    			<span class="u-show3">
	    			<a href="<?php echo hg_build_link('fans.php' , $user_param);?>"><?php echo $user_info['followers_count']; ?></a>
	    		</span>
    			<span class="u-show4">
	    			<a href="<?php echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);?>"><?php echo $user_info['video_count']; ?></a>
	    		</span>
		</div>
		</div>
		<?php echo hg_advert('left_1');?>
		<div class="bk-top1">点滴导航</div>
		<div class="wb-block1">
			<div class="menu">
			<?php 
			foreach($this->settings['nav'] as $k => $v)
			{
				if($k == $gScriptName)
				{
			?>
				<a class="<?php echo $v['class'];?>_click" href="<?php echo hg_build_link($v['filename']);?>"></a>
			<?php 
				}
				else 
				{
			?>
				<a class="<?php echo $v['class'];?>" href="<?php echo hg_build_link($v['filename']);?>"></a>
			<?php 
				}
			}
			?>
			</div>
		</div>
		
		<!-- 会员信息   -->
		<?php 
		if(is_array($vipUser))
		{
			//print_r($vipUser);		
		?>	
			<input id="vip_nums" type="hidden" name="vip_nums" value="<?php echo $total; ?>" />	
			<div id="vip_title" class="bk-top1">葫芦会员<a style="float:right;margin-right:10px;font-size:12px;" href="javascript:void(0);" onclick="nextVip();">换几个</a></div>
			
			<div id="vip" class="wb-block1" style="height:240px;text-align:center;">
				<ul>
				<?php 
				foreach ($vipUser AS $k => $v)
				{			
					?>
					<li class="gz-ul">
						<a href="<?php echo SNS_UCENTER; ?>user.php?user_id=<?php echo $v['id']; ?>" ><img title="<?php echo $v['username']; ?>" style="border:1px solid silver;padding:2px;" src="<?php echo $v['middle_avatar']; ?>" /></a>
						<a title="<?php echo $v['username']; ?>" href="<?php echo SNS_UCENTER; ?>user.php?user_id=<?php echo $v['id']; ?>"><?php echo hg_cutchars($v['username'] , 3 , '...' , 1); ?></a>
						<div class="close-concern">
						<?php
						if($this->user['id'] == $v['id']) 				//自己          
						{
							
						}
						else
						{
							
							if($v['is_friend'] == 1)    //已关注
							{
						?>
						<p><span class="been-concern">√已关注</span>	</p>			
						<?php	
							}
							else
							{
						?>
						<p id="<?php echo 'add_' . $v['id']; ?>"><a class="concern" href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 4)">＋加关注</a></p>
						<?php		
							} 
						} 						
						?>									
						</div>
					</li>
					<?php 
					}
					?>
				</ul>	
			</div>		
		<?php 		
		}		
		?>	
		
		<?php if (is_array($topic))
		{
		?>
		<div class="bk-top1">热门话题</div>
		<div class="wb-block1">
		<ul class="topic clear">
			<?php
			
			foreach($topic as $value)
			{				
			?>
			<li>
				<a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				<?php echo hg_cutchars($value['title'],10," ");?></a><span>(<?php echo $value['relate_count'];?>)</span>
			</li>
			<?php
			}
			?>
		</ul>
		</div>
		<?php }		
		if($topic_follow)
		{?>
		<!-- follow topic -->
		<div class="bk-top1"><?php echo $this->lang['topic_follow'];?><strong>(<span id="liv_topic_follow_num"><?php echo count($topic_follow);?></span>)</strong></div>
		<div class="wb-block1">
		<ul class="topic clear">
		<?php

		foreach($topic_follow as $key=>$value)
		{
		?>
		<li class="topic_li" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		<?php
		$title = '<a title="'.$value['title'].'" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'],10," ") . '</a>';
		echo $title;
		?>
		<a class="close" href="javascript:void(0);" onclick="del_Topic_Follow('<?php echo $value['topic_id'];?>',this)"></a>
		<div class="hidden" id="topic_<?php echo $value['topic_id']?>"><?php echo $value['title'];?></div>
		</li>
		<?php
		}
		
		?>
		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()"><?php echo $this->lang['insert'];?></a></li>
		<!-- end add follow topic -->
		</ul>
		</div>
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" <?php echo $this->lang['save']?> " onclick="addTopic()"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about"><?php echo $this->lang['topic_about'];?></dd>
		</dl>
		<?php }?>