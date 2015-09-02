<style>
 .message_left{width:100%;height:80px;}
.users_m{text-align:center; min-height: 72px;width:70px;float:left;}
.user_avatar{height:40px;width:40px;float:left;}
.users_m .user_name{float:left;width:40px;height:15px;color:#009FE9;margin-top:15px;overflow:hidden;}
.msg_div_list{ margin: 10px;}
.this_msg{width:600px;float:left;padding:0 0 5px 3px;background:#f7f7f7;border-bottom: 1px dotted #CCCCCC;}
.this_msg h3{border:none;padding:0} 
</style>
<?php if(is_array($from_who)){?>
<div class="message_left">
<?php foreach($from_who as $uid => $uinfo){?>
<div class="msg_div_list">
	<div class="users_m">
		<a href="#" class="user_avatar"><img src="<?php echo $uinfo['middle_avatar']?>"  title="<?php echo $uinfo['username']?>" /></a><br/>
		<a href="#" class="user_name"><?php echo $uinfo['username']?></a>
		<input type="hidden" name="this_uid" id="this_uid<?php echo $uinfo['id']?>" value="<?php echo $uinfo['last_sid']?>"/>
	</div>
	<div class="this_msg">
		
		<h3><a href="javascript:void(0)" onclick="show_msg_box(<?php echo $uinfo['id']?>)"><?php echo $uinfo['last_message']?></a></h3><br/>
		<span class="txt">消息最后发送于：<?php echo date("m-d H:i",$uinfo['last_stime']);?></span>
		<span style="margin-left:350px;"><a href="javascript:void(0)" onclick="show_msg_box(<?php echo $uinfo['id']?>)">查看完整记录</a></span>
	</div>
</div>
<div class="clear"></div>
<?php }?>
</div>
<?php }else{?>
<p>暂无消息...</p>
<?php }?>
 <?php echo $showpages;?>