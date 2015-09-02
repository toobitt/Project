<?php 
	if(is_array($topic_list)&&$topic_list)
	{
?>

<table cellspacing=0 cellpadding=0 style="width:100%">
	<tr class="tab_tr">
		<td>名称</td>
		<td>地盘/作者</td>
		<td>回复/阅读</td>
		<td>最后发表</td>
	</tr>
	<?php 
		foreach($topic_list as $key => $value)
		{
	?>
	<tr class="con_tr">
		<td>
			<?php echo $value['flag'];?>
			<span title="由 <?php  echo $value['user_name']; ?> 于 <?php echo $value['pub_time'];?> 发起, <?php echo $value['click_count'];?>次阅读, <?php echo $value['post_count'];?>篇回复">
			 <a href="<?php echo SNS_TOPIC.$value['topic_link'];?>"  <?php echo $value['style'];?>>
				<?php echo hg_cutchars($value['title'],22,'…',true);?></a><?php echo $value['cons'];?></span>
		</td>
		<td>
			<span style="font-size:14px;float: left;"><a href="<?php echo SNS_TOPIC.$value['group_link'];?>"><?php echo $value['group_name'];?></a></span>
			<div class="author">
				<?php echo $value['avatar'];?>
				<?php echo $value['user_link'];?>
				<span class="times"><?php echo $value['last_post_time'];?></span>
			</div>
		</td>
		<td><?php echo ($value['post_count']-1);?>/ <?php echo $value['click_count'];?></td>
		<td>
			<div class="last_pub">
				<span><?php echo $value['last_poster'];?></span>
				<span class="times"><?php echo $value['last_post_time'];?></span>
			</div>
		</td>
	</tr>
	<?php 
		}?>
</table>
<?php 
	echo $showpages;
	}
	else 
	{
		echo hg_show_null(" ","暂未发帖",1);
	}
	?>
	<div class="clear"></div>	

	