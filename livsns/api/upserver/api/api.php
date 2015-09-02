<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> 接口说明 </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
  <h2>1. 上传视频</h2>
  <ul>
  <li> 接口地址：http://<?php echo API_HOST;?>/<?php echo API_FILE;?>/create.php</li>
  <li> 输入参数： videofile  $_FILES文件流</li>
  <li> 返回: json<br />
&nbsp;&nbsp;&nbsp;array(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'id' => $last_id, //视频id<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	'type' => $filetype //视频类型<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	'size' => $filesize //视频总大小<br />
&nbsp;&nbsp;&nbsp;);<br />
 错误返回: 001 - 未指定文件传输，002 - 非法的文件类型， 003 - 视频移动失败</li>
  </ul>
  <h2>2. 获取视频信息接口</h2>
  <ul>
  <li> 接口地址：http://<?php echo API_HOST;?>/<?php echo API_FILE;?>/getVideoInfo.php</li>
  <li> 输入参数： id  视频id，多个用，号隔开 由create接口返回</li>
  <li> 返回: json<br />
&nbsp;&nbsp;&nbsp;Array<br />
&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [comment] => <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [audiohz] => 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [copyright] => www.YYeTs.net(c)-2011<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [author] => YYeTs人人影视-WiLL<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [title] => 迷离档案.Fringe.S03E14.Chi_Eng.HDTVrip.624X352-YYeTs人人影视<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [height] => 352 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [start] => 0.000000<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [Duration] => 2589000  //时长<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [bitrate] => 549 //码流<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [width] => 624 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   [status] => 1 //转码状态 0 - 转码中 1 - 转码完成<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [img] => http://<?php echo THUMB_URL;?>78.ssm/preview.jpg //视频截图<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [id] => 78<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [transize] => 272603 //转码中的视频已转码大小<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [totalsize] => 272603 //转码中的视频总大小<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    [filetype] => .mov //视频类型<br />
&nbsp;&nbsp;&nbsp;)<br />
 错误返回: 001 - 未指定视频ID， 002 - 未找到视频信息</li>
  </ul>
 </body>
</html>
