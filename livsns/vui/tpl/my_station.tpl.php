<?php
/* $Id: my_station.tpl.php 3560 2011-04-13 02:22:50Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="main_div">
	 <div class="right_window">
		<?php 
		if($stationInfo)
		{	
			$style_1 = 'style="display:none;"';
			$style_2 = 'style="display:block;"';
		}
		else 
		{
			$style_1 = 'style="display:block;"';
			$style_2 = 'style="display:none;"';
		}?>
			<h3><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a><?php echo $this->lang['station_mend'];?></h3>
			
			<div class="show_info" style="padding:0px;">
				<div class="station_show">
					<div <?php echo $style_2;?> class="station_logo" id="get_logo">
						<?php 
						if($stationInfo)
						{?>
							<img id="new_logo" src="<?php echo $stationInfo['small'];?>"/>
						<?php 
						}
						else 
						{?>
							<img id="new_logo" src="<?php echo UPLOAD_URL.LOGO_DIR;?>0.gif"/>
						<?php 							
						}
						?>
						<div class="logo_bt">
							<a href="javascript:void(0);" onclick="logoEdit();">修改LOGO</a>
							<a href="javascript:void(0);" onclick="stationEdit();">编辑资料</a>
						</div>
					</div>
					<div <?php echo $style_1;?> class="station_logo" id="set_logo">
						<form target="Upfiler_iframe" id="form1" enctype="multipart/form-data" method="post" action="my_station.php?a=uploadpic">
		        			<input type="file" name="files" id="files" onchange="uploads();"/> <a href="javascript:void(0);" onclick="logo_exit();">返回</a>
		        			<input type="hidden" name="logo_o" id="logo_o" value="<?php echo $stationInfo['logo'];?>"/>
							<input type="hidden" name="sta_id" id="sta_id" value="<?php echo $stationInfo['id'];?>"/>
		        		</form>
		        		<iframe height="1" frameborder="0" width="1" style="display: none;" src="about:blank" name="Upfiler_iframe" id="Upfiler_iframe" ></iframe>
						<?php 
						if($stationInfo)
						{?>
						<img style="display:none;" id="sta_logo" src="<?php echo RESOURCE_DIR?>img/loading.gif"/>
						<?php 
						}
						else 
						{?>
						<img id="sta_logo" src="<?php echo UPLOAD_URL.LOGO_DIR;?>0.gif"/>
						<?php 							
						}
						?>
						
						
					</div>
					<div <?php echo $style_2;?> id="get_station" class="station_info">
						<ul>
							<li class="station_name">
								<span class="enter_program"><a href="<?php echo hg_build_link("my_program.php")?>">进入节目单</a></span>
								<span id="get_station_name"><?php echo $stationInfo['web_station_name'];?></span>
							</li>
							<li>
								<span style="display: inline-block;"><?php echo $this->lang['station_brief'];?></span>
								<span id="get_brief" style=" line-height: 25px;letter-spacing: 1pt;display: inline;"><?php echo $stationInfo['brief'];?></span>
							</li>
							<li>
								<span style="display: inline-block;"><?php echo $this->lang['station_tags'];?></span>
								<span id="get_tags"><?php echo $stationInfo['tags'];?></span>
							</li>
						</ul>
					</div>
					<div <?php echo $style_1;?> id="set_station" class="station_info">
						<ul>
							<li>
								<span style="display: inline-block;vertical-align: top;"><?php echo $this->lang['station_name'];?></span>
								<input type="text" id="web_station_name" value=""/><span id="station_name_tip" style="color:red;width:15px;display:none">*</span>
							</li>
							<li>
								<span style="display: inline-block;"><?php echo $this->lang['station_tags'];?></span>
								<input type="text" id="tags" value=""/>
							</li>
							<li>
								<span style="display: inline-block;"><?php echo $this->lang['station_brief'];?></span>
								<textarea id="brief" name="brief" rows="4" cols="20"></textarea>
							</li>
							<li style="margin-left:85px;">
								<input id="station_bt" type="button" value="<?php echo $this->lang['ok'];?>" onclick="stationSubmmit();"/>
							</li>
						</ul>
					</div>
				</div>
		</div>
		<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	<?php include hg_load_template('my_right_menu');?>
	<div class="clear1"></div>
</div>

<?php include hg_load_template('foot');?>