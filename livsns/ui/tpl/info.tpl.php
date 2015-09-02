<?php 
/* $Id: info.tpl.php 3817 2011-04-28 09:45:21Z repheal $ */
?>

<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<?php include hg_load_template('status_pub');?>

<input type="hidden" value="点滴" name="source" id="source"/>


<dl id="Box" class="box" style="top:10%;left:5%;">
	<dt><b><?php echo $this->lang['status_title']?></b><span id="BoxClose">x</span> </dt>
	<dd>
	<div class="countsS">您还可以输入<span id="counter" class="counterS">140</span>字</div>
	<textarea onkeydown='countChar();' onkeyup='countChar();' name="status" id="status" rows="8" cols="50" style="font-size:12px;width:435px;height:80px;overflow:auto;border:1px solid #CACACA;">
</textarea>
		<input type="button" value=" <?php echo $this->lang['released']?> " id="Released" onclick="pubUserStatus();"/>
	</dd>
</dl>


    <div class="content clear people" id="equalize">
    <div class="content-left">
		<div class="rounded-top"></div>
    	<div class="expression">
	    	<img src="<?php echo $user_info['larger_avatar'];?>" class="pic"/>
        	<div class="people_user">
                <h2 id="username"><?php echo $user_info['username'];?></h2>
              <?php echo $user_info['location'];?>
                <?php if($user_info['id'] == $this->user['id'])
				{?>
					<a class="set" href="javascript:void(0);" onclick="OpenReleased('')"><?php echo $this->lang['status_mine'];?></a>
				<?php 			
				}
				else 
				{?>
					<a class="set" href="javascript:void(0);" onclick="OpenReleased('<?php echo $user_info['username'];?>')"><?php echo $this->lang['chat'];?></a>
					<a class="chat" title="和他聊天" style="position:absolute;right:30px;top:2px;" href="javascript:void(0);" onclick="showMsgBox('<?php echo  $user_info['username'];?>','<?php echo md5($user_info['id'] . $user_info['salt'] . $this->user['id'] . $this->user['salt']);?>')">&nbsp;&nbsp;</a>
				<?php 	
				}
				?>
            </div>
        </div>
       				<ul>
				<?php 
						$relation = array('truename'=>$this->lang['truename'],'birthday'=>$this->lang['birthday'],'email'=>$this->lang['email'],'qq'=>'QQ','msn'=>'MSN','mobile'=>'mobile');
						foreach($relation as $key =>$value)
						{
							$temp = $this->user_info[$key];
							if($temp)
							{
								if(strcmp($key,"birthday")==0 && is_numeric($temp))
								{
									echo "<li>".$value."： ".$this->lang['xingzuo'][$temp]."</li>";
								}
								else 
								{
									echo "<li>".$value."： ".$temp."</li>";
								}
							}
						}
				?>
				</ul>
</div>

<div class="content-right">	

		<div class="pad-all">
			<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
		<?php include hg_load_template('userInfo');?>
<!--            <h3>个人资料<a>设置</a></h3>-->
<!--            <ul class="information">-->
<!--            	<li>女，<a>金牛座</a></li>-->
<!--                <li>现居：<a>江苏</a><a>南京</a></li>-->
<!--                <li class="clear"><span>压力巨大朱斌表示压</span>介绍：</li>-->
<!--                <li>女，<a>金牛座</a></li>-->
<!--                <li>女，<a>金牛座</a></li>-->
<!--            </ul>-->
       
<!--            <h3>我的标签<a>修改</a><a>添加</a></h3>-->
<!--            <ul class="my-tags clear">-->
<!--            	<li><a>标题内容</a></li>-->
<!--                <li><a>标题内容</a></li>-->
<!--                <li><a>标题内容</a></li>-->
<!--                <li><a>标题内容</a></li>-->
<!--            </ul>-->
<!--            <h3>可能认识的人<a>换几个</a></h3>-->
<!--            <ul class="friends">-->
<!--            	 <li>-->
<!--                   <div class="subject">-->
<!--                       <a>发言人</a><span>浙江杭州</span>-->
<!--                       <div class="tag">标签：<a>UI</a><a>爱好</a></div>-->
<!--                       <strong id="00123">+加关注</strong>-->
<!--                   </div>-->
<!--                   <img src="IMG/1.jpg" class="pic"/></a>-->
<!--            	</li>-->
<!--            </ul>-->
				</div>

    </div>
</div>
<?php include hg_load_template('foot');?>