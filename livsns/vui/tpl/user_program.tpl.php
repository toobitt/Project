<?php
/* $Id: user_program.tpl.php 2633 2011-03-10 06:17:11Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('add_program');?>
<?php include hg_load_template('tips');?>
<div class="user">
	<div class="content clear">
<!--	<div><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$userinfo['id']));?>"><?php echo $stationInfo['web_station_name'];?></a></div>-->
<div><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$userinfo['id']));?>">《返回</a></div>
	<div>
		<h3><?php echo $userinfo['username']?$userinfo['username']:$this->user['username'];?>的节目单列表 </h3>
		<div style="width:220px;float:left;">
			<ul class="program_list">
			<?php
			$start_time=0;
			if(!$program_info)
			{?>
				<li id="default_list">默认分类</li>
			<?php 
			}
			else {
				foreach($program_info as $key =>$value)
				{
					$start_time = $value['end_time'];
					if($value['play'])
					{?>
						<li class="nowplay">
							<?php echo hg_encode_time($value['start_time']);?>—<?php echo hg_encode_time($value['end_time']);?>
							<a target="_blank" href="<?php echo hg_build_link('station_play.php',  array('sta_id'=>$value['sta_id'],'user_id'=>$value['user_id']));?>"><?php echo $value['programe_name'];?></a>
						</li>
					<?php 
					}
					else 
					{?>
						<li>
							<?php echo hg_encode_time($value['start_time']);?>—<?php echo hg_encode_time($value['end_time']);?>
							<?php echo $value['programe_name'];?>
						</li>
					<?php
					}
				}
			}
			?>
			</ul>
		</div>
	</div>
</div>
</div>
<?php include hg_load_template('foot');?>