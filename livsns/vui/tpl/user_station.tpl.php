<?php
/* $Id: user_station.tpl.php 2743 2011-03-14 03:33:19Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="user">
	<div class="content clear">
	<?php 
	if($stationInfo && $stationInfo['id'])
	{
		?>
	<ul class="station">
	<li style="font-size:16px;font-weight:600;"><?php echo $stationInfo['user']['username'].$this->lang['station'];?></li>
	<li class="clear" style="padding:10px 0;">
	<a class="logo" href="<?php echo hg_build_link('user_program.php',$id?array('sta_id'=>$stationInfo['id'],'user_id'=>$this->input['user_id']?$this->input['user_id']:($this->user['id']?$this->user['id']:0)):array('sta_id'=>$stationInfo['id']));?>"><img src="<?php echo $stationInfo['ori'];?>"/></a>
		<ul class="station-info">
			<li><?php echo $this->lang['station_name'].$stationInfo['web_station_name'];?></li>
			<li><?php echo $this->lang['station_brief'].$stationInfo['brief'];?></li>
			<li><?php echo $this->lang['collect_count'];?><span id="collect_count_<?php echo $stationInfo['id'];?>"><?php echo $stationInfo['collect_count'];?></span></li>
			<li><?php echo $this->lang['comment_count'].$stationInfo['comment_count'];?></li>
		</ul>
	</li>
<?php if(!$relation)
		{?>
		<li id="collect_<?php echo $stationInfo['id'];?>">
			<?php if($stationInfo['relation'])
			{?>
			<img src="./res/img/sy_button.jpg" width="58" height="18" />
		<?php 	
			}
			else 
			{?>
			<a href="javascript:void(0);" onclick="add_collect(<?php echo $stationInfo['id'];?>,1,<?php echo $stationInfo['user']['id'];?>);"><?php echo $this->lang['collect'];?></a>
		<?php 		
			}?>
		</li>
	<?php 
		}
	?>	
</ul>
<?php include hg_load_template('comment');?>
<?php 
		}
	else 
	{
		echo hg_show_null('提示', '该用户暂未设置频道！');
	}
?>
	
</div>
</div>
<?php include hg_load_template('foot');?>