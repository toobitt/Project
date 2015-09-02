<?php
/* $Id: show.php 8289 2012-03-13 06:15:46Z repheal $ */
?>
{template:head}
<div class="vui">
	<div class="con-left">
		<div class="station_top">
		{if $sta_id}
		<a class="get" href="<?php echo hg_build_link("my_station.php");?>">我的频道设置</a>
		{else}
		<a class="set" href="<?php echo hg_build_link("my_station.php");?>"></a>
		{/if}
			<a class="default" href="javascript:void(0);" onclick="open_create();">创建专辑</a>
			<a class="default" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."upload.php");?>">上传视频</a>
			<span>已创建<b>{$album_total}</b>个专辑，上传<b>{$user_info['video_count']}</b>个视频</span>
			
		</div>
		<div class="station_content">
			<div class="con_top">我关注的频道更新</div>
			<div class="pop" id="pop">
				<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
				<div id="pop_s"></div>
			</div>
			<ul class="con_middle">
			{if !empty($con_station)}
				{code}
				$totals = count($con_station);
				$i = 1;
				$num = 0;
				{/code}
				{foreach $con_station as $k => $v}
				{code}
					$num = count($v['programe']);
					if(is_array($v['programe']))
					{		
						$video = array();
						$programe = array();
						foreach($v['programe'] as $key => $value)	
						{
							$programe[$key]['programe_name'] = $value['programe_name'];
							$programe[$key]['id'] = $value['id'];
							$value['video']['programe_name'] = $value['programe_name'];
							$value['video']['programe_id'] = $value['id'];
							$video[] = $value['video'];
						}
						$border = "";
						if($totals != 1 && $i != $totals)
						{
							$border = "border_ok";
						}
						else 
						{
							$border = "border_no";
						}
				{/code}
					<li class="{$border}">
						<a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$v['user_id']));?>"><img class="station_logo" src="{$v['small']}"/></a>
						<ul class="his_list">
							<?php 
							$url = hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$v['id']));
							?>
							<li><a href="{$url}">{$v['web_station_name']}:</a>
								{if is_array($programe)}
									{code}
									$num = 1;
									{/code}
									{foreach $programe as $kp => $vp}
										
										<a style="color:#333;" title="{$vp['programe_name']}" href="{$url}#{$vp['id']}"><?php echo hg_cutchars($vp['programe_name'],8,"..");?></a>
										{code}
										$num ++;
										{/code}
									{/foreach}
								{/if}
								
							</li>
							<li>
							{if is_array($video)}
									<ul class="pre" id="pre_{$v['id']}">
									{code}
									$j = 1;
									{/code}
										{foreach $video as $ks => $vs}
										{code}
										$salt = hg_rand_num(2);
										{/code}
										<li>
											<div id="pres_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" style="position: relative;padding:5px 0;width:122px">
												<img src="{$vs['schematic']}"/>
												<a href="javascript:void(0);" onclick="scaleVideo(<?php echo $vs['id']+$vs['programe_id']+$salt;?>,{$v['id']});">
													<img class="play_bt" src="<?php echo RESOURCE_URL;?>feedvideoplay.gif"/>
												</a>
												<input id="vt_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="{$vs['title']}"/>
												<input id="vl_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="{$vs['streaming_media']}"/>
												<input id="vu_<?php echo $vs['id']+$vs['programe_id']+$salt;?>" type="hidden" value="{$url}#{$vs['programe_id']}"/>
											</div>
										</li>
										{code}
										$j++;
										{/code}
										{/foreach}
									</ul>
								{/if}
								<div id="v_{$v['id']}" style="display:none;"></div>
							</li>
							<li class="clear" style="color:#CBCBCB;font-size:12px;padding: 5px 0;"><?php echo date("Y-m-d H:i:s",$v['update_time']);?></li>
						</ul>
						<div class="clear"></div>
					</li>
				{code}
				$i++;
				{/code}
					{/if}		
				{/foreach}
			
			{else} 
			<li>
			{code}
				$null_title = "sorry!!!";
				$null_text = "暂未关注其他频道";
				$null_type = 0;
				$null_tip = '推荐频道列表';
				$null_url = 'http://htv.hoolo.tv/';
			{/code}
			{template:unit/null}
			</li>
			{/if}
			<li>{$showpages}</li>
			</ul>
			<div class="con_bottom clear"></div>
		</div>
	</div>
{template:unit/my_right_menu}
	<div class="clear1"></div>
</div>
{template:foot}