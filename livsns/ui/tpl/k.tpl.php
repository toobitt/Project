<?php
/* $Id: k.tpl.php 4060 2011-06-08 09:34:32Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('status_pub');?>
<input type="hidden" value="点滴" name="source" id="source"/>

<div class="theme clear " id="equalize">
	<div class="content-left">

	<form action="k.php" method="post">
		<div class="search">
<?php 
	if($info['member_id'])
	{
		$style1 ='style="display:none;"';
		$style2 ='style="display:block;"';
	}
	else 
	{
		$style1 ='style="display:block;"';
		$style2 ='style="display:none;"';
	}	
?>
<?php 
if($this->input['q'])
{
?>	
			
<a id="addTopics" <?php echo $style1;?> href="javascript:void(0);" onclick="addTopicFollow()"><span class="theme-cy"></span>关注该话题</a>
<a <?php echo $style2;?> id="delTopic" href="javascript:void(0);" onclick="delTopicFollow()"><span class="theme-close"></span>取消关注</a>
<input type="hidden" id="liv_topic_id" value="<?php echo $info['topic_id'];?>"/>
<?php }?>
			
			<a style="" href="javascript:void(0);" onclick="OpenReleaseds()"><span class="theme-gz"></span>参与该话题</a>
			<input class="text" type="text" name="q" id="q" value="<?php echo stripcslashes($keywords);?>"/>
			<input class="search-a" type="submit" id="search" value=" " />
		</div>
	</form>
<?php 
//echo "<pre>";
//print_r($statusline);
//echo "</pre>";
if (!empty($statusline)&&is_array($statusline))
{
	?>
	 <div class="my-business"><?php echo $this->lang['have'].$data['totalpages'].$this->lang['record'];?></div>
	<?php 
}
?>
<!--<div class="update">
        <p><a>有<span>12</span>条点滴更新，点击查看</a></p>
        </div>
-->
<div id="show"></div>
<?php include hg_load_template('forward');?>
<?php

if (!empty($statusline)&&is_array($statusline))
{
?>
<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<ul>
<?php
foreach($statusline as $key => $value)
{
	$user_url = hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['member_id']));
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
	$text_show =hg_match_red(hg_verify($text_show),$keywords);
	$transmit_info=$value['retweeted_status'];
?>
	<li class="clear" id="mid_<?php echo $value['id'];?>"  onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
		<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
		<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
		<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
		<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
		<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">3</div>
		
		<div class="blog-content">
			<p class="subject"><a href="<?php echo $user_url;?>"><?php echo $value['user']['username'];?></a>
			<?php
		echo $text_show."<br/>";
			?>		
			</p>
		<?php include hg_load_template('statusline_content');?>		
			<div class="speak clear">
			<div class="hidden" id="t_<?php echo $value['id'];?>"><?php echo hg_verify($title);?></div>
			<div class="hidden" id="f_<?php echo $value['id'];?>"><?php echo $forward_show;?></div>
				<span style="position:relative;">
					<a id = "<?php echo "fa".$value['id']?>" href="javascript:void(0);" onclick="OpenForward('<?php echo $value['id']?>','<?php echo $status_id;?>')"><?php echo $this->lang['forward'].'('.($value['transmit_count']+ $value['reply_count']).')'?></a>|
					<a  id="<?php echo "fal".$value['id']?>" href="javascript:void(0);" onclick="favorites('<?php echo $value['id']?>','<?php echo $this->user['id'];?>')"><?php echo $this->lang['collect'];?></a>|
					<a href="javascript:void(0);" onclick="getCommentList(<?php echo $value['id']?>,<?php echo $this->user['id']?>)"><?php echo $this->lang['comment'];?>(<span id="comm_<?php echo $value['id'];?>"><?php echo $value['comment_count'];?></span>)</a>
				</span>
				<strong><?php echo hg_get_date($value['create_at']);?></strong>
				<strong class="overflow" style="max-width:230px"><?php echo $this->lang['source'].$value['source']?></strong>
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
		<a href="<?php echo $user_url;?>">
		<img src="<?php echo $value['user']['middle_avatar'];?>"/>
		</a>
	</li>
<?php 
}
?>
 <li class="more"></li>
 </ul>
<?php
echo $showpages;
}
else
{
	echo hg_null_search($keywords);
}
?>
</div>

<div class="content-right">

<div class="pad-all">
	<div class="bk-top1">热门话题</div>
	<div class="wb-block1">
		<?php if ($topic)
		{?>
		<ul class="topic clear">
			<?php
			foreach($topic as $value)
			{
			?>
			<li>
				<a href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				<?php echo $value['title'];?></a><span>(<?php echo $value['relate_count'];?>)</span>
			</li>
			<?php
			}
			?>
		</ul>
		<?php }?>
	</div>

<?php if($this->user['id']>0)
{
	?>	
<!-- follow topic  -->	
	<div class="bk-top1">
	<?php echo $this->lang['topic_follow'];?><strong>(<span id="liv_topic_follow_num"><?php echo count($topic_follow);?></span>)</strong></div>
		<div class="wb-block1">
		<ul id="addtopicfollows" class="topic clear">
		<?php
		if($topic_follow)
		{
		foreach($topic_follow as $key=>$value)
		{
		?>
		<li class="topic_li" id="liv_topic_<?php echo $value['topic_id'];?>" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		<?php
		$title = '<a title="' . $value['title'] . '" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'] , 8 , ''). '</a>';
		echo $title;
		?>
		<a class="close" href="javascript:void(0);" onclick="delTopicFollow()"></a>
		</li>
		<?php
		}
		}
		?>
		</ul>	
		</div>
<!-- end follow topic  -->
	<?php
}?>
		
		</div>

</div>
</div>


<?php include hg_load_template('foot');?>