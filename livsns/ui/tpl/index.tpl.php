<?php 
/* $Id: index.tpl.php 15451 2012-12-13 02:52:31Z repheal $ */
?>
<?php include hg_load_template('head');?>
<script type="text/javascript">
<!--

$(document).ready(function (){

	//定时更新微博的发布时间
	update_publish_time = function ()
	{
		var time_date = new Array();
		var allTimeStamp = $('.timestamp');
		var allPublishTime = $('.publishtime');
		var len = $('.timestamp').length;

		for(var i = 0 ; i < len ; i++)
		{			
			allPublishTime[i].innerHTML = show_time_format(allTimeStamp[i].value , allPublishTime[i].innerHTML);		
		}					
	};

	//返回指定的时间格式
	function show_time_format(time_stamp , publish_time)
	{
		var timestamp = (Date.parse(new Date()))/1000; //当前时间戳
		var time_format = '';		
		var seconds = parseInt(timestamp) - parseInt(time_stamp);

		if(seconds < 60)
		{
			time_format = seconds + '秒前';	
		}
		else if(seconds >= 60 && seconds < 3600)
		{
			time_format = parseInt(seconds/60) + '分钟前';	
		}
		else
		{
			time_format = publish_time;
		}
		
		return time_format;						
	};

	var state = self.setInterval('update_publish_time()' , 60000); //每隔一分钟更新一次微博发布时间
		
	//update_publish_time();
});

//-->
</script>





 <div class="content clear" id="equalize">
    <div class="content-left">
    <?php if($this->user['id']>0)
    {
    ?>	
        <dl class="expression">
           <dt>来，说说你在做什么，想什么</dt>
           <dd class="text">
              <textarea onkeydown='countChar();' style="font-family: Tahoma,宋体;"  onkeyup='countChar();' name="status" id="status"></textarea>
              <div id="friends" style="display:none;"><div class="remind">想用@提到谁？</div></div>
           </dd>
           <dd class="picture" id="publish_picture"><img src="./res/img/pub_pic.png" align="middle" /><a href="javascript:void(0);">图片</a></dd>
           <dd class="video" id="publish_video"><img src="./res/img/pub_video.png" align="middle" /><a href="javascript:void(0);">视频</a></dd>
           <dd class="face" id="publish_face"><img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif"><a href="javascript:void(0);">表情</a></dd>
           <dd class="topics" id="publish_topics"><img src="./res/img/pub_topic.png" align="middle"/><a href="javascript:void(0);" onclick="add_topic_rule('status');">话题</a></dd>           
          <?php if(PUBLISH_TO_MULTI_GROUPS > 0){?>
           <dd class="syn-pub" style="font-size:12px;"><input type="checkbox" name="p_t_groups" id="p_t_groups" value='' onclick="showMyGroups()"/>&nbsp;<label for="p_t_groups">同时发布到我的地盘</label></dd>
            <?php }?>
            <!--
            <dd class="music"><img src="./res/img/pub_music.png" /><a>音乐</a></dd> --> 
            <dd class="themeBut"><input onFocus="this.blur();" id="themeBut" type="button" class="published" onclick="pushStatus();"/></dd>
          	<dd class="words">还能输入<span id="counter">140</span>字</dd>
          	<dd class="text group"  id="publish_to_groups"></dd>
        </dl>
        <div id="uploadimg" class="upload-img">
        	<div>上传图片<a class="close" href="javascript:void(0);" onclick="closedThis('uploadimg');"></a></div>
        	<div class="load-pic">
        		<div class="btn-green" onclick="filesTrigger();">
        			<em style="margin:0px 0px 0px -5px; position:absolute;z-index:-10;cursor:pointer;">从电脑选择图片</em>
        			<form target="Upfiler_iframe" id="form1" enctype="multipart/form-data" method="post" action="index.php?a=uploadpic">
        				<input type="file" name="files" id="files" size="1" class="btn-img" onchange="uploads();"/>
        			</form>
        			<iframe height="1" frameborder="0" width="1" style="display: none;" src="about:blank" name="Upfiler_iframe" id="Upfiler_iframe" ></iframe>
        		</div>
        		<center>
        		<p>仅支持JPG、GIF、PNG图片文件，且文件小于5M</p>        	
        		</center>
        	</div>
        </div>
        <div id="loading" class="loading">
        	<div>
        		<center>
        			<img src="./res/img/loading.gif"/>
        			请等待图片上传 ...
        		</center>
        	</div>
        </div>
        <div id="showimg" class="show-img">
        	<div>
        	<span style="float:right;padding-right:8px;"><a onclick="closedThis('showimg');" href="javascript:void(0);" class=""><b>X</b></a></span>
        		<span id="imgurl"></span>
        		<a class="del-img" href="javascript:void(0);" onclick="del_img();">删除</a>
        	</div>
        	<div class="clear">
        		<center>
        			<img id="img-s" src=""/>
        		</center>
        	</div>
        </div>
<!--        视频-->
        <div id="uploadvideo" class="upload-video">
			<div class="layermedia">
			    <div><a class="close" href="javascript:void(0);" onclick="closedThis('uploadvideo');"></a></div>
	        	<div class="video_list">
	        		请输入
	        		<?php 
	        		$space = "";
	        			foreach($this->settings['video_type'] as $key => $value)
	        			{?>
	        				<?php echo $space;?><a target="_blank" href="http://<?php echo $value;?>"><?php echo $key;?></a>
	        			<?php
	        				$space = "、";
	        			}
	        		?>  		
	        		等视频网站的视频播放页链接
	        	</div>
	        	<div id="video_upload" class="video_upload">
	        		<input type="text" id="video_url" value="http://" style="color:rgb(153,153,153);"/>
	        		<input type="button" id="video_submit" value="确定" />
	        	</div>
	        	<div id="video_load" class="video_upload" style="display:none;">
					<img src="./res/img/loading.gif"/>  视频上传中...
				</div>
	        	<p id="video_tip" class="video-tip">你输入的链接地址无法识别:)</p>
	        	<p id="video_act" class="video-act">
	        		<a href="javascript:void(0);" onclick="closedThis('uploadvideo');">取消操作</a>
	        		或者
	        		<a href="javascript:void(0);" id="video_act_del">作为普通的链接发布</a>。
	        	</p>
			</div>
        </div> 
        <input type="hidden" name="sel_gp_name" id="sel_gp_name" value='' /> 
        <div id="face" class="face_content" style="position: absolute; display: none; visibility: visible;z-index:499;"></div>
        
	<?php }?>

<?php
if (!empty($statusline)&&is_array($statusline))
{
	if(SCRIPTNAME == 'index' && $this->user['id']==0)
	{?>
		<?php include hg_load_template('statusline_index');?>
		<?php
	}
	else
	{?>
		<?php include hg_load_template('statusline_one');?>
		<?php 
	}
}
else
{
	 ?>
	 <?php hg_show_null('真不给力，SORRY!',"暂无发表点滴！");?>
	 <?php
}
?>
</div>

	<?php
		if($this->user['id'] > 0)
		{
	?>
    <div class="content-right">
	<div class="pad-all">
	<!-- load userInfo -->
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
	<div class="user">
		<div class="user-set">
			<h5><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>"><?php echo $user_info['username']; ?></a></h5>
			
			
			<div class="user-name">

			<div style="font-size:12px;color:gray;">性别：<?php echo hg_show_sex($user_info['sex']);?></div>
			<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="<?php echo hg_build_link(SNS_UCENTER . 'geoinfo.php');?>"><?php echo $user_info['group_name'];?></a></div>
			<?php
				$relation = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				foreach($relation as $key =>$value)
				{
					$temp = $user_info[$key];
					if($temp)
					{
						if(strcmp($key,"birthday")==0 && is_numeric($temp))
						{
							echo '<div style="font-size:12px;color:gray;"><span>'.$value. ' : <span>' . $this->lang['xingzuo'][$temp] . '</div>';
						}
						else
						{
							echo '<div style="font-size:12px;color:gray;"><span style="font-size:12px;color:gray;">'.$value. ' : </span>' . $temp . '</div>';
						}
					}				
				}
			?>
			</div>
		</div> 
		
		<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php'); ?>"><img src="<?php echo $user_info['middle_avatar']; ?>" title="<?php echo $user_info['username']; ?>" /></a>
	</div>
	
	<?php include hg_load_template('userInfo');?>
	
		</div>

	    </div>
	<?php 			
		}
		else 
		{
			?>
			<div class="content-right login">

		
		<!-- <form action="<?php echo SNS_UCENTER;?>login.php" method="POST">
			<div class="login-menu" id="login">
				<a class="register" href="<?php echo hg_build_link(SNS_UCENTER.'register.php'); ?>"></a>
				<div class="login-text">
					<input type="text" id="username" name="username" class="username_bg" onfocus="clearUser(this);" onblur="showUser(this);"/>
					<input type="password" id="password" name="password"/>
				</div>
				
				<div class="pwd-recovery" style=" visibility:hidden">
				<span>
				<input name="" type="checkbox" value="" checked />下次自动登录</span><a href="#">找回密码</a>
	
				</div>
				
				<input id="login_bt" class="login-input" type="submit" value=" " name="submit"/>
			</div>
		<input type="hidden" value="dologin" name="a" />
		<input type="hidden" value="<?php echo $this->input['referto'];?>" name="referto" />
		</form> -->
		<div class="pad-all">
				
		<div class="bk-top1">热门话题</div>                                                                                                                                                                                              
		<div class="wb-block1">
		<?php if ($topic)
		{?>
		<ul class="topic clear">
			<?php
			foreach($topic as $value)
			{
			?>
			<li>
				<a href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				<?php echo $value['title'];?></a><span>(<?php echo $value['relate_count'];?>)</span>
			</li>
			<?php
			}
			?>
		</ul>
		
		<?php }?>
		</div>
		</div>

	</div>
	<?php 
		}
	?>
</div>
<?php include hg_load_template('forward');?>
<?php include hg_load_template('select_group');?>
<input type="hidden" value="点滴" name="source" id="source"/>
<div class="clear2"></div>

<script type="text/javascript">

var htmlElement = document.documentElement;
var tmpTag = false;

htmlElement.onclick = function(e){
	
	if(tmpTag == false)
	{
		tmpTag = true;
	}
	else
	{
		e = e?e:window.event;
		var ori = e.srcElement?e.srcElement:e.target;
		var oriF = ori.parentNode;
		if($(oriF).attr('class') == 'face_menu' && oriF.tagName.toLowerCase() == 'ul')
		{
			return false;
		}
		var faceObj = $('#face');
		if(faceObj)
		{
			$('#face').css('display' , 'none');
			tmpTag = false;
		}
	}		
}
</script>
<?php include hg_load_template('foot');?>
