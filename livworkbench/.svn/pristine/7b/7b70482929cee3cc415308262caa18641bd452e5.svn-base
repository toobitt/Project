<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link href="ggk.css" type="text/css" rel="stylesheet" />
<title>刮刮卡</title>
</head>
<?php 
$type = $_REQUEST['type'];
?>
<style>
	.zhuanpan,.ggkBox{display:none;}
	.show{display:block;}
	/*刮刮卡*/
	.ggkBox{position:relative;background:url(bg.png);width:320px;height:480px;background-size:320px 480px;}
	.ggk-canvas,.ggk-txt{position:absolute;width:235px;height:101px;top:199px;left:42px;}
	.ggk-txt{background:#f0f0f0;text-align:center;line-height:101px;color:#9f9f9d;font-size:28px;}
	
	/*大转盘*/
	.demo{width:320px; height:320px; position:relative;}
	#disk{width:320px; height:320px; background:url(disk.jpg) no-repeat;background-size: 320px 320px;}
	#start{width:163px; height:320px; position:absolute; top: 27px;left: 94px;}
	#start img{width: 136px;cursor:pointer}
</style>
<body>
  <div class="ggkBox <?php if( $type == 1 ) {?>show<?php } ?>">
      <span class="ggk-txt">谢谢参与</span>
      <canvas id="ggk-canvas" class="ggk-canvas"></canvas>
  </div>
  
  <div id="main" class="zhuanpan <?php if( $type == 2 ){ ?> show <?php } ?>">
	   <div class="msg"></div>
	   <div class="demo">
	        <div id="disk"></div>
	        <div id="start"><img src="start.png" id="startbtn"></div>
	   </div>
  </div>
  
</body>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="jquery.easing.min.js"></script>
<script type="text/javascript">
	$(function(){ 
	     $("#startbtn").click(function(){ 
	        lottery(); 
	    }); 
	}); 
	
	
	function lottery(){ 
	    $.ajax({ 
	        type: 'POST', 
	        url: 'data.php', 
	        dataType: 'json', 
	        cache: false, 
	        error: function(){ 
	            alert('出错了！'); 
	            return false; 
	        }, 
	        success:function(json){ 
	            $("#startbtn").unbind('click').css("cursor","default"); 
	            var a = json.angle; //角度 
	            var p = json.prize; //奖项 
	            $("#startbtn").rotate({ 
	                duration:3000, //转动时间 
	                angle: 0, 
	                animateTo:1800+a, //转动角度 
	                easing: $.easing.easeOutSine, 
	                callback: function(){ 
	                    var con = confirm('恭喜你，中得'+p+'\n还要再来一次吗？'); 
	                    if(con){ 
	                        lottery(); 
	                    }else{ 
	                        return false; 
	                    } 
	                } 
	            }); 
	        } 
	    }); 
	} 
	
</script>
<script src="ggk.js"></script>
</html>