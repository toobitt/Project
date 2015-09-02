<?php include hg_load_template('head');?>

<div class="garea">
	<div class="g_larea">
    	<div class="g_are1"></div>
        <div class="g_are2">
        	<ul>
        	<li style="cursor: pointer;" onclick="changeCss(1)" class="<?php if(!$this->input['n']){?>bt<?php }else{?><?php }?>" id="_1">消息</li>
        	<li style="cursor: pointer;" onclick="changeCss(2)" id="_2" class="<?php if($this->input['n']){?>bt<?php }else{?><?php }?>">通知</li> 
          </ul>
        </div>
        <div class="g_are3"></div>
        <div id="content" class="g_are4">
        <?php echo $html;?>
        </div>
       
        <div style="font-size: 0pt; line-height: 0pt;"><img src="./res/img/hc_pic_btns.gif"></div>
    </div>
    <div class="g_rarea"> 
    	<div class="g_bre1">个人资料</div>
        <div class="g_bre2">
        	<div class="pic_img"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$this->user['id']));?>"><img src="<?php echo $this->user['large_avatar'];?>" width="127" height="128"/></a><span class="txt"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$this->user['id']));?>"><?php echo $this->user['username'];?></a></span></div>
                <div class="pic_iis">
                <ul>
                    <li><span class="tcolor">性别：</span><?php echo ($this->user['sex']) > 0 ?(($this->user['sex'] == 1) ? '男' : '女'):'保密';?></li>
					<li><span class="tcolor">所在地盘：</span><a href="<?php echo hg_build_link('geoinfo.php');?>"><?php echo $this->user['group_name'];?></a></li>
                    </ul>
                </div>
                <div class="pic_coss" style="padding:0;">
                	<ul>
                   		<li class="line" style="width:60px;"><div class="p1"><?php echo $this->user['attention_count'];?></div><div><a href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$this->user['id']));?>">关注</a></div></li>
                        <li class="line" style="width:60px;"><div class="p1"><?php echo $this->user['followers_count'];?></div><div><a href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$this->user['id']));?>">粉丝</a></div></li>
                        <li class="line" style="width:60px;"><div class="p1"><?php echo $this->user['status_count'];?></div><div><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$this->user['id']));?>">点滴</a></div></li>
                        <li style="width:60px;"><div class="p1"><?php echo $this->user['video_count'];?></div><div><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$this->user['id']));?>">视频</a></div></li>
                    </ul>
                </div> 
        </div> 
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div> 
    </div>
    </div>
    <?php include hg_load_template('foot');?>
