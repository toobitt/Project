<?php
/* $Id: my_station.php 566 2011-09-01 11:01:55Z develop_tong $ */
?>
{template:head}
<div class="main_div">
	 <div class="right_window">
	 {if $stationInfo}
		 {code}
			$style_1 = 'style="display:none;"';
			$style_2 = 'style="display:block;"';
		 {/code}
	 {else}
		{code}
			$style_1 = 'style="display:block;"';
			$style_2 = 'style="display:none;"';
		{/code}
	 {/if}
			<div class="show_head">
				<ul class="show_head_ul">
					<li><a class="show_head_default" href="{code} echo hg_build_link(SNS_VIDEO.'upload.php');{/code}">上传视频</a></li>
					{if ALLOW_PROGRAME}
					<li><a class="show_head_default" href="{code} echo hg_build_link(SNS_VIDEO.'my_program.php');{/code}">编辑节目单</a></li>
					{/if}
					<li><span class="show_head_cursor">频道设置</span></li>
				</ul>
			</div>
			
			<div class="show_info" style="padding:0px;">
				<div class="station_show clear">
					<div class="station_logo">
		        		<iframe height="1" frameborder="0" width="1" style="display: none;" src="about:blank" name="Upfiler_iframe" id="Upfiler_iframe" ></iframe>
						<?php 
						if($stationInfo)
						{?><div class="no_img">
						<img id="new_logo" src="{$stationInfo['small']}"/></div>
						<?php 
						}
						else 
						{?><div class="yes_img">
						<img id="new_logo" src="<?php echo UPLOAD_URL.LOGO_DIR;?>0.gif"/></div>
						<?php 							
						}
						?>
					</div>
					<div class="station_info">
						<div {$style_2} id="get_station" class="station_edit">
							<ul class="station_edit_ul">
								<li>
									<span class="enter_program">
									</span>
									<span class="blod">{$_lang['station_name']}</span>
									<span class="cont" id="get_station_name">{$stationInfo['web_station_name']}</span>
								</li>
								<li>
									<span class="blod">标&nbsp;&nbsp;&nbsp;&nbsp;签:</span>
									<span class="cont" id="get_tags">{$stationInfo['tags']}</span>
								</li>
								<li>
									<span class="blod" >{$_lang['station_brief']}</span>
									<span class="cont" id="get_brief">{$stationInfo['brief']}</span>
								</li>
							</ul>
							<a class="station_edit_a" href="javascript:void(0);" onclick="stationEdit();"></a>
						</div>
						<div {$style_1} id="set_station" class="station_edit">
							<ul class="station_edit_ul">								
								<li>
									<span class="blod">{$_lang['station_name']}</span>
									<input type="text" id="web_station_name" value=""/><span id="station_name_tip" style="color:red;width:15px;display:none">*</span>
								</li>
								<li>
									<span class="blod" >标&nbsp;&nbsp;&nbsp;&nbsp;签:</span>
									<input type="text" id="tags" value=""/>
								</li>
								<li>
									<span class="blod">{$_lang['station_brief']}</span>
									<textarea id="brief" name="brief" rows="2" cols="20"></textarea>
								</li>
								<li>
									<input  class="station_edit_bt" id="station_bt" type="button" onclick="stationSubmmit();"/>
								</li>
							</ul>
						</div>
						<div class="station_list">
							<form target="Upfiler_iframe" id="form1" enctype="multipart/form-data" method="post" action="my_station.php?a=uploadpic">
								<input class="station_logo_btn" type="file" name="files" id="files" onchange="uploads();"/>
								<a class="station_edit_logo" href="#"></a>
								<input type="hidden" name="logo_o" id="logo_o" value="{$stationInfo['logo']}"/>
								<input type="hidden" name="sta_id" id="sta_id" value="{$stationInfo['id']}"/>
							</form>
							{if ALLOW_PROGRAME}
							<a class="enter_program" href="<?php echo hg_build_link("my_program.php")?>"></a>
							{/if}
						</div>
					</div>
				</div>
		</div>
	</div>
	{template:unit/my_right_menu}
	<div class="clear1"></div>
</div>
{template:foot}