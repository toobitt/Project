{template:head}

<div class="garea">
	<div class="g_larea">
        <div class="g_are2">
        	<ul>
        	<li style="cursor: pointer;" onclick="changeCss(1)" id="_1" class="{if $n}bt_d{else}bt{/if}">消息</li>
        	<li style="cursor: pointer;" onclick="changeCss(2)" id="_2" class="{if $n}bt{else}bt_d{/if}">通知</li> 
          </ul>
        </div>
        <div id="content" class="g_are4">
		{if $n}
			{template:unit/user_notices}
		{else}
			{template:unit/user_msgs}
		{/if}
        </div>
       
        <div style="font-size: 0pt; line-height: 0pt;"><img src="./res/img/hc_pic_btns.gif"></div>
    </div>
    <div class="g_rarea"> 
    	<div class="g_bre1">个人资料</div>
        <div class="g_bre2">
        	<div class="pic_img"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$this->user['id']));?>"><img src="{$_user['large_avatar']}" width="127" height="128"/></a><span class="txt"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$this->user['id']));?>">{$_user['username']}</a></span></div>
                <div class="pic_iis">
                <ul>
                    <li><span class="tcolor">性别：</span>
                    {if $_user['sex']}
                    	{if $_user['sex'] == 1}
                    	 男  
                    	{else}
                    	女
                    	{/if}
                    {else}
                                                      保密
                    {/if}
                    </li>
					<li><span class="tcolor">所在地盘：</span><a href="<?php echo hg_build_link('geoinfo.php');?>">{$_user['group_name']}</a></li>
                    </ul>
                </div>
                <div class="pic_coss" style="padding:0;">
                	<ul>
                   		<li class="line" style="width:60px;"><div class="p1">{$_user['attention_count']}</div><div><a href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$_user['id']));?>">关注</a></div></li>
                        <li class="line" style="width:60px;"><div class="p1">{$_user['followers_count']}</div><div><a href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$_user['id']));?>">粉丝</a></div></li>
                        <li class="line" style="width:60px;"><div class="p1">{$_user['status_count']}</div><div><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$_user['id']));?>">点滴</a></div></li>
                        <li style="width:60px;"><div class="p1">{$_user['video_count']}</div><div><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$_user['id']));?>">视频</a></div></li>
                    </ul>
                </div> 
        </div> 
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div> 
    </div>
    </div>
{template:foot}
