<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: error.conf.php 4694 2011-10-11 09:46:44Z zhuld $
***************************************************************************/

$dict = array(
	'vod' => array('id'=>'视频ID','title'=>'标题', 'keywords'=>'关键字', 'comment'=>'描述', 'img'=>'预览图', 'subtitle'=>'副标题', 'keywords'=>'关键字', 'author'=>'作者', 'source'=>'来源','starttime'=>'收录时间','channel_id'=>'来源频道','format_duration'=>'时长','bitrate'=>'码流', 'resolution'=>'分辨率', 'totalsize'=>'文件大小', 'audio_channels'=>'声道','aspect'=>'宽高比', 'sampling_rate'=>'音频采样率', 'frame_rate'=>'视频帧率', 'audio'=>'音频编码','video'=>'视频编码','video_order_id'=>'排序'),
	'channel' => array('id'=>'频道ID'),

	'vod_collect' => array('id'=>'集合ID','collect_name' => '集合名称','count' => '集合里视频数','comment' => '集合描述','is_auto' => '该集合是否是自动创建'),
);
?>