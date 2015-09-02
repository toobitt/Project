<?php 
/* $Id: head.tpl.php 2158 2011-02-19 09:29:50Z yuna $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->page_title . ' - ' . $this->lang['name'];?></title>
<?php echo $extra_header;?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="<?php echo MAIN_URL;?>res/zhibo/images/zhibo.css" />
<link type="text/css" rel="stylesheet" href="<?php echo MAIN_URL;?>res/zhibo/images/tab.css" />
<?php 
echo hg_add_head_element('echo'); 
?>
<style type="text/css">
.face_content{background:#fff;width: 200px;border: 1px solid #DEDEDE; height: 180px;left: 0px;overflow-x: hidden; overflow-y: auto; top: 150px;}
.face_content ul{width:316px;}
.face_content ul li.faces{float:left;padding: 3px;clear:none;width:auto;}
.face_content ul li.faces img{}
.face_content .face_menu{float:left;}
.face_content .face_menu li{padding: 5px;cursor: pointer;float: left; font-size: 12px;border:none; width: 50px;}
</style>
</head>
<body style="height:100%;background-color:#E3E3E3;">

<script type="text/javascript"><!--
/* 定时刷新页面 发言信息  */

$(document).ready(function (){

	/* set scroll */
	set_scroll = function()
	{
		if(document.getElementById('speak'))
		{
			document.getElementById('speak').scrollTop=10000000;
		}
	};
	
	self.setInterval('set_scroll()' , 1000);    //每隔1秒中执行一次
	
	var state = self.setInterval('get_newest_speak()' , 10000);  //每隔10秒中执行一次
			
	/* 定时获取最新的发言信息  */
	get_newest_speak = function ()
	{
		var newest_id = $('#newest_id').val();  //当前也最后一条信息ID
		
		$.ajax({
			url: 'discuss.php',
	        type: 'POST',
	        dataType: 'html',
				timeout: 10000,
				cache: false,
	        data: {
	        		a: 'update',
	        		q: '<?php echo $keywords; ?>',
	        	is_comment : 1,
	       newest_id : newest_id
	        	},
	        error: function() {
	            //alert('Ajax request error');
	        },
	        success: function(r) {

		        if(r != 1)
		        {
		        	var json_obj = eval('(' + r + ')'); 	   //将json串转化为json对象
		        	var chat_content = json_obj.chat_content;  //内容
		        	var last_id = json_obj.last_id;            //最后ID
		        	
		        	$('#newest_id').val(last_id);
		        	$('#no_chat').remove();		        	         	
			        $('#speak').append(chat_content);
			        if(document.getElementById('speak').style.display == 'none')
			        {
			        	document.getElementById('speak').style.display = 'block'; 
				    }    			        		        		        	
				}

		        //定时让滚动条置底 
		        if(document.getElementById('speak'))
		    	{
		    		document.getElementById('speak').scrollTop=10000000;
		    	}  	        	
	        }
	    });	
	};

	


	/* discuss ajax login */
	doLogin = function()
	{
		var username = $('#username').val();
		var pwd = $('#pwd').val();

		if(username && pwd)
		{
			$.ajax({

				 url: 'login.php',
		        type: 'POST',
		    dataType: 'html',
		     timeout: 5000,
			   cache: false,
		        data: {
		        		a: 'dologin',
		         username: username,
		         password: pwd,
		   is_ajax_login : 1
		        	},
		        error: function() {
		            alert('Ajax request error');
		        },
		        success: function(r) {

			        if(r == 'LOGINFAIL')
			        {
			        	notice_message('用户名和密码不正确！');
					}
			        if(r == 'LOGINSUCCESS')
			        {
				     	$('#login_area').css('display' , 'none');
				     	$('#user_name').text(username);			                    					                        
				    }       
		        }
			});	
		}
		else
		{
			notice_message('请输入用户名和密码！');			
		}		
		
	};

	/* message notice */
	notice_message = function(content)
	{
		$('#notice').html('<span style="color:red;">'+ content +'</span>');	
	};


	loginClose = function ()
	{
		$('#login_area').css('display' , 'none');
	};

	/* face */
	if($("#publish_face").html())
	{
		var i = 0;
		$("#publish_face").click(function()
		{
			
			if(i%2)
			{
				$('#face').show();
			}
			else
			{
				$('#face').hide();
			}
			
			i++;		
		});
		
		insert_face = function(id,face){
			obj = $("#"+id);
			obj.val(obj.val()+face+' ');
			$('#face').hide();
			cursor(id,obj.val().length,obj.val().length);
		}
	}					
});

/* 按下CTRL+ENTER 发送聊天 兼容IE6 */
$(document).keypress(function(e){
    if(e.ctrlKey && e.which == 13 || e.which == 10) { 

        $("#publish").click();
    }       
});

--></script>

<script type="text/javascript">

$(document).ready(function (){
	if(document.getElementById('speak'))
	{
		document.getElementById('speak').scrollTop=10000000;
	}	
});

</script>

<input type="hidden" value="点滴" name="source" id="source"/>
<input id="newest_id" type="hidden" value="<?php echo $newest_id; ?>" />

<?php

if (!empty($statusline)&&is_array($statusline))
{
?>
<div class="zhibo_bor" >

	<div style="position:relative; background-color:#E3E3E3;" class="comment_content">
		<ul id="speak" class="zhibo_bbs" style="height:70px;background-color:#E3E3E3;">
		<?php
		foreach($statusline as $key => $value)
		{	
			$user_url = hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['member_id']));
			$len = strlen('#' . $keywords . '#');
			if (substr(trim($value['text']), ($len - 1), 1) == '#')
			{
				$value['text'] = substr(trim($value['text']), $len);
			}
			$text = hg_verify($value['text']);
			$text_show = '：'.($value['text']?$value['text']:$this->lang['forward_null']);
			if($value['reply_status_id'])
			{
				$forward_show = '//@'.$value['user']['username'].' '.$text_show;
				$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
				$status_id = $value['reply_user_id'];
			}
			else
			{
				$forward_show = '';
				$title = $this->lang['forward_one'].$value['text'];
				$status_id = $value['member_id'];
			}
			$text_show =hg_match_red(hg_verify($text_show),$keywords);
			$transmit_info=$value['retweeted_status'];
		?>
				<li>
					<span class="zhibo_huifu"><a href="#" onclick="disreplyStatus(<?php echo $value['id'];?>, '<?php echo $value['user']['username'];?>');return false;">回复</a></span>
					<a href="<?php echo $user_url;?>" class="zhibo_name" target="_blank"><?php echo $value['user']['username'];?></a>：
					<span class="zhibo_detail" style="color:black"><?php echo $text;?></span>
					<span class="zhibo_time"><?php echo hg_get_date($value['create_at']);?></span>
				</li>		
		<?php 
		}
		?>
		 </ul>
 
	 	<div id="login_area" style="background:#23272A;border:5px solid white;padding:10px;width:180px;height:120px;display:none;position:absolute;z-index:9999;left:50%;top:50%;margin-left:-100px;margin-top:-30px;">
			<a style="padding:2px;border:1px solid white;color:white;position:absolute;z-index:9999;right:1px;top:1px;" href="javascript:void(0);" onclick="loginClose();">×</a>
			<div style="margin-top:15px;">
				<div><span style="color:white">用户名：</span><input style="width:100px;" id="username" type="text" value="" name="username" /></div>
				<br />
				<div><span style="color:white">密码：&nbsp;&nbsp;</span><input style="width:100px;" id="pwd" type="password" value="" name="pwd" /></div>
			</div>
			<div style="text-align:center;margin-top:10px;">
				<a id="dologin" href="javascript:void(0);" onclick="doLogin();return false;"><img src="./res/img/ajax_login.gif" /></a>
			</div>
			<div id="notice" style="text-align:center;"></div> 
		</div>
	</div>
	
	<input type="hidden" value="#<?php echo $keywords; ?>#" name="keywords" id="keywords" />
	<input type="hidden" value="0" name="status_id" id="status_id" />
	
	<div class="zhibo_fabu" style="<?php echo $height?$height:"height:60px;";?>background-color:#E3E3E3;<?php echo $width;?>">
		<span style="float:right"><a style="font-size:12px;color:#000;" href="<?php echo SNS_MBLOG . 'k.php?q=' . urlencode($keywords);?>" target="_blank">更多&gt;&gt;</a></span>
		<span>我说：</span>
		<span class="face" id="publish_face">
			<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
			<a style="color:#2FA5D7;" href="javascript:void(0);">表情</a>
		</span>		
		<textarea name="status" id="status" style="width:294px;height:20px;"></textarea>
		<p style="text-align:right;margin-top:5px;">
			<span id="user_name" style="color:black;font-size:13px;font-weight:bold;font-family:Arial,Verdana,Tahoma,Simsun,sans-serif;"><?php echo ($this->user['username'] == '游客') ? ''  :$this->user['username']; ?></span>		
			<span style="color: #989898; display: inline-block; float: left; line-height: 25px; margin-left: 4px;">ctrl+Enter发言</span>
			<a id="publish" href="#" onclick="dispubUserStatus();return false;"><img src="<?php echo MAIN_URL;?>res/default/images/zhi_btn9.jpg" /></a>
		</p>
	</div>

</div>
 
<?php
}
else
{
	?>
	<div id="no_chat" class="search_error">
		<p>
			<img align="absmiddle" title="" alt="" src="./res/img/error.gif" /><span style="color: red;"><?php echo $keywords;?></span>暂无讨论
		</p>	
	</div>
	
	<div class="zhibo_bor">
		<div style="position:relative ;">
			<ul id="speak" class="zhibo_bbs" style="height:70px;display:none;background-color:#E3E3E3;">
			</ul>
			
			<div id="login_area" style="background:#23272A;border:5px solid white;padding:10px;width:180px;height:120px;display:none;position:absolute;z-index:9999;left:50%;top:50%;margin-left:-100px;margin-top:-30px;">
				<a style="padding:2px;border:1px solid white;color:white;position:absolute;z-index:9999;right:1px;top:1px;" href="javascript:void(0);" onclick="loginClose();">×</a>
				<div style="margin-top:15px;">
					<div><span style="color:white">用户名：</span><input style="width:100px;" id="username" type="text" value="" name="username" /></div>
					<br />
					<div><span style="color:white">密码：&nbsp;&nbsp;</span><input style="width:100px;" id="pwd" type="password" value="" name="pwd" /></div>
				</div>
				<div style="text-align:center;margin-top:10px;">
					<a id="dologin" href="javascript:void(0);" onclick="doLogin();return false;"><img src="./res/img/ajax_login.gif" /></a>
				</div>
				<div id="notice" style="text-align:center;"></div> 
			</div>
		</div>
		
		<input type="hidden" value="#<?php echo $keywords; ?>#" name="keywords" id="keywords" />
		<input type="hidden" value="0" name="status_id" id="status_id" />
		
		<div class="zhibo_fabu" style="<?php echo $height?$height:"height:60px;";?>background-color:#E3E3E3;<?php echo $width;?>">
			<span>我说：</span>
			<span class="face" id="publish_face">
				<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif" />
				<a style="color:#2FA5D7;" href="javascript:void(0);">表情</a>
			</span>		
			<textarea name="status" id="status" style="width:294px;height:20px;"></textarea>
			<p style="text-align:right;margin-top:5px;">
				<a style="line-height: 25px;font-size:12px;" href="<?php echo SNS_MBLOG . 'k.php?q=' . $keywords;?>" target="_blank">更多&gt;&gt;</a><span id="user_name" style="color:white;font-size:13px;font-weight:bold;font-family:Arial,Verdana,Tahoma,Simsun,sans-serif;"><?php echo ($this->user['username'] == '游客') ? ''  :$this->user['username']; ?></span>	<span style="color: #989898; display: inline-block; float: left; line-height: 25px; margin-left: 4px;">ctrl+Enter发言</span>
				<a id="publish" href="#" onclick="dispubUserStatus();return false;"><img src="<?php echo MAIN_URL;?>res/default/images/zhi_btn9.jpg" /></a>
			</p>
		</div>		
	</div>			
	<?php
}
?>

<div id="face" class="face_content" style="z-index:499;margin-top:-150px;margin-left:-55px;background-color:white;position: absolute;display:none;border:1px solid #DEDEDE;height:100px;left:120px;overflow-x:hidden;overflow-y:auto;top:150px;width:200px;"></div>

<?php include hg_load_template('tips');?>
<?php
echo hg_add_foot_element('echo'); 
?>



</body>
</html>