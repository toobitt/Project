<div style="position: absolute; z-index: 99; left:30%; display:none;overflow:hidden;height:313px;" id="dialog_{$salt_str}">
	<div class="MMaskBox">
		<!--<div class="MMaskAlpha"></div>-->
		<div class="MbgT"></div>
		<div class="MMaskInner">
			<div class="MbgL"></div>
			<div class="MbgR"></div>
			<div class="MMaskConBox">
				<a onclick="return dialogMinimize('{$salt_str}');" href="javascript:void(0);" class="MSmall"></a>
				<a onclick="return dialogDestroy('{$salt_str}');" href="javascript:void(0);" class="MClose"></a>
				<form onsubmit="return false;" name="msgform_{$salt_str}" id="msgform_{$salt_str}" method="post">
					<div class="MBox">
						<div style="position: relative;" class="MTop">
							<div id="caption_{$salt_str}" class="topCon" style="color: rgb(51, 51, 51);">{$to_name}</div>
						</div>
						<div class="MBottom">
							<div class="MBTop">
								<div id="contentTop_{$salt_str}" class="MBTBox"> 
									<ul class="messageList" id="contentList_{$salt_str}"> 
									{code} echo htmlspecialchars_decode(stripslashes($msgul));{/code}
									</ul> 
								</div>
							</div>
							<div class="MBBottom">
								<div class="MMessageBox">
									<div class="GUserAvatar"><img src="{$user_info['middle_avatar']}" class="pic"/></div>
									<div class="messageBox">
										<div class="monoceras"></div>
										<div>
											<span class="ct"><span class="cl"></span></span>
											<div class="messageCon">
												<div class="selectExpress clearfix">
												<div class="MSmile"><div class="absolute"><div class="facebox" id="face_c_content_{$salt_str}"><a onclick="show_face('face_content{$salt_str}');return false;" href="javascript:void(0);" class="choiceface"><img alt="" smilietext="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif"></a></div></div></div>
												<div style="position: absolute; display: none;z-index:9999;" class="facelist" id="face_content{$salt_str}">
													<ul class="face_menu" style="width:316px;">
													
														{code}
													        $face_name = $_settings['smile_name'];
													        $num = count($face_name);
													        $j = 1;
														{/code}
													        {foreach $face_name as $nk => $nv}
													      
													        <li onclick="face_tab({$j},{$num},'face_');" style="cursor:pointer;">{$nv}</li>
														
															{code}
																$j++;
															{/code}
													        
													        {/foreach}
													</ul>
													
													{code}
														$face = $_settings['smile_face'];
											     		$i = 1;
													{/code}
											        {foreach $face as $fk => $fv}
														{code}
															$facelist = hg_readdir($fv['dir']);
															$style = "";
														{/code}
											        	{if $i>1}
															{code}
																$style = ' style="display:none"';
															{/code}
														{/if}
											       		<ul id="face_{$i}" {$style} style="clear: both;width:316px;">
														
														{foreach $facelist as $lk => $lv}
													
															<li class="faces">
																<a onclick="chat_insert_face('content_{$salt_str}', ' :em{$fk}_{$lk}:');return false;" href="javascript:void(0);">
																	<img alt="" smilietext=":em{$fk}_{$lk}:" src="{$fv['url']}{$lv}">
																</a>
															</li>
														{/foreach}
														</ul>
											       	
														{code}
															$i++;
														{/code}
											        {/foreach}
											        
													</div>
												<span class="number">300字以内</span><span style="cursor: pointer;"   class="changeSubmit" id="quickSubmit_{$salt_str}">&nbsp;&nbsp;Ctrl + Enter 发送</span></div>
												<div class="clear">
													<textarea onkeydown="quick_submit('{$salt_str}',event)"  name="content_{$salt_str}" id="content_{$salt_str}" onclick="change_remind_css('{$salt_str}')"></textarea> 
												 	<input type="hidden" name="to_name" value="{$to_name}" id="to{$salt_str}" />
													<a onclick="return sendMessage('{$salt_str}');" href="javascript:void(0);" class="sumbit" id="s{$salt_str}"></a><a onclick="return false;" href="javascript:void(0);" class="disabled" id="dis_submit{$salt_str}" style="display:none;"></a>
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

        
        
