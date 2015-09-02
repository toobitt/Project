<style type='text/css'>
  .video_info_b{width:45%;height:40px;float:left;margin-left:20px;}
  .video_info_b div{width:120px;height:30px;float:left;}
</style>
{if $formdata['info'] == 2}
<div id="reviewing" style="display:none;color:#9c5e0c;background:#ffecb9;height:30px;width:100%;text-align:center;line-height:30px;">正在技术审核中......</div>
<script type="text/javascript">
	$('#reviewing').fadeIn(2000);
	setTimeout(function(){
		$('#reviewing').fadeOut(6000);
	},2000);
</script>
{else if $formdata['info'] == -1}
<div id="reviewing" style="color:#9c5e0c;background:#ffecb9;height:30px;width:100%;text-align:center;line-height:30px;">技术审核失败</div>
{else}
<div style="width:100%;height:1300px;margin-top:20px;">
	{code}
		$vinfo = $formdata['info'];
	{/code}
	<div style='width:100%;height:555px;'>
		<div class='video_info_b'>
			   <div>颜色格式</div>
			   <div>{$vinfo['ColorFormat']}</div>
		</div>
		<div class='video_info_b'>
			   <div>码流</div>
			   <div>{$vinfo['DataRateInBitsPerSec']}</div>
		</div>
		<div class='video_info_b'>
			   <div>DataRateType</div>
			   <div>{$vinfo['DataRateType']}</div>
		</div>
		<div class='video_info_b'>
			   <div>显示高度比</div>
			   <div>{$vinfo['DisplayHeight']}</div>
		</div>
		<div class='video_info_b'>
			   <div>显示宽度比</div>
			   <div>{$vinfo['DisplayWidth']}</div>
		</div>
		<div class='video_info_b'>
			   <div>画面组大小</div>
			   <div>{$vinfo['GOPSize']}</div>
		</div>
		<div class='video_info_b'>
			   <div>高度</div>
			   <div>{$vinfo['Height']}px</div>
		</div>
		<div class='video_info_b'>
			   <div>IsClipped</div>
			   <div>{$vinfo['IsClipped']}</div>
		</div>
		<div class='video_info_b'>
			   <div>音高</div>
			   <div>{$vinfo['Pitch']}</div>
		</div>
		<div class='video_info_b'>
			   <div>参考期</div>
			   <div>{$vinfo['ReferencePeriod']}</div>
		</div>
		<div class='video_info_b'>
			   <div>扫描模式</div>
			   <div>{$vinfo['ScanMode']}</div>
		</div>
		<div class='video_info_b'>
			   <div>标准帧率</div>
			   <div>{$vinfo['StandardRate']}帧/秒</div>
		</div>
		<div class='video_info_b'>
			   <div>标准缩放</div>
			   <div>{$vinfo['StandardScale']}</div>
		</div>
		<div class='video_info_b'>
			   <div>视频帧数目</div>
			   <div>{$vinfo['VideoFrameNum']}</div>
		</div>
		<div class='video_info_b'>
			   <div>视频流数目</div>
			   <div>{$vinfo['VideoStreamCount']}</div>
		</div>
		<div class='video_info_b'>
			   <div>视频子类型</div>
			   <div>{$vinfo['VideoSubType']}</div>
		</div>
		<div class='video_info_b'>
			   <div>视频类型</div>
			   <div>{$vinfo['VideoType']}</div>
		</div>
		<div class='video_info_b'>
			   <div>宽度</div>
			   <div>{$vinfo['Width']}px</div>
		</div>
		<div class='video_info_b'>
			   <div>音频采样数</div>
			   <div>{$vinfo['AudioSamplesNum']}</div>
		</div>
		<div class='video_info_b'>
			   <div>音频流数目</div>
			   <div>{$vinfo['AudioStreamCount']}</div>
		</div>
		<div class='video_info_b'>
			   <div>音频子类型</div>
			   <div>{$vinfo['AudioSubType']}</div>
		</div>
		<div class='video_info_b'>
			   <div>音频类型</div>
			   <div>{$vinfo['AudioType']}</div>
		</div>
		<div class='video_info_b'>
			   <div>音频平均码流</div>
			   <div>{$vinfo['AvgBitsPerSec']}</div>
		</div>
		<div class='video_info_b'>
			   <div>每样本比特数</div>
			   <div>{$vinfo['BitsPerSample']}</div>
		</div>
		<div class='video_info_b'>
			   <div>块级对齐</div>
			   <div>{$vinfo['BlockAlign']}</div>
		</div>
		<div class='video_info_b'>
			   <div>声道</div>
			   <div>{$vinfo['Channels']}</div>
		</div>
		<div class='video_info_b'>
			   <div>采样率</div>
			   <div>{$vinfo['SamplesPerSec']}</div>
		</div>
	</div>
	<div style='height:300px;margin-top:30px;overflow:auto;'>
			<div style='width:100%;height:20px;'>视频检测结果</div>
			<div style='width:100%;height:20px;'>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>出错帧￼位置</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>持续帧数目</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>检测类型</div>
			</div>
		{foreach $vinfo['VideoResult'] AS $kk => $vv}
			<div style='width:100%;height:20px;'>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vv['Pos']}</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vv['Length']}</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vv['Type']}</div>
			</div>
		{/foreach}
	</div>
	<div style='height:300px;margin-top:30px;overflow:auto;'>
			<div style='width:100%;height:20px;'>音频检测结果</div>
			<div style='width:100%;height:20px;'>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>出错帧￼位置</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>持续帧数目</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>声道</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>检测类型</div>
			</div>
		{foreach $vinfo['AudioResult'] AS $kkk => $vvv}
			<div style='width:100%;height:20px;'>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vvv['Pos']}</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vvv['Length']}</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vvv['Channel']}</div>
				  <div style='width:100px;float:left;height:20px;margin-left:20px;'>{$vvv['Type']}</div>
			</div>
		{/foreach}
	</div>
	
</div>

{/if}


