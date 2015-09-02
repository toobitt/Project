<?php
/* $Id: search.tpl.php 2846 2011-03-16 09:36:48Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="vui">
	<div class="con-left">
		
		<div class="station_content">
			<div class="station_top search_top">
				<form action="" method="post">
					<input class="search_in" type="text" name="k" value="<?php echo $name;?>"/>
					<input class="search_bt" type="submit" value="搜索"/>
				</form>
			</div>
					
			<?php 
				if(!$station_info)
				{	
				?>
				<div class="search_li">
					<?php echo hg_null_search($name);?>
				</div>
				<?php 
				}
				else
				{
					
				?> 
				
				
				<div class="con_top">检索结果</div>
				<div class="pop" id="pop">
					<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
					<div id="pop_s"></div>
				</div>
				<div class="con_middle con_middle_search">
				
				<?php 					
					if(is_array($station_info))
					{
						
					?> 
					<ul>
					<?php 	
						foreach($station_info as $key => $value)
						{
						?>
							<li class="search_li">
								<ul class="search_result">
									<li><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array("sta_id"=>$value['id']));?>"><img src="<?php echo $value['small'];?>"/></a></li>
									<li>名称：<a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array("sta_id"=>$value['id']));?>"><?php echo hg_match_red(hg_cutchars($value['web_station_name'],7," "),$name);?></a></li>
									<li>标签：<?php echo $value['tags']?hg_tags(hg_cutchars($value['tags'],7," "),$name,"station_search.php"):"暂无";?></li>
									<li>点击次数：<?php echo $value['click_count'];?></li>
								</ul>
							</li>
						<?php
						}
						?> 
					</ul>
					<?php 
					echo $showpages;
					}
				?> 
				</div>
				<div class="con_bottom clear"></div>
				<?php 	
				}
						
			?>
			
		</div>
	</div>
	<?php include hg_load_template('my_right_menu');?>
</div>
<?php include hg_load_template('foot');?>

