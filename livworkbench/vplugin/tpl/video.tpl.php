<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>新媒体视频上传与选择组件</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="http://libs.baidu.com/swfobject/2.2/swfobject.js" type="text/javascript"></script>
<link href="css/reset.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
</head>
<style>
.vod{height:420px;}
.vod ul{height: auto;overflow-x: hidden;max-height: 410px;position:relative;}
.vod ul li{position:relative}
.vod-list{background: #fff;border-bottom: 1px solid #c8d4e0;margin: 0 10px;padding: 0 10px;height:40px;font-size:14px;line-height: 40px;}
.vod-list .copy{float:right;text-decoration: underline;cursor:pointer}
.vod ul object{position: absolute;right: 10px;top: 7px}
</style>
<body>
<div class="video-area">
		<div class="hoge-page">
			<?php
				echo $page->show();
			?>
		</div>
		<div class="vod">
			<?php
			$jsData = array();
				if($vdata&&STYLE==1)
				{
			?>
				<ul class="video-list clearfix">
					<?php
						foreach($vdata as $key=>$video)
						{
							$needData = array();
							foreach($settings as $kk => $vv){
								$needData[$kk] = $video[$vv];
							}
							$jsData[$video['id']] = json_encode($needData);	
					?>
					<li>
						<a target="_self" class="video-link" _id="<?php echo $video['id'];?>"><img width="120" height="90" src="<?php echo array2img($video['img_info']);?>"></a>
						<div class="title"><?php echo $video['title'];?></div>
					</li>
					<?php
						}
					?>					
				</ul>
			<?php
				}
				elseif($vdata&&STYLE==2)
				{
				
				?>
							<ul class="video-lists ">
					<?php
						foreach($vdata as $key=>$video)
						{
							$needData = array();
							foreach($settings as $kk => $vv)
							{
								$needData[$kk] = $video[$vv];
							}
							$jsData[$video['id']] = json_encode($needData);	
					?>
					<li class="vod-list">
				 		<a target="_self"  _id="<?php echo $video['id'];?>">
							<span class="title"><?php echo $video['title'];?></span>
							<?php 
							$videourl = htmlspecialchars('<script type="text/javascript" src="'.URL.$video['id'].'"></script>');
							?>
							<span id="forLoadSwf<?php echo $video['id'];?>" class="forLoadSwf" data-index="<?php echo $video['id'];?>" _url="<?php echo $videourl;?>"></span>
						</a>
					</li>
					<?php
						}
					?>					
				</ul>   
				<?php }
			?>
		</div>
		<!--输出视频列表数据结束-->
				
</div>
</body>
<script type="text/javascript">
$('.forLoadSwf').each( function( ){
	var id = 'forLoadSwf' + $(this).data('index');
	var copyCon = $(this).attr('_url');
	var flashvars = {
		content: encodeURIComponent(copyCon),
		uri: '../vplugin/images/flash_copy_btn.png'
	};
	var params = {
			wmode: "transparent",
			allowScriptAccess: "always"
	};
	swfobject.embedSWF("../vplugin/js/clipboard.swf", id , "52", "25", "9.0.0", null, flashvars, params);
});
function copySuccess(){
	//flash回调
	alert("已复制到粘贴板！");
}

$(function(){
	var data = <?php echo json_encode($jsData);?>;
	var _height = $('html').height();
	parent.$('.video-area').height( _height );
	var cb = parent.cb;
	var settings = parent.settings;
	$('.video-link').on( 'click', function(){
		var id = $(this).attr( '_id' );
			videoinfo = data[id];
		console.log( videoinfo );
		var url = cb + '?' + 'videoinfo=' + encodeURIComponent( videoinfo ) + '&settings=' + encodeURIComponent( settings );
		parent.location.href = url;
	} );
});

</script>
</html>
