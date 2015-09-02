关于配置文件的相关说明
1、UPLOAD_SIZE_LIMIT 用于配置允许上传的文件大小 此处只是flash控制
2、CALLBACK 回调文件设置 这个文件通常位于第三方系统内 用于将用户需要的数据推送至此页面
3、$_configs['callback_map']这个映射配置用于确定将数据插入至相应的dom结构中
4、MEDIASERVER 指定转码服务器位置
5、
####################访问设置####################
define('APPID', '63');
define('APPKEY', '3v3ylbpMRvb3gcfbTFU6diYVh9PWdxn6');

//用户名和密码 一定要具有视频的相关权限
define('USER_NAME', 'hogesoft');
define('PASSWORD', 	'hoge2013!@#');

这组配置用于第三方系统访问m2o

6、APIURL 表示视频库的api地址
7、AUTH 权限系统的api地址
8、PAGE_NUMBER 每页显示的视频数目
9、VIDEO_STATUS 视频输出状态 默认必须是审核通过的 默认从第三方系统上传的也是审核通过的

示例：详细见test.html
	主要流程:a.通过iframe引入index.php?cb=cb.html&settings=vid,id,name,title,time2,duration
			b.cb表示回调文件 这个文件通常位于第三方系统 只需要拷贝过去即可
			c.settings参数主要控制需要推送的数据 和 推送数据在dom中的位置 示例表示将视频的id,title,和duration字段至dom的id是vid,name,和time2里
附录：
id=>视频唯一标识符
title=>视频标题
is_audio=>是否是音频
img_info=>缩略图
	host
	dir
	filepath
	filename
height=>高度
width=>宽度
totalsize=>视频大小 单位字节
aspect=>宽高比
sampling_rate＝>采样率
author=>作者
create_time=>创建时间
bitrate=>码流
audio_channels=>声道
frame_rate=>帧率
resolution＝>分辨率
duration=>时长 秒
