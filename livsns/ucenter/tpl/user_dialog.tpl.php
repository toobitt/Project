<div style="position: absolute; z-index: 99; left:30%;overflow:hidden;height:315px;" id="dialog_<?php echo $salt_str;?>">
<div class="MMaskBox">
	<!--<div class="MMaskAlpha"></div>-->
	<div class="MbgT"></div>
	<div class="MMaskInner">
		<div class="MbgL"></div>
		<div class="MbgR"></div>
		<div class="MMaskConBox">
			<a onclick="return dialogMinimize('<?php echo $salt_str;?>');" href="javascript:void(0);" class="MSmall"></a>
			<a onclick="return dialogDestroy('<?php echo $salt_str;?>');" href="javascript:void(0);" class="MClose"></a>
			<form onsubmit="return false;" name="msgform_<?php echo $salt_str;?>" id="msgform_<?php echo $salt_str;?>" method="post">
				<div class="MBox">
					<div style="position: relative;" class="MTop">
						<div id="caption_<?php echo $salt_str;?>" class="topCon" style="color: rgb(51, 51, 51);"><?php echo $to_name;?></div>
					</div>
					<div class="MBottom">
						<div class="MBTop">
							<div id="contentTop_<?php echo $salt_str;?>" class="MBTBox"> 
								<ul class="messageList" id="contentList_<?php echo $salt_str;?>"> 
								<?php foreach($messages as $pid => $p_info){?>
								<?php 
									if($p_info['fromwho'] == $this->user['username'])
									{
										$class = 'liBgDif';
									}
									else
									{
										$class = '';
									}
								?>
								<li id="pm_<?php echo $p_info['pid']?>" class="<?php echo $class?>"><div class="eachMessage clearfix"><div class="left"><span  class="user_name"><?php echo $p_info['fromwho']?></span></div><div class="right"><?php echo hg_get_date($p_info['stime'])?></div><div style="color: rgb(125, 125, 125);" class="eachMessageCon"><?php echo $p_info['content']?></div></div></li>
								<?php }?>
								</ul> 
							</div>
						</div>
						<div class="MBBottom">
							<div class="MMessageBox">
								<div class="GUserAvatar"><img src="<?php echo $user_info['middle_avatar'];?>" class="pic"/></div>
								<div class="messageBox">
									<div class="monoceras"></div>
									<div>
										<span class="ct"><span class="cl"></span></span>
										<div class="messageCon">
											<div class="selectExpress clearfix">
											<div class="MSmile"><div class="absolute"><div class="facebox" id="face_c_content_<?php echo $salt_str;?>"><a onclick="show_face('face_content<?php echo $salt_str;?>');return false;" href="javascript:void(0);" class="choiceface"><img alt="" smilietext="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif"></a></div></div></div>
											<div style="position: absolute; display: none;z-index:9999;" class="facelist" id="face_content<?php echo $salt_str;?>">
											<ul class="face_menu" style="width:316px;">
													<?php 
													        $face_name = $this->settings['smile_name'];
													        $num = count($face_name);
													        $j = 1;
													        foreach($face_name as $nk => $nv)
													        {?>
													        <li onclick="face_tab(<?php echo $j;?>,<?php echo $num;?>,'face_');" style="cursor:pointer;"><?php echo $nv;?></li>
														<?php 	   
														$j++;     		
													        }
													        ?>
													</ul>
													<?php 
											        $face = $this->settings['smile_face'];
											     	$i = 1;
											        foreach($face as $fk => $fv)
											        {
											        	$facelist = hg_readdir($fv['dir']);
											        	$style = "";
											        	if($i>1)
											        	{
											        		$style = ' style="display:none"';
											        	}
											        	?>
											       		<ul id="face_<?php echo $i?>" <?php echo $style;?> style="clear: both;width:316px;">
														<?php 
														foreach($facelist as $lk => $lv)
														{?>
															<li class="faces">
																<a onclick="chat_insert_face('content_<?php echo $salt_str;?>', ' :em<?php echo $fk;?>_<?php echo $lk;?>:');return false;" href="javascript:void(0);">
																	<img alt="" smilietext=":em<?php echo $fk;?>_<?php echo $lk;?>:" src="<?php echo $fv['url'].$lv;?>">
																</a>
															</li>
														<?php 
														}
														?>
														</ul>
											        <?php	
											        $i++;
											        }
											        ?> 	
											</div>
											<span class="number">300字以内</span><span style="cursor: pointer;"   class="changeSubmit" id="quickSubmit_<?php echo $salt_str?>">&nbsp;&nbsp;Ctrl + Enter 发送</span></div>
											<div class="clear">
												<textarea onkeydown="quick_submit('<?php echo $salt_str;?>',event)"  name="content_<?php echo $salt_str;?>" id="content_<?php echo $salt_str;?>" onclick="change_remind_css('<?php echo $salt_str;?>')"></textarea> 
											 	<input type="hidden" name="to_name" value="<?php echo $to_name;?>" id="to<?php echo $salt_str;?>" />
												<a onclick="return sendMessage('<?php echo $salt_str;?>');" href="javascript:void(0);" class="sumbit" id="s<?php echo $salt_str;?>"></a>
												<a onclick="return false;" href="javascript:void(0);" class="disabled" id="dis_submit<?php echo $salt_str;?>" style="display:none;"></a>
											</div>
										</div>
										<span class="cb"><span class="cl"></span></span>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		
	</div>
	<div class="MbgB"></div>
	</div>
	</div>