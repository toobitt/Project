<?php 
/* $Id: report.tpl.php 3821 2011-04-29 07:55:06Z repheal $ */
?>
<style type="text/css">
.reportbox{width:392px;height:auto;display:none;margin:0 auto;width:550px;padding:10px}
.reportbox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold}
.reportbox .text{padding:10px;margin:0;background:#fff;width:514px}
.reportbox_top{background:url(./res/img/Rounded.png) 0 -319px no-repeat;height:16px;font-size:0}
.reportbox_middle{float: left;padding:0 8px;background:url(./res/img/zp_bg.png) repeat-y;width:auto;}
.reportbox_bottom{background: url("./res/img/Rounded.png") no-repeat scroll 0 -336px transparent; float: left; font-size: 0; height: 16px; width: 550px;}


#reportClose{cursor:pointer;float:right;}
.tip5{background-position: 0 -64px;}
.tipicon{background: url(./res/img/tipicon.png) no-repeat 0 -64px;_background: url(./res/img/tipicon.gif) no-repeat 0 -64px;width: 16px;height: 16px;overflow: hidden;}
.linedot1 {background-image: url(./res/img/linedot1.gif);background-repeat: repeat-x;height: 1px;overflow: hidden;clear: both;}
.popreport_tip {color: #717171;margin-bottom: 6px;}
.popreport_title {line-height: 20px;margin-top: 10px;}
.popreport_info {border: 1px solid #CCC;background-color: #FFFFE5;padding: 10px;margin-top: 6px;zoom: 1;overflow: hidden;}
.popreport_infol {float: left;margin-right: 10px;}
.popreport_infol img {width: 30px;height: 30px;padding: 2px;border: 1px solid #CCC;}
.popreport_infor {float: left;width: 428px;overflow: hidden;}
.popreport_infor p {color: #717171;line-height: 18px;word-wrap: break-word;}
.popreport_say {padding-top: 10px;}
.popreport_say textarea {font-size: 12px;background-color: #F9FBF0;border-color: #999 #C9C9C9 #C9C9C9 #999;border-style: solid;border-width: 1px;color: #999;height: 30px;margin-bottom: 10px;margin-top: 10px;padding: 5px;width: 486px;}
.report_text{background: url(./res/img/report.jpg) no-repeat;}
.popreport_btn {margin-top: 10px;}
.popreport_btn p {display: inline-block;float: left;width: 300px;line-height: 18px;color: #717171;}
.popreport_btn a {float: right;margin: 4px 4px 0;}
.btn_normal {background-position: -320px -100px;height: 23px;line-height: 23px;padding-left: 20px;}
.btn_normal em {background-position: right -123px;height: 23px;padding-right: 20px;}
.btn_normal,em{display: inline-block;height: 23px;color: #333;line-height: 23px;font-size: 12px;font-weight: normal;cursor: pointer;text-decoration: none;font-family: "宋体";background: url(./res/img/new_index_bg1.png) no-repeat -320px -100px;}
a:hover { text-decoration:none;} 
</style>
<div id="report" class="reportbox" style="top:10%;left:5%;display:none;">
<?php 
if($this->input['id'])
{
	$types = 5;//视频
	$urls = SNS_VIDEO.'video_play.php?id=';
}
if($this->input['sta_id'])
{
	$types = 11;//频道
	$urls =  SNS_VIDEO.'station_play.php?sta_id=';
}
?>
<div style="display:none;" id="types"><?php echo $types;?></div>
<div style="display:none;" id="urls"><?php echo $urls;?></div>

	<div class="reportbox_top"></div>
	<div class="reportbox_middle">
		<h3><span id="reportClose">X</span>举报不良信息</h3>
		<div class="text">
			<div class="popreport_tip"><img style="vertical-align: -2px;margin-right: 4px;" src="./res/img/transparent.gif" class="tipicon tip5"/>不良信息是指含有色情、暴力、广告或其他骚扰你正常点滴生活的内容</div>
			<div class="linedot1"></div>
			<div class="popreport_title" id="users"></div>
			<div class="popreport_info">
				<div class="popreport_infol">
					<img id="avatars" alt="" src="">
				</div>
				<div class="popreport_infor">
					<p id="contents"></p>				
				</div>
			</div>
			<div class="popreport_say">
				<div class="popreport_title">你可以填写更多举报说明：<span>（选填）</span></div>
					<textarea id="report_text" class="report_text" onfocus="clearReport(this);" onblur="showReport(this);" style="overflow-y: hidden; overflow-x: hidden; "></textarea>
			</div>
			<div class="linedot1"></div>
			<div class="popreport_btn">
				<p>请放心，你的隐私将会得到保护。<br></p>
				<a class="btn_normal" href="javascript:void(0);" onclick="report_clear();"><em>取消</em></a>
				<a class="btn_normal" href="javascript:void(0);" onclick="report_add();"><em>确认举报</em></a>			
			</div>
			<div class="clearit"></div>
		</div>
		</div>
	<div class="reportbox_bottom"></div>
</div>